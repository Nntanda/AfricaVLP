<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Utility\Hash;
use Cake\Http\Cookie\Cookie;
use Cake\Http\ServerRequest;
use Cake\I18n\Time;
use Cake\Routing\Router;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->Auth->allow();
    }

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function display(...$path)
    {
        $count = count($path);
        if (!$count) {
            return $this->redirect('/');
        }
        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }
        $page = $subpage = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        $this->set(compact('page', 'subpage'));

        try {
            $this->render(implode('/', $path));
        } catch (MissingTemplateException $exception) {
            if (Configure::read('debug')) {
                throw $exception;
            }
            throw new NotFoundException();
        }
    }

    public function chooseLanguage()
    {
        $langs = Hash::normalize(Configure::read('I18n.languages'));
        // dd($this->request->getCookie('lang'));

        if ($this->request->is('Post')) {
            $lang = $this->request->getData('lang');
            if (isset($lang) && !empty($lang)) {
                $cookieLang = (new Cookie('lang'))
                    ->withValue($lang)
                    ->withExpiry(new Time('+1 year'));
                $this->response = $this->response->withCookie($cookieLang);
                
                $referer = Router::parseRequest(new ServerRequest($this->request->referer('/', true)));
                if (isset($referer['_matchedRoute'])) { unset($referer['_matchedRoute']); }
                if (isset($referer['_middleware'])) { unset($referer['_middleware']); }
                $pass = $referer['pass']; unset($referer['pass']);
                if (isset($referer['_name']) && ($referer['_name'] == 'organization:actions' || $referer['_name'] = 'organization:home')) { unset($pass[0]); }
                $referer['lang'] = $lang;
                $referer = array_merge($referer, $pass);

                $this->redirect(Router::url($referer));
            }
        }

        $this->set(compact('langs'));
    }

    public function index()
    {
        $this->loadModel('Widgets');
        $this->loadModel('News');
        $this->loadModel('Resources');
        $this->loadModel('BlogPosts');
        $this->loadModel('Events');

        $widgets = $this->Widgets->find()->where(['status' => STATUS_ACTIVE])->toArray();
        $slides = Hash::extract($widgets, '{n}[name='.$this->Widgets::IMAGE_SLIDER .']');
        $about_blocks = Hash::extract($widgets, '{n}[name='.$this->Widgets::ABOUT_BLOCK .']');
        $footer = Hash::extract($widgets, '{n}[name='.$this->Widgets::FOOTER .']');

        $news = $this->News->find()->where(['News.status' => NEWS_STATUS_PUBLISHED])->contain(['Organizations', 'PublishingCategories', 'VolunteeringCategories'])->order(['News.created' => 'DESC'])->limit(4);
        $resources = $this->Resources->find()->where(['Resources.status' => STATUS_ACTIVE])->contain(['Organizations', 'ResourceTypes'])->order(['Resources.created' => 'DESC'])->limit(4);
        $blogPosts = $this->BlogPosts->find()->where(['BlogPosts.status' => NEWS_STATUS_PUBLISHED])->contain(['PublishingCategories', 'VolunteeringCategories'])->order(['BlogPosts.created' => 'DESC'])->limit(4);
        $events = $this->Events->find()->where(['Events.status' => STATUS_ACTIVE])->contain(['Organizations', 'Countries', 'Cities'])->order(['Events.created' => 'DESC'])->limit(4);

        $lang = Configure::read('App.language');

        $this->set(compact('slides', 'about_blocks', 'footer', 'news', 'resources', 'blogPosts', 'events', 'lang'));
    }

    public function aboutUs()
    {
        $this->loadModel('Widgets');

        $widgets = $this->Widgets->find()->where(['status' => STATUS_ACTIVE])->toArray();
        $main = Hash::extract($widgets, '{n}[name='.$this->Widgets::ABOUT_PAGE_MAIN .']');
        $subSections = Hash::extract($widgets, '{n}[name='.$this->Widgets::ABOUT_PAGE_SUBSECTION .']');

        $this->set(compact('main', 'subSections'));
    }

    public function getCountryStats($country)
    {
        $this->loadModel('Countries');

        $country = $this->Countries->find()->where(['nicename LIKE' => "%$country%", 'region_id IS NOT' => null])->select(['id'])->first();
        $events = $this->Countries->Events->find()->where(['Events.country_id' => $country->id])->count();
        $allUsers = $this->Countries->Users->find()->where(['Users.resident_country_id' => $country->id])->count();
        $maleUsers = $this->Countries->Users->find()->where(['Users.resident_country_id' => $country->id, 'Users.gender LIKE' => 'Male'])->count();
        $femaleUsers = $this->Countries->Users->find()->where(['Users.resident_country_id' => $country->id, 'Users.gender LIKE' => 'Female'])->count();
        
        return $this->response->withType('application/json')->withStringBody(json_encode([
            'id' => $country->id,'events' => $events,
            'users' => [
                'total' => $allUsers,
                'male' => $allUsers > 0 ? round(($maleUsers/$allUsers) * 100, 2) : 0,
                'female' => $allUsers > 0 ? round(($femaleUsers/$allUsers) * 100, 2) : 0
            ]
        ]));
    }

    public function subscribeNewsletter()
    {
        try {
            $this->loadModel('NewsletterSubscriptions');

            if ($this->request->is('post')) {
                $data = $this->request->getData();
                if ($this->NewsletterSubscriptions->exists(['email' => $data['email']])) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'status' => 'error',
                        'message' => 'Email already subscribed',
                    ]));
                }

                $subscriber = $this->NewsletterSubscriptions->newEntity($data);
                if ($subscriberSaved = $this->NewsletterSubscriptions->save($subscriber)) {
                    $response = $this->response->withType('application/json')->withStringBody(json_encode([
                        'status' => 'success',
                        'message' => 'Subscription saved successfully'
                    ]));
                } else {
                    $response = $this->response->withType('application/json')->withStringBody(json_encode([
                        'status' => 'error',
                        'message' => 'Error saving subscription',
                        'errors' => $subscriber->getErrors()
                    ]));
                }
            } else {
                $response = $this->response->withType('application/json')->withStringBody(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid request',
                ]));
            }
            return $response;
        } catch (\Throwable $th) {
            return $this->response->withType('application/json')->withStringBody(json_encode([
                'status' => 'error',
                'message' => 'Error occurred',
            ]));
        }
        
    }

    public function interactiveMap()
    {
        $this->loadModel('Countries');
        $this->loadModel('VolunteeringCategories');

        $countries = $this->Countries->find('list')->where(['status' => STATUS_ACTIVE, 'region_id IS NOT' => null]);
        $volunteeringCategories = $this->VolunteeringCategories->find('list')->where(['status' => STATUS_ACTIVE]);
        $display = $this->request->getQuery('display', 'volunteer_organizations');

        $this->set(compact('volunteeringCategories', 'countries', 'display'));
    }

    public function getEventsLocations() 
    {
        $this->loadModel('Events');

        // $events = $this->Events->find()->where(['Events.lat IS NOT' => null, 'Events.lat IS NOT' => '', 'Events.lng IS NOT' => null, 'Events.lng IS NOT' => '']);
        $events = $this->Events->find();

        $country_id = $this->request->getQuery('country_id');
        $this->loadModel('Countries');
        if($country_id != null && !empty($country_id)) {
            $events = $events->where(['Events.country_id' => $country_id])->contain(['Organizations', 'Countries', 'Cities', 'VolunteeringCategories']);
            $country = $this->Countries->get($country_id);
        } else {
            $top10countries = $this->Countries->find()->select(['id', 'iso', 'nicename', 'data_count' => 'COUNT(Events.id)'])->innerJoinWith('Events', function($q) {
                // return $q->where(['Events.lat IS NOT' => null, 'Events.lat IS NOT' => '', 'Events.lng IS NOT' => null, 'Events.lng IS NOT' => '']);
                return $q;
            })->group('Countries.id')->order(['data_count' => 'DESC'])->limit(10);

            $buttom10countries = $this->Countries->find()->select(['id', 'iso', 'nicename', 'data_count' => 'COUNT(Events.id)'])->innerJoinWith('Events', function($q) {
                // return $q->where(['Events.lat IS NOT' => null, 'Events.lat IS NOT' => '', 'Events.lng IS NOT' => null, 'Events.lng IS NOT' => '']);
                return $q;
            })->group('Countries.id')->order(['data_count' => 'ASC'])->limit(10);
        }

        $category_id = $this->request->getQuery('cat');
        if($category_id != null && !empty($category_id)) {
            $events = $events->matching('VolunteeringCategories', function ($q) use ($category_id) {
                return $q->where(['VolunteeringCategories.id' => $category_id]);
            });
        }

        return $this->response->withType('application/json')->withStringBody(json_encode(array_merge(
            ['data' => $events],
            compact('country_id', 'country', 'top10countries', 'buttom10countries')
        )));
    }

    public function getOrganizationsLocations() 
    {
        $this->loadModel('Organizations');

        //$organizations = $this->Organizations->find()->where(['Organizations.lat IS NOT' => null, 'Organizations.lat IS NOT' => '', 'Organizations.lng IS NOT' => null, 'Organizations.lng IS NOT' => '', 'Organizations.status' => STATUS_ACTIVE]);
        $organizations = $this->Organizations->find()->where(['Organizations.status' => STATUS_ACTIVE]);

        $country_id = $this->request->getQuery('country_id');
        $this->loadModel('Countries');
        if($country_id != null && !empty($country_id)) {
            $organizations = $organizations->where(['Organizations.country_id' => $country_id])->contain(['Countries', 'Cities', 'VolunteeringCategories']);
            $country = $this->Countries->get($country_id);
        } else {
            $top10countries = $this->Countries->find()->select(['id', 'iso', 'nicename', 'data_count' => 'COUNT(Organizations.id)'])->innerJoinWith('Organizations', function($q) {
                // return $q->where(['Organizations.lat IS NOT' => null, 'Organizations.lat IS NOT' => '', 'Organizations.lng IS NOT' => null, 'Organizations.lng IS NOT' => '', 'Organizations.status' => STATUS_ACTIVE]);
                return $q->where(['Organizations.status' => STATUS_ACTIVE]);
            })->group('Countries.id')->order(['data_count' => 'DESC'])->limit(10);

            $buttom10countries = $this->Countries->find()->select(['id', 'iso', 'nicename', 'data_count' => 'COUNT(Organizations.id)'])->innerJoinWith('Organizations', function($q) {
                // return $q->where(['Organizations.lat IS NOT' => null, 'Organizations.lat IS NOT' => '', 'Organizations.lng IS NOT' => null, 'Organizations.lng IS NOT' => '', 'Organizations.status' => STATUS_ACTIVE]);
                return $q->where(['Organizations.status' => STATUS_ACTIVE]);
            })->group('Countries.id')->order(['data_count' => 'ASC'])->limit(10);
        }

        $category_id = $this->request->getQuery('cat');
        if($category_id != null && !empty($category_id)) {
            $organizations = $organizations->matching('VolunteeringCategories', function ($q) use ($category_id) {
                return $q->where(['VolunteeringCategories.id' => $category_id]);
            });
        }

        return $this->response->withType('application/json')->withStringBody(json_encode(array_merge(
            [
            'data' => $organizations,
            'cat' => $category_id
            ],
            compact('country_id', 'country', 'top10countries', 'buttom10countries')
        )));
    }

    public function countryPage($iso = null)
    {
        $this->loadModel('Countries');
        $this->loadModel('Events');
        $this->loadModel('Organizations');
        $this->loadModel('Users');
        $this->loadModel('News');
        $this->loadModel('BlogPosts');

        $country = $this->Countries->find()->where(['Countries.iso' => $iso])->where(['Countries.region_id IS NOT' => null, 'Countries.status' => STATUS_ACTIVE])->contain(['Regions'])->first();

        if ($country == null) {
            $this->Flash->error(__('The record was not found.'));

            return $this->redirect($this->referer());
        }

        $events = $this->Events->find()->where(['country_id' => $country->id])->count();
        $organizations = $this->Organizations->find()->where(['country_id' => $country->id, 'status' => STATUS_ACTIVE])->count();

        $newsCount = $this->News->find()->where(['OR' => [['region_id IS' => null], ['region_id' => $country->region_id]]])->count();
        $blogsCount = $this->BlogPosts->find()->where(['OR' => [['region_id IS' => null], ['region_id' => $country->region_id]]])->count();

        $volunteers = $this->Users->find()->where(['Users.resident_country_id' => $country->id])->innerJoinWith('VolunteeringHistories')->group('Users.id');
        
        $ageRanges = ['15-19' => 0, '20-24' => 0, '25-29' => 0, '30-35' => 0];
        foreach ($volunteers as $volunteer) {
            $age = $volunteer->date_of_birth->diffInYears(\Cake\I18n\Date::now());
            switch (true) {
                case ($age >= 15):
                case ($age <= 19):
                    $ageRanges['15-19'] += 1;
                    break;
                
                case ($age >= 20):
                case ($age <= 24):
                    $ageRanges['20-24'] += 1;
                    break;
                
                case ($age >= 25):
                case ($age <= 29):
                    $ageRanges['25-29'] += 1;
                    break;
                
                case ($age >= 30):
                case ($age <= 35):
                    $ageRanges['30-35'] += 1;
                    break;
                
                default:
                    # code...
                    break;
            }
        }
        $totalVolunteers = $volunteers->count();

        $maleVolunteers = $this->Users->find()->where(['Users.resident_country_id' => $country->id, 'Users.gender LIKE' => 'Male'])->innerJoinWith('VolunteeringHistories')->group('Users.id')->count();
        $femaleVolunteers = $this->Users->find()->where(['Users.resident_country_id' => $country->id, 'Users.gender LIKE' => 'Female'])->innerJoinWith('VolunteeringHistories')->group('Users.id')->count();

        $volunteeringCategories = $this->Organizations->VolunteeringCategories->find('list')->where(['status' => STATUS_ACTIVE]);
        $this->set(compact('country', 'organizations', 'volunteers', 'totalVolunteers', 'events', 'maleVolunteers', 'femaleVolunteers', 'ageRanges', 'volunteeringCategories', 'newsCount', 'blogsCount'));
    }

    public function getAgendaData($iso = null)
    {
        $this->loadModel('Countries');
        $this->loadModel('Organizations');

        $country = $this->Countries->find()->where(['Countries.iso' => $iso])->where(['Countries.region_id IS NOT' => null, 'Countries.status' => STATUS_ACTIVE])->contain(['Regions'])->first();

        if ($country == null) {
            $this->Flash->error(__('The record was not found.'));

            return $this->response->withType('application/json')->withStringBody(json_encode([
                'status' => 'error',
                'message' => 'Country record not found'
            ]))->withStatus(404);
        }

        $organizationTypes = $this->Organizations->OrganizationTypes->find()->select(['id', 'name'])->where(['OrganizationTypes.status' => STATUS_ACTIVE]);
        $organizationTypes->formatResults(function ($results) {
            return $results->map(function ($organizationType) {
                $events = $this->Organizations->Events->find()->innerJoinWith('Organizations', function ($q) use ($organizationType) {
                    return $q->where(['Organizations.organization_type_id' => $organizationType->id]);
                });
                $category_id = $this->request->getQuery('cat');
                if($category_id != null && !empty($category_id)) {
                    $events = $events->matching('VolunteeringCategories', function ($q) use ($category_id) {
                        return $q->where(['VolunteeringCategories.id' => $category_id]);
                    });
                }
                
                $organizationType->events_count = $events->count();
                return $organizationType;
            });
        });

        return $this->response->withType('application/json')->withStringBody(json_encode([
            'status' => 'success',
            'data' => $organizationTypes
        ]));
    }

    public function countryList()
    {
        $this->loadModel('Countries');

        $countries = $this->Countries->find()->where(['Countries.region_id IS NOT' => null, 'Countries.status' => STATUS_ACTIVE])->contain(['Regions']);

        $this->set(compact('countries'));
    }

}

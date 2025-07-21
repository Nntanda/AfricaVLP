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

use Cake\Controller\Controller;
use Cake\Event\Event;
use Google\Cloud\Translate\V2\TranslateClient;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        
        $this->loadComponent('Auth', [
            // 'authorize' => ['Controller'],
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password'
                    ],
                ]
            ],
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
             // If unauthorized, return them to page they were just on
            'unauthorizedRedirect' => $this->referer()
        ]);
        $this->Auth->allow(['getCitiesListByCountry']);

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        $authUser = '';
        if(isset($this->Auth) && $this->Auth->user()!= null) {
            $interests = \Cake\Utility\Hash::extract($this->Auth->user('platform_interests'), '{n}.name');
            $authUser = $this->Auth->user();
            $authUser['allow_news'] = false;
            if (in_array('Volunteer & Exchange opportunities', $interests) || in_array('Research', $interests) || in_array('Newsletters and Articles', $interests)) {
                $authUser['allow_news'] = true;
            }

            $authUser['allow_events'] = false;
            if (in_array('Volunteer & Exchange opportunities', $interests)) {
                $authUser['allow_events'] = true;
            }

            $authUser['allow_resources'] = false;
            if (in_array('Volunteer & Exchange opportunities', $interests) || in_array('Research', $interests)) {
                $authUser['allow_resources'] = true;
            }

            $authUser['allow_organizations'] = false;
            if (in_array('Networking with organizations', $interests)) {
                $authUser['allow_organizations'] = true;
            }
            
        }

        $this->set(compact('authUser'));
    }

    public function translateTexts() {
        if ($this->request->is('post')) {

            $requestData = $this->request->getData();
            $sourceLanguage = $requestData['sourceLanguage'];
            $resData = [];
            foreach ($requestData['data'] as $data) {
                $sourceTexts = $data['sourceTexts'];
                $targetLanguage = $data['lang'];
                
                try {
                    $translate = new TranslateClient(['keyFilePath' => ROOT .'/webroot/js/auvlp-dev-sk.json']);
                    $result = $translate->translateBatch($sourceTexts, [
                        'source' => $sourceLanguage,
                        'target' => $targetLanguage,
                    ]);

                    $resData[] = ["lang" => $targetLanguage, "data" => $result];
            
                } catch (\Cake\Core\Exception\Exception $ex) {
                    $this->log($ex);
                }
            }

            $resBody = json_encode($resData);
            $response = $this->response->withType('application/json')->withStringBody($resBody);
    
            return $response;

        }
    }

    public function getCountryListByRegion($region_id = null)
    {
        $this->loadModel('Countries');
        $countries = $this->Countries->find('listByRegion', ['region_id' => $region_id]);
        return $this->response->withType('application/json')->withStringBody(json_encode($countries));

    }

    public function getCitiesListByCountry($country_id = null)
    {
        $this->loadModel('Cities');
        $cities = $this->Cities->find('listByCountry', ['country_id' => $country_id, 'status' => STATUS_ACTIVE]);
        return $this->response->withType('application/json')->withStringBody(json_encode($cities));

    }

    public function sendFeedback()
    {
        try {
            $this->loadModel('UserFeedbacks');
            if (!$this->request->is(['patch', 'post', 'put'])) {
                throw new \Exception('Bad request');
            }

            $userFeedback = $this->UserFeedbacks->newEntity($this->request->getData());
            if ($this->UserFeedbacks->save($userFeedback)) {
                return $this->response->withType('application/json')->withStringBody(json_encode(['status' => 'success']));
            }
            throw new \Exception('Error saving feedback');
        } catch (\Throwable $th) {
            $this->log($th);
            return $this->response->withType('application/json')->withStringBody(json_encode([
                'status' => 'error',
                'message' => $th->getMessage()
            ]));
        }
    }
}

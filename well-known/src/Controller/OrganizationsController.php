<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Hash;
use Cake\Core\Configure;

/**
 * Organizations Controller
 *
 * @property \App\Model\Table\OrganizationsTable $Organizations
 *
 * @method \App\Model\Entity\Organization[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrganizationsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        // $this->Email = new EmailSender();
        $this->viewBuilder()->setLayout('organization');
    }

    public function beforeFilter(\Cake\Event\Event $event)
    {
        $id = $this->request->getParam('id');
        if (!$this->Organizations->OrganizationUsers->exists([
            'organization_id' => $id,
            'user_id' => $this->Auth->user('id'),
            'status' => STATUS_ACTIVE
        ])) {
            $this->Flash->error(__('Unauthorized! Access denied.'));

            return $this->redirect('/');
        }
    }

    public function _checkProfile($org)
    {
        if ($org->status !== STATUS_ACTIVE) {
            return $this->redirect(['action' => 'inactive', 'id' => $org->id]);
        }

        if (
            (empty($org->country_id) || $org->country_id === null) ||
            (empty($org->address) || $org->address === null) ||
            (empty($org->email) || $org->email === null) ||
            (empty($org->phone_number) || $org->phone_number === null)
        ) {
            $this->Flash->error(__('Complete your profile. You will gain access after the AU has approved your application.'));

            return $this->redirect(['action' => 'profile', 'id' => $org->id]);
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index($id = null)
    {
        try {
            $this->loadModel('VolunteeringInterests');
            $organization = $this->Organizations->get($id);
            $this->_checkProfile($organization);
            $events = $this->Organizations->Events->find()->where(['organization_id' => $organization->id])->contain(['Countries', 'Cities'])->where(function ($exp, $q) {
                return $exp->gt('Events.start_date', $q->func()->now());
            })->order(['Events.id' => 'DESC'])->limit(3);

            $events->formatResults(function ($results) {
                return $results->map(function ($event) {
                    $event->interests = $this->VolunteeringInterests->find()->innerJoinWith('VolunteeringOppurtunities.Events', function ($q) use ($event) {
                        return $q->where(['Events.id' => $event->id]);
                    })->count();
                    return $event;
                });
            });

            $news = $this->Organizations->News->find()->where(['organization_id' => $organization->id])->contain(['Regions', 'PublishingCategories', 'VolunteeringCategories'])->limit(4);

            $resources = $this->Organizations->Resources->find()->where(['organization_id' => $organization->id])->contain(['Countries', 'ResourceTypes'])->limit(4);

            $this->set(compact('organization', 'events', 'news', 'resources'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            return $this->redirect('/');
        }
    }

    public function inactive($id = null)
    {
        try {
            $this->loadModel('VolunteeringInterests');
            $organization = $this->Organizations->get($id);

            if ($organization->status === STATUS_ACTIVE) {
                return $this->redirect(['action' => 'index', 'id' => $organization->id]);
            }

            $this->set(compact('organization'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            return $this->redirect('/');
        }
    }

    public function profile($id = null)
    {
        try {
            $organization = $this->Organizations->get($id, [
                'contain' => [
                    'VolunteeringCategories',
                    'OrganizationOffices' => function ($q) {
                        return $q->where(['OrganizationOffices.status' => STATUS_ACTIVE])->contain(['Countries', 'Cities']);
                    },
                ]
            ]);

            if (!empty($this->request->data['upload_additional_file']['name'])) {
                $file = $this->request->data['upload_additional_file'];
                $max_size = 5 * 1024 * 1024;
                if($file['size'] >= $max_size){
                    $this->Flash->error(__('File size should not be more than 5MB. Please, try again.'));
                }
                
                $cloudOptions = array("width" => 1200, "height" => 630, "crop" => "crop");
                $cloudinary_config = Configure::read('Cloudinary');
                \Cloudinary::config($cloudinary_config);
                $cloudinaryAPIReq = \Cloudinary\Uploader::upload($file["tmp_name"]);
                $imageFileName = $cloudinaryAPIReq['secure_url'];
                $organization->additional_file = $imageFileName;
            }

            if (!empty($this->request->data['upload_organization_report_file']['name'])) {
                $file = $this->request->data['upload_organization_report_file'];
                $max_size = 5 * 1024 * 1024;
                if($file['size'] >= $max_size){
                    $this->Flash->error(__('File size should not be more than 5MB. Please, try again.'));
                }

                $cloudOptions = array("width" => 1200, "height" => 630, "crop" => "crop");
                $cloudinary_config = Configure::read('Cloudinary');
                \Cloudinary::config($cloudinary_config);
                $cloudinaryAPIReq = \Cloudinary\Uploader::upload($file["tmp_name"]);
                $imageOrgAnnuFileName = $cloudinaryAPIReq['secure_url'];
                $organization->pan_africanism_organiz_annu_file = $imageOrgAnnuFileName;
            }

            if (!empty($this->request->data['upload_organization_policy_file']['name'])) {
                $file = $this->request->data['upload_organization_policy_file'];
                $max_size = 5 * 1024 * 1024;
                if($file['size'] >= $max_size){
                    $this->Flash->error(__('File size should not be more than 5MB. Please, try again.'));
                }

                $cloudOptions = array("width" => 1200, "height" => 630, "crop" => "crop");
                $cloudinary_config = Configure::read('Cloudinary');
                \Cloudinary::config($cloudinary_config);
                $cloudinaryAPIReq = \Cloudinary\Uploader::upload($file["tmp_name"]);
                $imageOrgPolFileName = $cloudinaryAPIReq['secure_url'];
                $organization->pan_africanism_organiz_pol_file = $imageOrgPolFileName;
            }

            if (!empty($this->request->data['upload_country_national_file']['name'])) {
                $file = $this->request->data['upload_country_national_file'];
                $max_size = 5 * 1024 * 1024;
                if($file['size'] >= $max_size){
                    $this->Flash->error(__('File size should not be more than 5MB. Please, try again.'));
                }

                $cloudOptions = array("width" => 1200, "height" => 630, "crop" => "crop");
                $cloudinary_config = Configure::read('Cloudinary');
                \Cloudinary::config($cloudinary_config);
                $cloudinaryAPIReq = \Cloudinary\Uploader::upload($file["tmp_name"]);
                $imageCountryFileName = $cloudinaryAPIReq['secure_url'];
                $organization->pan_africanism_country_file = $imageCountryFileName;
            }

            $organization = $this->Organizations->patchEntity($organization, $this->request->getData());
            if ($this->request->is(['patch', 'post', 'put'])) {
                $organization = $this->Organizations->patchEntity($organization, $this->request->getData());
                if ($this->Organizations->save($organization)) {
                    $this->Flash->success(__('The profile details has been saved.'));

                    return $this->redirect(['action' => 'profile', 'id' => $id]);
                }
                $this->Flash->error(__('The profile details could not be saved. Please, try again.'));
            }

            $countries = $this->Organizations->Countries->find('list')->where(['status' => STATUS_ACTIVE, 'region_id IS NOT' => null]);
            $cities = $this->Organizations->Cities->find('list')->where(['status' => STATUS_ACTIVE, 'country_id' => $organization->country_id]);
            $volunteering_categories = $this->Organizations->VolunteeringCategories->find('list')->where(['VolunteeringCategories.status' => STATUS_ACTIVE]);
            $this->set(compact('organization', 'countries', 'cities', 'volunteering_categories'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'index', $id]);
        }
    }

    public function uploadProfileImage($id = null)
    {
        try {
            if ($this->request->is(['patch', 'post', 'put'])) {
                $organization = $this->Organizations->get($id);
                $cropped_image = $this->request->getData('image');
                $this->loadComponent('CloudUpload');

                $uploaded = $this->CloudUpload->upload($cropped_image, [
                    'path' => 'organizations',
                    'resize' => false,
                ]);

                if ($uploaded) {
                    $data = ['logo' => $uploaded['url']];
                    $organization = $this->Organizations->patchEntity($organization, $data);
                    if ($this->Organizations->save($organization)) {
                        $response = $this->response->withType('application/json')->withStringBody(json_encode([
                            'status' => 'success',
                            'message' => 'Profile saved successfully'
                        ]));
                    } else {
                        $response = $this->response->withType('application/json')->withStringBody(json_encode([
                            'status' => 'error',
                            'message' => 'Error saving profile',
                            'errors' => $organization->getErrors()
                        ]));
                    }
                    return $response;
                }

                return $this->response->withType('application/json')->withStringBody(json_encode([
                    'status' => 'error',
                    'message' => 'Error uploading image',
                ]));
            }
        } catch (\Exception $ex) {
            $this->log($ex);
            return $this->response->withType('application/json')->withStringBody(json_encode([
                'status' => 'error',
                'message' => 'Error occurred',
            ]));
        }
    }

    public function addOffice($id = null)
    {
        try {
            if ($this->request->is(['patch', 'post', 'put'])) {
                $organization = $this->Organizations->get($id);
                $data = $this->request->getData('organization_offices');
                $data['organization_id'] = $organization->id;
                $data['status'] = STATUS_ACTIVE;

                $orgOffice = $this->Organizations->OrganizationOffices->newEntity($data);
                if ($this->Organizations->OrganizationOffices->save($orgOffice)) {
                    $this->Flash->success(__('The office details has been saved.'));

                    return $this->redirect($this->referer());
                }
                $this->Flash->error(__('The office details could not be saved. Please, try again.'));
                return $this->redirect($this->referer());
            }
        } catch (\Throwable $th) {
            $this->log($ex);
            $this->Flash->error(__('Error adding address'));
            return $this->redirect($this->referer());
        }
    }

    public function deleteOffice($id = null)
    {
        try {
            if ($this->request->is(['patch', 'post', 'put'])) {
                $organization = $this->Organizations->get($id);
                $officeId = $this->request->getData('id');

                $orgOffice = $this->Organizations->OrganizationOffices->get($officeId);
                if ($this->Organizations->OrganizationOffices->delete($orgOffice)) {
                    $this->Flash->success(__('The office has been deleted.'));

                    return $this->redirect($this->referer());
                }
                $this->Flash->error(__('The office could not be deleted. Please, try again.'));
                return $this->redirect($this->referer());
            }
        } catch (\Throwable $th) {
            $this->log();
            $this->Flash->error(__('Error deleting address'));
            return $this->redirect($this->referer());
        }
    }

    public function events($id)
    {
        try {
            $this->loadModel('VolunteeringInterests');

            $organization = $this->Organizations->get($id);
            $this->_checkProfile($organization);

            $events = $this->Organizations->Events->find()->where(['organization_id' => $organization->id])->contain(['Countries', 'Cities']);

            $search = $this->request->getQuery('s');
            if ($search != null && !empty($search)) {
                $events = $events->where(['OR' => [
                    ["MATCH(Events.title) AGAINST(:search IN NATURAL LANGUAGE MODE)"],
                    ["MATCH(Events.description) AGAINST(:search IN NATURAL LANGUAGE MODE)"],
                ]])->bind(':search', $search);
            }

            $status = $this->request->getQuery('status');
            if ($status != null && !empty($status)) {
                switch ($status) {
                    case 'past':
                        $events = $events->where(function ($exp, $q) {
                            return $exp->lt('Events.start_date', $q->func()->now());
                        })->where(function ($exp, $q) {
                            return $exp->lt('Events.end_date', $q->func()->now());
                        });
                        break;

                    case 'ongoing':
                        $events = $events->where(function ($exp, $q) {
                            return $exp->lt('Events.start_date', $q->func()->now());
                        })->where(function ($exp, $q) {
                            return $exp->gt('Events.end_date', $q->func()->now());
                        });
                        break;

                    case 'upcoming':
                        $events = $events->where(function ($exp, $q) {
                            return $exp->gt('Events.start_date', $q->func()->now());
                        });
                        break;

                    default:
                        # code...
                        break;
                }
            }

            $events->formatResults(function ($results) {
                return $results->map(function ($event) {
                    $event->interests = $this->VolunteeringInterests->find()->innerJoinWith('VolunteeringOppurtunities.Events', function ($q) use ($event) {
                        return $q->where(['Events.id' => $event->id]);
                    })->count();
                    return $event;
                });
            });

            $events = $this->paginate($events);

            $this->set(compact('organization', 'events', 'search', 'status'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'index', $id]);
        }
    }

    public function event($id, $eventId)
    {
        try {
            $organization = $this->Organizations->get($id);

            $event = $this->Organizations->Events->find()->where(['Events.id' => $eventId])->contain([
                'VolunteeringOppurtunities.VolunteeringInterests' => ['VolunteeringOppurtunities.VolunteeringRoles', 'Users'],
                'EventComments'
            ])->first();

            $interestsFilter = Hash::extract($event, 'volunteering_oppurtunities.{n}.volunteering_interests');
            $interests = [];
            foreach ($interestsFilter as $interestList) {
                $interests = array_merge($interests, $interestList);
            }

            $this->set(compact('organization', 'event', 'interests'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'events', $id]);
        }
    }

    public function createEvent($id = null)
    {
        try {
            $organization = $this->Organizations->get($id);
            $this->_checkProfile($organization);

            $this->loadModel('Events');
            $event = $this->Events->newEntity(['organization_id' => $organization->id]);

            if ($this->request->is(['patch', 'post', 'put'])) {
                $requestData = $this->request->getData();
                $event = $this->Events->patchEntity($event, $requestData);
                if ($event = $this->Events->save($event)) {
                    $this->Flash->success(__('The event details has been saved.'));

                    $redirectUrl = ['_name' => 'organization:actions', 'action' => 'events', 'id' => $id];
                    if ($event->requesting_volunteers) {
                        $redirectUrl = ['_name' => 'organization:actions', 'action' => 'addEventOppurtunities', 'id' => $id, $event->id];
                    }

                    return $this->redirect($redirectUrl);
                }
                $this->Flash->error(__('The event details could not be saved. Please, try again.'));
            }

            $regions = $this->Events->Regions->find('list')->where(['status' => STATUS_ACTIVE]);
            $countries = $this->Organizations->Countries->find('list')->where(['status' => STATUS_ACTIVE, 'region_id IS NOT' => null]);
            $cities = $this->Organizations->Cities->find('list')->where(['status' => STATUS_ACTIVE, 'country_id' => $organization->country_id]);
            $volunteering_categories = $this->Organizations->VolunteeringCategories->find('list')->where(['VolunteeringCategories.status' => STATUS_ACTIVE]);

            $this->set(compact('organization', 'countries', 'cities', 'regions', 'volunteering_categories', 'event'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'events', $id]);
        }
    }

    public function editEvent($id, $eventId)
    {
        try {
            $organization = $this->Organizations->get($id);
            $this->_checkProfile($organization);

            $this->Organizations->Events->setlocale('en_GB');
            $event = $this->Organizations->Events->find('translations')->where(['Events.id' => $eventId])->contain([
                'VolunteeringOppurtunities' => ['VolunteeringRoles', 'VolunteeringCategories'],
                'EventComments', 'Countries', 'Cities', 'VolunteeringCategories'
            ])->first();

            if ($this->request->is(['patch', 'post', 'put'])) {
                $requestData = $this->request->getData();
                $event = $this->Organizations->Events->patchEntity($event, $requestData);
                if ($event = $this->Organizations->Events->save($event)) {
                    $this->Flash->success(__('The event details has been saved.'));

                    $redirectUrl = ['_name' => 'organization:actions', 'action' => 'events', 'id' => $id];

                    return $this->redirect($redirectUrl);
                }
                $this->Flash->error(__('The event details could not be saved. Please, try again.'));
            }

            $regions = $this->Organizations->Events->Regions->find('list')->where(['status' => STATUS_ACTIVE]);
            $countries = $this->Organizations->Countries->find('list')->where(['status' => STATUS_ACTIVE, 'region_id IS NOT' => null]);
            $cities = $this->Organizations->Cities->find('list')->where(['status' => STATUS_ACTIVE, 'country_id' => $organization->country_id]);
            $volunteering_categories = $this->Organizations->VolunteeringCategories->find('list')->where(['VolunteeringCategories.status' => STATUS_ACTIVE]);
            $volunteering_roles = $this->Organizations->Events->VolunteeringOppurtunities->VolunteeringRoles->find('list')->where(['status' => STATUS_ACTIVE]);
            $volunteering_durations = $this->Organizations->Events->VolunteeringOppurtunities->VolunteeringDurations->find('list')->where(['status' => STATUS_ACTIVE]);

            $this->set(compact('organization', 'countries', 'cities', 'regions', 'event', 'volunteering_categories', 'volunteering_roles', 'volunteering_durations'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'events', $id]);
        }
    }

    public function addEventOppurtunities($id = null, $eventId = null)
    {
        try {
            $organization = $this->Organizations->get($id, [
                'contain' => [
                    'VolunteeringCategories',
                ]
            ]);

            $this->loadModel('Events');
            $event = $this->Events->get($eventId);

            if ($this->request->is(['patch', 'post', 'put'])) {
                $requestData = $this->request->getData();
                $event = $this->Events->patchEntity($event, $requestData);
                if ($this->Events->save($event)) {
                    $this->Flash->success(__('The event details has been saved.'));

                    return $this->redirect(['_name' => 'organization:actions', 'action' => 'events', 'id' => $id]);
                }
                $this->Flash->error(__('The event details could not be saved. Please, try again.'));
            }

            $volunteering_categories = $this->Organizations->VolunteeringCategories->find('list')->where(['VolunteeringCategories.status' => STATUS_ACTIVE]);
            $volunteering_roles = $this->Events->VolunteeringOppurtunities->VolunteeringRoles->find('list')->where(['status' => STATUS_ACTIVE]);
            $volunteering_durations = $this->Events->VolunteeringOppurtunities->VolunteeringDurations->find('list')->where(['status' => STATUS_ACTIVE]);

            $this->set(compact('organization', 'volunteering_categories', 'event', 'volunteering_roles', 'volunteering_durations'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'events', $id]);
        }
    }

    public function addEventOppurtunity($id = null, $opId = null)
    {
        try {
            $organization = $this->Organizations->get($id, [
                'contain' => [
                    'VolunteeringCategories',
                ]
            ]);

            $this->loadModel('VolunteeringOppurtunities');
            $oppurtunity = $this->VolunteeringOppurtunities->newEntity();

            if ($this->request->is(['patch', 'post', 'put'])) {
                $requestData = $this->request->getData();
                $oppurtunity = $this->VolunteeringOppurtunities->patchEntity($oppurtunity, $requestData);
                if ($this->VolunteeringOppurtunities->save($oppurtunity)) {
                    $this->Flash->success(__('The event details has been saved.'));

                    return $this->redirect($this->referer());
                }
                $this->Flash->error(__('The event details could not be saved. Please, try again.'));
            }

            $this->set(compact('organization', 'oppurtunity'));
            return $this->redirect($this->referer());
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect($this->referer());
        }
    }

    public function editEventOppurtunity($id = null, $opId = null)
    {
        try {
            $organization = $this->Organizations->get($id, [
                'contain' => [
                    'VolunteeringCategories',
                ]
            ]);

            $this->loadModel('VolunteeringOppurtunities');
            $oppurtunity = $this->VolunteeringOppurtunities->get($opId);

            if ($this->request->is(['patch', 'post', 'put'])) {
                $requestData = $this->request->getData();
                $oppurtunity = $this->VolunteeringOppurtunities->patchEntity($oppurtunity, $requestData);
                if ($this->VolunteeringOppurtunities->save($oppurtunity)) {
                    $this->Flash->success(__('The event details has been saved.'));

                    return $this->redirect($this->referer());
                }
                $this->Flash->error(__('The event details could not be saved. Please, try again.'));
            }

            $this->set(compact('organization', 'oppurtunity'));
            return $this->redirect($this->referer());
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect($this->referer());
        }
    }

    public function approveInterest($id = null, $interestId = null)
    {
        try {
            $organization = $this->Organizations->get($id);
            $this->loadModel('VolunteeringInterests');
            $this->loadModel('VolunteeringHistories');

            $interest = $this->VolunteeringInterests->get($interestId);
            $interest->status = STATUS_ACTIVE;
            if ($this->VolunteeringInterests->save($interest)) {
                $volunteeringHistoryData = [
                    'organization_id' => $organization->id,
                    'user_id' => $interest->user_id,
                    'volunteering_oppurtunity_id' => $interest->volunteering_oppurtunity_id,
                ];
                $alumniData = [
                    'organization_id' => $organization->id,
                    'user_id' => $interest->user_id,
                ];

                if (!$this->VolunteeringHistories->exists($volunteeringHistoryData)) {
                    $volunteeringHistory = $this->VolunteeringHistories->newEntity($volunteeringHistoryData);
                    $volunteeringHistory->status = STATUS_ACTIVE;
                    $this->VolunteeringHistories->save($volunteeringHistory);
                }

                if (!$this->Organizations->OrganizationAlumni->exists($alumniData)) {
                    $organizationAlumni = $this->Organizations->OrganizationAlumni->newEntity($alumniData);
                    $organizationAlumni->status = STATUS_ACTIVE;
                    $this->Organizations->OrganizationAlumni->save($organizationAlumni);
                }

                $this->Flash->success(__('The interest details has been updated.'));
                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('The interest details could not be updated. Please, try again.'));

            $this->set(compact('organization', 'interest'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect($this->referer());
        }
    }

    public function news($id)
    {
        try {
            $organization = $this->Organizations->get($id);
            $this->_checkProfile($organization);

            $news = $this->Organizations->News->find()->where(['organization_id' => $organization->id])->contain(['Regions', 'PublishingCategories', 'VolunteeringCategories']);

            $search = $this->request->getQuery('s');
            if ($search != null && !empty($search)) {
                $news = $news->where(['OR' => [
                    ["MATCH(News.title) AGAINST(:search IN NATURAL LANGUAGE MODE)"],
                ]])->bind(':search', $search);
            }

            $status = $this->request->getQuery('status');
            if ($status != null && !empty($status)) {
                $news = $news->where(['News.status' => $status]);
            }

            $category_id = $this->request->getQuery('cat');
            if ($category_id != null && !empty($category_id)) {
                $news = $news->matching('VolunteeringCategories', function ($q) use ($category_id) {
                    return $q->where(['VolunteeringCategories.id' => $category_id]);
                });
            }
            $news = $this->paginate($news);
            $statuses = Configure::read('NEWS_STATUSES');
            $volunteering_categories = $this->Organizations->News->VolunteeringCategories->find('list')->where(['status' => STATUS_ACTIVE]);

            $this->set(compact('organization', 'news', 'statuses', 'search', 'status', 'category_id', 'volunteering_categories'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'index', $id]);
        }
    }

    public function editNews($id, $newsId)
    {
        try {
            $organization = $this->Organizations->get($id);
            $this->_checkProfile($organization);

            $this->Organizations->News->setlocale('en_GB');
            $news = $this->Organizations->News->find('translations')->where(['News.id' => $newsId])->contain([
                'NewsComments', 'PublishingCategories', 'VolunteeringCategories', 'Tags'
            ])->first();

            if ($this->request->is(['patch', 'post', 'put'])) {
                $requestData = $this->request->getData();
                $news = $this->Organizations->News->patchEntity($news, $requestData);
                if ($this->Organizations->News->save($news)) {
                    $this->Flash->success(__('The news details has been saved.'));

                    $redirectUrl = ['_name' => 'organization:actions', 'action' => 'news', 'id' => $id];
                    return $this->redirect($redirectUrl);
                }
                $this->Flash->error(__('The news details could not be saved. Please, try again.'));
            }

            $regions = $this->Organizations->News->Regions->find('list')->where(['status' => STATUS_ACTIVE]);
            $volunteering_categories = $this->Organizations->VolunteeringCategories->find('list')->where(['VolunteeringCategories.status' => STATUS_ACTIVE]);

            $publishing_categories = $this->Organizations->News->PublishingCategories->find('list', ['limit' => 200]);
            if ($organization->organization_type_id !== $this->Organizations::GOVERNMENT_ORG || $organization->organization_type_id !== $this->Organizations::UNIVERSITY_ORG) {
                $publishing_categories->where(['PublishingCategories.name NOT LIKE' => 'Needs']);
            }

            $statuses = Configure::read('NEWS_STATUSES');

            $this->set(compact('organization', 'news', 'statuses', 'regions', 'volunteering_categories', 'publishing_categories'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'news', $id]);
        }
    }

    public function postNews($id = null)
    {
        try {
            $organization = $this->Organizations->get($id);
            $this->_checkProfile($organization);

            $news = $this->Organizations->News->newEntity(['organization_id' => $organization->id]);

            if ($this->request->is(['patch', 'post', 'put'])) {
                $requestData = $this->request->getData();
                $news = $this->Organizations->News->patchEntity($news, $requestData);
                if ($this->Organizations->News->save($news)) {
                    $this->Flash->success(__('The news details has been saved.'));

                    $redirectUrl = ['_name' => 'organization:actions', 'action' => 'news', 'id' => $id];
                    return $this->redirect($redirectUrl);
                }
                $this->Flash->error(__('The news details could not be saved. Please, try again.'));
            }

            $regions = $this->Organizations->News->Regions->find('list')->where(['status' => STATUS_ACTIVE]);
            $volunteering_categories = $this->Organizations->VolunteeringCategories->find('list')->where(['VolunteeringCategories.status' => STATUS_ACTIVE]);

            $publishing_categories = $this->Organizations->News->PublishingCategories->find('list', ['limit' => 200]);
            if ($organization->organization_type_id !== $this->Organizations::GOVERNMENT_ORG || $organization->organization_type_id !== $this->Organizations::UNIVERSITY_ORG) {
                $publishing_categories->where(['PublishingCategories.name NOT LIKE' => 'Needs']);
            }

            $statuses = Hash::remove(Configure::read('NEWS_STATUSES'), NEWS_STATUS_DEACTIVATED);

            $this->set(compact('organization', 'news', 'statuses', 'regions', 'volunteering_categories', 'publishing_categories'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'news', $id]);
        }
    }

    public function messages($id)
    {
        try {
            $organization = $this->Organizations->get($id);
            $this->_checkProfile($organization);
            $this->loadModel('Conversations');

            $conversations = $this->Conversations->find();
            $conversations->innerJoinWith('ConversationParticipants', function ($q) use ($organization) {
                return $q->where(['ConversationParticipants.organization_id' => $organization->id]);
            });
            $conversations->formatResults(function ($results) use ($organization) {
                return $results->map(function ($conversationData) use ($organization) {
                    return $this->Conversations->loadInto($conversationData, [
                        'ConversationMessages' => [
                            'strategy' => 'subquery',
                            'queryBuilder' => function ($q) {
                                return $q->order(['ConversationMessages.created' => 'DESC'])->limit(1);
                            }
                        ],
                        'ConversationParticipants' => [
                            'strategy' => 'subquery',
                            'queryBuilder' => function ($q) use ($organization) {
                                return $q->where(['ConversationParticipants.organization_id IS NOT' => $organization->id])->contain(['Organizations']);
                            }
                        ]
                    ]);
                });
            });

            $conversations = $this->paginate($conversations);
            $organizations = $this->Organizations->find('list')->where(['id IS NOT' => $organization->id]);
            $conversation = $this->Conversations->newEntity();

            $this->set(compact('organization', 'conversations', 'organizations', 'conversation'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'index', $id]);
        }
    }

    public function message($id, $cid)
    {
        try {
            $organization = $this->Organizations->get($id);
            $this->_checkProfile($organization);
            $this->loadModel('Conversations');

            $conversations = $this->Conversations->find()->where(['Conversations.id' => $cid])->contain('ConversationMessages', function ($q) {
                return $q->select($this->Conversations->ConversationMessages)->select(['date_created' => 'DATE(ConversationMessages.created)'])->contain(['Organizations', 'Users'])->enableAutoFields(true);
            });
            $conversations->contain('ConversationParticipants', function ($q) use ($organization) {
                return $q->where(['ConversationParticipants.organization_id IS NOT' => $organization->id])->contain('Organizations');
            });

            $conversations->formatResults(function ($results) {
                return $results->map(function ($conversationData) {
                    $conversationData->conversation_messages = collection($conversationData->conversation_messages)->groupBy('date_created');
                    return $conversationData;
                });
            });

            $conversation = $conversations->first();

            $message = $this->Conversations->ConversationMessages->newEntity([
                'conversation_id' => $conversation->id,
                'organization_id' => $organization->id,
                'user_id' => $this->Auth->user('id'),
                'status' => STATUS_ACTIVE,
            ]);

            if ($this->request->is(['patch', 'post', 'put'])) {
                $message = $this->Conversations->ConversationMessages->patchEntity($message, $this->request->getData());
                if ($this->Conversations->ConversationMessages->save($message)) {
                    $this->Flash->success(__('The message has been sent.'));
                    return $this->redirect(['action' => 'message', 'id' => $organization->id, $cid]);
                }
            }

            $this->set(compact('organization', 'conversation'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'messages', $id]);
        }
    }


    public function newMessage($id)
    {
        try {
            $organization = $this->Organizations->get($id);

            if ($this->request->is(['patch', 'post', 'put'])) {
                $data = $this->request->getData();
                $participant_id = $data['conversation_participants']['organization_id'];
                $messageData['conversation_messages'][] = [
                    'message' => $data['conversation_messages']['message'],
                    'organization_id' => $organization->id,
                    'user_id' => $this->Auth->user('id')
                ];

                $this->loadModel('Conversations');
                $conversation = $this->Conversations->find()->join([
                    'c' => [
                        'table' => 'conversation_participants',
                        'type' => 'INNER',
                        'conditions' => ['c.conversation_id = Conversations.id', 'c.organization_id' => $organization->id],
                    ],
                    'u' => [
                        'table' => 'conversation_participants',
                        'type' => 'INNER',
                        'conditions' => ['u.conversation_id = Conversations.id', 'u.organization_id' => $participant_id],
                    ]
                ])->first();

                if (!$conversation) {
                    $conversation = $this->Conversations->newEntity([
                        'status' => STATUS_ACTIVE,
                        'conversation_participants' => [
                            ['organization_id' => $organization->id],
                            ['organization_id' => $participant_id],
                        ]
                    ]);
                }
                $conversation = $this->Conversations->patchEntity($conversation, $messageData);
                if ($this->Conversations->save($conversation)) {
                    $this->Flash->success(__('The message has been sent.'));
                }
            }

            $this->set(compact('organization', 'conversation'));
            return $this->redirect($this->referer());
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'messages', $id]);
        }
    }

    public function auMessages($id)
    {
        try {
            $organization = $this->Organizations->get($id);
            $this->_checkProfile($organization);

            if (!$this->Organizations->AdminSupports->exists(['organization_id' => $id])) {
                $adminSupport = $this->Organizations->AdminSupports->newEntity(['organization_id' => $id, 'status' => STATUS_ACTIVE]);
                $this->Organizations->AdminSupports->save($adminSupport);
            }

            $conversation = $this->Organizations->AdminSupports->find()->where(['AdminSupports.organization_id' => $id])->contain(['Organizations'])->contain('AdminSupportMessages', function ($q) {
                return $q->select($this->Organizations->AdminSupports->AdminSupportMessages)->select(['date_created' => 'DATE(AdminSupportMessages.created)'])->contain(['SenderUsers'])->enableAutoFields(true);
            });

            $conversation->formatResults(function ($results) {
                return $results->map(function ($conversationData) {
                    $conversationData->admin_support_messages = collection($conversationData->admin_support_messages)->groupBy('date_created');
                    return $conversationData;
                });
            });

            $conversation = $conversation->first();
            $this->Organizations->AdminSupports->AdminSupportMessages->query()
                ->update()
                ->set(['is_read' => true])
                ->where(['admin_support_id' => $conversation->id, 'sender' => 'au', 'is_read IS NOT' => true])
                ->execute();

            $supportMessage = $this->Organizations->AdminSupports->AdminSupportMessages->newEntity();
            if ($this->request->is(['patch', 'post', 'put'])) {
                $data = $this->request->getData();
                $data['admin_support_id'] = $conversation->id;
                $data['sender'] = 'organization';
                $data['sender_user_id'] = $this->Auth->user('id');
                $data['status'] = STATUS_ACTIVE;

                $supportMessage = $this->Organizations->AdminSupports->AdminSupportMessages->patchEntity($supportMessage, $data);
                if ($this->Organizations->AdminSupports->AdminSupportMessages->save($supportMessage)) {
                    $this->Flash->success(__('The message has been sent.'));

                    $redirectUrl = ['_name' => 'organization:actions', 'action' => 'auMessages', 'id' => $id];
                    return $this->redirect($redirectUrl);
                }
                $this->Flash->error(__('The message could not been sent.'));
            }

            $this->set(compact('organization', 'conversation', 'supportMessage'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'index', $id]);
        }
    }

    public function resources($id)
    {
        try {
            $organization = $this->Organizations->get($id);
            $this->_checkProfile($organization);

            $resources = $this->Organizations->Resources->find()->where(['organization_id' => $organization->id])->contain(['Countries', 'ResourceTypes']);

            $search = $this->request->getQuery('s');
            if ($search != null && !empty($search)) {
                $resources = $resources->where(['OR' => [
                    ["MATCH(Resources.title) AGAINST(:search IN NATURAL LANGUAGE MODE)"],
                    ["MATCH(Resources.description) AGAINST(:search IN NATURAL LANGUAGE MODE)"],
                ]])->bind(':search', $search);
            }

            $resource_type = $this->request->getQuery('resource_type');
            if ($resource_type != null && $resource_type !== '') {
                $resources = $resources->where(['Resources.resource_type_id' => $resource_type]);
            }

            $resources = $this->paginate($resources);
            $resourceTypes = $this->Organizations->Resources->ResourceTypes->find('list', ['limit' => 200]);

            $this->set(compact('organization', 'resources', 'resourceTypes', 'search', 'resource_type'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'index', $id]);
        }
    }

    public function addResource($id = null)
    {
        try {
            $organization = $this->Organizations->get($id);
            $this->_checkProfile($organization);

            $resource = $this->Organizations->Resources->newEntity();

            if ($this->request->is(['patch', 'post', 'put'])) {
                $requestData = $this->request->getData();
                $resource = $this->Organizations->Resources->patchEntity($resource, $requestData);
                $resource->organization_id = $organization->id;
                if ($this->Organizations->Resources->save($resource)) {
                    $this->Flash->success(__('The resource has been saved.'));

                    $redirectUrl = ['_name' => 'organization:actions', 'action' => 'resources', 'id' => $id];
                    return $this->redirect($redirectUrl);
                }
                $this->Flash->error(__('The resource could not be saved. Please, try again.'));
            }

            $regions = $this->Organizations->Resources->Regions->find('list')->where(['status' => STATUS_ACTIVE]);
            $countries = $this->Organizations->Countries->find('list')->where(['status' => STATUS_ACTIVE, 'region_id IS NOT' => null]);
            $volunteering_categories = $this->Organizations->VolunteeringCategories->find('list')->where(['VolunteeringCategories.status' => STATUS_ACTIVE]);
            $resourceTypes = $this->Organizations->Resources->ResourceTypes->find('list');
            $statuses = Configure::read('STATUSES');

            $this->set(compact('organization', 'resource', 'statuses', 'regions', 'volunteering_categories', 'resourceTypes', 'countries'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'resources', $id]);
        }
    }

    public function editResource($id, $rId)
    {
        try {
            $organization = $this->Organizations->get($id);
            $this->_checkProfile($organization);

            $this->Organizations->Resources->setlocale('en_GB');
            $resource = $this->Organizations->Resources->find('translations')->where(['Resources.id' => $rId])->contain([
                'Regions', 'Countries', 'ResourceTypes', 'VolunteeringCategories'
            ])->first();
            if ($resource == null) {
                $this->Flash->error(__('The record was not found.'));

                return $this->redirect(['action' => 'index']);
            }

            if ($this->request->is(['patch', 'post', 'put'])) {
                $requestData = $this->request->getData();
                $resource = $this->Organizations->Resources->patchEntity($resource, $requestData);
                if ($this->Organizations->Resources->save($resource)) {
                    $this->Flash->success(__('The resource has been saved.'));

                    $redirectUrl = ['_name' => 'organization:actions', 'action' => 'resources', 'id' => $id];
                    return $this->redirect($redirectUrl);
                }
                $this->Flash->error(__('The resource could not be saved. Please, try again.'));
            }

            $regions = $this->Organizations->Resources->Regions->find('list')->where(['status' => STATUS_ACTIVE]);
            $countries = $this->Organizations->Countries->find('list')->where(['status' => STATUS_ACTIVE, 'region_id IS NOT' => null]);
            $volunteering_categories = $this->Organizations->VolunteeringCategories->find('list')->where(['VolunteeringCategories.status' => STATUS_ACTIVE]);
            $resourceTypes = $this->Organizations->Resources->ResourceTypes->find('list');
            $statuses = Configure::read('STATUSES');

            $this->set(compact('organization', 'resource', 'statuses', 'regions', 'volunteering_categories', 'resourceTypes', 'countries'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'resources', $id]);
        }
    }

    public function admins($id = null)
    {
        try {
            $organization = $this->Organizations->get($id);
            $this->_checkProfile($organization);

            $admins = $this->Organizations->OrganizationUsers->find()->where(['organization_id' => $organization->id, 'user_id NOT IN' => [$organization->user_id, $this->Auth->user('id')]])->contain(['Users']);
            $admins = $this->paginate($admins);

            $this->loadModel('TmpOrganizationUsers');
            $pendingInvites = $this->TmpOrganizationUsers->find()->where(['organization_id' => $organization->id])->limit(10);

            $this->set(compact('organization', 'admins', 'pendingInvites'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'index', $id]);
        }
    }

    public function addAdmin($id = null)
    {
        try {
            $organization = $this->Organizations->get($id);
            $this->_checkProfile($organization);

            if ($this->request->is(['patch', 'post', 'put'])) {
                $data = $this->request->getData();
                if ($this->Organizations->Users->exists([
                    'email' => $data['email']
                ])) {
                    $user = $this->Organizations->Users->find()->where(['email' => $data['email']])->first();

                    if (!$this->Organizations->OrganizationUsers->exists([
                        'organization_id' => $organization->id,
                        'user_id' => $user->id
                    ])) {
                        $userData = [
                            'organization_id' => $organization->id,
                            'user_id' => $user->id,
                            'role' => $data['role'],
                            'status' => STATUS_ACTIVE,
                        ];
                        $organizationUser = $this->Organizations->OrganizationUsers->newEntity($userData);

                        if ($this->Organizations->OrganizationUsers->save($organizationUser)) {
                            $this->Flash->success(__('The user has been added.'));
                            $redirectUrl = ['_name' => 'organization:actions', 'action' => 'admins', 'id' => $id];
                            return $this->redirect($redirectUrl);
                        }
                        $this->Flash->error(__('The user could not be added.'));
                    }
                } else {
                    $this->loadModel('TmpOrganizationUsers');

                    if (!$this->TmpOrganizationUsers->exists(['organization_id' => $organization->id, 'email' => $data['email']])) {
                        $userData = [
                            'organization_id' => $organization->id,
                            'email' => $data['email'],
                            'role' => $data['role'],
                        ];

                        $tmpUser = $this->TmpOrganizationUsers->newEntity($userData);
                        if ($tmpUserSaved = $this->TmpOrganizationUsers->save($tmpUser)) {
                            try {
                                // Send Invite Email
                                $this->Email = new \App\Email\EmailSender();
                                $this->Email->sendOrganizationUserInviteEmail($tmpUserSaved->email, $organization->name, $tmpUserSaved->role);

                                $this->Flash->success(__('The user has been invited successfully.'));
                                $redirectUrl = ['_name' => 'organization:actions', 'action' => 'admins', 'id' => $id];
                                return $this->redirect($redirectUrl);
                            } catch (Exception $e) {
                                $this->Flash->error(__('The user could not be added.'));
                            }
                        }
                        $this->Flash->error(__('The user could not be added.'));
                    } else {
                        $this->Flash->error(__('The user has already been invited.'));
                    }
                }
            }

            $this->set(compact('organization', 'conversation'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'admins', $id]);
        }
    }

    public function editAdmin($id = null, $adminId = null)
    {
        try {
            $organization = $this->Organizations->get($id);
            $this->_checkProfile($organization);
            $organizationUser = $this->Organizations->OrganizationUsers->get($adminId);

            if ($this->request->is(['patch', 'post', 'put'])) {
                $data = $this->request->getData();
                $organizationUser = $this->Organizations->OrganizationUsers->patchEntity($organizationUser, $data);

                if ($this->Organizations->OrganizationUsers->save($organizationUser)) {
                    $this->Flash->success(__('The user has been saved.'));
                    $redirectUrl = ['_name' => 'organization:actions', 'action' => 'admins', 'id' => $id];
                    return $this->redirect($redirectUrl);
                }
                $this->Flash->error(__('The user could not be saved.'));
            }
            $statuses = Configure::read('STATUSES');
            $this->set(compact('organization', 'organizationUser', 'statuses'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'admins', $id]);
        }
    }

    public function volunteersReport($id = null)
    {
        $this->viewBuilder()->setLayout('organization-report');
        try {
            $organization = $this->Organizations->get($id);
            $this->_checkProfile($organization);

            $this->loadModel('Users');
            $volunteers = $this->Users->find()->contain('Countries.Regions')->innerJoinWith('VolunteeringHistories', function ($q) use ($id) {
                return $q->where(['VolunteeringHistories.organization_id' => $id]);
            })->group('Users.id');

            $search = $this->request->getQuery('s');
            if ($search != null && !empty($search)) {
                $volunteers = $volunteers->where(['OR' => [
                    'Users.first_name LIKE' => "%$search%",
                    'Users.last_name LIKE' => "%$search%",
                    'Users.email LIKE' => "%$search%",
                ]]);
            }

            $filter = $this->request->getQuery('filter');
            if (isset($filter['date_from'], $filter['date_to']) && !empty($filter['date_from']) && !empty($filter['date_to'])) {
                $from = $filter['date_from'];
                $to = $filter['date_to'];
                $volunteers = $volunteers->innerJoinWith('VolunteeringHistories', function ($q) use ($from, $to) {
                    return $q->where(function ($exp, $q) use ($from) {
                        return $exp->gte('VolunteeringHistories.created', $from);
                    })->where(function ($exp, $q) use ($to) {
                        return $exp->lte('VolunteeringHistories.created', $to);
                    });
                })->group('Users.id');
            }
            if (isset($filter['region_id']) && !empty($filter['region_id'])) {
                $region = $filter['region_id'];
                $volunteers = $volunteers->innerJoinWith('Countries', function ($q) use ($region) {
                    return $q->where(['Countries.region_id' => $region]);
                });
            }
            if (isset($filter['country_id']) && !empty($filter['country_id'])) {
                $volunteers = $volunteers->where(['Users.country_id' => $filter['country_id']]);
            }
            if (isset($filter['gender']) && !empty($filter['gender'])) {
                $volunteers = $volunteers->where(['Users.gender' => $filter['gender']]);
            }
            if (isset($filter['age_range']) && !empty($filter['age_range'])) {
                $ages = explode(';', $filter['age_range']);
                $from = date('Y-m-d', strtotime("-$ages[1] years"));
                $to = date('Y-m-d', strtotime("-$ages[0] years"));
                $volunteers = $volunteers->where(function ($exp, $q) use ($from) {
                    return $exp->gte('Users.date_of_birth', $from);
                })->where(function ($exp, $q) use ($to) {
                    return $exp->lte('Users.date_of_birth', $to);
                });
            }
            if (isset($filter['interests']) && !empty($filter['interests'])) {
                $interests = $filter['interests'];
                $volunteers = $volunteers->innerJoinWith('VolunteeringCategories', function ($q) use ($interests) {
                    return $q->where(['VolunteeringCategories.id IN' => $interests]);
                });
            }

            $total = $volunteers->count();
            $totalFemale = $volunteers->filter(function ($volunteer) {
                return ($volunteer->gender == 'Female');
            })->count();
            $totalMale = $volunteers->filter(function ($volunteer) {
                return ($volunteer->gender == 'Male');
            })->count();
            $volunteers = $this->paginate($volunteers);

            $countries = $this->Users->Countries->find('list')->where(['status' => STATUS_ACTIVE, 'region_id IS NOT' => null]);
            $regions = $this->Users->Countries->Regions->find('list')->where(['status' => STATUS_ACTIVE]);
            $interests = $this->Organizations->VolunteeringCategories->find('list')->where(['status' => STATUS_ACTIVE]);

            $this->set(compact('organization', 'volunteers', 'total', 'totalFemale', 'totalMale', 'search', 'filter', 'countries', 'regions', 'interests'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'index', $id]);
        }
    }

    public function organizationsReport($id = null)
    {
        $this->viewBuilder()->setLayout('organization-report');
        try {
            $organization = $this->Organizations->get($id);
            $this->_checkProfile($organization);

            $organizationsQuery = $this->Organizations->find()->contain(['Countries.Regions', 'VolunteeringCategories']);

            $search = $this->request->getQuery('s');
            if ($search != null && !empty($search)) {
                $organizationsQuery = $organizationsQuery->where(['OR' => ["MATCH(Organizations.name) AGAINST(:search IN NATURAL LANGUAGE MODE)"]])->bind(':search', $search);
            }

            $filter = $this->request->getQuery('filter');
            if (isset($filter['date_from'], $filter['date_to']) && !empty($filter['date_from']) && !empty($filter['date_to'])) {
                $from = $filter['date_from'];
                $to = $filter['date_to'];
                $organizationsQuery = $organizationsQuery->where(function ($exp, $q) use ($from) {
                    return $exp->gte('Organizations.created', $from);
                })->where(function ($exp, $q) use ($to) {
                    return $exp->lte('Organizations.created', $to);
                });
            }
            if (isset($filter['region_id']) && !empty($filter['region_id'])) {
                $region = $filter['region_id'];
                $organizationsQuery = $organizationsQuery->innerJoinWith('Countries', function ($q) use ($region) {
                    return $q->where(['Countries.region_id' => $region]);
                });
            }
            if (isset($filter['country_id']) && !empty($filter['country_id'])) {
                $organizationsQuery = $organizationsQuery->where(['Organizations.country_id' => $filter['country_id']]);
            }
            if (isset($filter['verification_status']) && $filter['verification_status'] != '') {
                $organizationsQuery = $organizationsQuery->where(['Organizations.is_verified' => $filter['verification_status']]);
            }
            if (isset($filter['status']) && !empty($filter['status'])) {
                $organizationsQuery = $organizationsQuery->where(['Organizations.status' => $filter['status']]);
            }
            if (isset($filter['sectors']) && !empty($filter['sectors'])) {
                $sectors = $filter['sectors'];
                $organizationsQuery = $organizationsQuery->innerJoinWith('VolunteeringCategories', function ($q) use ($sectors) {
                    return $q->where(['VolunteeringCategories.id IN' => $sectors]);
                });
            }

            $organizationsQuery->formatResults(function ($results) {
                return $results->map(function ($organization) {
                    $organization->no_of_volunteers = $this->Organizations->VolunteeringHistories->find()->distinct(['VolunteeringHistories.user_id'])->where(['VolunteeringHistories.organization_id' => $organization->id])->count();
                    return $organization;
                });
            });

            $total = $organizationsQuery->count();
            $organizations = $this->paginate($organizationsQuery);
            $totalActive = $organizationsQuery->filter(function ($organization) {
                return ($organization->status === STATUS_ACTIVE);
            })->count();
            $totalInactive = $organizationsQuery->filter(function ($organization) {
                return ($organization->status !== STATUS_ACTIVE);
            })->count();

            $countries = $this->Organizations->Countries->find('list')->where(['status' => STATUS_ACTIVE, 'region_id IS NOT' => null]);
            $regions = $this->Organizations->Countries->Regions->find('list')->where(['status' => STATUS_ACTIVE]);
            $sectors = $this->Organizations->VolunteeringCategories->find('list')->where(['status' => STATUS_ACTIVE]);

            $this->set(compact('organization', 'organizations', 'total', 'totalActive', 'totalInactive', 'search', 'filter', 'countries', 'regions', 'sectors'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'index', $id]);
        }
    }

    public function eventsReport($id = null)
    {
        $this->viewBuilder()->setLayout('organization-report');
        try {
            $organization = $this->Organizations->get($id);
            $this->_checkProfile($organization);

            $this->loadModel('Events');
            $eventsQuery = $this->Events->find()->contain(['Countries.Regions', 'Organizations'])->where(['Events.requesting_volunteers' => 1]);

            $search = $this->request->getQuery('s');
            if ($search != null && !empty($search)) {
                $eventsQuery = $eventsQuery->where(['OR' => ["MATCH(Events.title) AGAINST(:search IN NATURAL LANGUAGE MODE)"]])->bind(':search', $search);
            }

            $filter = $this->request->getQuery('filter');
            if (isset($filter['date_from'], $filter['date_to']) && !empty($filter['date_from']) && !empty($filter['date_to'])) {
                $from = $filter['date_from'];
                $to = $filter['date_to'];
                $eventsQuery = $eventsQuery->where(function ($exp, $q) use ($from) {
                    return $exp->gte('Events.created', $from);
                })->where(function ($exp, $q) use ($to) {
                    return $exp->lte('Events.created', $to);
                });
            }
            if (isset($filter['region_id']) && !empty($filter['region_id'])) {
                $region = $filter['region_id'];
                $eventsQuery = $eventsQuery->innerJoinWith('Countries', function ($q) use ($region) {
                    return $q->where(['Countries.region_id' => $region]);
                });
            }
            if (isset($filter['country_id']) && !empty($filter['country_id'])) {
                $eventsQuery = $eventsQuery->where(['Events.country_id' => $filter['country_id']]);
            }
            if (isset($filter['status']) && !empty($filter['status'])) {
                switch ($filter['status']) {
                    case 'past':
                        $eventsQuery = $eventsQuery->where(function ($exp, $q) {
                            return $exp->lt('Events.start_date', $q->func()->now());
                        })->where(function ($exp, $q) {
                            return $exp->lt('Events.end_date', $q->func()->now());
                        });
                        break;

                    case 'active':
                        $eventsQuery = $eventsQuery->where(function ($exp, $q) {
                            return $exp->lt('Events.start_date', $q->func()->now());
                        })->where(function ($exp, $q) {
                            return $exp->gt('Events.end_date', $q->func()->now());
                        });
                        break;

                    case 'upcoming':
                        $eventsQuery = $eventsQuery->where(function ($exp, $q) {
                            return $exp->gt('Events.start_date', $q->func()->now());
                        });
                        break;

                    default:
                        # code...
                        break;
                }
            }
            if (isset($filter['sectors']) && !empty($filter['sectors'])) {
                $sectors = $filter['sectors'];
                $eventsQuery = $eventsQuery->innerJoinWith('VolunteeringCategories', function ($q) use ($sectors) {
                    return $q->where(['VolunteeringCategories.id IN' => $sectors]);
                });
            }

            $total = $eventsQuery->count();
            $events = $this->paginate($eventsQuery);
            $totalActive = $eventsQuery->filter(function ($event) {
                $now = \Cake\I18n\Time::now();
                return ($event->start_date < $now && $event->end_date > $now);
            })->count();
            $totalUpcoming = $eventsQuery->filter(function ($event) {
                $now = \Cake\I18n\Time::now();
                return ($event->start_date > $now);
            })->count();
            $totalPast = $eventsQuery->filter(function ($event) {
                $now = \Cake\I18n\Time::now();
                return ($event->start_date < $now && $event->end_date < $now);
            })->count();

            $eventsQuery->formatResults(function ($results) {
                return $results->map(function ($event) {
                    $event->no_of_volunteers = $this->Organizations->VolunteeringHistories->find()->distinct(['VolunteeringHistories.user_id'])->innerJoinWith('VolunteeringOppurtunities.Events', function ($q) use ($event) {
                        return $q->where(['Events.id' => $event->id]);
                    })->count();
                    return $event;
                });
            });

            $countries = $this->Organizations->Countries->find('list')->where(['status' => STATUS_ACTIVE, 'region_id IS NOT' => null]);
            $regions = $this->Organizations->Countries->Regions->find('list')->where(['status' => STATUS_ACTIVE]);
            $sectors = $this->Organizations->VolunteeringCategories->find('list')->where(['status' => STATUS_ACTIVE]);

            $this->set(compact('organization', 'events', 'total', 'totalActive', 'totalUpcoming', 'totalPast', 'search', 'filter', 'countries', 'regions', 'sectors'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'index', $id]);
        }
    }
}

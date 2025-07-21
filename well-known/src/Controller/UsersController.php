<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Controller\Traits\RegisterTrait;
use Cake\Core\Configure;
use Cake\Utility\Hash;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        // $this->Email = new EmailSender();

        $this->Auth->allow([
            //Login
            'login',
            'logout',
            //Register T
            'createAccount',
            'createProfile',
            'validateEmail',
            //Password Management
            'requestResetPassword',
            'changePassword',
            'resetPassword',
            'resendTokenValidation',
            'resendEmailValidation',
            //Profile
            'userProfile',
        ]);
    }

    use RegisterTrait;

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Countries', 'Cities']
        ];
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function profile()
    {
        try {
            $user = $this->Users->get($this->Auth->user('id'), [
                'contain' => ['Countries', 'BirthCountry', 'Cities', 'OrganizationAlumni' => function ($q) {
                    return $q->contain(['Organizations'])->limit(5);
                }, 'VolunteeringInterests' => function ($q) {
                    return $q->order(['VolunteeringInterests.created' => 'DESC'])->contain([
                        'VolunteeringOppurtunities' => [
                            'Events' => ['Cities', 'Countries', 'Organizations']
                        ]
                    ])->limit(5);
                }, 'PlatformInterests']
            ]);
            $this->loadModel('VolunteeringCategories');
            $volunteeringHistoryCategory = $this->VolunteeringCategories->find()->innerJoinWith('VolunteeringOppurtunities', function ($q) use ($user) {
                return $q->innerJoinWith('VolunteeringHistories', function ($q) use ($user) {
                    return $q->where(['VolunteeringHistories.user_id' => $user->id]);
                })->group('VolunteeringOppurtunities.id');
            })->group('VolunteeringCategories.id');
            $volunteeringHistoryCategory->limit(5);
            $volunteeringHistoryCategory->formatResults(function ($results) use ($user) {
                return $results->map(function ($categoryData) use ($user) {
                    return $this->VolunteeringCategories->loadInto($categoryData, [
                        'VolunteeringOppurtunities' => [
                            'queryBuilder' => function ($q) use ($user) {
                                return $q->matching('VolunteeringHistories', function ($q) use ($user) {
                                    return $q->where(['VolunteeringHistories.user_id' => $user->id]);
                                })->limit(5);
                            },
                            'VolunteeringRoles',
                            'Events'
                        ]
                    ]);
                });
            });

            $allCountries = $this->Users->Countries->find('list')->where(['status' => STATUS_ACTIVE]);
            $africanCountries = $this->Users->Countries->find('list')->where(['status' => STATUS_ACTIVE, 'region_id IS NOT' => null]);
            $cities = $this->Users->Cities->find('list')->where(['status' => STATUS_ACTIVE, 'country_id' => $user->resident_country_id]);
            $platformInterests = $this->Users->PlatformInterests->find('list');

            if ($this->request->is(['patch', 'post', 'put'])) {
                $user = $this->Users->patchEntity($user, $this->request->getData());
                if ($this->Users->save($user)) {
                    $this->Flash->success(__('The profile details has been saved.'));
    
                    return $this->redirect(['action' => 'profile']);
                }
                $this->Flash->error(__('The profile details could not be saved. Please, try again.'));
            }
    
            $this->set(compact('allCountries', 'africanCountries', 'volunteeringHistoryCategory', 'cities', 'platformInterests'));
            $this->set('user', $user);
        } catch (\Exception $ex) {
            $this->log($ex);
            return $this->redirect('/');
        }
    }
    
    public function userProfile($id = null)
    {
        try {
            $user = $this->Users->get($id, [
                'contain' => ['Countries', 'BirthCountry', 'Cities', 'OrganizationAlumni' => function ($q) {
                    return $q->contain(['Organizations'])->limit(5);
                }, 'VolunteeringInterests' => function ($q) {
                    return $q->order(['VolunteeringInterests.created' => 'DESC'])->contain([
                        'VolunteeringOppurtunities' => [
                            'Events' => ['Cities', 'Countries', 'Organizations']
                        ]
                    ])->limit(5);
                }]
            ]);
            $this->loadModel('VolunteeringCategories');
            $volunteeringHistoryCategory = $this->VolunteeringCategories->find()->innerJoinWith('VolunteeringOppurtunities', function ($q) use ($user) {
                return $q->innerJoinWith('VolunteeringHistories', function ($q) use ($user) {
                    return $q->where(['VolunteeringHistories.user_id' => $user->id]);
                });
            })->group('VolunteeringCategories.id');
            $volunteeringHistoryCategory->limit(5);
            $volunteeringHistoryCategory->formatResults(function ($results) use ($user) {
                return $results->map(function ($categoryData) use ($user) {
                    return $this->VolunteeringCategories->loadInto($categoryData, [
                        'VolunteeringOppurtunities' => [
                            'queryBuilder' => function ($q) use ($user) {
                                return $q->matching('VolunteeringHistories', function ($q) use ($user) {
                                    return $q->where(['VolunteeringHistories.user_id' => $user->id]);
                                })->limit(5);
                            },
                            'VolunteeringRoles',
                            'Events'
                        ]
                    ]);
                });
            });
    
            $this->set(compact('volunteeringHistoryCategory'));
            $this->set('user', $user);
        } catch (\Exception $ex) {
            $this->log($ex);
            return $this->redirect($this->referer());
        }
    }

    /**
     * Volunteering interests method
     *
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function volunteeringInterests()
    {
        try {
            $user = $this->Users->get($this->Auth->user('id'), [
                'contain' => ['Countries', 'Cities', 'OrganizationAlumni' => ['Organizations']]
            ]);
            
            $volunteeringInterests = $this->Users->VolunteeringInterests->find()->where(['VolunteeringInterests.user_id' => $user->id])->order(['VolunteeringInterests.created' => 'DESC'])->contain([
                'VolunteeringOppurtunities' => [
                    'Events' => ['Cities', 'Countries']
                ]
            ]);

            $volunteeringInterests = $this->paginate($volunteeringInterests);
            $this->set(compact('volunteeringInterests'));
            $this->set('user', $user);
        } catch (\Exception $ex) {
            $this->log($ex);
            return $this->redirect('/');
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $countries = $this->Users->Countries->find('list', ['limit' => 200]);
        $cities = $this->Users->Cities->find('list', ['limit' => 200])->where(['status' => STATUS_ACTIVE]);
        $this->set(compact('user', 'countries', 'cities'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    /**
     * Register a new user
     *
     * @throws NotFoundException
     * @return mixed
     */
    public function createAccount($type = null, $level = 'organization-details')
    {
        try {
            if (!Configure::read('Users.Registration.active')) {
                throw new NotFoundException();
            }

            $userId = $this->Auth->user('id');
            if (!empty($userId) && !Configure::read('Users.Registration.allowLoggedIn')) {
                return $this->redirect(Configure::read('Users.Profile.route'));
            }

            $sessionOrgDetails = $this->request->getSession()->read('organization-details');
            $user = $this->Users->newEntity($sessionOrgDetails);
            $validateEmail = (bool)Configure::read('Users.Email.validate');
            $tokenExpiration = Configure::read('Users.Token.expiration');
            $options = [
                'token_expiration' => $tokenExpiration,
                'validate_email' => $validateEmail,
            ];
            $requestData = $this->request->getData();

            $this->loadModel('Countries');
            $countries = $this->Countries->find('list')->where(['status' => STATUS_ACTIVE, 'region_id IS NOT' => null]);
            $platformInterests = $this->Users->PlatformInterests->find('list');
            $languages = Configure::read('I18n.languages');
            foreach ($languages as $key => $language) {
                $languages[$key] = Hash::get($language, 'nativeName');
            }
            $organizationTypes = \App\Model\Table\OrganizationsTable::TYPES_LIST;

            $this->set(compact('user', 'type', 'level', 'countries', 'platformInterests', 'languages', 'organizationTypes', 'sessionOrgDetails'));
            $this->set('_serialize', ['user']);

            if (!$this->request->is('post')) {
                return;
            }

            if ($type === 'organization') {
                if ($level === 'organization-details') {
                    $level = 'user-details';
                    $this->request->getSession()->write('organization-details', $requestData);
                    $this->set(compact('level'));
                    return;
                } elseif ($level === "upload-document") {
                    $requestDataOld = $this->request->getSession()->read('organization-details');
                    $requestData['organizations'][0] = array_merge($requestData['organizations'][0], $requestDataOld['organizations'][0]);

                    if (isset($requestData['organizations'][0]['policy']['name'])) {
                        $this->loadModel('ResourceTypes');
                        $requestData['organizations'][0]['resources'][0] = [
                            'name' => $requestData['organizations'][0]['name']. ' volunteer policy',
                            'resource_type_id' => $this->ResourceTypes->find()->where(['name LIKE' => '%Volunteering Policies%'])->first()->id,
                            'file' => $requestData['organizations'][0]['policy'],
                            'status' => STATUS_ACTIVE
                        ];
                    }
                    $level = 'user-details';
                    $this->request->getSession()->write('organization-details', $requestData);
                    $this->set(compact('level'));
                    return;
                } elseif ($level === "user-details") {
                    $requestData = array_merge($requestData, $this->request->getSession()->read('organization-details'));
                }
            }

            $userSaved = $this->Users->register($user, $requestData, $options);
            if (!$userSaved) {
                $this->Flash->error(__('The user could not be saved'));
                $this->Flash->errorList($user->getErrors());
                $this->log($user->getErrors());

                return;
            }
            $this->request->getSession()->delete('organization-details');
            $this->Flash->success('Account created successfully');
            
            // $this->request->getSession()->write('user_id', $userSaved->id);
            // return $this->redirect(['action' => 'createProfile']);
            $this->_checkOrganizationInvites($userSaved);
            return $this->redirect(['action' => 'createAccount', 'success']);

            // return $this->_afterRegister($userSaved);
        } catch (\NotFoundException $ex) {
            return $this->redirect('/');
        }
    }

    public function createProfile($level = null)
    {
        try {
            $userId = $this->request->getSession()->read('user_id');

            $user = $this->Users->get($userId);

            if ($this->request->is(['patch', 'post', 'put'])) {
                $user = $this->Users->patchEntity($user, $this->request->getData());
                $user->registration_status = $this->Users::PROFILE_CREATED;
                if ($this->Users->save($user)) {
                    $this->Flash->success(__('The profile details has been saved.'));
                    if ($user->is_email_verified) {
                        $redirect = ['action' => 'login'];
                    } else {
                        $redirect = ['action' => 'createProfile', 'success'];
                    }
    
                    return $this->redirect($redirect);
                }
                $this->Flash->error(__('The profile details could not be saved. Please, try again.'));
            }

            $this->loadModel('Countries');
            $countries = $this->Countries->find('list')->where(['status' => STATUS_ACTIVE, 'region_id IS NOT' => null]);
            $volunteeringCategories = $this->Users->VolunteeringCategories->find('list')->where(['status' => STATUS_ACTIVE]);

            $this->set(compact('user', 'countries', 'level', 'volunteeringCategories'));
            $this->set('_serialize', ['user']);

        } catch (\NotFoundException $ex) {
            return $this->redirect($this->referer());
        } catch (\Exception $ex) {
            return $this->redirect($this->referer());
        }
    }

    /**
     * Login method
     *
     * @return \Cake\Http\Response|null
     */
    public function login()
    {
        $userId = $this->Auth->user('id');
        if (!empty($userId)) {
            return $this->redirect(Configure::read('Users.Profile.route'));
        }

        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                // if ($user['registration_status'] === $this->Users::ACCOUNT_CREATED) {
                //     $this->request->getSession()->write('user_id', $user['id']);
                //     return $this->redirect(['action' => 'createProfile']);
                // }
                // if ($user['is_email_verified']) {
                    $user = $this->Users->get($user['id'], [
                        'contain' => ['PlatformInterests']
                    ]);
                    $this->Auth->setUser($user->toArray());
    
                    return $this->redirect($this->Auth->redirectUrl());
                // } else {
                //     $this->Flash->verifyEmail(__('Email not yet verified. Follow the link from the registration welcome mail to verify your email.'), ['params' => ['id' => $user['id']]]);
                //     return;
                // }
            }
            $this->Flash->error(__('Invalid credentials, try again'));
        }
    }

    /**
     * Logout method
     *
     * @return \Cake\Http\Response
     */
    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    public function _checkOrganizationInvites($user = null)
    {
        if ($user !== null && !empty($user)) {
            $this->loadModel('TmpOrganizationUsers');
            $this->loadModel('OrganizationUsers');
            $invites = $this->TmpOrganizationUsers->find()->where(['email' => $user->email]);
            if ($invites->count() > 0) {
                foreach ($invites as $invite) {
                    $organizationUser = $this->OrganizationUsers->newEntity([
                        'user_id' => $user->id,
                        'organization_id' => $invite->organization_id,
                        'role' => $invite->role,
                        'status' => STATUS_ACTIVE
                    ]);
                    $this->OrganizationUsers->save($organizationUser);
                }
            }
        }
    }

    public function uploadProfileImage()
    {
        try {
            if ($this->request->is(['patch', 'post', 'put'])) {
                $user = $this->Users->get($this->Auth->user('id'));
                $cropped_image = $this->request->getData('image');
                $this->loadComponent('CloudUpload');
                
                $uploaded = $this->CloudUpload->upload($cropped_image, [
                    'path' => 'users',
                    'resize' => false,
                ]);

                if ($uploaded) {
                    $data = ['profile_image' => $uploaded['url']];
                    $user = $this->Users->patchEntity($user, $data);
                    if ($this->Users->save($user)) {
                        $response = $this->response->withType('application/json')->withStringBody(json_encode([
                            'status' => 'success',
                            'message' => 'Profile saved successfully'
                        ]));
                    } else {
                        $response = $this->response->withType('application/json')->withStringBody(json_encode([
                            'status' => 'error',
                            'message' => 'Error saving profile',
                            'errors' => $user->getErrors()
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
}

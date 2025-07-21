<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Dashboard Controller
 *
 *
 * @method \App\Model\Entity\Dashboard[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DashboardController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->loadModel('Resources');
        $this->loadModel('BlogPosts');
        $this->loadModel('News');
        $this->loadModel('Events');
        $this->loadModel('Organizations');
        $this->loadModel('Users');

        $resources = $this->Resources->find()->order(['created' => 'DESC'])->limit(4);
        $blogPosts = $this->BlogPosts->find()->order(['BlogPosts.created' => 'DESC'])->limit(4);
        $news = $this->News->find()->order(['News.created' => 'DESC'])->limit(4);
        
        $pastEvents = $this->Events->find()->where(['start_date <' => 'NOW()'])->count();
        $upcomingEvents = $this->Events->find()->where(['start_date >=' => 'NOW()'])->count();

        $organizations = $this->Organizations->find()->where(['organization_type_id' => $this->Organizations::VOLUNTEERING_ORG])->count();
        $organizationsActive = $this->Organizations->find()->where(['organization_type_id' => $this->Organizations::VOLUNTEERING_ORG, 'status' => STATUS_ACTIVE])->count();
        $organizationsInactive = $this->Organizations->find()->where(['organization_type_id' => $this->Organizations::VOLUNTEERING_ORG])->where(['OR' => [['status IS NOT' => STATUS_ACTIVE], ['status IS' => null]]])->count();

        $institutions = $this->Organizations->find()->where(['organization_type_id' => $this->Organizations::GOVERNMENT_ORG])->count();
        $institutionsActive = $this->Organizations->find()->where(['organization_type_id' => $this->Organizations::GOVERNMENT_ORG, 'status' => STATUS_ACTIVE])->count();
        $institutionsInactive = $this->Organizations->find()->where(['organization_type_id' => $this->Organizations::GOVERNMENT_ORG])->where(['OR' => [['status IS NOT' => STATUS_ACTIVE], ['status IS' => null]]])->count();

        $volunteers = $this->Users->find()->count();
        $volunteersActive = $this->Users->find()->where(['Users.status' => STATUS_ACTIVE])->count();
        $volunteersInactive = $this->Users->find()->where(['OR' => [['Users.status IS NOT' => STATUS_ACTIVE], ['Users.status IS' => null]]])->count();

        $this->set(compact('resources', 'blogPosts', 'news', 'pastEvents', 'upcomingEvents', 'organizations', 'organizationsActive', 'organizationsInactive', 'institutions', 'institutionsActive', 'institutionsInactive', 'volunteers', 'volunteersActive', 'volunteersInactive'));
    }

    /**
     * Login method
     *
     * @return \Cake\Http\Response|null
     */
    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);

                return $this->redirect($this->Auth->redirectUrl());
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

    /**
     * Audit trail method
     * 
     * @return \Cake\Http\Response
     */
    public function auditTrail()
    {
        $this->loadModel('ActivityLogs');
        $logs = $this->ActivityLogs->find()->select(['ActivityLogs.object_model', 'ActivityLogs.object_id', 'ActivityLogs.action', 'ActivityLogs.data', 'issuer' => 'Admins.name', 'ActivityLogs.created_at'])->innerJoinWith('Admins')->order(['ActivityLogs.created_at' => 'DESC']);
        
        $action = $this->request->getQuery('action');
        if($action != null && $action !== '') {
            $logs = $logs->where(['ActivityLogs.action' => $action]);
        }
        
        $search = $this->request->getQuery('s');
        if($search != null && $search !== '') {
            $logs = $logs->where(['OR' => [
                'ActivityLogs.object_model LIKE' => "%$search%",
                'Admins.name LIKE' => "%$search%",
            ]]);
        }

        $range = $this->request->getQuery('range');
        if($range != null && $range !== '') {
            $dates = explode('/', $range);
            $logs = $logs->where(function ($exp, $q) use ($dates) {
                return $exp->gte('ActivityLogs.created_at', $dates[0]);
            })->where(function ($exp, $q) use ($dates) {
                return $exp->lte('ActivityLogs.created_at', $dates[1]);
            });
        }

        $total = $logs->count();
        $logs = $this->paginate($logs);
        $this->set(compact('logs', 'total', 'search', 'action', 'range'));
    }

    /**
     * User feedbacks method
     * 
     * @return \Cake\Http\Response
     */
    public function userFeedbacks()
    {
        $this->loadModel('UserFeedbacks');
        $feedbacks = $this->UserFeedbacks->find()->contain('Users')->order(['UserFeedbacks.created' => 'DESC']);
        
        $rating = $this->request->getQuery('rating');
        if($rating != null && $rating !== '') {
            $feedbacks = $feedbacks->where(['UserFeedbacks.feedback_rating' => $rating]);
        }

        $range = $this->request->getQuery('range');
        if($range != null && $range !== '') {
            $dates = explode('/', $range);
            $feedbacks = $feedbacks->where(function ($exp, $q) use ($dates) {
                return $exp->gte('UserFeedbacks.created', $dates[0]);
            })->where(function ($exp, $q) use ($dates) {
                return $exp->lte('UserFeedbacks.created', $dates[1]);
            });
        }

        $total = $feedbacks->count();
        $feedbacks = $this->paginate($feedbacks);
        $this->set(compact('feedbacks', 'total', 'rating', 'range'));
    }

    /**
     * Notifications method
     * 
     * @return \Cake\Http\Response
     */
    public function notifications($status = 'unread')
    {
        $this->loadModel('Notifications');
        $notifications = $this->Notifications->find()->order(['Notifications.created' => 'DESC'])->contain(['News.Organizations', 'Organizations', 'Resources.Organizations', 'Events.Organizations']);
        
        $action = $this->request->getQuery('action');
        if($action != null && $action !== '') {
            $notifications = $notifications->where(['Notifications.action' => $action]);
        }
        
        $search = $this->request->getQuery('s');
        if($search != null && $search !== '') {
            $notifications = $notifications->where(['OR' => [
                'Notifications.object_model LIKE' => "%$search%",
            ]]);
        }

        $range = $this->request->getQuery('range');
        if($range != null && $range !== '') {
            $dates = explode('/', $range);
            $notifications = $notifications->where(function ($exp, $q) use ($dates) {
                return $exp->gte('Notifications.created', $dates[0]);
            })->where(function ($exp, $q) use ($dates) {
                return $exp->lte('Notifications.created', $dates[1]);
            });
        }

        $total = $notifications->count();
        $notifications = $this->paginate($notifications);
        $this->set(compact('notifications', 'total', 'search', 'action', 'range'));
    }

    public function updateReadNotification($id = null)
    {
        try {
            $this->loadModel('Notifications');
            $notification = $this->Notifications->get($id);
            $notification->is_read = 1;
            $this->Notifications->save($notification);
            return $this->redirect(['action' => 'notifications']);

        } catch (\Throwable $th) {
            $this->log($th);
            return $this->redirect(['action' => 'notifications']);
        }
    }

    public function volunteersReport()
    {
        try {
            $this->loadModel('Organizations');
            $this->loadModel('Users');
            $volunteers = $this->Users->find()->contain('Countries.Regions')->innerJoinWith('VolunteeringHistories')->group('Users.id');

            $search = $this->request->getQuery('s');
            if($search != null && !empty($search)) {
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
                $volunteers = $volunteers->innerJoinWith('VolunteeringHistories', function($q) use($from, $to) {
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
                $volunteers = $volunteers->where(['Users.country_id' => $filter['country_id'] ]);
            }
            if (isset($filter['gender']) && !empty($filter['gender'])) {
                $volunteers = $volunteers->where(['Users.gender' => $filter['gender'] ]);
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

            $this->set(compact('volunteers', 'total', 'totalFemale', 'totalMale', 'search', 'filter', 'countries', 'regions', 'interests'));

        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function organizationsReport()
    {
        try {
            $this->loadModel('Organizations');
            $organizationsQuery = $this->Organizations->find()->contain(['Countries.Regions', 'VolunteeringCategories']);

            $search = $this->request->getQuery('s');
            if($search != null && !empty($search)) {
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
                $organizationsQuery = $organizationsQuery->where(['Organizations.country_id' => $filter['country_id'] ]);
            }
            if (isset($filter['verification_status']) && $filter['verification_status'] != '') {
                $organizationsQuery = $organizationsQuery->where(['Organizations.is_verified' => $filter['verification_status'] ]);
            }
            if (isset($filter['status']) && !empty($filter['status'])) {
                $organizationsQuery = $organizationsQuery->where(['Organizations.status' => $filter['status'] ]);
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

            $this->set(compact('organizations', 'total', 'totalActive', 'totalInactive', 'search', 'filter', 'countries', 'regions', 'sectors'));

        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function eventsReport()
    {
        try {
            $this->loadModel('Organizations');
            $this->loadModel('Events');
            $eventsQuery = $this->Events->find()->contain(['Countries.Regions', 'Organizations'])->where(['Events.requesting_volunteers' =>1]);

            $search = $this->request->getQuery('s');
            if($search != null && !empty($search)) {
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
                $eventsQuery = $eventsQuery->where(['Events.country_id' => $filter['country_id'] ]);
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

            $this->set(compact('events', 'total', 'totalActive', 'totalUpcoming', 'totalPast', 'search', 'filter', 'countries', 'regions', 'sectors'));

        } catch (\Throwable $ex) {
            $this->log($ex);
            $this->Flash->error(__('An error occured. Please, try again.'));
            return $this->redirect(['action' => 'index']);
        }
    }
}

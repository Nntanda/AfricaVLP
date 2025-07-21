<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Countries']
        ];
        $this->loadModel('VolunteeringInterests');
        $users = $this->Users->find();

        $status = $this->request->getQuery('status');
        if($status != null && $status !== '') {
            $users = $users->where(['Users.status' => $status]);
        }
        
        $search = $this->request->getQuery('s');
        if($search != null && $search !== '') {
            $users = $users->where(['OR' => [
                ['Users.first_name LIKE' => "%$search%"],
                ['Users.last_name LIKE' => "%$search%"],
                ['Users.email LIKE' => "%$search%"]
            ]]);
        }
        
        $region_id = $this->request->getQuery('region_id');
        if($region_id != null && $region_id !== '') {
            $users = $users->innerJoinWith('Countries', function ($q) use ($region_id) {
                return $q->where(['Countries.region_id' => $region_id]);
            })->group('Users.id');
        }
        $total = $users->count();
        $users = $this->paginate($users);

        $statuses = Configure::read('STATUSES');
        $this->loadModel('Regions');
        $regions = $this->Regions->find('list', ['limit' => 200]);

        $this->set(compact('users', 'total', 'type', 'statuses', 'status', 'search', 'regions', 'region_id'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null, $sub = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Countries', 'Cities']
        ]);

        switch ($sub) {
            case 'volunteering_experience':
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
                $user->volunteer_badges = $volunteeringHistoryCategory;
                break;
            
            case 'volunteering_interests':
                $interests = $this->Users->VolunteeringInterests->find()->where(['user_id' => $user->id])->contain(['VolunteeringOppurtunities' => ['Events', 'VolunteeringRoles']])->limit(10);
                $user->volunteering_interests = $interests;
                break;
            
            default:
                # code...
                break;
        }
        $statuses = Configure::read('STATUSES');

        $this->set('user', $user);
        $this->set(compact('sub', 'statuses'));
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

                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $countries = $this->Users->Countries->find('list', ['limit' => 200]);
        $cities = $this->Users->Cities->find('list', ['limit' => 200]);
        $this->set(compact('user', 'countries', 'cities'));
        return $this->redirect($this->referer());
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

    public function download(){
        $this->paginate = [
            'contain' => ['Countries']
        ];
        $this->loadModel('VolunteeringInterests');
        $users = $this->Users->find()
        ->select([
            'id', 'first_name', 'last_name', 'email',
            'resident_country_id' , 'profile_image', 'gender',
            'date_of_birth', 'marital_status', 'phone_number', 'current_address'
        ]);

        $status = $this->request->getQuery('status');
        if($status != null && $status !== '') {
            $users = $users->where(['Users.status' => $status]);
        }
        
        $search = $this->request->getQuery('s');
        if($search != null && $search !== '') {
            $users = $users->where(['OR' => [
                ['Users.first_name LIKE' => "%$search%"],
                ['Users.last_name LIKE' => "%$search%"],
                ['Users.email LIKE' => "%$search%"]
            ]]);
        }
        
        $region_id = $this->request->getQuery('region_id');
        if($region_id != null && $region_id !== '') {
            $users = $users->innerJoinWith('Countries', function ($q) use ($region_id) {
                return $q->where(['Countries.region_id' => $region_id]);
            })->group('Users.id');
        }
        $total = $users->count();
        //$users = $this->paginate($users);

        $statuses = Configure::read('STATUSES');
        $this->loadModel('Regions');
        $regions = $this->Regions->find('list', ['limit' => 200]);

        $this->set(compact('users', 'total', 'type', 'statuses', 'status', 'search', 'regions', 'region_id'));    
        $this->layout = null;
        $this->autoLayout = false;
        Configure::write('debug', 0);
    }
}

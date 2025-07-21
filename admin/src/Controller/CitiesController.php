<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Filesystem\File;
use Cake\Utility\Hash;
use Cake\Core\Configure;

/**
 * Cities Controller
 *
 * @property \App\Model\Table\CitiesTable $Cities
 *
 * @method \App\Model\Entity\City[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CitiesController extends AppController
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
        $country_id = $this->request->getQuery('country_id');
        $status = $this->request->getQuery('status');
        $search = $this->request->getQuery('s');
        $cities = $this->Cities->find();

        if($country_id != null && $country_id !== '') {
            $cities = $cities->where(['Cities.country_id' => $country_id]);
        }
        if($status != null && $status !== '') {
            $cities = $cities->where(['Cities.status' => $status]);
        }
        if($search != null && $search !== '') {
            $cities = $cities->where(['OR' => [
                'Cities.name LIKE' => "%$search%",
            ]]);
        }

        $total = $cities->count();
        $cities = $this->paginate($cities);
        $countries = $this->Cities->Countries->find('list')->where(['Countries.region_id IS NOT' => Null]);
        $statuses = Configure::read('STATUSES');

        $this->set(compact('cities', 'countries', 'statuses', 'country_id', 'status', 'search', 'total'));
    }

    /**
     * View method
     *
     * @param string|null $id City id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        try {
            $city = $this->Cities->get($id, [
                'contain' => ['Countries', 'Events', 'OrganizationOffices', 'Organizations', 'Users']
            ]);
    
            $this->set('city', $city);
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $ex) {
            $this->Flash->error(__('The record was not found.'));

            return $this->redirect(['action' => 'index']);
        }
        
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $city = $this->Cities->newEntity();
        
        if ($this->request->is('post')) {
            $city = $this->Cities->patchEntity($city, $this->request->getData());
            if ($this->Cities->save($city)) {
                $this->Flash->success(__('The city has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The city could not be saved. Please, try again.'));
        }
        $countries = $this->Cities->Countries->find('list')->where(['Countries.region_id IS NOT' => Null]);
        $statuses = Configure::read('STATUSES');
        $this->set(compact('city', 'countries', 'statuses'));
    }

    /**
     * Edit method
     *
     * @param string|null $id City id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        try {
            $city = $this->Cities->get($id, [
                'contain' => []
            ]);
            if ($this->request->is(['patch', 'post', 'put'])) {
                $city = $this->Cities->patchEntity($city, $this->request->getData());
                if ($this->Cities->save($city)) {
                    $this->Flash->success(__('The city has been saved.'));
    
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The city could not be saved. Please, try again.'));
            }
            $countries = $this->Cities->Countries->find('list')->where(['Countries.region_id IS NOT' => Null]);
            $statuses = Configure::read('STATUSES');
            $this->set(compact('city', 'countries', 'statuses'));
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $ex) {
            $this->Flash->error(__('The record was not found.'));

            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id City id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        try {
            $city = $this->Cities->get($id);
            if ($this->Cities->delete($city)) {
                $this->Flash->success(__('The city has been deleted.'));
            } else {
                $this->Flash->error(__('The city could not be deleted. Please, try again.'));
            }
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $ex) {
            $this->Flash->error(__('The record was not found.'));

            return $this->redirect(['action' => 'index']);
        }

        return $this->redirect(['action' => 'index']);
    }
}

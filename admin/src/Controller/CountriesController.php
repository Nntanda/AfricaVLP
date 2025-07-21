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
 * @method \App\Model\Entity\country[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CountriesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Regions']
        ];
        $region_id = $this->request->getQuery('region_id');
        $status = $this->request->getQuery('status');
        $search = $this->request->getQuery('s');

        $countries = $this->Countries->find()->where(['Countries.region_id IS NOT' => Null]);

        if($region_id != null && $region_id !== '') {
            $countries = $countries->where(['Countries.region_id' => $region_id]);
        }
        if($status != null && $status !== '') {
            $countries = $countries->where(['Countries.status' => $status]);
        }
        if($search != null && $search !== '') {
            $countries = $countries->where(['OR' => [
                'Countries.name LIKE' => "%$search%",
            ]]);
        }

        $total = $countries->count();
        $countries = $this->paginate($countries);
        $statuses = Configure::read('STATUSES');
        $regions = $this->Countries->Regions->find('list');

        $this->set(compact('countries', 'statuses', 'regions', 'region_id', 'status', 'search', 'total'));
    }

    /**
     * View method
     *
     * @param string|null $id country id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        try {
            $country = $this->Countries->get($id, [
                'contain' => ['Events', 'Organizations']
            ]);
    
            $this->set('country', $country);
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
        $country = $this->Countries->newEntity();
        
        if ($this->request->is('post')) {
            $country = $this->Countries->patchEntity($country, $this->request->getData());
            if ($this->Countries->save($country)) {
                $this->Flash->success(__('The country has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The country could not be saved. Please, try again.'));
        }
        $regions = $this->Countries->Regions->find('list');
        $statuses = Configure::read('STATUSES');
        $this->set(compact('country', 'regions', 'statuses'));
    }

    /**
     * Edit method
     *
     * @param string|null $id country id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        try {
            $country = $this->Countries->get($id, [
                'contain' => []
            ]);
            if ($this->request->is(['patch', 'post', 'put'])) {
                $country = $this->Countries->patchEntity($country, $this->request->getData());
                if ($this->Countries->save($country)) {
                    $this->Flash->success(__('The country has been saved.'));
    
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The country could not be saved. Please, try again.'));
            }
            $regions = $this->Countries->Regions->find('list');
            $statuses = Configure::read('STATUSES');
            $this->set(compact('country', 'regions', 'statuses'));
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $ex) {
            $this->Flash->error(__('The record was not found.'));

            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id country id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        try {
            $country = $this->Countries->get($id);
            if ($this->Countries->delete($country)) {
                $this->Flash->success(__('The country has been deleted.'));
            } else {
                $this->Flash->error(__('The country could not be deleted. Please, try again.'));
            }
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $ex) {
            $this->Flash->error(__('The record was not found.'));

            return $this->redirect(['action' => 'index']);
        }

        return $this->redirect(['action' => 'index']);
    }
}

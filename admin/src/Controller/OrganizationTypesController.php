<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

/**
 * OrganizationTypes Controller
 *
 * @property \App\Model\Table\OrganizationTypesTable $OrganizationTypes
 *
 * @method \App\Model\Entity\OrganizationType[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrganizationTypesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $organizationTypes = $this->paginate($this->OrganizationTypes);

        $this->set(compact('organizationTypes'));
    }

    /**
     * View method
     *
     * @param string|null $id Organization Type id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        try {
            $organizationType = $this->OrganizationTypes->get($id, [
                'contain' => []
            ]);
    
            $this->set('organizationType', $organizationType);
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
        $organizationType = $this->OrganizationTypes->newEntity();
        if ($this->request->is('post')) {
            $organizationType = $this->OrganizationTypes->patchEntity($organizationType, $this->request->getData());
            if ($this->OrganizationTypes->save($organizationType)) {
                $this->Flash->success(__('The organization type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The organization type could not be saved. Please, try again.'));
        }
        $statuses = Configure::read('STATUSES');
        $this->set(compact('organizationType', 'statuses'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Organization Type id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->OrganizationTypes->setlocale('en_GB');
        $organizationType = $this->OrganizationTypes->find('translations')->where(['OrganizationTypes.id' => $id])->first();
        if ($organizationType == null) {
            $this->Flash->error(__('The record was not found.'));

            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $organizationType = $this->OrganizationTypes->patchEntity($organizationType, $this->request->getData());
            if ($this->OrganizationTypes->save($organizationType)) {
                $this->Flash->success(__('The organization type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The organization type could not be saved. Please, try again.'));
        }
        $statuses = Configure::read('STATUSES');
        $this->set(compact('organizationType', 'statuses'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Organization Type id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        try {
            $organizationType = $this->OrganizationTypes->get($id);
            if ($this->OrganizationTypes->delete($organizationType)) {
                $this->Flash->success(__('The organization type has been deleted.'));
            } else {
                $this->Flash->error(__('The organization type could not be deleted. Please, try again.'));
            }

        } catch (\Cake\Datasource\Exception\RecordNotFoundException $ex) {
            $this->Flash->error(__('The record was not found.'));

            return $this->redirect(['action' => 'index']);
        }

        return $this->redirect(['action' => 'index']);
    }
}

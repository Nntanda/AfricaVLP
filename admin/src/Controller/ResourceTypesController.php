<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

/**
 * ResourceTypes Controller
 *
 * @property \App\Model\Table\ResourceTypesTable $ResourceTypes
 *
 * @method \App\Model\Entity\ResourceType[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ResourceTypesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $resourceTypes = $this->paginate($this->ResourceTypes);

        $this->set(compact('resourceTypes'));
    }

    /**
     * View method
     *
     * @param string|null $id Resource Type id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        try {
            $resourceType = $this->ResourceTypes->get($id, [
                'contain' => ['ResourceTypes_name_translation', 'I18n', 'Resources']
            ]);
    
            $this->set('resourceType', $resourceType);
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
        $resourceType = $this->ResourceTypes->newEntity();
        if ($this->request->is('post')) {
            $resourceType = $this->ResourceTypes->patchEntity($resourceType, $this->request->getData());
            if ($this->ResourceTypes->save($resourceType)) {
                $this->Flash->success(__('The resource type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The resource type could not be saved. Please, try again.'));
        }
        $statuses = Configure::read('STATUSES');
        $this->set(compact('resourceType', 'statuses'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Resource Type id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->ResourceTypes->setlocale('en_GB');
        $resourceType = $this->ResourceTypes->find('translations')->where(['ResourceTypes.id' => $id])->first();
        if ($resourceType == null) {
            $this->Flash->error(__('The record was not found.'));

            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $resourceType = $this->ResourceTypes->patchEntity($resourceType, $this->request->getData());
            if ($this->ResourceTypes->save($resourceType)) {
                $this->Flash->success(__('The resource type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The resource type could not be saved. Please, try again.'));
        }
        $statuses = Configure::read('STATUSES');
        $this->set(compact('resourceType', 'statuses'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Resource Type id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        try {
            $resourceType = $this->ResourceTypes->get($id);
            if ($this->ResourceTypes->delete($resourceType)) {
                $this->Flash->success(__('The resource type has been deleted.'));
            } else {
                $this->Flash->error(__('The resource type could not be deleted. Please, try again.'));
            }

        } catch (\Cake\Datasource\Exception\RecordNotFoundException $ex) {
            $this->Flash->error(__('The record was not found.'));

            return $this->redirect(['action' => 'index']);
        }

        return $this->redirect(['action' => 'index']);
    }
}

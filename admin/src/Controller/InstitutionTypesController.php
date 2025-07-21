<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

/**
 * InstitutionTypes Controller
 *
 * @property \App\Model\Table\InstitutionTypesTable $InstitutionTypes
 *
 * @method \App\Model\Entity\InstitutionType[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InstitutionTypesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $institutionTypes = $this->paginate($this->InstitutionTypes);

        $this->set(compact('institutionTypes'));
    }

    /**
     * View method
     *
     * @param string|null $id Institution Type id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        try {
            $institutionType = $this->InstitutionTypes->get($id, [
                'contain' => ['Organizations']
            ]);
    
            $this->set('institutionType', $institutionType); 
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
        $institutionType = $this->InstitutionTypes->newEntity();
        if ($this->request->is('post')) {
            $institutionType = $this->InstitutionTypes->patchEntity($institutionType, $this->request->getData());
            if ($this->InstitutionTypes->save($institutionType)) {
                $this->Flash->success(__('The institution type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The institution type could not be saved. Please, try again.'));
        }
        $statuses = Configure::read('STATUSES');
        $this->set(compact('institutionType', 'statuses'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Institution Type id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->InstitutionTypes->setlocale('en_GB');
        $institutionType = $this->InstitutionTypes->find('translations')->where(['InstitutionTypes.id' => $id])->first();
        if ($institutionType == null) {
            $this->Flash->error(__('The record was not found.'));

            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $institutionType = $this->InstitutionTypes->patchEntity($institutionType, $this->request->getData());
            if ($this->InstitutionTypes->save($institutionType)) {
                $this->Flash->success(__('The institution type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The institution type could not be saved. Please, try again.'));
        }
        $statuses = Configure::read('STATUSES');
        $this->set(compact('institutionType', 'statuses'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Institution Type id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        try {
            $institutionType = $this->InstitutionTypes->get($id);
            if ($this->InstitutionTypes->delete($institutionType)) {
                $this->Flash->success(__('The institution type has been deleted.'));
            } else {
                $this->Flash->error(__('The institution type could not be deleted. Please, try again.'));
            }

        } catch (\Cake\Datasource\Exception\RecordNotFoundException $ex) {
            $this->Flash->error(__('The record was not found.'));

            return $this->redirect(['action' => 'index']);
        }

        return $this->redirect(['action' => 'index']);
    }
}

<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Utility\Hash;
use Cake\Filesystem\File;

/**
 * VolunteeringRoles Controller
 *
 * @property \App\Model\Table\VolunteeringRolesTable $VolunteeringRoles
 *
 * @method \App\Model\Entity\VolunteeringRole[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class VolunteeringRolesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $volunteeringRoles = $this->paginate($this->VolunteeringRoles);

        $this->set(compact('volunteeringRoles'));
    }

    /**
     * View method
     *
     * @param string|null $id Volunteering Role id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        try {
            $volunteeringRole = $this->VolunteeringRoles->get($id, [
                'contain' => ['VolunteeringOppurtunities']
            ]);
    
            $this->set('volunteeringRole', $volunteeringRole);
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
        $volunteeringRole = $this->VolunteeringRoles->newEntity();
        if ($this->request->is('post')) {
            $volunteeringRole = $this->VolunteeringRoles->patchEntity($volunteeringRole, $this->request->getData());
            if ($this->VolunteeringRoles->save($volunteeringRole)) {
                $this->Flash->success(__('The volunteering role has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The volunteering role could not be saved. Please, try again.'));
        }
        $statuses = Configure::read('STATUSES');
        $this->set(compact('volunteeringRole', 'statuses'));
    }

    public function addDefault()
    {
        $roles_entity_file = new File(CONFIG.'v_roles_entity.json');
        $volunteeringRoles = json_decode($roles_entity_file->read(), true);
        $roles_entity_file->close();

        $remRoles = $volunteeringRoles;
        foreach ($volunteeringRoles as $key => $role) {
            $volunteeringRole = $this->VolunteeringRoles->newEntity($role);
            if ($saved = $this->VolunteeringRoles->save($volunteeringRole)) {
                $remRoles = Hash::remove($remRoles, "{$key}");
            }
        }
        $roles_entity_file = new File(CONFIG.'v_roles_entity.json');
        $roles_entity_file->write(json_encode($this->VolunteeringRoles->newEntities($remRoles)));
        $roles_entity_file->close();
        
        dd($saved);
    }

    /**
     * Edit method
     *
     * @param string|null $id Volunteering Role id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->VolunteeringRoles->setlocale('en_GB');
        $volunteeringRole = $this->VolunteeringRoles->find('translations')->where(['VolunteeringRoles.id' => $id])->first();
        if ($volunteeringRole == null) {
            $this->Flash->error(__('The record was not found.'));

            return $this->redirect(['action' => 'index']);
        }
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $volunteeringRole = $this->VolunteeringRoles->patchEntity($volunteeringRole, $this->request->getData());
            if ($this->VolunteeringRoles->save($volunteeringRole)) {
                $this->Flash->success(__('The volunteering role has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The volunteering role could not be saved. Please, try again.'));
        }
        $statuses = Configure::read('STATUSES');
        $this->set(compact('volunteeringRole', 'statuses'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Volunteering Role id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        try {
            $volunteeringRole = $this->VolunteeringRoles->get($id);
            if ($this->VolunteeringRoles->delete($volunteeringRole)) {
                $this->Flash->success(__('The volunteering role has been deleted.'));
            } else {
                $this->Flash->error(__('The volunteering role could not be deleted. Please, try again.'));
            }

        } catch (\Cake\Datasource\Exception\RecordNotFoundException $ex) {
            $this->Flash->error(__('The record was not found.'));

            return $this->redirect(['action' => 'index']);
        }

        return $this->redirect(['action' => 'index']);
    }
}

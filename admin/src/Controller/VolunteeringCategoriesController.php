<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

/**
 * VolunteeringCategories Controller
 *
 * @property \App\Model\Table\VolunteeringCategoriesTable $VolunteeringCategories
 *
 * @method \App\Model\Entity\VolunteeringCategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class VolunteeringCategoriesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $volunteeringCategories = $this->paginate($this->VolunteeringCategories);

        $this->set(compact('volunteeringCategories'));
    }

    /**
     * View method
     *
     * @param string|null $id Volunteering Category id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $volunteeringCategory = $this->VolunteeringCategories->get($id, [
            'contain' => ['EventCategories', 'NewsCategories', 'OrganizationCategories']
        ]);

        $this->set('volunteeringCategory', $volunteeringCategory);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {        
        $volunteeringCategory = $this->VolunteeringCategories->newEntity();
        if ($this->request->is('post')) {
            $volunteeringCategory = $this->VolunteeringCategories->patchEntity($volunteeringCategory, $this->request->getData());
            if ($this->VolunteeringCategories->save($volunteeringCategory)) {
                $this->Flash->success(__('The volunteering category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The volunteering category could not be saved. Please, try again.'));
        }
        $statuses = Configure::read('STATUSES');
        $this->set(compact('volunteeringCategory', 'statuses'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Volunteering Category id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->VolunteeringCategories->setlocale('en_GB');
        $volunteeringCategory = $this->VolunteeringCategories->find('translations')->where(['VolunteeringCategories.id' => $id])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $volunteeringCategory = $this->VolunteeringCategories->patchEntity($volunteeringCategory, $this->request->getData());
            if ($this->VolunteeringCategories->save($volunteeringCategory)) {
                $this->Flash->success(__('The volunteering category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The volunteering category could not be saved. Please, try again.'));
        }
        $statuses = Configure::read('STATUSES');
        $this->set(compact('volunteeringCategory', 'statuses'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Volunteering Category id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $volunteeringCategory = $this->VolunteeringCategories->get($id);
        if ($this->VolunteeringCategories->delete($volunteeringCategory)) {
            $this->Flash->success(__('The volunteering category has been deleted.'));
        } else {
            $this->Flash->error(__('The volunteering category could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

/**
 * CategoryOfResources Controller
 *
 * @property \App\Model\Table\CategoryOfResourcesTable $CategoryOfResources
 *
 * @method \App\Model\Entity\CategoryOfResource[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoryOfResourcesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $categoryOfResources = $this->paginate($this->CategoryOfResources);

        $this->set(compact('categoryOfResources'));
    }

    /**
     * View method
     *
     * @param string|null $id Category Of Resource id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $categoryOfResource = $this->CategoryOfResources->get($id, [
            'contain' => ['ResourceCategories']
        ]);

        $this->set('categoryOfResource', $categoryOfResource);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $categoryOfResource = $this->CategoryOfResources->newEntity();
        if ($this->request->is('post')) {
            $categoryOfResource = $this->CategoryOfResources->patchEntity($categoryOfResource, $this->request->getData());
            if ($this->CategoryOfResources->save($categoryOfResource)) {
                $this->Flash->success(__('The category of resource has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The category of resource could not be saved. Please, try again.'));
        }
        $statuses = Configure::read('STATUSES');
        $this->set(compact('categoryOfResource', 'statuses'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Category Of Resource id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->CategoryOfResources->setlocale('en_GB');
        $categoryOfResource = $this->CategoryOfResources->find('translations')->where(['CategoryOfResources.id' => $id])->first();
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $categoryOfResource = $this->CategoryOfResources->patchEntity($categoryOfResource, $this->request->getData());
            if ($this->CategoryOfResources->save($categoryOfResource)) {
                $this->Flash->success(__('The category of resource has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The category of resource could not be saved. Please, try again.'));
        }
        $statuses = Configure::read('STATUSES');
        $this->set(compact('categoryOfResource', 'statuses'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Category Of Resource id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $categoryOfResource = $this->CategoryOfResources->get($id);
        if ($this->CategoryOfResources->delete($categoryOfResource)) {
            $this->Flash->success(__('The category of resource has been deleted.'));
        } else {
            $this->Flash->error(__('The category of resource could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

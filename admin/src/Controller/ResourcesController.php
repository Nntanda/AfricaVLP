<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

/**
 * Resources Controller
 *
 * @property \App\Model\Table\ResourcesTable $Resources
 *
 * @method \App\Model\Entity\Resource[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ResourcesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Organizations', 'ResourceTypes', 'Regions', 'Countries']
        ];
        $resources = $this->Resources->find()->order(['Resources.created' => 'DESC']);

        $status = $this->request->getQuery('status');
        if($status != null && $status !== '') {
            $resources = $resources->where(['Resources.status' => $status]);
        }
        
        $search = $this->request->getQuery('s');
        if($search != null && $search !== '') {
            $resources = $resources->where(['OR' => [
                ["MATCH(Resources.title) AGAINST(:search IN NATURAL LANGUAGE MODE)"],
                ["MATCH(Resources.description) AGAINST(:search IN NATURAL LANGUAGE MODE)"],
            ]])->bind(':search', $search);
        }
        
        $region_id = $this->request->getQuery('region_id');
        if($region_id != null && $region_id !== '') {
            $resources = $resources->where(['Resources.region_id' => $region_id]);
        }
        
        $country_id = $this->request->getQuery('country_id');
        if($country_id != null && $country_id !== '') {
            $resources = $resources->where(['Resources.country_id' => $country_id]);
        }
        
        $resource_type = $this->request->getQuery('resource_type');
        if($resource_type != null && $resource_type !== '') {
            $resources = $resources->where(['Resources.resource_type_id' => $resource_type]);
        }

        $total = $resources->count();
        $resources = $this->paginate($resources);
        $statuses = Configure::read('STATUSES');
        $regions = $this->Resources->Regions->find('list', ['limit' => 200]);
        $countries = $this->Resources->Countries->find('list')->where(['region_id IS NOT' => null]);
        $resourceTypes = $this->Resources->ResourceTypes->find('list', ['limit' => 200]);

        $this->set(compact('resources', 'total', 'type', 'statuses', 'status', 'search', 'regions', 'region_id', 'countries', 'country_id', 'resourceTypes', 'resource_type'));
    }

    /**
     * View method
     *
     * @param string|null $id Resource id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        try {
            $resource = $this->Resources->get($id, [
                'contain' => ['Organizations', 
                'ResourceTypes', 
                'ResourceCategories']
            ]);
            $this->set('resource', $resource);
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
        $resource = $this->Resources->newEntity();
        if ($this->request->is('post')) {
            $resource = $this->Resources->patchEntity($resource, $this->request->getData());
            if ($this->Resources->save($resource)) {
                // Handle multiple file uploads
                $files = $this->request->getData('file');
                if (!empty($files) && is_array($files)) {
                    $this->loadModel('ResourceFiles');
                    foreach ($files as $file) {
                        if ($file['error'] === UPLOAD_ERR_OK) {
                            $resourceFile = $this->ResourceFiles->newEntity();
                            $resourceFile->resource_id = $resource->id;
                            // Use UploadImageBehavior logic to upload file and get URL
                            $uploadBehavior = $this->ResourceFiles->getBehavior('UploadImage');
                            if ($uploadBehavior) {
                                $resourceFile->set('file_link', null);
                                $resourceFile->set('file_type', null);
                                $entity = clone $resourceFile;
                                $entity->set('file', $file);
                                $uploadBehavior->beforeSave(null, $entity, null);
                                $resourceFile->file_link = $entity->file_link;
                                $resourceFile->file_type = $entity->file_type;
                            }
                            $this->ResourceFiles->save($resourceFile);
                        }
                    }
                }

                $this->Flash->success(__('The resource has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The resource could not be saved. Please, try again.'));
        }
        $statuses = Configure::read('STATUSES');
        $regions = $this->Resources->Regions->find('list');
        $resourceTypes = $this->Resources->ResourceTypes->find('list', ['limit' => 200]);
        $volunteeringCategories = $this->Resources->VolunteeringCategories->find('list')->where(['VolunteeringCategories.status' => STATUS_ACTIVE]);
        $this->set(compact('resource', 'regions', 'resourceTypes', 'statuses', 'volunteeringCategories'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Resource id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->Resources->setlocale('en_GB');
        $resource = $this->Resources->find('translations')->where(['Resources.id' => $id])->contain(['VolunteeringCategories'])->first();
        if ($resource == null) {
            $this->Flash->error(__('The record was not found.'));

            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $resource = $this->Resources->patchEntity($resource, $this->request->getData());
            if ($this->Resources->save($resource)) {
                // Handle multiple file uploads
                $files = $this->request->getData('file');
                if (!empty($files) && is_array($files)) {
                    $this->loadModel('ResourceFiles');
                    foreach ($files as $file) {
                        if ($file['error'] === UPLOAD_ERR_OK) {
                            $resourceFile = $this->ResourceFiles->newEntity();
                            $resourceFile->resource_id = $resource->id;
                            // Use UploadImageBehavior logic to upload file and get URL
                            $uploadBehavior = $this->ResourceFiles->getBehavior('UploadImage');
                            if ($uploadBehavior) {
                                $resourceFile->set('file_link', null);
                                $resourceFile->set('file_type', null);
                                $entity = clone $resourceFile;
                                $entity->set('file', $file);
                                $uploadBehavior->beforeSave(null, $entity, null);
                                $resourceFile->file_link = $entity->file_link;
                                $resourceFile->file_type = $entity->file_type;
                            }
                            $this->ResourceFiles->save($resourceFile);
                        }
                    }
                }

                $this->Flash->success(__('The resource has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The resource could not be saved. Please, try again.'));
        }
        $statuses = Configure::read('STATUSES');
        $regions = $this->Resources->Regions->find('list');
        $resourceTypes = $this->Resources->ResourceTypes->find('list', ['limit' => 200]);
        $volunteeringCategories = $this->Resources->VolunteeringCategories->find('list')->where(['VolunteeringCategories.status' => STATUS_ACTIVE]);
        $this->set(compact('resource', 'regions', 'resourceTypes', 'statuses', 'volunteeringCategories'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Resource id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        try {
            $resource = $this->Resources->get($id);
            if ($this->Resources->delete($resource)) {
                $this->Flash->success(__('The resource has been deleted.'));
            } else {
                $this->Flash->error(__('The resource could not be deleted. Please, try again.'));
            }

        } catch (\Cake\Datasource\Exception\RecordNotFoundException $ex) {
            $this->Flash->error(__('The record was not found.'));

            return $this->redirect(['action' => 'index']);
        }

        return $this->redirect(['action' => 'index']);
    }
}

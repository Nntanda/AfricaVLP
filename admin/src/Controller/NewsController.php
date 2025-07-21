<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Utility\Hash;

/**
 * News Controller
 *
 * @property \App\Model\Table\NewsTable $News
 *
 * @method \App\Model\Entity\News[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NewsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Organizations', 'Regions']
        ];
        $news = $this->News->find()->order(['News.created' => 'DESC']);

        $status = $this->request->getQuery('status');
        if($status != null && $status !== '') {
            $news = $news->where(['News.status' => $status]);
        }
        
        $search = $this->request->getQuery('s');
        if($search != null && $search !== '') {
            $news = $news->where(['OR' => [
                ["MATCH(News.title) AGAINST(:search IN NATURAL LANGUAGE MODE)"],
                ["MATCH(News.content) AGAINST(:search IN NATURAL LANGUAGE MODE)"],
            ]])->bind(':search', $search);
        }

        $total = $news->count();
        $statuses = Configure::read('NEWS_STATUSES');
        $news = $this->paginate($news);

        $this->set(compact('news', 'total', 'statuses', 'search', 'status'));
    }

    public function tags(...$tags)
    {
        $this->paginate = [
            'contain' => ['Organizations', 'Regions']
        ];
        // Use the ArticlesTable to find tagged articles.
        $news = $this->News->find('tagged', [
            'tags' => $tags
        ])->order(['News.created' => 'DESC']);
        $total = $news->count();
        $news = $this->paginate($news);

        // Pass variables into the view template context.
        $this->set(compact('news', 'tags', 'total'));
    }

    /**
     * View method
     *
     * @param string|null $id News id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        try {
            $news = $this->News->get($id, [
                'contain' => ['Organizations', 'Regions', 'PublishingCategories', 'NewsCategories', 'Tags']
            ]);
    
            $this->set('news', $news);
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
        $news = $this->News->newEntity();
        if ($this->request->is('post')) {
            $news = $this->News->patchEntity($news, $this->request->getData());
            if ($this->News->save($news)) {
                $this->Flash->success(__('The news has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The news could not be saved. Please, try again.'));
        }
        // $organizations = $this->News->Organizations->find('list', ['limit' => 200]);
        $regions = $this->News->Regions->find('list', ['limit' => 200]);
        $publishingCategories = $this->News->PublishingCategories->find('list', ['limit' => 200])->where(['PublishingCategories.name NOT LIKE' => 'Needs']);
        $volunteeringCategories = $this->News->VolunteeringCategories->find('list')->where(['VolunteeringCategories.status' => STATUS_ACTIVE]);
        $statuses = Hash::remove(Configure::read('NEWS_STATUSES'), NEWS_STATUS_DEACTIVATED);
        $this->set(compact('news', 'statuses', 'regions', 'publishingCategories', 'volunteeringCategories'));
    }

    /**
     * Edit method
     *
     * @param string|null $id News id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->News->setlocale('en_GB');
        $news = $this->News->find('translations')->where(['News.id' => $id])->contain(['PublishingCategories', 'VolunteeringCategories', 'Tags'])->first();

        if ($news == null) {
            $this->Flash->error(__('The record was not found.'));

            return $this->redirect(['action' => 'index']);
        }
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $news = $this->News->patchEntity($news, $this->request->getData());
            if ($this->News->save($news)) {
                $this->Flash->success(__('The news has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The news could not be saved. Please, try again.'));
        }
        $regions = $this->News->Regions->find('list', ['limit' => 200]);
        $publishingCategories = $this->News->PublishingCategories->find('list', ['limit' => 200])->where(['PublishingCategories.name NOT LIKE' => 'Needs']);
        $volunteeringCategories = $this->News->VolunteeringCategories->find('list')->where(['VolunteeringCategories.status' => STATUS_ACTIVE]);
        $statuses = Hash::remove(Configure::read('NEWS_STATUSES'), NEWS_STATUS_DRAFT);
        $this->set(compact('news', 'regions', 'publishingCategories', 'statuses', 'volunteeringCategories'));
    }

    /**
     * Delete method
     *
     * @param string|null $id News id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        try {
            $news = $this->News->get($id);
            if ($this->News->delete($news)) {
                $this->Flash->success(__('The news has been deleted.'));
            } else {
                $this->Flash->error(__('The news could not be deleted. Please, try again.'));
            }
    
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $ex) {
            $this->Flash->error(__('The record was not found.'));

            return $this->redirect(['action' => 'index']);
        }
        
        return $this->redirect(['action' => 'index']);
    }
}

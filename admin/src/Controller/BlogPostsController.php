<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Utility\Hash;

/**
 * BlogPosts Controller
 *
 * @property \App\Model\Table\BlogPostsTable $BlogPosts
 *
 * @method \App\Model\Entity\BlogPost[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BlogPostsController extends AppController
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
        $blogPosts = $this->BlogPosts->find()->order(['BlogPosts.created' => 'DESC']);

        $status = $this->request->getQuery('status');
        if($status != null && $status !== '') {
            $blogPosts = $blogPosts->where(['BlogPosts.status' => $status]);
        }
        
        $search = $this->request->getQuery('s');
        if($search != null && $search !== '') {
            $blogPosts = $blogPosts->where(['OR' => [
                ["MATCH(BlogPosts.title) AGAINST(:search IN NATURAL LANGUAGE MODE)"],
                ["MATCH(BlogPosts.content) AGAINST(:search IN NATURAL LANGUAGE MODE)"],
            ]])->bind(':search', $search);
        }

        $total = $blogPosts->count();
        $statuses = Configure::read('NEWS_STATUSES');
        $blogPosts = $this->paginate($blogPosts);

        $this->set(compact('blogPosts', 'total', 'statuses', 'search', 'status'));
    }

    public function tags(...$tags)
    {
        $this->paginate = [
            'contain' => ['Regions']
        ];
        // Use the ArticlesTable to find tagged articles.
        $blogPosts = $this->BlogPosts->find('tagged', [
            'tags' => $tags
        ])->order(['BlogPosts.created' => 'DESC']);
        $total = $blogPosts->count();
        $blogPosts = $this->paginate($blogPosts);

        // Pass variables into the view template context.
        $this->set(compact('blogPosts', 'tags', 'total'));
    }

    /**
     * View method
     *
     * @param string|null $id Blog Post id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $blogPost = $this->BlogPosts->get($id, [
            'contain' => ['Regions', 'BlogCategories', 'BlogPostComments', 'BlogPublishingCategories', 'Tags']
        ]);

        $this->set('blogPost', $blogPost);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $blogPost = $this->BlogPosts->newEntity();
        if ($this->request->is('post')) {
            $blogPost = $this->BlogPosts->patchEntity($blogPost, $this->request->getData());
            if ($this->BlogPosts->save($blogPost)) {
                $this->Flash->success(__('The blog post has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The blog post could not be saved. Please, try again.'));
        }
        $regions = $this->BlogPosts->Regions->find('list', ['limit' => 200]);
        $publishingCategories = $this->BlogPosts->PublishingCategories->find('list', ['limit' => 200])->where(['PublishingCategories.name NOT LIKE' => 'Needs']);
        $volunteeringCategories = $this->BlogPosts->VolunteeringCategories->find('list')->where(['VolunteeringCategories.status' => STATUS_ACTIVE]);
        $statuses = Hash::remove(Configure::read('NEWS_STATUSES'), NEWS_STATUS_DEACTIVATED);
        $this->set(compact('blogPost', 'regions', 'publishingCategories', 'statuses', 'volunteeringCategories'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Blog Post id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->BlogPosts->setlocale('en_GB');
        $blogPost = $this->BlogPosts->find('translations')->where(['BlogPosts.id' => $id])->contain(['PublishingCategories', 'VolunteeringCategories', 'Tags'])->first();

        if ($blogPost == null) {
            $this->Flash->error(__('The record was not found.'));

            return $this->redirect(['action' => 'index']);
        }
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $blogPost = $this->BlogPosts->patchEntity($blogPost, $this->request->getData());
            if ($this->BlogPosts->save($blogPost)) {
                $this->Flash->success(__('The blog post has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The blog post could not be saved. Please, try again.'));
        }
        $regions = $this->BlogPosts->Regions->find('list', ['limit' => 200]);
        $publishingCategories = $this->BlogPosts->PublishingCategories->find('list', ['limit' => 200])->where(['PublishingCategories.name NOT LIKE' => 'Needs']);
        $volunteeringCategories = $this->BlogPosts->VolunteeringCategories->find('list')->where(['VolunteeringCategories.status' => STATUS_ACTIVE]);
        $statuses = Hash::remove(Configure::read('NEWS_STATUSES'), NEWS_STATUS_DRAFT);
        $this->set(compact('blogPost', 'regions', 'publishingCategories', 'statuses', 'volunteeringCategories'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Blog Post id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $blogPost = $this->BlogPosts->get($id);
        if ($this->BlogPosts->delete($blogPost)) {
            $this->Flash->success(__('The blog post has been deleted.'));
        } else {
            $this->Flash->error(__('The blog post could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

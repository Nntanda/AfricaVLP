<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * BlogPosts Controller
 *
 * @property \App\Model\Table\BlogPostsTable $BlogPosts
 *
 * @method \App\Model\Entity\BlogPost[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BlogPostsController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->Auth->allow(['index', 'view', 'tags']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->loadModel('VolunteeringCategories');
        $this->loadModel('PublishingCategories');

        $this->paginate = [
            'contain' => ['Regions', 'VolunteeringCategories', 'PublishingCategories', 'Tags']
        ];
        $blogPosts = $this->BlogPosts->find()->where(['BlogPosts.status' => NEWS_STATUS_PUBLISHED])->order(['BlogPosts.created' => 'DESC']);

        $search = $this->request->getQuery('s');
        if($search != null && !empty($search)) {
            $blogPosts = $blogPosts->where(['OR' => [
                'BlogPosts.title LIKE' => "%$search%",
                'BlogPosts.content LIKE' => "%$search%",
            ]]);
        }

        $region_id = $this->request->getQuery('region_id');
        if($region_id != null && !empty($region_id)) {
            if ($region_id === 'all') {
                $blogPosts = $blogPosts->where(['BlogPosts.region_id IS' => null]);
            } else {
                $blogPosts = $blogPosts->where(['BlogPosts.region_id' => $region_id]);
            }
        }

        $category_id = $this->request->getQuery('cat');
        if($category_id != null && !empty($category_id)) {
            $blogPosts = $blogPosts->matching('VolunteeringCategories', function ($q) use ($category_id) {
                return $q->where(['VolunteeringCategories.id' => $category_id]);
            });
        }
        $blogPosts = $this->paginate($blogPosts);
        $regions = array_merge(['all' => 'All Regions'], $this->BlogPosts->Regions->find('list')->toArray());
        $volunteering_categories = $this->VolunteeringCategories->find('list')->where(['status' => STATUS_ACTIVE]);

        $this->set(compact('blogPosts', 'search', 'regions', 'region_id', 'volunteering_categories'));
    }

    public function tags(...$tags)
    {
        $this->paginate = [
            'contain' => ['Regions', 'VolunteeringCategories', 'PublishingCategories', 'Tags']
        ];
        // Use the ArticlesTable to find tagged articles.
        $blogPosts = $this->BlogPosts->find('tagged', [
            'tags' => $tags
        ])->where(['BlogPosts.status' => NEWS_STATUS_PUBLISHED])->order(['BlogPosts.created' => 'DESC']);

        $search = $this->request->getQuery('s');
        if($search != null && !empty($search)) {
            $blogPosts = $blogPosts->where(['OR' => [
                'BlogPosts.title LIKE' => "%$search%",
                'BlogPosts.content LIKE' => "%$search%",
            ]]);
        }

        $region_id = $this->request->getQuery('region_id');
        if($region_id != null && !empty($region_id)) {
            if ($region_id === 'all') {
                $blogPosts = $blogPosts->where(['BlogPosts.region_id IS' => null]);
            } else {
                $blogPosts = $blogPosts->where(['BlogPosts.region_id' => $region_id]);
            }
        }

        $category_id = $this->request->getQuery('cat');
        if($category_id != null && !empty($category_id)) {
            $blogPosts = $blogPosts->matching('VolunteeringCategories', function ($q) use ($category_id) {
                return $q->where(['VolunteeringCategories.id' => $category_id]);
            });
        }

        $blogPosts = $this->paginate($blogPosts);
        $regions = array_merge(['all' => 'All Regions'], $this->BlogPosts->Regions->find('list')->toArray());
        $volunteering_categories = $this->BlogPosts->VolunteeringCategories->find('list')->where(['status' => STATUS_ACTIVE]);

        $this->set(compact('blogPosts', 'tags', 'search', 'regions', 'region_id', 'volunteering_categories'));
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
        try {
            $blogPost = $this->BlogPosts->get($id, [
                'contain' => ['Regions', 'VolunteeringCategories', 'PublishingCategories', 'BlogPostComments' => ['Users'], 'Tags']
            ]);

            if ($this->request->is(['patch', 'put', 'post'])) {
                $commentData = $this->request->getData('blog_post_comments');
                $commentData['blog_post_id'] = $blogPost->id;
                $commentData['user_id'] = $this->Auth->user('id');
                $comment = $this->BlogPosts->BlogPostComments->newEntity($commentData);
                if ($this->BlogPosts->BlogPostComments->save($comment)) {
                    $this->Flash->success(__('The comment has been saved.'));
                    return $this->redirect(['action' => 'view', $id]);
                }
                $this->Flash->error(__('The comment could not be saved. Please, try again.'));
            }
    
            $this->set('blogPost', $blogPost);
        } catch (\Exception $ex) {
            $this->log($ex);

            return $this->redirect(['action' => 'index']);
        }

    }
}

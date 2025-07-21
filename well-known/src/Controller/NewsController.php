<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * News Controller
 *
 * @property \App\Model\Table\NewsTable $News
 *
 * @method \App\Model\Entity\News[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NewsController extends AppController
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

        $this->paginate = [
            'contain' => ['Organizations', 'Regions', 'VolunteeringCategories', 'PublishingCategories', 'Tags']
        ];
        $news = $this->News->find()->where(['News.status' => NEWS_STATUS_PUBLISHED])->order(['News.created' => 'DESC']);

        $search = $this->request->getQuery('s');
        if($search != null && !empty($search)) {
            $news = $news->where(['OR' => [
                'News.title LIKE' => "%$search%",
                'News.content LIKE' => "%$search%",
            ]]);
        }

        $region_id = $this->request->getQuery('region_id');
        if($region_id != null && !empty($region_id)) {
            if ($region_id === 'all') {
                $news = $news->where(['News.region_id IS' => null]);
            } else {
                $news = $news->where(['News.region_id' => $region_id]);
            }
        }

        $category_id = $this->request->getQuery('cat');
        if($category_id != null && !empty($category_id)) {
            $news = $news->matching('VolunteeringCategories', function ($q) use ($category_id) {
                return $q->where(['VolunteeringCategories.id' => $category_id]);
            });
        }
        $news = $this->paginate($news);
        $regions = array_merge(['all' => 'All Regions'], $this->News->Regions->find('list')->toArray());
        $volunteering_categories = $this->VolunteeringCategories->find('list')->where(['status' => STATUS_ACTIVE]);

        $this->set(compact('news', 'search', 'regions', 'region_id', 'volunteering_categories'));
    }

    public function tags(...$tags)
    {
        $this->paginate = [
            'contain' => ['Organizations', 'Regions', 'VolunteeringCategories', 'PublishingCategories', 'Tags']
        ];
        // Use the ArticlesTable to find tagged articles.
        $news = $this->News->find('tagged', [
            'tags' => $tags
        ])->where(['News.status' => NEWS_STATUS_PUBLISHED])->order(['News.created' => 'DESC']);

        $search = $this->request->getQuery('s');
        if($search != null && !empty($search)) {
            $news = $news->where(['OR' => [
                'News.title LIKE' => "%$search%",
                'News.content LIKE' => "%$search%",
            ]]);
        }

        $region_id = $this->request->getQuery('region_id');
        if($region_id != null && !empty($region_id)) {
            if ($region_id === 'all') {
                $news = $news->where(['News.region_id IS' => null]);
            } else {
                $news = $news->where(['News.region_id' => $region_id]);
            }
        }

        $category_id = $this->request->getQuery('cat');
        if($category_id != null && !empty($category_id)) {
            $news = $news->matching('VolunteeringCategories', function ($q) use ($category_id) {
                return $q->where(['VolunteeringCategories.id' => $category_id]);
            });
        }

        $news = $this->paginate($news);
        $regions = array_merge(['all' => 'All Regions'], $this->News->Regions->find('list')->toArray());
        $volunteering_categories = $this->News->VolunteeringCategories->find('list')->where(['status' => STATUS_ACTIVE]);

        $this->set(compact('news', 'tags', 'search', 'regions', 'region_id', 'volunteering_categories'));
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
                'contain' => ['Organizations', 'Regions', 'VolunteeringCategories', 'PublishingCategories', 'NewsComments' => ['Users'], 'Tags']
            ]);

            if ($this->request->is(['patch', 'put', 'post'])) {
                $commentData = $this->request->getData('news_comments');
                $commentData['news_id'] = $news->id;
                $commentData['user_id'] = $this->Auth->user('id');
                $comment = $this->News->NewsComments->newEntity($commentData);
                if ($this->News->NewsComments->save($comment)) {
                    $this->Flash->success(__('The comment has been saved.'));
                    return $this->redirect(['action' => 'view', $id]);
                }
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
    
            $this->set('news', $news);
        } catch (\Exception $ex) {
            $this->log($ex);

            return $this->redirect(['action' => 'index']);
        }
        
    }
}

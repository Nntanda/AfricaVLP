<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Events Controller
 *
 * @property \App\Model\Table\EventsTable $Events
 *
 * @method \App\Model\Entity\Event[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EventsController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->Auth->allow(['index', 'view']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Organizations', 'Countries', 'Cities', 'Regions']
        ];
        $events = $this->Events->find()->where(['Events.status' => STATUS_ACTIVE])->order(['Events.created' => 'DESC']);

        $search = $this->request->getQuery('s');
        if($search != null && !empty($search)) {
            $events = $events->where(['OR' => [
                'Events.title LIKE' => "%$search%",
                'Events.description LIKE' => "%$search%",
            ]]);
        }

        $region_id = $this->request->getQuery('region_id');
        if($region_id != null && !empty($region_id)) {
            $events = $events->where(['Events.region_id' => $region_id]);
        }

        $country_id = $this->request->getQuery('country_id');
        if($country_id != null && !empty($country_id)) {
            $resources = $events->where(['Events.country_id' => $country_id]);
        }

        // $category_id = $this->request->getQuery('cat');
        // if($category_id != null && !empty($category_id)) {
        //     $news = $news->matching('VolunteeringCategories', function ($q) use ($category_id) {
        //         return $q->where(['VolunteeringCategories.id' => $category_id]);
        //     });
        // }
        $events = $this->paginate($events);
        $regions = $this->Events->Regions->find('list')->where(['status' => STATUS_ACTIVE]);
        $countries = $this->Events->Countries->find('list')->where(['status' => STATUS_ACTIVE]);
        // $volunteering_categories = $this->VolunteeringCategories->find('list')->where(['status' => STATUS_ACTIVE]);

        $this->set(compact('events', 'search', 'regions', 'region_id', 'countries', 'country_id'));
    }

    /**
     * View method
     *
     * @param string|null $id Event id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        try {
            $event = $this->Events->get($id, [
                'contain' => ['Organizations', 'Countries', 'Cities', 'Regions', 'EventComments' => ['Users'], 'VolunteeringOppurtunities' => ['VolunteeringRoles']]
            ]);
    
            if ($this->request->is(['patch', 'put', 'post'])) {
                $commentData = $this->request->getData('event_comments');
                $commentData['event_id'] = $event->id;
                $commentData['user_id'] = $this->Auth->user('id');
                $comment = $this->Events->EventComments->newEntity($commentData);
                if ($this->Events->EventComments->save($comment)) {
                    $this->Flash->success(__('The comment has been saved.'));
                    return $this->redirect(['action' => 'view', $id]);
                }
                $this->Flash->error(__('The comment could not be saved. Please, try again.'));
            }

            $this->set('event', $event);
        } catch (\Exception $ex) {
            $this->log($ex);

            return $this->redirect(['action' => 'index']);
        }
        
    }

    public function showInterest()
    {
        try {
            if ($this->request->is(['patch', 'put', 'post'])) {
                $this->loadModel('VolunteeringInterests');
                $requestData = $this->request->getData();
                $requestData['user_id'] = $this->Auth->user('id');
                $requestData['type'] = $this->VolunteeringInterests::PRE_EVENT;
                $interest = $this->VolunteeringInterests->newEntity($requestData);
                // dd($interest);
                if ($this->VolunteeringInterests->save($interest)) {
                    $this->Flash->success(__('The interest has been saved.'));
                    return $this->redirect($this->referer());
                }
                $this->Flash->error(__('The interest could not be saved. Please, try again.'));
            }
            return $this->redirect($this->referer());

        } catch (\Exception $ex) {
            $this->log($ex);

            return $this->redirect($this->referer());
        }
    }
}

<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

/**
 * Events Controller
 *
 * @property \App\Model\Table\EventsTable $Events
 *
 * @method \App\Model\Entity\Event[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EventsController extends AppController
{
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
        $events = $this->Events->find()->order(['Events.created' => 'DESC']);

        $status = $this->request->getQuery('status');
        if($status != null && $status !== '') {
            $events = $events->where(['Events.status' => $status]);
        }
        
        $search = $this->request->getQuery('s');
        if($search != null && $search !== '') {
            $events = $events->where(['OR' => [
                ["MATCH(Events.title) AGAINST(:search IN NATURAL LANGUAGE MODE)"],
                ["MATCH(Events.description) AGAINST(:search IN NATURAL LANGUAGE MODE)"],
            ]])->bind(':search', $search);
        }
        
        $region_id = $this->request->getQuery('region_id');
        if($region_id != null && $region_id !== '') {
            $events = $events->innerJoinWith('Countries', function ($q) use ($region_id) {
                return $q->where(['Countries.region_id' => $region_id]);
            })->group('Events.id');
        }
        
        $country_id = $this->request->getQuery('country_id');
        if($country_id != null && $country_id !== '') {
            $events = $events->where(['Events.country_id' => $country_id]);
        }

        $total = $events->count();
        $statuses = Configure::read('STATUSES');
        $countries = $this->Events->Countries->find('list')->where(['region_id IS NOT' => null]);
        $regions = $this->Events->Countries->Regions->find('list', ['limit' => 200]);
        $events = $this->paginate($events);

        $this->set(compact('events', 'total', 'statuses', 'search', 'status', 'regions', 'region_id', 'countries', 'country_id'));
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
        $event = $this->Events->get($id, [
            'contain' => ['Organizations', 'Countries', 'Cities', 'Regions', 'EventComments.Users', 'VolunteeringOppurtunities.VolunteeringRoles']
        ]);

        $this->set('event', $event);
    }

    /**
     * Edit method
     *
     * @param string|null $id Event id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $event = $this->Events->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $event = $this->Events->patchEntity($event, $this->request->getData());
            if ($this->Events->save($event)) {
                $this->Flash->success(__('The event has been saved.'));

                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('The event could not be saved. Please, try again.'));
        }
        $organizations = $this->Events->Organizations->find('list', ['limit' => 200]);
        $countries = $this->Events->Countries->find('list', ['limit' => 200]);
        $cities = $this->Events->Cities->find('list', ['limit' => 200]);
        $regions = $this->Events->Regions->find('list', ['limit' => 200]);
        $this->set(compact('event', 'organizations', 'countries', 'cities', 'regions'));
        return $this->redirect($this->referer());
    }

    /**
     * Delete method
     *
     * @param string|null $id Event id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $event = $this->Events->get($id);
        if ($this->Events->delete($event)) {
            $this->Flash->success(__('The event has been deleted.'));
        } else {
            $this->Flash->error(__('The event could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

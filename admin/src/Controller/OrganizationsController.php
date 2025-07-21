<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

/**
 * Organizations Controller
 *
 *
 * @method \App\Model\Entity\Organization[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrganizationsController extends AppController
{
    //var $helpers = array('Html', 'Form', 'Csv');

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $organizations = $this->Organizations->find()->contain(['Users'])->order(['Organizations.created' => 'DESC']);

        $status = $this->request->getQuery('status');
        if ($status != null && $status !== '') {
            $organizations = $organizations->where(['Organizations.status' => $status]);
        }

        $verification = $this->request->getQuery('verification');
        if ($verification != null && $verification !== '') {
            if ($verification) {
                $organizations = $organizations->where(['Organizations.is_verified IS' => true]);
            } else {
                $organizations = $organizations->where(['OR' => [['Organizations.is_verified IS NOT' => true], ['Organizations.is_verified IS' => null]]]);
            }
        }

        $search = $this->request->getQuery('s');
        if ($search != null && $search !== '') {
            $organizations = $organizations->where(['OR' => [
                ['Organizations.name LIKE' => "%$search%"],
                ['Organizations.about LIKE' => "%$search%"]
            ]]);
        }

        $region_id = $this->request->getQuery('region_id');
        if ($region_id != null && $region_id !== '') {
            $organizations = $organizations->innerJoinWith('Countries', function ($q) use ($region_id) {
                return $q->where(['Countries.region_id' => $region_id]);
            })->group('Organizations.id');
        }

        $country_id = $this->request->getQuery('country_id');
        if ($country_id != null && $country_id !== '') {
            $organizations = $organizations->where(['Organizations.country_id' => $country_id]);
        }

        $total = $organizations->count();
        $organizations = $this->paginate($organizations);
        $statuses = Configure::read('STATUSES');
        $this->loadModel('Regions');
        $regions = $this->Regions->find('list', ['limit' => 200]);
        $countries = $this->Organizations->Countries->find('list')->where(['region_id IS NOT' => null]);
        $verifications = [0 => __('Unverified'), 1 => __('Verified')];

        $this->set(compact('organizations', 'total', 'type', 'statuses', 'status', 'search', 'regions', 'region_id', 'countries', 'country_id', 'verifications', 'verification'));
    }

    /**
     * View method
     *
     * @param string|null $id Organization id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null, $sub = null)
    {
        try {
            $organization = $this->Organizations->get($id, [
                'contain' => ['Users', 'Countries', 'Cities', 'OrganizationCategories', 'VolunteeringCategories']
            ]);
            $organization->event_count = $this->Organizations->Events->find()->where(['organization_id' => $organization->id])->count();
            $organization->volunteer_count = $this->Organizations->VolunteeringHistories->find()->where(['organization_id' => $organization->id])->count();

            switch ($sub) {
                case 'volunteers':
                    $volunteers = $this->Organizations->VolunteeringHistories->find()->where(['organization_id' => $organization->id])->contain('Users', 'VolunteeringOppurtunities.VolunteeringRoles')->limit(10);
                    $organization->volunteers = $volunteers;
                    break;

                case 'events':
                    $events = $this->Organizations->Events->find()->where(['organization_id' => $organization->id])->limit(10);
                    $organization->events = $events;
                    break;

                case 'news':
                    $news = $this->Organizations->News->find()->where(['organization_id' => $organization->id])->contain(['PublishingCategories'])->limit(10);
                    $organization->news = $news;
                    break;

                case 'resources':
                    $resources = $this->Organizations->Resources->find()->where(['organization_id' => $organization->id])->contain(['ResourceTypes'])->limit(10);
                    $organization->resources = $resources;
                    break;

                default:
                    # code...
                    break;
            }
            $statuses = Configure::read('STATUSES');
            $this->set('organization', $organization);
            $this->set(compact('sub', 'statuses'));
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $ex) {
            $this->Flash->error(__('The record was not found.'));
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Organization id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        try {
            $organization = $this->Organizations->get($id, [
                'contain' => []
            ]);
            if ($this->request->is(['patch', 'post', 'put'])) {
                $organization = $this->Organizations->patchEntity($organization, $this->request->getData());
                if ($this->Organizations->save($organization)) {
                    $this->Flash->success(__('The organization has been saved.'));

                    return $this->redirect($this->referer());
                }
                $this->Flash->error(__('The organization could not be saved. Please, try again.'));
            }
            $this->set(compact('organization'));
            return $this->redirect($this->referer());
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $ex) {
            $this->Flash->error(__('The record was not found.'));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function download()
    {
        $organizations = $this->Organizations->find();

        $status = $this->request->getQuery('status');
        if ($status != null && $status !== '') {
            $organizations = $organizations->where(['Organizations.status' => $status]);
        }

        $verification = $this->request->getQuery('verification');
        if ($verification != null && $verification !== '') {
            if ($verification) {
                $organizations = $organizations->where(['Organizations.is_verified IS' => true]);
            } else {
                $organizations = $organizations->where(['OR' => [['Organizations.is_verified IS NOT' => true], ['Organizations.is_verified IS' => null]]]);
            }
        }

        $search = $this->request->getQuery('s');
        if ($search != null && $search !== '') {
            $organizations = $organizations->where(['OR' => [
                ['Organizations.name LIKE' => "%$search%"],
                ['Organizations.about LIKE' => "%$search%"]
            ]]);
        }

        $region_id = $this->request->getQuery('region_id');
        if ($region_id != null && $region_id !== '') {
            $organizations = $organizations->innerJoinWith('Countries', function ($q) use ($region_id) {
                return $q->where(['Countries.region_id' => $region_id]);
            })->group('Organizations.id');
        }

        $country_id = $this->request->getQuery('country_id');
        if ($country_id != null && $country_id !== '') {
            $organizations = $organizations->where(['Organizations.country_id' => $country_id]);
        }

        $total = $organizations->count();
        //$organizations = $this->paginate($organizations);
        $statuses = Configure::read('STATUSES');
        $this->loadModel('Regions');
        $regions = $this->Regions->find('list', ['limit' => 200]);
        $countries = $this->Organizations->Countries->find('list')->where(['region_id IS NOT' => null]);

        $this->set(compact('organizations', 'total', 'type', 'statuses', 'status', 'search', 'regions', 'region_id', 'countries', 'country_id', 'verifications', 'verification'));
        
        $this->layout = null;
        $this->autoLayout = false;
        Configure::write('debug', 0);
    }

    public function downloadSurvey($id = null)
    {
        $id = $this->request->getQuery('id');
        $organizations = $this->Organizations->find()
        ->where(['Organizations.id' => $id]);
        
        $this->set(compact('organizations', 'id'));        
        $this->layout = null;
        $this->autoLayout = false;
        Configure::write('debug', 0);
    }
}

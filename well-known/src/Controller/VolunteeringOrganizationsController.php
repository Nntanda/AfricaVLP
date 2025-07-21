<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * VolunteeringOrganizations Controller
 *
 *
 * @method \App\Model\Entity\VolunteeringOrganization[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class VolunteeringOrganizationsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Organizations');
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
            'contain' => ['Countries', 'Cities', 'VolunteeringCategories']
        ];

        $organizations = $this->Organizations->find()->where(['Organizations.status' => STATUS_ACTIVE]);
        
        $search = $this->request->getQuery('s');
        if($search != null && $search !== '') {
            $organizations = $organizations->where(["MATCH(Organizations.name) AGAINST(:search IN NATURAL LANGUAGE MODE)"])->bind(':search', $search);
        }
        
        $country_id = $this->request->getQuery('country_id');
        if($country_id != null && $country_id !== '') {
            $organizations = $organizations->where(['Organizations.country_id' => $country_id]);
        }

        $category_id = $this->request->getQuery('cat');
        if($category_id != null && !empty($category_id)) {
            $organizations = $organizations->matching('VolunteeringCategories', function ($q) use ($category_id) {
                return $q->where(['VolunteeringCategories.id' => $category_id]);
            });
        }

        $organizations->formatResults(function ($results) {
            return $results->map(function ($organization) {
                $organization->volunteer_count = $this->Organizations->VolunteeringHistories->find()->where(['organization_id' => $organization->id])->count();

                return $organization;
            });
        });

        $organizations = $this->paginate($organizations);
        $countries = $this->Organizations->Countries->find('list')->where(['status' => STATUS_ACTIVE, 'region_id IS NOT' => null]); 
        $volunteeringCategories = $this->Organizations->VolunteeringCategories->find('list')->where(['status' => STATUS_ACTIVE]);

        $this->set(compact('organizations', 'countries', 'volunteeringCategories', 'search', 'country_id', 'category_id'));
    }

    /**
     * View method
     *
     * @param string|null $id Volunteering Organization id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $organization = $this->Organizations->get($id, [
            'contain' => ['Countries', 'Cities', 'VolunteeringCategories', 'Events' => function ($q) {
                return $q->contain(['Countries', 'Cities'])->order(['Events.id' => 'DESC'])->limit(3);
            }]
        ]);
        $organization->volunteer_count = $this->Organizations->VolunteeringHistories->find()->where(['organization_id' => $organization->id])->count();
        $organization->volunteer_male_count = $this->Organizations->VolunteeringHistories->find()->where(['organization_id' => $organization->id])->innerJoinWith('Users', function ($q) {
            return $q->where(['Users.gender LIKE' => 'Male']);
        })->group('VolunteeringHistories.id')->count();
        $organization->volunteer_female_count = $this->Organizations->VolunteeringHistories->find()->where(['organization_id' => $organization->id])->innerJoinWith('Users', function ($q) {
            return $q->where(['Users.gender LIKE' => 'Female']);
        })->group('VolunteeringHistories.id')->count();


        $this->set('organization', $organization);
    }
}

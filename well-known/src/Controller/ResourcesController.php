<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Resources Controller
 *
 * @property \App\Model\Table\ResourcesTable $Resources
 *
 * @method \App\Model\Entity\Resource[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ResourcesController extends AppController
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
            'contain' => ['Organizations', 'Regions', 'Countries', 'ResourceTypes', 'VolunteeringCategories']
        ];
        $resources = $this->Resources->find()->where(['Resources.status' => STATUS_ACTIVE])->order(['Resources.created' => 'DESC']);


        $search = $this->request->getQuery('s');
        if($search != null && !empty($search)) {
            $resources = $resources->where(['OR' => [
                ['Resources.title LIKE' => "%$search%"],
                ['Resources.description LIKE' => "%$search%"],
            ]]);
        }

        $region_id = $this->request->getQuery('region_id');
        if($region_id != null && !empty($region_id)) {
            if ($region_id === 'all') {
                $resources = $resources->where(['Resources.region_id IS' => null]);
            } else {
                $resources = $resources->where(['Resources.region_id' => $region_id]);
            }
        }

        $country_id = $this->request->getQuery('country_id');
        if($country_id != null && !empty($country_id)) {
            $resources = $resources->where(['Resources.country_id' => $country_id]);
        }

        $resource_type_id = $this->request->getQuery('resource_type_id');
        if($resource_type_id != null && !empty($resource_type_id)) {
            $resources = $resources->where(['Resources.resource_type_id' => $resource_type_id]);
        }

        $category_id = $this->request->getQuery('cat');
        if($category_id != null && !empty($category_id)) {
            $resources = $resources->matching('VolunteeringCategories', function ($q) use ($category_id) {
                return $q->where(['VolunteeringCategories.id' => $category_id]);
            });
        }
        
        $regions = array_merge(['all' => 'All Regions'], $this->Resources->Regions->find('list')->toArray());
        $resourceTypes = $this->Resources->ResourceTypes->find('list');
        $volunteering_categories = $this->Resources->VolunteeringCategories->find('list')->where(['status' => STATUS_ACTIVE]);
        $countries = $this->Resources->Countries->find('list')->where(['status' => STATUS_ACTIVE, 'region_id IS NOT' => null]);

        $resources = $this->paginate($resources);

        $this->set(compact('resources', 'search', 'regions', 'region_id', 'volunteering_categories', 'resourceTypes', 'resource_type_id', 'countries', 'country_id'));
    }
}

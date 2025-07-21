<?php
namespace App\View\Cell;

use Cake\View\Cell;

/**
 * MyOrganizations cell
 */
class UserOrganizationsCell extends Cell
{
    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Initialization logic run at the end of object construction.
     *
     * @return void
     */
    public function initialize()
    {
    }

    /**
     * Default display method.
     *
     * @return void
     */
    public function display($user_id)
    {
        $this->loadModel('OrganizationUsers');

        $userOrganizations = $this->OrganizationUsers->find()->where(['OrganizationUsers.user_id' => $user_id, 'OrganizationUsers.status' => STATUS_ACTIVE])->contain(['Organizations' => ['fields' => ['id', 'name', 'logo']]]);

        $this->set(compact('userOrganizations'));
    }

    public function resourceTypeLinks()
    {
        $this->loadModel('ResourceTypes');
        $resourceTypes = $this->ResourceTypes->find()->where(['status' => STATUS_ACTIVE]);

        $this->set(compact('resourceTypes'));
    }
}

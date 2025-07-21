<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use ArrayObject;

/**
 * Tags behavior
 */
class AdminNotificationBehavior extends Behavior
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * @param Event $event the event
     * @param Entity $entity saving entity
     * @param ArrayObject $options save options
     * @return void
     */
    public function afterSave(Event $event, Entity $entity, ArrayObject $options)
    {
        $object_model = $this->_table->getRegistryAlias();
        $object_id = $entity->id;
        $type = $entity->isNew() ? 'create' : 'update';
        
        $notificationTable = $this->getNotificationTable();
        $notification = $notificationTable->newEntity(compact('object_model', 'object_id', 'type'));

        $notificationTable->save($notification);
    }

    private function getDirtyData(EntityInterface $entity = null)
    {
        if ($entity === null) {
            return null;
        }

        if (method_exists($entity, 'getVisible')) {
            // CakePHP >= 3.8
            return $entity->extract($entity->getVisible(), true);
        }

        // CakePHP < 3.8
        return $entity->extract($entity->visibleProperties(), true);
    }

    private function getNotificationTable()
    {
        if (method_exists(TableRegistry::class, 'getTableLocator')) {
            $tableLocator = TableRegistry::getTableLocator();
        } else {
            $tableLocator = TableRegistry::locator();
        }

        return $tableLocator->get('Notifications');
    }
}

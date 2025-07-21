<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Notification Entity
 *
 * @property int $id
 * @property string $type
 * @property string $object_model
 * @property int $object_id
 * @property string $data
 * @property bool $is_read
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Object $object
 */
class Notification extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'type' => true,
        'object_model' => true,
        'object_id' => true,
        'is_read' => true,
        'created' => true,
        'object' => true
    ];
}

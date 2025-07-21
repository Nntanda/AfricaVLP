<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * NewsletterContent Entity
 *
 * @property int $id
 * @property int $object_id
 * @property string $object_type
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Object $object
 */
class NewsletterContent extends Entity
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
        'object_id' => true,
        'object_model' => true,
        'created' => true,
        'object' => true
    ];
}

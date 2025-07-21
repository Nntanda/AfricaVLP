<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EventCategory Entity
 *
 * @property int $id
 * @property int|null $volunteering_oppurtunity_id
 * @property int|null $volunteering_category_id
 *
 * @property \App\Model\Entity\VolunteeringOppurtunity $volunteering_oppurtunity
 * @property \App\Model\Entity\VolunteeringCategory $volunteering_category
 */
class EventCategory extends Entity
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
        'volunteering_oppurtunity_id' => true,
        'volunteering_category_id' => true,
        'volunteering_oppurtunity' => true,
        'volunteering_category' => true
    ];
}

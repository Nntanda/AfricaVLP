<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VolunteeringInterest Entity
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $volunteering_oppurtunity_id
 * @property int|null $type
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\VolunteeringOppurtunity $volunteering_oppurtunity
 */
class VolunteeringInterest extends Entity
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
        'user_id' => true,
        'volunteering_oppurtunity_id' => true,
        'type' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'volunteering_oppurtunity' => true
    ];
}

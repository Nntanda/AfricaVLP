<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VolunteeringOppurtunity Entity
 *
 * @property int $id
 * @property int|null $event_id
 * @property int|null $volunteering_duration_id
 * @property int|null $volunteering_role_id
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Event $event
 * @property \App\Model\Entity\VolunteeringDuration $volunteering_duration
 * @property \App\Model\Entity\VolunteeringRole $volunteering_role
 * @property \App\Model\Entity\EventCategory[] $event_categories
 * @property \App\Model\Entity\VolunteeringHistory[] $volunteering_histories
 * @property \App\Model\Entity\VolunteeringInterest[] $volunteering_interests
 */
class VolunteeringOppurtunity extends Entity
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
        'event_id' => true,
        'volunteering_duration_id' => true,
        'volunteering_role_id' => true,
        'number' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'event' => true,
        'volunteering_duration' => true,
        'volunteering_role' => true,
        'volunteering_categories' => true,
        'volunteering_histories' => true,
        'volunteering_interests' => true
    ];
}

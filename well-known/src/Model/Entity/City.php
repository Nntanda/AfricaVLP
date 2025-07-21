<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * City Entity
 *
 * @property int $id
 * @property int|null $country_id
 * @property string|null $name
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Country $country
 * @property \App\Model\Entity\Event[] $events
 * @property \App\Model\Entity\OrganizationOffice[] $organization_offices
 * @property \App\Model\Entity\Organization[] $organizations
 * @property \App\Model\Entity\User[] $users
 */
class City extends Entity
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
        'country_id' => true,
        'name' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'country' => true,
        'events' => true,
        'organization_offices' => true,
        'organizations' => true,
        'users' => true
    ];
}

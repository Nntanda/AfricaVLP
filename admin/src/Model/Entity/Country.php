<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Country Entity
 *
 * @property int $id
 * @property string|null $iso
 * @property string|null $name
 * @property string|null $nicename
 * @property string|null $iso3
 * @property int|null $numcode
 * @property int|null $phonecode
 * @property int|null $region_id
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Region $region
 * @property \App\Model\Entity\City[] $cities
 * @property \App\Model\Entity\Event[] $events
 * @property \App\Model\Entity\OrganizationOffice[] $organization_offices
 * @property \App\Model\Entity\Organization[] $organizations
 */
class Country extends Entity
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
        'iso' => true,
        'name' => true,
        'nicename' => true,
        'iso3' => true,
        'numcode' => true,
        'phonecode' => true,
        'capital' => true,
        'official_language' => true,
        'population' => true,
        'region_id' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'region' => true,
        'cities' => true,
        'events' => true,
        'organization_offices' => true,
        'organizations' => true
    ];
}

<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrganizationOffice Entity
 *
 * @property int $id
 * @property int|null $organization_id
 * @property int|null $country_id
 * @property int|null $city_id
 * @property string|null $address
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Organization $organization
 * @property \App\Model\Entity\Country $country
 * @property \App\Model\Entity\City $city
 */
class OrganizationOffice extends Entity
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
        'organization_id' => true,
        'country_id' => true,
        'city_id' => true,
        'address' => true,
        'lat' => true,
        'lng' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'organization' => true,
        'country' => true,
        'city' => true
    ];
}

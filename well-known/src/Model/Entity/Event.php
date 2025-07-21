<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\Behavior\Translate\TranslateTrait;

/**
 * Event Entity
 *
 * @property int $id
 * @property int|null $organization_id
 * @property string|null $title
 * @property string|null $description
 * @property int|null $country_id
 * @property int|null $city_id
 * @property string|null $address
 * @property \Cake\I18n\FrozenTime|null $start_date
 * @property \Cake\I18n\FrozenTime|null $end_date
 * @property int|null $status
 * @property int|null $requesting_volunteers
 * @property int|null $has_remunerations
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $region_id
 *
 * @property \App\Model\Entity\Organization $organization
 * @property \App\Model\Entity\Country $country
 * @property \App\Model\Entity\City $city
 * @property \App\Model\Entity\Region $region
 * @property \App\Model\Entity\EventComment[] $event_comments
 * @property \App\Model\Entity\VolunteeringOppurtunity[] $volunteering_oppurtunities
 */
class Event extends Entity
{
    use TranslateTrait;

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
        'title' => true,
        'description' => true,
        'country_id' => true,
        'city_id' => true,
        'address' => true,
        'lat' => true,
        'lng' => true,
        'start_date' => true,
        'end_date' => true,
        'image' => true,
        'file' => true,
        'status' => true,
        'requesting_volunteers' => true,
        'has_remunerations' => true,
        'created' => true,
        'modified' => true,
        '_translations' => true,
        'region_id' => true,
        'organization' => true,
        'country' => true,
        'city' => true,
        'region' => true,
        'event_comments' => true,
        'volunteering_oppurtunities' => true,
        'volunteering_categories' => true,
        'url' => true
    ];
}

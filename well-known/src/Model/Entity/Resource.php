<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\Behavior\Translate\TranslateTrait;

/**
 * Resource Entity
 *
 * @property int $id
 * @property int|null $organization_id
 * @property int|null $region_id
 * @property int|null $country_id
 * @property string|null $title
 * @property string $description
 * @property int|null $resource_type_id
 * @property string|null $file_type
 * @property string|null $file_link
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Organization $organization
 * @property \App\Model\Entity\Region $region
 * @property \App\Model\Entity\Country $country
 * @property \App\Model\Entity\ResourceType $resource_type
 * @property \App\Model\Entity\ResourceCategory[] $resource_categories
 */
class Resource extends Entity
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
        'region_id' => true,
        'country_id' => true,
        'title' => true,
        'description' => true,
        'resource_type_id' => true,
        'file_type' => true,
        'file_link' => true,
        'file' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        '_translations' => true,
        'organization' => true,
        'region' => true,
        'country' => true,
        'resource_type' => true,
        'volunteering_categories' => true
    ];
}

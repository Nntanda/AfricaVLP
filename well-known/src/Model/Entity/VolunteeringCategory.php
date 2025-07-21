<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VolunteeringCategory Entity
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\BlogCategory[] $blog_categories
 * @property \App\Model\Entity\EventCategory[] $event_categories
 * @property \App\Model\Entity\NewsCategory[] $news_categories
 * @property \App\Model\Entity\OrganizationCategory[] $organization_categories
 */
class VolunteeringCategory extends Entity
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
        'name' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'blog_categories' => true,
        'event_categories' => true,
        'news_categories' => true,
        'organization_categories' => true
    ];
}

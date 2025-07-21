<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BlogPublishingCategory Entity
 *
 * @property int $id
 * @property int|null $blog_post_id
 * @property int|null $publishing_category_id
 *
 * @property \App\Model\Entity\BlogPost $blog_post
 * @property \App\Model\Entity\PublishingCategory $publishing_category
 */
class BlogPublishingCategory extends Entity
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
        'blog_post_id' => true,
        'publishing_category_id' => true,
        'blog_post' => true,
        'publishing_category' => true
    ];
}

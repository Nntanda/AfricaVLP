<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\Behavior\Translate\TranslateTrait;
use Cake\Collection\Collection;

/**
 * News Entity
 *
 * @property int $id
 * @property int|null $organization_id
 * @property string|null $title
 * @property string|null $slug
 * @property string|null $content
 * @property int|null $status
 * @property int|null $region_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Organization $organization
 * @property \App\Model\Entity\Region $region
 * @property \App\Model\Entity\NewsCategory[] $news_categories
 * @property \App\Model\Entity\PublishingCategory[] $publishing_categories
 */
class News extends Entity
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
        'slug' => true,
        'content' => true,
        'image' => true,
        'file' => true,
        'status' => true,
        'region_id' => true,
        'tag_string' => true,
        'created' => true,
        'modified' => true,
        '_translations' => true,
        'organization' => true,
        'region' => true,
        'news_categories' => true,
        'publishing_categories' => true,
        'volunteering_categories' => true,
        'tags' => true,
    ];

    protected function _getTagString()
    {
        if (isset($this->_properties['tag_string'])) {
            return $this->_properties['tag_string'];
        }
        if (empty($this->tags)) {
            return '';
        }
        $tags = new Collection($this->tags);
        $str = $tags->reduce(function ($string, $tag) {
            return $string . $tag->title . ', ';
        }, '');
        return trim($str, ', ');
    }
}

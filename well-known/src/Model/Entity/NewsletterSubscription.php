<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * NewsletterSubscription Entity
 *
 * @property int $id
 * @property string $email
 * @property bool $weekly
 * @property bool $monthly
 * @property bool $quarterly
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class NewsletterSubscription extends Entity
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
        'email' => true,
        'weekly' => true,
        'monthly' => true,
        'quarterly' => true,
        'created' => true,
        'modified' => true
    ];
}

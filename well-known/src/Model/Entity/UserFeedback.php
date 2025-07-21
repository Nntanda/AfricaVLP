<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserFeedback Entity
 *
 * @property int $id
 * @property string $object_model
 * @property int $object_id
 * @property string $feedback_message
 * @property int|null $feedback_rating
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Object $object
 * @property \App\Model\Entity\User $user
 */
class UserFeedback extends Entity
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
        'object_model' => true,
        'object_id' => true,
        'feedback_message' => true,
        'feedback_rating' => true,
        'user_id' => true,
        'created' => true,
        'object' => true,
        'user' => true
    ];
}

<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AdminSupportMessage Entity
 *
 * @property int $id
 * @property int $admin_support_id
 * @property string|null $message
 * @property string $sender
 * @property int $sender_user_id
 * @property \Cake\I18n\FrozenTime|null $time
 * @property int|null $read
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\AdminSupport $admin_support
 * @property \App\Model\Entity\SenderUser $sender_user
 */
class AdminSupportMessage extends Entity
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
        'admin_support_id' => true,
        'message' => true,
        'sender' => true,
        'sender_user_id' => true,
        'time' => true,
        'is_read' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'admin_support' => true,
        'sender_user' => true
    ];
}

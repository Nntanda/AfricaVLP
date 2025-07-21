<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ConversationMessage Entity
 *
 * @property int $id
 * @property int|null $conversation_id
 * @property string|null $message
 * @property int|null $organization_id
 * @property int|null $user_id
 * @property \Cake\I18n\FrozenTime|null $time
 * @property int|null $read
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Conversation $conversation
 * @property \App\Model\Entity\Organization $organization
 * @property \App\Model\Entity\User $user
 */
class ConversationMessage extends Entity
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
        'conversation_id' => true,
        'message' => true,
        'organization_id' => true,
        'user_id' => true,
        'time' => true,
        'read' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'conversation' => true,
        'organization' => true,
        'user' => true
    ];
}

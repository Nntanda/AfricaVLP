<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TmpOrganizationUser Entity
 *
 * @property int $id
 * @property int|null $organization_id
 * @property string $email
 * @property string|null $role
 * @property \Cake\I18n\FrozenTime|null $created
 *
 * @property \App\Model\Entity\Organization $organization
 */
class TmpOrganizationUser extends Entity
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
        'email' => true,
        'role' => true,
        'created' => true,
        'organization' => true
    ];
}

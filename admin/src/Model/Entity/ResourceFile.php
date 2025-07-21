<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class ResourceFile extends Entity
{
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}

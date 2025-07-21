<?php

namespace App\Model\Behavior;

use Cake\Datasource\EntityInterface;
use Cake\I18n\Time;
use Cake\ORM\Behavior;

/**
 * Covers the user registration
 */
class BaseTokenBehavior extends Behavior
{

    /**
     * Remove user token for validation
     *
     * @param EntityInterface $user user object.
     * @return EntityInterface
     */
    protected function _removeValidationToken(EntityInterface $user)
    {
        $user->token = null;
        $user->token_expires = null;

        return $this->_table->save($user);
    }
}

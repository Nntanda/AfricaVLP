<?php

namespace App\Model\Behavior;

use App\Email\EmailSender;
use App\Exception\UserNotFoundException;
use App\Exception\WrongPasswordException;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Utility\Hash;
use InvalidArgumentException;

/**
 * Covers the password management features
 */
class PasswordBehavior extends BaseTokenBehavior
{
    /**
     * Constructor hook method.
     *
     * @param array $config The configuration settings provided to this behavior.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->Email = new EmailSender();
    }

    /**
     * Resets user token
     *
     * @param string $reference User username or email
     * @param array $options checkActive, sendEmail, expiration
     *
     * @return string
     * @throws InvalidArgumentException
     * @throws UserNotFoundException
     */
    public function resetToken($reference, array $options = [])
    {
        if (empty($reference)) {
            throw new InvalidArgumentException(__("Reference cannot be null"));
        }

        $expiration = Hash::get($options, 'expiration');
        if (empty($expiration)) {
            throw new InvalidArgumentException(__("Token expiration cannot be empty"));
        }

        $user = $this->_getUser($reference);

        if (empty($user)) {
            throw new UserNotFoundException(__("User not found"));
        }
        $user->updateToken($expiration);
        $saveResult = $this->_table->save($user);
        $template = !empty($options['emailTemplate']) ? $options['emailTemplate'] : 'reset_password';
        
        if (Hash::get($options, 'sendEmail')) {
            $this->Email->sendResetPasswordEmail($saveResult, null, $template);
        }

        return $saveResult;
    }

    /**
     * Get the user by email or username
     *
     * @param string $reference reference could be either an email or username
     * @return mixed user entity if found
     */
    protected function _getUser($reference)
    {
        return $this->_table->findByEmail($reference)->first();
    }

    /**
     * Change password method
     *
     * @param EntityInterface $user user data.
     * @throws WrongPasswordException
     * @return mixed
     */
    public function changePassword(EntityInterface $user)
    {
        try {
            $currentUser = $this->_table->get($user->id, [
                'contain' => []
            ]);
        } catch (RecordNotFoundException $e) {
            throw new UserNotFoundException(__("User not found"));
        }

        if (!empty($user->current_password)) {
            if (!$user->checkPassword($user->current_password, $currentUser->password)) {
                throw new WrongPasswordException(__('The current password does not match'));
            }
            if ($user->current_password === $user->password_confirm) {
                throw new WrongPasswordException(__(
                    'You cannot use the current password as the new one'
                ));
            }
        }
        $user = $this->_table->save($user);
        if (!empty($user)) {
            $user = $this->_removeValidationToken($user);
        }

        return $user;
    }
}

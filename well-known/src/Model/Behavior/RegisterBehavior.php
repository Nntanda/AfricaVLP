<?php

namespace App\Model\Behavior;

use App\Email\EmailSender;
use App\Exception\TokenExpiredException;
use App\Exception\UserNotFoundException;
use App\Model\Entity\User;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use DateTime;
use InvalidArgumentException;

/**
 * Covers the user registration
 */
class RegisterBehavior extends BaseTokenBehavior
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
        $this->validateEmail = (bool)Configure::read('Users.Email.validate');
        $this->Email = new EmailSender();
    }

    /**
     * Registers an user.
     *
     * @param EntityInterface $user User information
     * @param array $data User information
     * @param array $options ['tokenExpiration]
     * @return bool|EntityInterface
     * @throws InvalidArgumentException
     */
    public function register($user, $data, $options)
    {
        $validateEmail = Hash::get($options, 'validate_email');
        $tokenExpiration = Hash::get($options, 'token_expiration');
        $emailClass = Hash::get($options, 'email_class');
        $user = $this->_table->patchEntity(
            $user,
            $data,
            ['validate' => Hash::get($options, 'validator') ?: $this->getRegisterValidators($options)]
        );
        $user->updateToken($tokenExpiration);
        $this->_table->isValidateEmail = $validateEmail;
        $userSaved = $this->_table->save($user);
        if ($userSaved && $validateEmail) {
            $this->Email->sendWelcomeEmail($user, $emailClass);
        }

        return $userSaved;
    }

    /**
     * Reset user email verification token.
     *
     * @param EntityInterface $user User information
     * @param array $data User information
     * @param array $options ['tokenExpiration]
     * @return bool|EntityInterface
     * @throws InvalidArgumentException
     */
    public function resetEmailToken($user, $options)
    {
        $tokenExpiration = Hash::get($options, 'token_expiration');
        $emailClass = Hash::get($options, 'email_class');

        if (empty($tokenExpiration)) {
            throw new InvalidArgumentException(__("Token expiration cannot be empty"));
        }

        $user->updateToken($tokenExpiration);

        $userSaved = $this->_table->save($user);
        if ($userSaved && Hash::get($options, 'sendEmail')) {
            $this->Email->sendValidationEmail($user, $emailClass);
        }

        return $userSaved;
    }

    /**
     * Resend validation email
     * @param  EntityInterface $user User information
     * @param  String $emailClass 
     * @return void
     */
    public function resendEmailValidation($user, $emailClass=null)
    {
        $this->Email->sendValidationEmail($user, $emailClass);

        return $user;
    }

    /**
     * Validates token and return user
     *
     * @param string $token toke to be validated.
     * @param null $callback function that will be returned.
     * @throws TokenExpiredException when token has expired.
     * @throws UserNotFoundException when user isn't found.
     * @return User $user
     */
    public function validate($token, $callback = null)
    {
        $user = $this->_table->find()
            ->select(['token_expires', 'id', 'token'])
            ->where(['token' => $token])
            ->first();
        if (empty($user)) {
            throw new UserNotFoundException(__("User not found for the given token and email."));
        }
        if ($user->tokenExpired()) {
            throw new TokenExpiredException(__("Token has already expired user with no token"));
        }
        if (!method_exists($this, $callback)) {
            return $user;
        }

        return $this->_table->{$callback}($user);
    }

    /**
     * Verifies a user email
     *
     * @param EntityInterface $user user object.
     * @return mixed User entity or bool false if the user could not be activated
     * @throws UserEmailValidException
     */
    public function verifyEmail(EntityInterface $user)
    {
        $user->activation_date = new DateTime();
        $user->token_expires = null;
        $user->is_email_verified = true;

        return $this->_table->save($user);
    }

    /**
     * buildValidator
     *
     * @param Event $event event
     * @param Validator $validator validator
     * @param string $name name
     * @return Validator
     */
    public function buildValidator(Event $event, Validator $validator, $name)
    {
        if ($name === 'default') {
            return $this->_emailValidator($validator, $this->validateEmail);
        }

        return $validator;
    }

    /**
     * Email validator
     *
     * @param Validator $validator Validator instance.
     * @param bool $validateEmail true when email needs to be required
     * @return Validator
     */
    protected function _emailValidator(Validator $validator, $validateEmail)
    {
        $this->validateEmail = $validateEmail;
        $validator
            ->add('email', 'valid', ['rule' => 'email'])
            ->notEmptyString('email', __d('Users', 'This field is required'), function ($context) {
                return $this->validateEmail;
            });

        return $validator;
    }

    /**
     * Returns the list of validators
     *
     * @param array $options Array of options ['validate_email' => true/false, 'use_tos' => true/false]
     * @return Validator
     */
    public function getRegisterValidators($options)
    {
        $validateEmail = Hash::get($options, 'validate_email');

        $validator = $this->_table->validationDefault(new Validator());
        $validator = $this->_table->validationRegister($validator);

        if ($validateEmail) {
            $validator = $this->_emailValidator($validator, $validateEmail);
        }

        return $validator;
    }
}

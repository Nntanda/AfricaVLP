<?php
/**
 * Copyright 2010 - 2017, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2017, Cake Development Corporation (https://www.cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace App\Controller\Traits;

use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Response;

/**
 * Covers registration features and email token validation
 *
 * @property \Cake\Http\ServerRequest $request
 */
trait RegisterTrait
{
    use PasswordManagementTrait;
    use CustomUsersTableTrait;

    /**
     * Register a new user
     *
     * @throws NotFoundException
     * @return mixed
     */
    public function register()
    {
        if (!Configure::read('Users.Registration.active')) {
            throw new NotFoundException();
        }

        $userId = $this->Auth->user('id');
        if (!empty($userId) && !Configure::read('Users.Registration.allowLoggedIn')) {
            return $this->redirect(Configure::read('Users.Profile.route'));
        }

        $usersTable = $this->getUsersTable();
        $user = $usersTable->newEntity();
        $validateEmail = (bool)Configure::read('Users.Email.validate');
        $tokenExpiration = Configure::read('Users.Token.expiration');
        $options = [
            'token_expiration' => $tokenExpiration,
            'validate_email' => $validateEmail,
        ];
        $requestData = $this->request->getData();

        $this->set(compact('user'));
        $this->set('_serialize', ['user']);

        if (!$this->request->is('post')) {
            return;
        }

        $userSaved = $usersTable->register($user, $requestData, $options);
        if (!$userSaved) {
            $this->Flash->error(__('The user could not be saved'));

            return;
        }

        $this->Flash->success('Registeration successful, please log in');

        return $this->redirect(['action' => 'register']);

        // return $this->_afterRegister($userSaved);
    }

    /**
     * Check the POST and validate it for registration, for now we check the reCaptcha
     *
     * @return bool
     */
    protected function _validateRegisterPost()
    {
        if (!Configure::read('Users.reCaptcha.registration')) {
            return true;
        }

        return $this->validateReCaptcha(
            $this->request->getData('g-recaptcha-response'),
            $this->request->clientIp()
        );
    }

    /**
     * Prepare flash messages after registration, and dispatch afterRegister event
     *
     * @param EntityInterface $userSaved User entity saved
     * @return Response
     */
    // protected function _afterRegister(EntityInterface $userSaved)
    // {
    //     $validateEmail = (bool)Configure::read('Users.Email.validate');
    //     $message = __('You have registered successfully, please log in');
    //     if ($validateEmail) {
    //         $message = __('We just sent you an email, follow the link to activate your account.');
    //     }
    //     $this->Flash->success($message);

    //     return $this->redirect(['action' => 'register']);
    // }

    /**
     * Validate an email
     *
     * @param string $token token
     * @return void
     */
    public function validateEmail($token = null)
    {
        $this->validate('email', $token);
    }

    /**
     * Resend Token validation
     *
     * @return mixed
     */
    public function resendEmailValidation()
    {
        $usersTable = $this->getUsersTable();

        if (isset($this->Auth) && $this->Auth->user()!= null) {
            $user = $usersTable->get($this->Auth->user('id'));
        } elseif ($this->request->is('post')) {
            $user = $usersTable->get($this->request->getData('id'));
        }

        if($user->is_email_verified){
            $this->Flash->success('Email already verified');
            return $this->redirect($this->request->referer());
        }

        if ($user->tokenExpired()) {
            try {
                if($usersTable->resetEmailToken($user,[
                    'sendEmail'=>true, 
                    'token_expiration'=>Configure::read('Users.Token.expiration')
                ])){
                    $this->Flash->success(__(
                        'Email verification link sent successfully. Please check your email.'
                    ));
                } else {
                    $this->Flash->error(__('Error resetting email verification link'));
                }

                return $this->redirect($this->request->referer());
            } catch (Exception $ex) {
                $this->Flash->error(__('Token could not be reset'));
            }
        }else{
            if($usersTable->resendEmailValidation($user)){
                $this->Flash->success(__(
                        'Email verification link sent successfully. Please check your email.'
                    ));
            }else{
                $this->Flash->error(__('Error resending email verification link'));
            }
        }

        return $this->redirect($this->request->referer());
    }
}

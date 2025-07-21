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

use App\Exception\UserNotFoundException;
use App\Exception\WrongPasswordException;
use Cake\Core\Configure;
use Cake\Validation\Validator;
use Exception;

/**
 * Covers the password management: reset, change
 *
 * @property \Cake\Http\ServerRequest $request
 */
trait PasswordManagementTrait
{
    use UserValidationTrait;

    /**
     * Change password
     *
     * @return mixed
     */
    public function changePassword()
    {
        $user = $this->getUsersTable()->newEntity();
        $id = $this->Auth->user('id');
        if (!empty($id)) {
            $user->id = $this->Auth->user('id');
            $validatePassword = true;

            $redirect = Configure::read('Users.Profile.route');
        } else {
            // $this->viewBuilder()->setLayout('login');
            $user->id = $this->request->getSession()->read(Configure::read('Users.Key.Session.resetPasswordUserId'));
            $validatePassword = false;
            if (!$user->id) {
                $this->Flash->error(__('User was not found'));
                $this->redirect($this->Auth->getConfig('loginAction'));

                return;
            }

            $redirect = $this->Auth->getConfig('loginAction');
        }

        $this->set('validatePassword', $validatePassword);
        if ($this->request->is('post')) {
            try {
                $validator = $this->getUsersTable()->validationPasswordConfirm(new Validator());
                if (!empty($id)) {
                    $validator = $this->getUsersTable()->validationCurrentPassword($validator);
                }
                $user = $this->getUsersTable()->patchEntity(
                    $user,
                    $this->request->getData(),
                    ['validate' => $validator]
                );

                if ($user->getErrors()) {
                    $this->Flash->error(__('Validation Error: Password was not changed'));
                } else {
                    $user = $this->getUsersTable()->changePassword($user);
                    if ($user) {
                        $this->Flash->success(__('Password has been changed successfully'));

                        return $this->redirect($redirect);
                    } else {
                        $this->Flash->error(__('Error: Password could not be changed'));
                    }
                }
            } catch (UserNotFoundException $exception) {
                $this->Flash->error(__('User was not found'));
            } catch (WrongPasswordException $wpe) {
                $this->Flash->error(__('{0}', $wpe->getMessage()));
            } catch (Exception $exception) {
                $this->Flash->error(__('Password could not be changed'));
                $this->log($exception);
                $this->log($user);
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Reset password
     *
     * @param null $token token data.
     * @return void
     */
    public function resetPassword($token = null)
    {
        $this->validate('password', $token);
    }

    /**
     * Reset password
     *
     * @return void|\Cake\Network\Response
     */
    public function requestResetPassword()
    {
        $this->set('user', $this->getUsersTable()->newEntity());
        $this->set('_serialize', ['user']);
        if (!$this->request->is('post')) {
            return;
        }

        $reference = $this->request->getData('reference');
        try {
            $resetUser = $this->getUsersTable()->resetToken($reference, [
                'expiration' => Configure::read('Users.Token.expiration'),
                'checkActive' => false,
                'sendEmail' => true,
                'ensureActive' => Configure::read('Users.Registration.ensureActive')
            ]);
            if ($resetUser) {
                $msg = __('Please check your email to continue with password reset process');
                $this->Flash->success($msg);
            } else {
                $msg = __('The password token could not be generated. Please try again');
                $this->Flash->error($msg);
            }

            return $this->redirect(['action' => 'login']);
        } catch (UserNotFoundException $exception) {
            $this->Flash->error(__('User {0} was not found', $reference));
        } catch (Exception $exception) {
            $this->Flash->error(__('Token could not be reset'));
            $this->log($exception);
        }
    }
}

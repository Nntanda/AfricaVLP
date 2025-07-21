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

use App\Exception\TokenExpiredException;
use App\Exception\UserNotFoundException;
use Cake\Core\Configure;
use Cake\Network\Response;
use Exception;

/**
 * Covers the user validation
 *
 * @property \Cake\Http\ServerRequest $request
 */
trait UserValidationTrait
{
    /**
     * Validates email
     *
     * @param string $type 'email' or 'password' to validate the user
     * @param string $token token
     * @return Response
     */
    public function validate($type = null, $token = null)
    {
        try {
            switch ($type) {
                case 'email':
                    $result = $this->getUsersTable()->validate($token, 'verifyEmail');
                    if ($result) {
                        $this->Flash->success(__('Email validated successfully'));
                    } else {
                        $this->Flash->error(__('Email could not be validated'));
                    }
                    break;
                case 'password':
                    $result = $this->getUsersTable()->validate($token);
                    if (!empty($result)) {
                        $this->Flash->success(__('Reset password token was validated successfully'));
                        $this->request->getSession()->write(
                            Configure::read('Users.Key.Session.resetPasswordUserId'),
                            $result->id
                        );

                        return $this->redirect(['action' => 'changePassword']);
                    } else {
                        $this->Flash->error(__('Reset password token could not be validated'));
                    }
                    break;
                default:
                    $this->Flash->error(__('Invalid validation type'));
            }
        } catch (UserNotFoundException $ex) {
            $this->Flash->error(__('Invalid token or user account already validated'));
        } catch (TokenExpiredException $ex) {
            $this->Flash->error(__('Token already expired'));
        }

        return $this->redirect(['action' => 'login']);
    }

    /**
     * Resend Token validation
     *
     * @return mixed
     */
    public function resendTokenValidation()
    {
        $this->set('user', $this->getUsersTable()->newEntity());
        $this->set('_serialize', ['user']);
        if (!$this->request->is('post')) {
            return;
        }
        $reference = $this->request->getData('reference');
        try {
            if ($this->getUsersTable()->resetToken($reference, [
                'expiration' => Configure::read('Users.Token.expiration'),
                'checkActive' => true,
                'sendEmail' => true,
                'emailTemplate' => 'validation'
            ])) {
                $this->Flash->success(__(
                    'Token has been reset successfully. Please check your email.'
                ));
            } else {
                $this->Flash->error(__('Token could not be reset'));
            }

            return $this->redirect(['action' => 'login']);
        } catch (UserNotFoundException $ex) {
            $this->Flash->error(__('User {0} was not found', $reference));
        } catch (UserAlreadyActiveException $ex) {
            $this->Flash->error(__('User {0} is already active', $reference));
        } catch (Exception $ex) {
            $this->Flash->error(__('Token could not be reset'));
        }
    }
}

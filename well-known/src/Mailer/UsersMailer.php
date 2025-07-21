<?php

namespace App\Mailer;

use Cake\Datasource\EntityInterface;
use Cake\Mailer\Email;
use Cake\Mailer\Mailer;

/**
 * User Mailer
 *
 */
class UsersMailer extends Mailer
{
    /**
     * Constructor hook method.
     *
     * @param array $config The configuration settings provided to this behavior.
     * @return void
     */
    // public function __construct(Email $email = null)
    // {
    //     parent::__construct();
    //     $this->from(['info@naijadealerz.com'=>'Naijadealerz']);
    // }

    /**
     * Send the templated email to the user
     *
     * @param EntityInterface $user User entity
     * @param string $subject Subject, note the first_name of the user will be prepended if exist
     * @param string $template string, note the first_name of the user will be prepended if exists
     *
     * @return array email send result
     */
    protected function welcome(EntityInterface $user, $subject, $template = 'welcome')
    {
        $firstName = isset($user['first_name'])? $user['first_name'] . ', ' : '';
        $user->setHidden(['password', 'token_expires', 'api_token']);

        $this
            ->setTo($user['email'])
            ->setSubject($firstName . $subject)
            ->setViewVars(compact('user'));
            
        $this->viewBuilder()->setTemplate($template);
    }
    /**
     * Send the templated email to the user
     *
     * @param EntityInterface $user User entity
     * @param string $subject Subject, note the first_name of the user will be prepended if exist
     * @param string $template string, note the first_name of the user will be prepended if exists
     *
     * @return array email send result
     */
    protected function validation(EntityInterface $user, $subject, $template = 'verification')
    {
        $firstName = isset($user['first_name'])? $user['first_name'] . ', ' : '';
        $user->setHidden(['password', 'token_expires', 'api_token']);

        $this
            ->setTo($user['email'])
            ->setSubject($firstName . $subject)
            ->setViewVars(compact('user'));
            
        $this->viewBuilder()->setTemplate($template);
    }

    /**
     * Send the reset password email to the user
     *
     * @param EntityInterface $user User entity
     * @param string $template string, note the first_name of the user will be prepended if exists
     *
     * @return array email send result
     */
    protected function resetPassword(EntityInterface $user, $template = 'reset_password')
    {
        $firstName = isset($user['first_name'])? $user['first_name'] . ', ' : '';
        $subject = __('{0}Your reset password link', $firstName);
        $user->setHidden(['password', 'token_expires', 'api_token']);

        $this
            ->setTo($user['email'])
            ->setSubject($subject)
            ->setViewVars($user->toArray());
            
        $this->viewBuilder()->setTemplate($template);
    }

    /**
     * Send account validation email to the user
     *
     * @param EntityInterface $user User entity
     * @param EntityInterface $socialAccount SocialAccount entity
     * @param string $template string, note the first_name of the user will be prepended if exists
     *
     * @return array email send result
     */
    protected function socialAccountValidation(
        EntityInterface $user,
        EntityInterface $socialAccount,
        $template = 'CakeDC/Users.social_account_validation'
    ) {
        $firstName = isset($user['first_name'])? $user['first_name'] . ', ' : '';
        //note: we control the space after the username in the previous line
        $subject = __d('CakeDC/Users', '{0}Your social account validation link', $firstName);
        $this
            ->setTo($user['email'])
            ->setSubject($subject)
            ->setViewVars(compact('user', 'socialAccount'));
            
        $this->viewBuilder()->setTemplate($template);
    }

    /**
     * Send newsletter email to users
     *
     * @param EntityInterface $user User entity
     * @param EntityInterface $socialAccount SocialAccount entity
     * @param string $template string, note the first_name of the user will be prepended if exists
     *
     * @return array email send result
     */
    protected function newsletter($emailList, $newsContent, $subject = '', $template = 'newsletter') {
        $this
            ->setTo($emailList)
            ->setSubject($subject)
            ->setViewVars(compact('newsContent'));
            
        $this->viewBuilder()->setTemplate($template);
        $this->viewBuilder()->setLayout('newsletter');
    }

    /**
     * Send newsletter email to users
     *
     * @param EntityInterface $user User entity
     * @param EntityInterface $socialAccount SocialAccount entity
     * @param string $template string, note the first_name of the user will be prepended if exists
     *
     * @return array email send result
     */
    protected function newOrganizationUser($user, $subject = '', $template = 'organization_created_user') {
        $this
            ->setTo($user['email'])
            ->setSubject($subject)
            ->setViewVars(compact('user'));
            
        $this->viewBuilder()->setTemplate($template);
    }

    /**
     * Send newsletter email to users
     *
     * @param EntityInterface $user User entity
     * @param EntityInterface $socialAccount SocialAccount entity
     * @param string $template string, note the first_name of the user will be prepended if exists
     *
     * @return array email send result
     */
    protected function newOrganizationAU($emailList, $organization, $subject = '', $template = 'organization_created_au') {
        $this
            ->setTo($emailList)
            ->setSubject($subject)
            ->setViewVars(compact('organization'));
            
        $this->viewBuilder()->setTemplate($template);
    }

    /**
     * Send newsletter email to users
     *
     * @param EntityInterface $user User entity
     * @param EntityInterface $socialAccount SocialAccount entity
     * @param string $template string, note the first_name of the user will be prepended if exists
     *
     * @return array email send result
     */
    protected function organizationUserInvite($email, $organization_name, $role, $subject = '', $template = 'organization_user_invite') {
        $this
            ->setTo($email)
            ->setSubject($subject)
            ->setViewVars(compact('organization_name', 'role'));
            
        $this->viewBuilder()->setTemplate($template);
    }

    /**
     * Send newsletter email to users
     *
     * @param EntityInterface $user User entity
     * @param EntityInterface $socialAccount SocialAccount entity
     * @param string $template string, note the first_name of the user will be prepended if exists
     *
     * @return array email send result
     */
    protected function organizationUser($user, $organization_name, $role, $subject = '', $template = 'organization_user') {
        $this
            ->setTo($user['email'])
            ->setSubject($subject)
            ->setViewVars(compact('user','organization_name', 'role'));
            
        $this->viewBuilder()->setTemplate($template);
    }

    /**
     * Send newsletter email to users
     *
     * @param EntityInterface $user User entity
     * @param EntityInterface $socialAccount SocialAccount entity
     * @param string $template string, note the first_name of the user will be prepended if exists
     *
     * @return array email send result
     */
    protected function organizationUserAdded($user, $organization_user, $role, $subject = '', $template = 'organization_user_added') {
        $this
            ->setTo($organization_user->email)
            ->setSubject($subject)
            ->setViewVars(compact('organization_user', 'user', 'role'));
            
        $this->viewBuilder()->setTemplate($template);
    }
}

<?php
namespace App\Email;

use Cake\Datasource\EntityInterface;
use Cake\Mailer\Email;
use Cake\Mailer\MailerAwareTrait;

/**
 * Email sender class
 *
 */
class EmailSender
{
    use MailerAwareTrait;

    /**
     * Send welcome email
     *
     * @param EntityInterface $user User entity
     * @param Email $email instance, if null the default email configuration with the
     * @return void
     */
    public function sendWelcomeEmail(EntityInterface $user, Email $email = null)
    {
        $this
            ->getMailer(
                'Users',
                $this->_getEmailInstance($email)
            )
            ->send('welcome', [$user, __('Welcome to the African Union Volunteering Linkage Platform.')]);
    }

    /**
     * Send validation email
     *
     * @param EntityInterface $user User entity
     * @param Email $email instance, if null the default email configuration with the
     * @return void
     */
    public function sendValidationEmail(EntityInterface $user, Email $email = null)
    {
        $this
            ->getMailer(
                'Users',
                $this->_getEmailInstance($email)
            )
            ->send('validation', [$user, __('Your account validation link')]);
    }

    /**
     * Send the reset password email
     *
     * @param EntityInterface $user User entity
     * @param Email $email instance, if null the default email configuration with the
     * @param string $template email template
     * Users.validation template will be used, so set a ->template() if you pass an Email
     * instance
     * @return array email send result
     */
    public function sendResetPasswordEmail(
        EntityInterface $user,
        Email $email = null,
        $template = 'reset_password'
    ) {
        return $this
            ->getMailer(
                'Users',
                $this->_getEmailInstance($email)
            )
            ->send('resetPassword', [$user, $template]);
    }

    /**
     * Send message email
     *
     * @param EntityInterface $user User entity
     * @param Email $email instance, if null the default email configuration with the
     * @return void
     */
    public function sendMessageEmail(EntityInterface $user, Email $email = null)
    {
        $this
            ->getMailer(
                'Users',
                $this->_getEmailInstance($email)
            )
            ->send('message', [$user, __('You have a new message')]);
    }

    /**
     * Send newsletter email
     *
     * @param EntityInterface $user User entity
     * @param Email $email instance, if null the default email configuration with the
     * @return void
     */
    public function sendNewsletterEmail($emailList, $newsContent, $subject = '', Email $email = null)
    {
        $this
            ->getMailer(
                'Users',
                $this->_getEmailInstance($email)
            )
            ->send('newsletter', [$emailList, $newsContent, $subject]);
    }

    /**
     * Send newsletter email
     *
     * @param EntityInterface $user User entity
     * @param Email $email instance, if null the default email configuration with the
     * @return void
     */
    public function sendNewOrganizationUserEmail($user, $subject = '', Email $email = null)
    {
        $this
            ->getMailer(
                'Users',
                $this->_getEmailInstance($email)
            )
            ->send('newOrganizationUser', [$user, $subject]);
    }

    /**
     * Send newsletter email
     *
     * @param EntityInterface $user User entity
     * @param Email $email instance, if null the default email configuration with the
     * @return void
     */
    public function sendNewOrganizationAUEmail($emailList, $organization, $subject = '', Email $email = null)
    {
        $this
            ->getMailer(
                'Users',
                $this->_getEmailInstance($email)
            )
            ->send('newOrganizationAU', [$emailList, $organization, $subject]);
    }

    /**
     * Send newsletter email
     *
     * @param EntityInterface $user User entity
     * @param Email $email instance, if null the default email configuration with the
     * @return void
     */
    public function sendOrganizationUserInviteEmail($userEmail, $organization_name, $role, Email $email = null)
    {
        $this
            ->getMailer(
                'Users',
                $this->_getEmailInstance($email)
            )
            ->send('organizationUserInvite', [$userEmail, $organization_name, $role, __('Invitation to African Union Volunteer Linkage Platform.')]);
    }

    /**
     * Send newsletter email
     *
     * @param EntityInterface $user User entity
     * @param Email $email instance, if null the default email configuration with the
     * @return void
     */
    public function sendOrganizationUserEmail($user, $organization_name, $role, Email $email = null)
    {
        $this
            ->getMailer(
                'Users',
                $this->_getEmailInstance($email)
            )
            ->send('organizationUser', [$user, $organization_name, $role, __('You have been added to an Organization.')]);
    }

    /**
     * Send newsletter email
     *
     * @param EntityInterface $user User entity
     * @param Email $email instance, if null the default email configuration with the
     * @return void
     */
    public function sendOrganizationUserAddedEmail($user, $organization_user, $role, Email $email = null)
    {
        $this
            ->getMailer(
                'Users',
                $this->_getEmailInstance($email)
            )
            ->send('organizationUserAdded', [$user, $organization_user, $role, __('An admin has been added to your Organization.')]);
    }

    /**
     * Get or initialize the email instance. Used for mocking.
     *
     * @param Email $email if email provided, we'll use the instance instead of creating a new one
     * @return Email
     */
    protected function _getEmailInstance(Email $email = null)
    {
        if ($email === null) {
            // $email = new Email('default'); default
            $email = new Email('Postmark');
            $email->setEmailFormat('html');
            // $email->setFrom(['noreply@initsng.com' => 'AU VLP']);
            $email->setFrom(['noreply@volunteer.africa' => 'AU VLP']);
        }

        return $email;
    }
}

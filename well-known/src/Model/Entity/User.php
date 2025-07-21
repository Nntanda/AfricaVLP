<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;
use Cake\I18n\Time;
use DateTime;

/**
 * User Entity
 *
 * @property int $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $password
 * @property int|null $resident_country_id
 * @property int|null $city_id
 * @property string|null $phone_number
 * @property string|null $language
 * @property string|null $profile_image
 * @property string|null $token
 * @property string|null $gender
 * @property \Cake\I18n\FrozenDate|null $date_of_birth
 * @property string|null $place_of_birth
 * @property int|null $nationality_at_birth
 * @property int|null $current_nationality
 * @property string|null $marital_status
 * @property string|null $current_address
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $status
 *
 * @property \App\Model\Entity\Country $country
 * @property \App\Model\Entity\City $city
 * @property \App\Model\Entity\BlogPostComment[] $blog_post_comments
 * @property \App\Model\Entity\ConversationMessage[] $conversation_messages
 * @property \App\Model\Entity\EventComment[] $event_comments
 * @property \App\Model\Entity\ForumPost[] $forum_posts
 * @property \App\Model\Entity\ForumThread[] $forum_threads
 * @property \App\Model\Entity\NewsComment[] $news_comments
 * @property \App\Model\Entity\OrganizationAlumnus[] $organization_alumni
 * @property \App\Model\Entity\OrganizationUser[] $organization_users
 * @property \App\Model\Entity\Organization[] $organizations
 * @property \App\Model\Entity\VolunteeringHistory[] $volunteering_histories
 * @property \App\Model\Entity\VolunteeringInterest[] $volunteering_interests
 */
class User extends Entity
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
        'first_name' => true,
        'last_name' => true,
        'email' => true,
        'password' => true,
        'resident_country_id' => true,
        'city_id' => true,
        'phone_number' => true,
        'short_profile' => true,
        'language' => true,
        'profile_image' => true,
        'file' => true,
        'token' => true,
        'token_expires' => true,
        'gender' => true,
        'date_of_birth' => true,
        'place_of_birth' => true,
        'nationality_at_birth' => true,
        'current_nationality' => true,
        'marital_status' => true,
        'current_address' => true,
        'availability' => true,
        'created' => true,
        'modified' => true,
        'status' => true,
        'has_volunteering_experience' => true,
        'volunteered_program' => true,
        'year_of_service' => true,
        'country_served_in' => true,
        'experience_rating' => true,
        'country' => true,
        'city' => true,
        'blog_post_comments' => true,
        'conversation_messages' => true,
        'event_comments' => true,
        'forum_posts' => true,
        'forum_threads' => true,
        'news_comments' => true,
        'organization_alumni' => true,
        'organization_users' => true,
        'organizations' => true,
        'volunteering_histories' => true,
        'volunteering_interests' => true,
        'platform_interests' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
        'token'
    ];

    /**
     * @param string $password password that will be set.
     * @return bool|string
     */
    protected function _setPassword($password)
    {
        return $this->hashPassword($password);
    }

    /**
     * @param string $password password that will be confirm.
     * @return bool|string
     */
    protected function _setConfirmPassword($password)
    {
        return $this->hashPassword($password);
    }

    protected function _getDetails()
    {
        return $this->_properties['first_name'].' '.$this->_properties['last_name'];
    }

    /**
     * Hash a password using the configured password hasher,
     * use DefaultPasswordHasher if no one was configured
     *
     * @param string $password password to be hashed
     * @return mixed
     */
    public function hashPassword($password)
    {
        $PasswordHasher = $this->getPasswordHasher();

        return $PasswordHasher->hash($password);
    }

    /**
     * Return the configured Password Hasher
     *
     * @return mixed
     */
    public function getPasswordHasher()
    {
        $passwordHasher = '\Cake\Auth\DefaultPasswordHasher';

        return new $passwordHasher;
    }

    /**
     * Checks if a password is correctly hashed
     *
     * @param string $password password that will be check.
     * @param string $hashedPassword hash used to check password.
     * @return bool
     */
    public function checkPassword($password, $hashedPassword)
    {
        $PasswordHasher = $this->getPasswordHasher();

        return $PasswordHasher->check($password, $hashedPassword);
    }

    /**
     * Returns if the token has already expired
     *
     * @return bool
     */
    public function tokenExpired()
    {
        if (empty($this->token_expires)) {
            return true;
        }

        return new Time($this->token_expires) < Time::now();
    }

    /**
     * Generate token_expires and token in a user
     * @param int $tokenExpiration seconds to expire the token from Now
     * @return void
     */
    public function updateToken($tokenExpiration = 0)
    {
        $expiration = new Time('now');
        $this->token_expires = $expiration->addSeconds($tokenExpiration);

        $this->token = str_replace('-', '', Text::uuid());
    }
}

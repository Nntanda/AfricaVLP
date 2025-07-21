<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

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
 * @property string|null $availability
 * @property bool $is_email_verified
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
        'language' => true,
        'profile_image' => true,
        'token' => true,
        'gender' => true,
        'date_of_birth' => true,
        'place_of_birth' => true,
        'nationality_at_birth' => true,
        'current_nationality' => true,
        'marital_status' => true,
        'current_address' => true,
        'availability' => true,
        'is_email_verified' => true,
        'created' => true,
        'modified' => true,
        'status' => true,
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
        'volunteering_interests' => true
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
}

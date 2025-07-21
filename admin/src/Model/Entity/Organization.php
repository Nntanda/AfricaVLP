<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Organization Entity
 *
 * @property int $id
 * @property int|null $organization_type_id
 * @property string|null $name
 * @property string|null $about
 * @property int|null $country_id
 * @property int|null $city_id
 * @property string|null $logo
 * @property int|null $institution_type_id
 * @property string|null $government_affliliation
 * @property int|null $category_id
 * @property \Cake\I18n\FrozenDate|null $date_of_establishment
 * @property string $address
 * @property string $email
 * @property string|null $phone_number
 * @property string|null $website
 * @property string|null $facebbok_url
 * @property string|null $instagram_url
 * @property string|null $twitter_url
 * @property int|null $user_id
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\OrganizationType $organization_type
 * @property \App\Model\Entity\Country $country
 * @property \App\Model\Entity\City $city
 * @property \App\Model\Entity\InstitutionType $institution_type
 * @property \App\Model\Entity\CategoryOfOrganization $category_of_organization
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\AdminSupport[] $admin_supports
 * @property \App\Model\Entity\ConversationMessage[] $conversation_messages
 * @property \App\Model\Entity\ConversationParticipant[] $conversation_participants
 * @property \App\Model\Entity\Event[] $events
 * @property \App\Model\Entity\ForumThread[] $forum_threads
 * @property \App\Model\Entity\News[] $news
 * @property \App\Model\Entity\OrganizationAlumnus[] $organization_alumni
 * @property \App\Model\Entity\OrganizationCategory[] $organization_categories
 * @property \App\Model\Entity\OrganizationOffice[] $organization_offices
 * @property \App\Model\Entity\OrganizationUser[] $organization_users
 * @property \App\Model\Entity\Resource[] $resources
 * @property \App\Model\Entity\VolunteeringHistory[] $volunteering_histories
 */
class Organization extends Entity
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
        'organization_type_id' => true,
        'name' => true,
        'about' => true,
        'country_id' => true,
        'city_id' => true,
        'logo' => true,
        'institution_type_id' => true,
        'government_affliliation' => true,
        'category_id' => true,
        'date_of_establishment' => true,
        'address' => true,
        'lng' => true,
        'lat' => true,
        'email' => true,
        'phone_number' => true,
        'website' => true,
        'facebbok_url' => true,
        'instagram_url' => true,
        'twitter_url' => true,
        'user_id' => true,
        'status' => true,
        'is_verified' => true,
        'created' => true,
        'modified' => true,
        'organization_type' => true,
        'country' => true,
        'city' => true,
        'institution_type' => true,
        'category_of_organization' => true,
        'user' => true,
        'admin_supports' => true,
        'conversation_messages' => true,
        'conversation_participants' => true,
        'events' => true,
        'forum_threads' => true,
        'news' => true,
        'organization_alumni' => true,
        'organization_categories' => true,
        'organization_offices' => true,
        'organization_users' => true,
        'resources' => true,
        'volunteering_histories' => true
    ];
}

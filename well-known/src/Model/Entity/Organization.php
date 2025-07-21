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
        'file' => true,
        'institution_type_id' => true,
        'government_affliliation' => true,
        'is_national_volunteer_program' => true,
        'category_id' => true,
        'date_of_establishment' => true,
        'address' => true,
        'email' => true,
        'phone_number' => true,
        'website' => true,
        'facebook_url' => true,
        'instagram_url' => true,
        'twitter_url' => true,
        'user_id' => true,
        'status' => true,
        'lat' => true,
        'lng' => true,
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
        'volunteering_categories' => true,
        'organization_offices' => true,
        'organization_users' => true,
        'resources' => true,
        'volunteering_histories' => true,
        'pan_africanism' => true,
        'education_skills' => true,
        'health_wellbeing' => true,
        'no_poverty' => true,
        'agriculture_rural' => true,
        'democratic_values' => true,
        'environmental_sustainability' => true,
        'infrastructure_development' => true,
        'peace_security' => true,
        'culture' => true,
        'gender_inequality' => true,
        'youth_empowerment' => true,
        'reduced_inequality' => true,
        'sustainable_city' => true,
        'responsible_consumption' => true,
        'pan_africanism_pan' => true,
        'education_skills_pan' => true,
        'citizen_health_pan' => true,
        'poverty_pan' => true,
        'pan_africanism_edu' => true,
        'education_skills_edu' => true,
        'citizen_health_edu' => true,
        'poverty_edu' => true,
        'pan_africanism_health' => true,
        'education_skills_health' => true,
        'citizen_health_health' => true,
        'poverty_health' => true,
        'pan_africanism_nopov' => true,
        'education_skills_nopov' => true,
        'citizen_health_nopov' => true,
        'poverty_nopov' => true,
        'pan_africanism_agric' => true,
        'education_skills_agric' => true,
        'citizen_health_agric' => true,
        'poverty_agric' => true,
        'pan_africanism_demo' => true,
        'education_skills_demo' => true,
        'citizen_health_demo' => true,
        'poverty_demo' => true,
        'pan_africanism_enviro' => true,
        'education_skills_enviro' => true,
        'citizen_health_enviro' => true,
        'poverty_enviro' => true,
        'pan_africanism_infra' => true,
        'education_skills_infra' => true,
        'citizen_health_infra' => true,
        'poverty_infra' => true,
        'pan_africanism_peace' => true,
        'education_skills_peace' => true,
        'citizen_health_peace' => true,
        'poverty_peace' => true,
        'pan_africanism_culture' => true,
        'education_skills_culture' => true,
        'citizen_health_culture' => true,
        'poverty_culture' => true,
        'pan_africanism_gender' => true,
        'education_skills_gender' => true,
        'citizen_health_gender' => true,
        'poverty_gender' => true,
        'pan_africanism_youth' => true,
        'education_skills_youth' => true,
        'citizen_health_youth' => true,
        'poverty_youth' => true,
        'pan_africanism_reduced' => true,
        'education_skills_reduced' => true,
        'citizen_health_reduced' => true,
        'poverty_reduced' => true,
        'pan_africanism_sustainable' => true,
        'education_skills_sustainable' => true,
        'citizen_health_sustainable' => true,
        'poverty_sustainable' => true,
        'pan_africanism_responsible' => true,
        'education_skills_responsible' => true,
        'citizen_health_responsible' => true,
        'poverty_responsible' => true,
        'volunteer_exchange_region' => true,
        'volunteer_exchange_intern' => true,
        'pan_africanism_resources' => true,
        'pan_africanism_organiz_pol' => true,
        'pan_africanism_organiz_annu' => true,
        'pan_africanism_organiz_pol_file' => true,
        'pan_africanism_organiz_annu_file' => true,
        'additional_file' => true,
        'pan_africanism_country_file' =>true
    ];
}

<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\City $city
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit City'), ['action' => 'edit', $city->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete City'), ['action' => 'delete', $city->id], ['confirm' => __('Are you sure you want to delete # {0}?', $city->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Cities'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New City'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Countries'), ['controller' => 'Countries', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Country'), ['controller' => 'Countries', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Events'), ['controller' => 'Events', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Event'), ['controller' => 'Events', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Organization Offices'), ['controller' => 'OrganizationOffices', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Organization Office'), ['controller' => 'OrganizationOffices', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Organizations'), ['controller' => 'Organizations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Organization'), ['controller' => 'Organizations', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="cities view large-9 medium-8 columns content">
    <h3><?= h($city->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Country') ?></th>
            <td><?= $city->has('country') ? $this->Html->link($city->country->name, ['controller' => 'Countries', 'action' => 'view', $city->country->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($city->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($city->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $this->Number->format($city->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($city->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($city->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Events') ?></h4>
        <?php if (!empty($city->events)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Organization Id') ?></th>
                <th scope="col"><?= __('Title') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __('Country Id') ?></th>
                <th scope="col"><?= __('City Id') ?></th>
                <th scope="col"><?= __('Address') ?></th>
                <th scope="col"><?= __('Start Date') ?></th>
                <th scope="col"><?= __('End Date') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Requesting Volunteers') ?></th>
                <th scope="col"><?= __('Has Remunerations') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Region Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($city->events as $events): ?>
            <tr>
                <td><?= h($events->id) ?></td>
                <td><?= h($events->organization_id) ?></td>
                <td><?= h($events->title) ?></td>
                <td><?= h($events->description) ?></td>
                <td><?= h($events->country_id) ?></td>
                <td><?= h($events->city_id) ?></td>
                <td><?= h($events->address) ?></td>
                <td><?= h($events->start_date) ?></td>
                <td><?= h($events->end_date) ?></td>
                <td><?= h($events->status) ?></td>
                <td><?= h($events->requesting_volunteers) ?></td>
                <td><?= h($events->has_remunerations) ?></td>
                <td><?= h($events->created) ?></td>
                <td><?= h($events->modified) ?></td>
                <td><?= h($events->region_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Events', 'action' => 'view', $events->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Events', 'action' => 'edit', $events->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Events', 'action' => 'delete', $events->id], ['confirm' => __('Are you sure you want to delete # {0}?', $events->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Organization Offices') ?></h4>
        <?php if (!empty($city->organization_offices)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Organization Id') ?></th>
                <th scope="col"><?= __('Country Id') ?></th>
                <th scope="col"><?= __('City Id') ?></th>
                <th scope="col"><?= __('Address') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($city->organization_offices as $organizationOffices): ?>
            <tr>
                <td><?= h($organizationOffices->id) ?></td>
                <td><?= h($organizationOffices->organization_id) ?></td>
                <td><?= h($organizationOffices->country_id) ?></td>
                <td><?= h($organizationOffices->city_id) ?></td>
                <td><?= h($organizationOffices->address) ?></td>
                <td><?= h($organizationOffices->status) ?></td>
                <td><?= h($organizationOffices->created) ?></td>
                <td><?= h($organizationOffices->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'OrganizationOffices', 'action' => 'view', $organizationOffices->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'OrganizationOffices', 'action' => 'edit', $organizationOffices->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'OrganizationOffices', 'action' => 'delete', $organizationOffices->id], ['confirm' => __('Are you sure you want to delete # {0}?', $organizationOffices->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Organizations') ?></h4>
        <?php if (!empty($city->organizations)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Organization Type Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('About') ?></th>
                <th scope="col"><?= __('Country Id') ?></th>
                <th scope="col"><?= __('City Id') ?></th>
                <th scope="col"><?= __('Logo') ?></th>
                <th scope="col"><?= __('Institution Type Id') ?></th>
                <th scope="col"><?= __('Government Affliliation') ?></th>
                <th scope="col"><?= __('Category Id') ?></th>
                <th scope="col"><?= __('Date Of Establishment') ?></th>
                <th scope="col"><?= __('Phone Number') ?></th>
                <th scope="col"><?= __('Website') ?></th>
                <th scope="col"><?= __('Facebbok Url') ?></th>
                <th scope="col"><?= __('Instagram Url') ?></th>
                <th scope="col"><?= __('Twitter Url') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($city->organizations as $organizations): ?>
            <tr>
                <td><?= h($organizations->id) ?></td>
                <td><?= h($organizations->organization_type_id) ?></td>
                <td><?= h($organizations->name) ?></td>
                <td><?= h($organizations->about) ?></td>
                <td><?= h($organizations->country_id) ?></td>
                <td><?= h($organizations->city_id) ?></td>
                <td><?= h($organizations->logo) ?></td>
                <td><?= h($organizations->institution_type_id) ?></td>
                <td><?= h($organizations->government_affliliation) ?></td>
                <td><?= h($organizations->category_id) ?></td>
                <td><?= h($organizations->date_of_establishment) ?></td>
                <td><?= h($organizations->phone_number) ?></td>
                <td><?= h($organizations->website) ?></td>
                <td><?= h($organizations->facebbok_url) ?></td>
                <td><?= h($organizations->instagram_url) ?></td>
                <td><?= h($organizations->twitter_url) ?></td>
                <td><?= h($organizations->user_id) ?></td>
                <td><?= h($organizations->status) ?></td>
                <td><?= h($organizations->created) ?></td>
                <td><?= h($organizations->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Organizations', 'action' => 'view', $organizations->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Organizations', 'action' => 'edit', $organizations->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Organizations', 'action' => 'delete', $organizations->id], ['confirm' => __('Are you sure you want to delete # {0}?', $organizations->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Users') ?></h4>
        <?php if (!empty($city->users)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('First Name') ?></th>
                <th scope="col"><?= __('Last Name') ?></th>
                <th scope="col"><?= __('Email') ?></th>
                <th scope="col"><?= __('Password') ?></th>
                <th scope="col"><?= __('Resident Country Id') ?></th>
                <th scope="col"><?= __('City Id') ?></th>
                <th scope="col"><?= __('Phone Number') ?></th>
                <th scope="col"><?= __('Language') ?></th>
                <th scope="col"><?= __('Profile Image') ?></th>
                <th scope="col"><?= __('Token') ?></th>
                <th scope="col"><?= __('Gender') ?></th>
                <th scope="col"><?= __('Date Of Birth') ?></th>
                <th scope="col"><?= __('Place Of Birth') ?></th>
                <th scope="col"><?= __('Nationality At Birth') ?></th>
                <th scope="col"><?= __('Current Nationality') ?></th>
                <th scope="col"><?= __('Marital Status') ?></th>
                <th scope="col"><?= __('Current Address') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($city->users as $users): ?>
            <tr>
                <td><?= h($users->id) ?></td>
                <td><?= h($users->first_name) ?></td>
                <td><?= h($users->last_name) ?></td>
                <td><?= h($users->email) ?></td>
                <td><?= h($users->password) ?></td>
                <td><?= h($users->resident_country_id) ?></td>
                <td><?= h($users->city_id) ?></td>
                <td><?= h($users->phone_number) ?></td>
                <td><?= h($users->language) ?></td>
                <td><?= h($users->profile_image) ?></td>
                <td><?= h($users->token) ?></td>
                <td><?= h($users->gender) ?></td>
                <td><?= h($users->date_of_birth) ?></td>
                <td><?= h($users->place_of_birth) ?></td>
                <td><?= h($users->nationality_at_birth) ?></td>
                <td><?= h($users->current_nationality) ?></td>
                <td><?= h($users->marital_status) ?></td>
                <td><?= h($users->current_address) ?></td>
                <td><?= h($users->created) ?></td>
                <td><?= h($users->modified) ?></td>
                <td><?= h($users->status) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $users->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Users', 'action' => 'edit', $users->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Users', 'action' => 'delete', $users->id], ['confirm' => __('Are you sure you want to delete # {0}?', $users->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InstitutionType $institutionType
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Institution Type'), ['action' => 'edit', $institutionType->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Institution Type'), ['action' => 'delete', $institutionType->id], ['confirm' => __('Are you sure you want to delete # {0}?', $institutionType->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Institution Types'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Institution Type'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Organizations'), ['controller' => 'Organizations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Organization'), ['controller' => 'Organizations', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="institutionTypes view large-9 medium-8 columns content">
    <h3><?= h($institutionType->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($institutionType->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($institutionType->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $this->Number->format($institutionType->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($institutionType->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($institutionType->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Organizations') ?></h4>
        <?php if (!empty($institutionType->organizations)): ?>
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
            <?php foreach ($institutionType->organizations as $organizations): ?>
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
</div>

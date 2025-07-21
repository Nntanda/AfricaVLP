<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\VolunteeringRole $volunteeringRole
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Volunteering Role'), ['action' => 'edit', $volunteeringRole->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Volunteering Role'), ['action' => 'delete', $volunteeringRole->id], ['confirm' => __('Are you sure you want to delete # {0}?', $volunteeringRole->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Volunteering Roles'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Volunteering Role'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Volunteering Oppurtunities'), ['controller' => 'VolunteeringOppurtunities', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Volunteering Oppurtunity'), ['controller' => 'VolunteeringOppurtunities', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="volunteeringRoles view large-9 medium-8 columns content">
    <h3><?= h($volunteeringRole->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($volunteeringRole->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($volunteeringRole->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $this->Number->format($volunteeringRole->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($volunteeringRole->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($volunteeringRole->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Volunteering Oppurtunities') ?></h4>
        <?php if (!empty($volunteeringRole->volunteering_oppurtunities)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Event Id') ?></th>
                <th scope="col"><?= __('Volunteering Duration Id') ?></th>
                <th scope="col"><?= __('Volunteering Role Id') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($volunteeringRole->volunteering_oppurtunities as $volunteeringOppurtunities): ?>
            <tr>
                <td><?= h($volunteeringOppurtunities->id) ?></td>
                <td><?= h($volunteeringOppurtunities->event_id) ?></td>
                <td><?= h($volunteeringOppurtunities->volunteering_duration_id) ?></td>
                <td><?= h($volunteeringOppurtunities->volunteering_role_id) ?></td>
                <td><?= h($volunteeringOppurtunities->status) ?></td>
                <td><?= h($volunteeringOppurtunities->created) ?></td>
                <td><?= h($volunteeringOppurtunities->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'VolunteeringOppurtunities', 'action' => 'view', $volunteeringOppurtunities->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'VolunteeringOppurtunities', 'action' => 'edit', $volunteeringOppurtunities->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'VolunteeringOppurtunities', 'action' => 'delete', $volunteeringOppurtunities->id], ['confirm' => __('Are you sure you want to delete # {0}?', $volunteeringOppurtunities->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

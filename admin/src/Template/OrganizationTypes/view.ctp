<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrganizationType $organizationType
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Organization Type'), ['action' => 'edit', $organizationType->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Organization Type'), ['action' => 'delete', $organizationType->id], ['confirm' => __('Are you sure you want to delete # {0}?', $organizationType->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Organization Types'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Organization Type'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="organizationTypes view large-9 medium-8 columns content">
    <h3><?= h($organizationType->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($organizationType->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($organizationType->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $this->Number->format($organizationType->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($organizationType->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($organizationType->modified) ?></td>
        </tr>
    </table>
</div>

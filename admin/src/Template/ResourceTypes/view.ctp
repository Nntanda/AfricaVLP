<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ResourceType $resourceType
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Resource Type'), ['action' => 'edit', $resourceType->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Resource Type'), ['action' => 'delete', $resourceType->id], ['confirm' => __('Are you sure you want to delete # {0}?', $resourceType->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Resource Types'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Resource Type'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Resource Types Name Translation'), ['controller' => 'ResourceTypes_name_translation', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Resource Types Name Translation'), ['controller' => 'ResourceTypes_name_translation', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List I18n'), ['controller' => 'I18n', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New I18n'), ['controller' => 'I18n', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Resources'), ['controller' => 'Resources', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Resource'), ['controller' => 'Resources', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="resourceTypes view large-9 medium-8 columns content">
    <h3><?= h($resourceType->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($resourceType->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Resource Types Name Translation') ?></th>
            <td><?= $resourceType->has('name_translation') ? $this->Html->link($resourceType->name_translation->id, ['controller' => 'ResourceTypes_name_translation', 'action' => 'view', $resourceType->name_translation->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($resourceType->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $this->Number->format($resourceType->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($resourceType->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($resourceType->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related I18n') ?></h4>
        <?php if (!empty($resourceType->_i18n)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Locale') ?></th>
                <th scope="col"><?= __('Model') ?></th>
                <th scope="col"><?= __('Foreign Key') ?></th>
                <th scope="col"><?= __('Field') ?></th>
                <th scope="col"><?= __('Content') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($resourceType->_i18n as $i18n): ?>
            <tr>
                <td><?= h($i18n->id) ?></td>
                <td><?= h($i18n->locale) ?></td>
                <td><?= h($i18n->model) ?></td>
                <td><?= h($i18n->foreign_key) ?></td>
                <td><?= h($i18n->field) ?></td>
                <td><?= h($i18n->content) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'I18n', 'action' => 'view', $i18n->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'I18n', 'action' => 'edit', $i18n->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'I18n', 'action' => 'delete', $i18n->id], ['confirm' => __('Are you sure you want to delete # {0}?', $i18n->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Resources') ?></h4>
        <?php if (!empty($resourceType->resources)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Organization Id') ?></th>
                <th scope="col"><?= __('Title') ?></th>
                <th scope="col"><?= __('Resource Type Id') ?></th>
                <th scope="col"><?= __('File Type') ?></th>
                <th scope="col"><?= __('File Link') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($resourceType->resources as $resources): ?>
            <tr>
                <td><?= h($resources->id) ?></td>
                <td><?= h($resources->organization_id) ?></td>
                <td><?= h($resources->title) ?></td>
                <td><?= h($resources->resource_type_id) ?></td>
                <td><?= h($resources->file_type) ?></td>
                <td><?= h($resources->file_link) ?></td>
                <td><?= h($resources->status) ?></td>
                <td><?= h($resources->created) ?></td>
                <td><?= h($resources->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Resources', 'action' => 'view', $resources->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Resources', 'action' => 'edit', $resources->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Resources', 'action' => 'delete', $resources->id], ['confirm' => __('Are you sure you want to delete # {0}?', $resources->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

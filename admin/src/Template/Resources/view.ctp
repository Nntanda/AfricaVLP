<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Resource $resource
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Resource'), ['action' => 'edit', $resource->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Resource'), ['action' => 'delete', $resource->id], ['confirm' => __('Are you sure you want to delete # {0}?', $resource->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Resources'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Resource'), ['action' => 'add']) ?> </li>
        <!-- <li><?= $this->Html->link(__('List Organizations'), ['controller' => 'Organizations', 'action' => 'index']) ?> </li> -->
        <!-- <li><?= $this->Html->link(__('List Resource Categories'), ['controller' => 'ResourceCategories', 'action' => 'index']) ?> </li> -->
    </ul>
</nav>
<div class="resources view large-9 medium-8 columns content">
    <h3><?= h($resource->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Organization') ?></th>
            <td><?= $resource->has('organization') ? $this->Html->link($resource->organization->name, ['controller' => 'Organizations', 'action' => 'view', $resource->organization->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($resource->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('File Type') ?></th>
            <td><?= h($resource->file_type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('File Link') ?></th>
            <td><?= h($resource->file_link) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Resources Title Translation') ?></th>
            <td><?= $resource->has('title_translation') ? $this->Html->link($resource->title_translation->id, ['controller' => 'Resources_title_translation', 'action' => 'view', $resource->title_translation->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($resource->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Resource Type Id') ?></th>
            <td><?= $this->Number->format($resource->resource_type_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $this->Number->format($resource->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($resource->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($resource->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related I18n') ?></h4>
        <?php if (!empty($resource->_i18n)): ?>
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
            <?php foreach ($resource->_i18n as $i18n): ?>
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
        <h4><?= __('Related Resource Categories') ?></h4>
        <?php if (!empty($resource->resource_categories)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Resource Id') ?></th>
                <th scope="col"><?= __('Category Of Resource Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($resource->resource_categories as $resourceCategories): ?>
            <tr>
                <td><?= h($resourceCategories->id) ?></td>
                <td><?= h($resourceCategories->resource_id) ?></td>
                <td><?= h($resourceCategories->category_of_resource_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ResourceCategories', 'action' => 'view', $resourceCategories->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ResourceCategories', 'action' => 'edit', $resourceCategories->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ResourceCategories', 'action' => 'delete', $resourceCategories->id], ['confirm' => __('Are you sure you want to delete # {0}?', $resourceCategories->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

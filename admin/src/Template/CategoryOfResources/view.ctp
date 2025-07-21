<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CategoryOfResource $categoryOfResource
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Category Of Resource'), ['action' => 'edit', $categoryOfResource->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Category Of Resource'), ['action' => 'delete', $categoryOfResource->id], ['confirm' => __('Are you sure you want to delete # {0}?', $categoryOfResource->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Category Of Resources'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category Of Resource'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Category Of Resources Name Translation'), ['controller' => 'CategoryOfResources_name_translation', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category Of Resources Name Translation'), ['controller' => 'CategoryOfResources_name_translation', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List I18n'), ['controller' => 'I18n', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New I18n'), ['controller' => 'I18n', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Resource Categories'), ['controller' => 'ResourceCategories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Resource Category'), ['controller' => 'ResourceCategories', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="categoryOfResources view large-9 medium-8 columns content">
    <h3><?= h($categoryOfResource->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($categoryOfResource->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Category Of Resources Name Translation') ?></th>
            <td><?= $categoryOfResource->has('name_translation') ? $this->Html->link($categoryOfResource->name_translation->id, ['controller' => 'CategoryOfResources_name_translation', 'action' => 'view', $categoryOfResource->name_translation->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($categoryOfResource->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $this->Number->format($categoryOfResource->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($categoryOfResource->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($categoryOfResource->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related I18n') ?></h4>
        <?php if (!empty($categoryOfResource->_i18n)): ?>
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
            <?php foreach ($categoryOfResource->_i18n as $i18n): ?>
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
        <?php if (!empty($categoryOfResource->resource_categories)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Resource Id') ?></th>
                <th scope="col"><?= __('Category Of Resource Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($categoryOfResource->resource_categories as $resourceCategories): ?>
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

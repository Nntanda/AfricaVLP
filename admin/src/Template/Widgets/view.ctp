<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Widget $widget
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Widget'), ['action' => 'edit', $widget->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Widget'), ['action' => 'delete', $widget->id], ['confirm' => __('Are you sure you want to delete # {0}?', $widget->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Widgets'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Widget'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="widgets view large-9 medium-8 columns content">
    <h3><?= h($widget->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($widget->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($widget->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Image') ?></th>
            <td><?= h($widget->image) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($widget->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $this->Number->format($widget->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($widget->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($widget->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Content') ?></h4>
        <?= $this->Text->autoParagraph(h($widget->content)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related I18n') ?></h4>
        <?php if (!empty($widget->_i18n)): ?>
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
            <?php foreach ($widget->_i18n as $i18n): ?>
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
</div>

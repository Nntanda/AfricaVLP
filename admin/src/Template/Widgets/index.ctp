<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Widget[]|\Cake\Collection\CollectionInterface $widgets
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Widget'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="widgets index large-9 medium-8 columns content">
    <h3><?= __('Widgets') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                <th scope="col"><?= $this->Paginator->sort('image') ?></th>
                <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($widgets as $widget): ?>
            <tr>
                <td><?= $this->Number->format($widget->id) ?></td>
                <td><?= h($widget->name) ?></td>
                <td><?= h($widget->title) ?></td>
                <td><?= h($widget->image) ?></td>
                <td><?= $this->Number->format($widget->status) ?></td>
                <td><?= h($widget->created) ?></td>
                <td><?= h($widget->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $widget->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $widget->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $widget->id], ['confirm' => __('Are you sure you want to delete # {0}?', $widget->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>

<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\VolunteeringCategory $volunteeringCategory
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Volunteering Category'), ['action' => 'edit', $volunteeringCategory->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Volunteering Category'), ['action' => 'delete', $volunteeringCategory->id], ['confirm' => __('Are you sure you want to delete # {0}?', $volunteeringCategory->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Volunteering Categories'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Volunteering Category'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Event Categories'), ['controller' => 'EventCategories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Event Category'), ['controller' => 'EventCategories', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List News Categories'), ['controller' => 'NewsCategories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New News Category'), ['controller' => 'NewsCategories', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Organization Categories'), ['controller' => 'OrganizationCategories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Organization Category'), ['controller' => 'OrganizationCategories', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="volunteeringCategories view large-9 medium-8 columns content">
    <h3><?= h($volunteeringCategory->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($volunteeringCategory->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($volunteeringCategory->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $this->Number->format($volunteeringCategory->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($volunteeringCategory->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($volunteeringCategory->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Event Categories') ?></h4>
        <?php if (!empty($volunteeringCategory->event_categories)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Volunteering Oppurtunity Id') ?></th>
                <th scope="col"><?= __('Volunteering Category Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($volunteeringCategory->event_categories as $eventCategories): ?>
            <tr>
                <td><?= h($eventCategories->id) ?></td>
                <td><?= h($eventCategories->volunteering_oppurtunity_id) ?></td>
                <td><?= h($eventCategories->volunteering_category_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'EventCategories', 'action' => 'view', $eventCategories->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'EventCategories', 'action' => 'edit', $eventCategories->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'EventCategories', 'action' => 'delete', $eventCategories->id], ['confirm' => __('Are you sure you want to delete # {0}?', $eventCategories->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related News Categories') ?></h4>
        <?php if (!empty($volunteeringCategory->news_categories)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('News Id') ?></th>
                <th scope="col"><?= __('Volunteering Category Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($volunteeringCategory->news_categories as $newsCategories): ?>
            <tr>
                <td><?= h($newsCategories->id) ?></td>
                <td><?= h($newsCategories->news_id) ?></td>
                <td><?= h($newsCategories->volunteering_category_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'NewsCategories', 'action' => 'view', $newsCategories->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'NewsCategories', 'action' => 'edit', $newsCategories->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'NewsCategories', 'action' => 'delete', $newsCategories->id], ['confirm' => __('Are you sure you want to delete # {0}?', $newsCategories->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Organization Categories') ?></h4>
        <?php if (!empty($volunteeringCategory->organization_categories)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Organization Id') ?></th>
                <th scope="col"><?= __('Volunteering Category Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($volunteeringCategory->organization_categories as $organizationCategories): ?>
            <tr>
                <td><?= h($organizationCategories->id) ?></td>
                <td><?= h($organizationCategories->organization_id) ?></td>
                <td><?= h($organizationCategories->volunteering_category_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'OrganizationCategories', 'action' => 'view', $organizationCategories->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'OrganizationCategories', 'action' => 'edit', $organizationCategories->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'OrganizationCategories', 'action' => 'delete', $organizationCategories->id], ['confirm' => __('Are you sure you want to delete # {0}?', $organizationCategories->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

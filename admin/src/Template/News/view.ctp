<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\News $news
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit News'), ['action' => 'edit', $news->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete News'), ['action' => 'delete', $news->id], ['confirm' => __('Are you sure you want to delete # {0}?', $news->id)]) ?> </li>
        <li><?= $this->Html->link(__('List News'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New News'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Organizations'), ['controller' => 'Organizations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Organization'), ['controller' => 'Organizations', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Regions'), ['controller' => 'Regions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Region'), ['controller' => 'Regions', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List News Title Translation'), ['controller' => 'News_title_translation', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New News Title Translation'), ['controller' => 'News_title_translation', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List News Content Translation'), ['controller' => 'News_content_translation', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New News Content Translation'), ['controller' => 'News_content_translation', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List I18n'), ['controller' => 'I18n', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New I18n'), ['controller' => 'I18n', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List News Categories'), ['controller' => 'NewsCategories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New News Category'), ['controller' => 'NewsCategories', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Publishing Categories'), ['controller' => 'PublishingCategories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Publishing Category'), ['controller' => 'PublishingCategories', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="news view large-9 medium-8 columns content">
    <h3><?= h($news->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Organization') ?></th>
            <td><?= $news->has('organization') ? $this->Html->link($news->organization->name, ['controller' => 'Organizations', 'action' => 'view', $news->organization->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($news->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Slug') ?></th>
            <td><?= h($news->slug) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Region') ?></th>
            <td><?= $news->has('region') ? $this->Html->link($news->region->name, ['controller' => 'Regions', 'action' => 'view', $news->region->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('News Title Translation') ?></th>
            <td><?= $news->has('title_translation') ? $this->Html->link($news->title_translation->id, ['controller' => 'News_title_translation', 'action' => 'view', $news->title_translation->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('News Content Translation') ?></th>
            <td><?= $news->has('content_translation') ? $this->Html->link($news->content_translation->id, ['controller' => 'News_content_translation', 'action' => 'view', $news->content_translation->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($news->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $this->Number->format($news->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($news->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($news->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Content') ?></h4>
        <?= $this->Text->autoParagraph(h($news->content)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Publishing Categories') ?></h4>
        <?php if (!empty($news->publishing_categories)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($news->publishing_categories as $publishingCategories): ?>
            <tr>
                <td><?= h($publishingCategories->id) ?></td>
                <td><?= h($publishingCategories->name) ?></td>
                <td><?= h($publishingCategories->status) ?></td>
                <td><?= h($publishingCategories->created) ?></td>
                <td><?= h($publishingCategories->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'PublishingCategories', 'action' => 'view', $publishingCategories->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'PublishingCategories', 'action' => 'edit', $publishingCategories->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'PublishingCategories', 'action' => 'delete', $publishingCategories->id], ['confirm' => __('Are you sure you want to delete # {0}?', $publishingCategories->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related I18n') ?></h4>
        <?php if (!empty($news->_i18n)): ?>
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
            <?php foreach ($news->_i18n as $i18n): ?>
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
        <h4><?= __('Related News Categories') ?></h4>
        <?php if (!empty($news->news_categories)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('News Id') ?></th>
                <th scope="col"><?= __('Volunteering Category Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($news->news_categories as $newsCategories): ?>
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
</div>

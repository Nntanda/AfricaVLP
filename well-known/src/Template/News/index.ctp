<div class="main">
    <div class="container">
    <div class="innder-head">
        <h1><?= __('News') ?></h1>
        <div class="row justify-content-center">
            <div class="col-6">
                <div id="custom-search-input">
                    <?= $this->Form->create(false, ['type' => 'get']) ?>
                    <div class="input-group">
                        <input type="text" class="search-query form-control" value="<?= $search ?>" name="s" placeholder="Search news"/>
                        <span class="input-group-btn">
                        <button type="button" disabled="disabled">
                            <span class="fa fa-search"></span>
                        </button>
                        </span>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="container-fluid">
    <div class="row flex-xl-nowrap">
        <div class="col-md-3 bd-sidebar">
            <nav class="navbar-collapse bd-links" id="bd-docs-nav" aria-label="Main navigation">
                <p><?= __('Filter By') ?></p>
                <ul class="">
                    <li class="header">
                        <?= $this->Html->link('ALL', ['action' => 'index']) ?>
                    </li>
                </ul>
                <?php 
                    echo $this->Form->create(false, ['type' => 'Get']);
                    echo $this->Form->control('region_id', ['empty' => __('Select Region'), 'label' => false, 'class' => 'form-control filter-select', 'templates' => [
                      'inputContainer' => '{{content}}'
                    ], 'style' => 'max-width: 250px;', 'value' => $region_id ]);
                    echo $this->Form->end();
                ?>

                <ul class="">
                    <li class="header">
                        <?= __('SECTORS') ?>
                    </li>
                    <?php foreach ($volunteering_categories as $k => $v): ?>
                        <li class="">
                            <?= $this->Html->link($v, ['?' => ['cat' => $k]]) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
        <div class="col-md-9 items-list">
            <?php if ($news->count() < 1): ?>
                <div class="alert alert-info" role="alert">
                    <?= __('No record found') ?>
                </div>
            <?php endif; ?>
            <?php foreach ($news as $newsData): ?>
                <div class="wrap mb-4">
                    <div class="card">
                        <div class="row no-gutters d-flex align-items-stretch">
                            <div class="col-lg-3 card-img" style="background-image: url(<?= $newsData->image ?>);"></div>
                            <div class="col-lg-9">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-9 d-flex flex-column">
                                            <h4 class="card-title"><?= h($newsData->title) ?></h4>
                                            <p class="card-text"><?= $this->Text->truncate(strip_tags($newsData->content), 150, ['ellipsis' => '...']) ?></p>
                                            <div class="row list-tag align-items-end">
                                                <div class="col-md-6">
                                                    <p class="text-muted flex-fill"><?= __('Date') ?>:
                                                    <span><?= $newsData->created->format('M d, Y') ?></span></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="text-muted flex-fill"><?= __('Organizer') ?>:
                                                    <span><?= $newsData->has('organization') ? $newsData->organization->name .($newsData->organization->is_verified ? ' <span class="badge badge-success rounded-circle text-light"><i class="fa fa-check"></i><span>' : '') : 'AU' ?></span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 d-flex align-items-center justify-content-end">
                                            <a href="<?= $this->Url->build(['controller' => 'News', 'action' => 'view', $newsData->id]) ?>" class="btn btn-small"><?= __('Read More') ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <div class="location">
                                <?php if (!empty($newsData->tags)): ?>
                                    <?php foreach ($newsData->tags as $tag): ?>
                                        <span class="badge badge-info text-white font-weight-normal">
                                            <a href="<?= $this->Url->build(['action' => 'tagged', $tag->title]) ?>" class="text-reset"><?= h($tag->title) ?></a>
                                        </span>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <div class="sector d-flex align-items-center">
                                <?php foreach ($newsData->volunteering_categories as $volunteering_category): ?><span><?= h($volunteering_category->name) ?></span><?php endforeach; ?> |
                                <?php foreach ($newsData->publishing_categories as $publishing_category): ?><span><?= h($publishing_category->name) ?></span><?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="row justify-content-center">
                <?= $this->element('Navigation/pagination') ?>
            </div>
        </div>
    </div>
    </div>
</div>

<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
        var a = $('a[href="<?php echo $this->Url->build() ?>"]');
        if (!a.parent().hasClass('treeview') && !a.parent().parent().hasClass('pagination')) {
            a.parent().addClass('active');
        }
        
        $('.filter-select').change(function () {
          $(this).closest('form').submit()
        })
    });
</script>

<?php $this->end(); ?>
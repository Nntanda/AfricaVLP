<div class="main">
    <div class="container">
    <div class="innder-head">
        <h1><?= __('Volunteering Organizations') ?></h1>
        <div class="row justify-content-center">
            <div class="col-6">
                <div id="custom-search-input">
                    <?= $this->Form->create(false, ['type' => 'get']) ?>
                    <div class="input-group">
                        <input type="text" class="search-query form-control" value="<?= $search ?>" name="s" placeholder="<?= __('Search Volunteering Organizations') ?>"/>
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
                        <?= $this->Html->link(__('ALL'), ['action' => 'index']) ?>
                    </li>
                </ul>
                <?php 
                    echo $this->Form->create(false, ['type' => 'Get']);
                    echo $this->Form->control('country_id', ['empty' => __('Select Country'), 'label' => false, 'class' => 'form-control filter-select', 'templates' => [
                      'inputContainer' => '{{content}}'
                    ], 'style' => 'max-width: 250px;', 'value' => $country_id ]);
                    echo $this->Form->end();
                ?>

                <ul class="">
                    <li class="header">
                        <?= __('SECTORS') ?>
                    </li>
                    <?php foreach ($volunteeringCategories as $k => $v): ?>
                        <li class="">
                            <?= $this->Html->link($v, ['?' => ['cat' => $k]]) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
        <div class="col-md-9 items-list">
            <?php if ($organizations->count() < 1): ?>
                <div class="alert alert-info" role="alert">
                    <?= __('No record found') ?>
                </div>
            <?php endif; ?>
            <?php foreach ($organizations as $organization): ?>
                <div class="wrap mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-lg-2 img-container">
                                    <img class="rounded" src="<?= $organization->logo ?>" alt="">
                                </div>
                                <div class="col-lg-10">
                                    <div class="row align-items-center">
                                        <div class="col-lg-9">
                                            <h4 class="card-title"><?= h($organization->name) ?></h4>
                                            <p class="card-text"><?= $this->Text->truncate(strip_tags($organization->about), 150, ['ellipsis' => '...']) ?></p>
                                            <div class="row list-tag align-items-end">
                                                <div class="col-md-6">
                                                    <p class="text-muted flex-fill"><?= __('Volunteers') ?>:
                                                    <span><?= $organization->volunteer_count ?></span></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="text-muted flex-fill"><?= __('Sectors') ?>:
                                                    <span><?= $organization->has('volunteering_categories') ? count($organization->volunteering_categories) : 0 ?></span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="d-flex align-items-center justify-content-end">
                                                <a href="<?= $this->Url->build(['action' => 'view', $organization->id]) ?>" class="btn btn-small"><?= __('View Details') ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <div class="location">
                                <p><img src="https://www.countryflags.io/<?= $organization->country->iso ?>/flat/64.png" alt=""><?= h($organization->has('city') ? $organization->city->name. ', ' : '')?> <a href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'countryPage', $organization->country->iso]) ?>"><?= $organization->country->nicename ?></a></p>
                            </div>
                            <div class="sector d-flex align-items-center">
                                <?php 
                                 $items = $organization->volunteering_categories; 
                                 $item =  array_slice($items, 0, 5);
                                foreach ($item as $volunteering_category): ?><span><?= h($volunteering_category->name) ?></span><?php endforeach; ?>
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
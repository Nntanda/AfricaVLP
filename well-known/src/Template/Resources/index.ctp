<div class="main">
    <div class="container">
    <div class="innder-head">
        <h1><?= isset($resource_type_id) && isset($resourceTypes->toArray()[$resource_type_id]) ? $resourceTypes->toArray()[$resource_type_id] : __('Resources') ?></h1>
        <div class="row justify-content-center">
            <div class="col-6">
                <div id="custom-search-input">
                    <?= $this->Form->create(false, ['type' => 'get']) ?>
                    <div class="input-group">
                        <input type="text" class="search-query form-control" value="<?= $search ?>" name="s" placeholder="Search"/>
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
                    echo $this->Form->control('resource_type_id', ['empty' => __('Select Resource Type'), 'label' => false, 'class' => 'form-control filter-select', 'templates' => [
                        'inputContainer' => '{{content}}'
                      ], 'style' => 'max-width: 250px;', 'value' => $resource_type_id ]);
                      
                    echo $this->Form->control('region_id', ['empty' => __('Select Region'), 'label' => false, 'class' => 'form-control filter-select mt-3', 'templates' => [
                      'inputContainer' => '{{content}}'
                    ], 'style' => 'max-width: 250px;', 'value' => $region_id ]);
                    
                    echo $this->Form->control('country_id', ['empty' => 'Select Country', 'label' => false, 'class' => 'form-control filter-select mt-3', 'templates' => [
                      'inputContainer' => '{{content}}'
                    ], 'style' => 'max-width: 250px;', 'value' => $country_id ]);
                    
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
            <?php if ($resources->count() < 1): ?>
                <div class="alert alert-info" role="alert">
                    <?= __('No record found') ?>
                </div>
            <?php endif; ?>
            <?php foreach ($resources as $resource): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row no-gutters d-flex align-items-stretch">
                        <div class="col-lg-10">
                            <div class="card-content d-flex flex-column">
                                <h4 class="card-title"><?= h($resource->title) ?></h4>
                                <p class="card-text"><?= $this->Text->truncate($resource->description, 150, ['ellipsis' => '...']) ?></p>
                                <p class="card-text d-flex mt-auto">
                                    <small class="text-muted flex-fill"><?= __('Date Published') ?>:
                                        <span><?= $resource->created->format('M d, Y') ?></span></small>
                                    <small class="text-muted flex-fill"><?= __('Publisher') ?>:
                                        <span><?= $resource->has('organization') ? $resource->organization->name .($resource->organization->is_verified ? ' <span class="badge badge-success rounded-circle text-light"><i class="fa fa-check"></i><span>' : '') : 'AU' ?></span></small>
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-2 d-flex align-items-center justify-content-end">
                            <?php if (isset($authUser) && isset($authUser['allow_resources']) && $authUser['allow_resources']){ ?>
                            <a href="<?= $resource->file_link ?>" class="btn btn-small" target="_blank"><?= __('View') ?></a>
                            <?php } else { ?>
                                <div class="alert alert-info" role="alert">
                                    <?= __('Please login or signup to view resources') ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                <div class="location">
                    <?php if ($resource->has('country')) { ?><p> <img src="https://www.countryflags.io/<?= $resource->country->iso ?>/flat/64.png" alt=""> <a href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'countryPage', $resource->country->iso]) ?>"><?= $resource->country->nicename ?></a> </p><?php } ?>
                </div>
                <div class="sector d-flex align-items-center">
                    <?php if ($resource->has('volunteering_categories')) {foreach ($resource->volunteering_categories as $volunteering_category): ?> <span class="badge badge-light"><?= h($volunteering_category->name) ?></span><?php endforeach;} ?> |
                    <?php if ($resource->has('resource_type')): ?><span class="badge badge-light"><?= h($resource->resource_type->name) ?></span><?php endif; ?>
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
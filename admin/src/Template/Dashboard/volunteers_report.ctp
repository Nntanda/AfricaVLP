<div class="container my-3">
    <div class="d-flex justify-content-end quick-actions">
        <button type="button" name="button" class="filter" data-toggle="modal" data-target="#exampleModal4"><?= __('Filter') ?> <img src="<?= $this->Url->image('filter.svg') ?>" alt=""></button>
        <button type="button" name="button" class=""><?= __('Export') ?> <img src="<?= $this->Url->image('export.svg') ?>" alt=""></button>
    </div>
</div>
<div class="container reports">

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h3><?= $this->Number->format($total) ?></h3>
                    <p><?= __('No of Volunteers') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h3><?= $this->Number->format($totalFemale) ?></h3>
                    <p><?= __('Female') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h3><?= $this->Number->format($totalMale) ?></h3>
                    <p><?= __('Male') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- begin:: Subheader -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid align-items-center">
            <!-- <div class="row"> -->
                <div class="col-md-7">
                    <div class="kt-subheader__main">
                        <h3 class="kt-subheader__title"><?= __('Volunteers') ?></h3>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="kt-subheader__toolbar" id="kt_subheader_search">
                    <?= $this->Form->create(false, ['type' => 'get', 'class' => 'kt-subheader__search']) ?>
                        <div class="input-group">
                            <input type="text" name="s" class="form-control" value="<?= $search ?>" placeholder="Search..." id="generalSearch">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">
                                <i class="flaticon2-search-1"></i>
                                </span>
                            </div>
                        </div>
                    <?= $this->Form->end(); ?>
                    </div>
                </div>

            <!-- </div> -->
        </div>
    </div>
    <!-- end:: Subheader -->

    <!--begin::Dashboard 4-->
    <!--begin::Portlet-->
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <!--begin::Section-->
            <div class="kt-section">
                <div class="kt-section__content">
                    <div class="data-table panel panel-default panel-table" style="overflow-x:auto;">
                        <div class="panel-body table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                    <th scope="col"><?= __('Name') ?></th>
                                    <th scope="col"><?= __('Gender') ?></th>
                                    <th scope="col"><?= __('Region') ?></th>
                                    <th scope="col"><?= __('Country') ?></th>
                                    <th scope="col"><?= __('Email') ?></th>
                                    <th scope="col"><?= __('Action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($volunteers as $volunteer): ?>
                                    <tr>
                                        <td><?= h($volunteer->first_name. ' '. $volunteer->last_name) ?></td>
                                        <td><?= h($volunteer->gender); ?></td>
                                        <td><?= $volunteer->has('country') ? ($volunteer->country->has('region') ? $volunteer->country->region->name : '') : '' ?></td>
                                        <td><?= $volunteer->has('country') ? $volunteer->country->nicename : '' ?></td>
                                        <td><?= h($volunteer->email) ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <img src="<?= $this->Url->image('table-menu.svg') ?>" alt="">
                                                </a>
                    
                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                                    <a class="dropdown-item" href="<?= $this->Url->build($volunteer->id) ?>" target="_blank"><?= __('View') ?></a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Portlet-->
    <!--end::Dashboard 4-->

    <?php if ($volunteers->count() < 1): ?>
        <div class="alert alert-info" role="alert">
            <?= __('No record found') ?>
        </div>
    <?php else: ?>
    <div class="row justify-content-center">
        <?= $this->element('Navigation/pagination') ?>
    </div>
    <?php endif; ?>
</div>

<?= $this->Form->create(false, ['type' => 'get']) ?>
<div class="filter-modal modal fade" id="exampleModal4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel"><?= __('Edit Filters') ?> <a href="<?= $this->Url->build(['action'=>'volunteersReport']) ?>"><?= __('Clear all filters') ?></a></h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h6><?= __('Date Range') ?></h6>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label><?= __('From') ?></label>
                            <input type="date" name="filter[date_from]" max="3000-12-31" min="1000-01-01" class="form-control" value="<?= isset($filter['date_from']) ? $filter['date_from'] : '' ?>">
                        </div>
                        <div class="form-group">
                            <label><?= __('To') ?></label>
                            <input type="date" name="filter[date_to]" min="1000-01-01" max="3000-12-31" class="form-control" value="<?= isset($filter['date_to']) ? $filter['date_to'] : '' ?>">
                        </div>
                    </div>
                </div>
                <div class='age-range'>
                    <h6 class=""><?= __('Age') ?></h6>
                    <?= $this->Form->control('filter.age_range', ['label' => false, 'empty' => __('Select Region'), 'class' => 'js-range-slider', 'value' => isset($filter['age_range']) ? $filter['age_range'] : '']) ?>
                </div>
                <div class='age-range'>
                    <h6 class=""><?= __('Region') ?></h6>
                    <?= $this->Form->control('filter.region_id', ['label' => false, 'empty' => __('Select Region'), 'value' => isset($filter['region_id']) ? $filter['region_id'] : '']) ?>
                </div>
                <div class='age-range'>
                    <h6 class=""><?= __('Country') ?></h6>
                    <?= $this->Form->control('filter.country_id', ['label' => false, 'empty' => __('Select Country'), 'value' => isset($filter['country_id']) ? $filter['country_id'] : '']) ?>
                </div>
                <div class='age-range'>
                    <h6 class=""><?= __('Interest') ?></h6>
                    <?= $this->Form->control('filter.interest', ['label' => false, 'placeholder' => __('Select Interest'), 'value' => isset($filter['interest']) ? $filter['interest'] : '', 'class' => 'form-control select2', 'multiple' => true, 'style' => 'width: 100%']) ?>
                </div>
                <div class='age-range'>
                    <h6 class=""><?= __('Gender') ?></h6>
                    <?= $this->Form->control('filter.gender', ['label' => false, 'empty' => __('Select Gender'), 'options' => ['male' => __('Male'), 'female' => __('Female')], 'value' => isset($filter['gender']) ? $filter['gender'] : '' ]) ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->hidden('s', ['value' => $search]); ?>
<?= $this->Form->end(); ?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<?php $this->Html->css('ion.rangeSlider.min', ['block' => 'css']) ?>

<?php $this->Html->script("https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js", ['block' => 'script']) ?>
<?php $this->Html->script("ion.rangeSlider.min.js", ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
        $(".select2").select2()

        
        $('.filter-select').change(function () {
          $(this).closest('form').submit()
        })

        $(".js-range-slider").ionRangeSlider({
            skin: "round",
            type: "double",
            grid: true,
            min: 0,
            max: 70,
            // from: 20,
            // to: 40,
        });
    });

</script>

<?php $this->end(); ?>
<div class="container my-3">
    <div class="d-flex justify-content-end quick-actions">
        <button type="button" name="button" class="filter" data-toggle="modal" data-target="#exampleModal4"><?= __('Filter') ?> <img src="<?= $this->Url->image('filter.svg') ?>" alt=""></button>
        <button type="button" name="button" class=""><?= __('Export') ?> <img src="<?= $this->Url->image('export.svg') ?>" alt=""></button>
    </div>
</div>
<div class="container reports">

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h3><?= $this->Number->format($total) ?></h3>
                    <p><?= __('Volunteering Events') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h3><?= $this->Number->format($totalActive) ?></h3>
                    <p><?= __('Active Events') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h3><?= $this->Number->format($totalUpcoming) ?></h3>
                    <p><?= __('Upcoming Events') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h3><?= $this->Number->format($totalPast) ?></h3>
                    <p><?= __('Past Events') ?></p>
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
                        <h3 class="kt-subheader__title"><?= __('Volunteering Events') ?></h3>
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
                                    <th scope="col"><?= __('Organizer') ?></th>
                                    <th scope="col"><?= __('Region') ?></th>
                                    <th scope="col"><?= __('No of Volunteers') ?></th>
                                    <th scope="col"><?= __('Status') ?></th>
                                    <th scope="col"><?= __('Action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($events as $event): ?>
                                    <tr>
                                        <td><?= h($event->title) ?></td>
                                        <td><?= $event->has('organization') ? h($event->organization->name) : '' ?></td>
                                        <td><?= $event->has('country') ? ($event->country->has('region') ? $event->country->region->name : '') : '' ?></td>
                                        <td><?= $this->Number->format($event->no_of_volunteers) ?></td>
                                        <td><?php $now = \Cake\I18n\Time::now();
                                        if ($now > $event->start_date && $now > $event->end_date) {
                                            echo __('Past');
                                        } elseif ($now > $event->start_date && $now < $event->end_date) {
                                            echo __('Ongoing');
                                        } else {
                                            echo __('Upcoming');
                                        } ?></td>
                                        <td>
                                            <div class="kt-widget-7__item-toolbar">
                                                <div class="dropdown dropdown-inline">
                                                    <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="flaticon-more-1"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <ul class="kt-nav">
                                                            <li class="kt-nav__item">
                                                                <a class="kt-nav__link" href="<?= $this->Url->build(['controller' => 'Events', 'action' => 'view',$event->id]) ?>" target="_blank"><?= __('View') ?></a>
                                                                
                                                            </li>
                                                        </ul>
                                                    </div>
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

    <?php if ($events->count() < 1): ?>
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
                <h2 class="modal-title" id="exampleModalLabel"><?= __('Edit Filters') ?> <a href="<?= $this->Url->build(['action'=>'eventsReport']) ?>"><?= __('Clear all filters') ?></a></h2>
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
                    <h6 class=""><?= __('Region') ?></h6>
                    <?= $this->Form->control('filter.region_id', ['label' => false, 'empty' => __('Select Region'), 'value' => isset($filter['region_id']) ? $filter['region_id'] : '']) ?>
                </div>
                <div class='age-range'>
                    <h6 class=""><?= __('Country') ?></h6>
                    <?= $this->Form->control('filter.country_id', ['label' => false, 'empty' => __('Select Country'), 'value' => isset($filter['country_id']) ? $filter['country_id'] : '']) ?>
                </div>
                <div class='age-range'>
                    <h6 class=""><?= __('Sectors') ?></h6>
                    <?= $this->Form->control('filter.sectors', ['label' => false, 'placeholder' => __('Select Sectors'), 'value' => isset($filter['sectors']) ? $filter['sectors'] : '', 'class' => 'select2 form-control', 'multiple' => true, 'style' => 'width: 100%']) ?>
                </div>
                <div class='age-range'>
                    <h6 class=""><?= __('Event Status') ?></h6>
                    <?= $this->Form->control('filter.status', ['label' => false, 'empty' => __('Select Status'), 'options' => ['past' => 'past', 'upcoming' => 'upcoming', 'active' => 'active'], 'value' => isset($filter['status']) ? $filter['status'] : '' ]) ?>
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

<?php $this->Html->css('https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css', ['block' => 'css']) ?>
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
    });

</script>

<?php $this->end(); ?>
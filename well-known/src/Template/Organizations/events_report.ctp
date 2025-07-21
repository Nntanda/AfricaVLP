<div class="container">
    <div class="d-flex justify-content-end quick-actions">
        <button type="button" name="button" class="filter" data-toggle="modal" data-target="#exampleModal4"><?= __('Filter') ?> <img src="<?= $this->Url->image('filter.svg') ?>" alt=""></button>
        <button type="button" name="button" class=""><?= __('Export') ?> <img src="<?= $this->Url->image('export.svg') ?>" alt=""></button>
    </div>
</div>
<div class="container">

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
    <div class="row align-items-center">
        <div class="col-md-7">
            <h4><?= __('Volunteering Events') ?></h4>
        </div>
        <div class="col-md-5">
            <?= $this->Form->create(false, ['type' => 'get']) ?>
                <input type="text" class="form-control search mb-4" value="<?= $search ?>" name="s" placeholder="<?= __('Search Events') ?>">
            <?= $this->Form->end() ?>
        </div>
    </div>

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
                            <div class="dropdown">
                                <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="<?= $this->Url->image('table-menu.svg') ?>" alt="">
                                </a>
    
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="<?= $this->Url->build(['controller' => 'Events', 'action' => 'view',$event->id]) ?>" target="_blank"><?= __('View') ?></a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
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
                <h2 class="modal-title" id="exampleModalLabel"><?= __('Edit Filters') ?> <a href="<?= $this->Url->build(['action'=>'eventsReport', $organization->id]) ?>"><?= __('Clear all filters') ?></a></h2>
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

<div class="container">
    <div class="d-flex justify-content-between top-line align-items-center">
        <h3><?= __('Events') ?></h3>
    </div>

    <div class="row">
        <div class="col-md-8">
            <?= $this->Form->create(false, ['type' => 'get']) ?>
                <label for=""><?= __('Search') ?></label>
                <input type="text" class="search-query form-control" value="<?= $search ?>" name="s" placeholder="<?= __('Search event') ?>"/>
            <?= $this->Form->end() ?>
        </div>
        <div class="col-md-4">
            <label for=""><?= __('Event status') ?></label>
            <?php 
                echo $this->Form->create(false, ['type' => 'Get']);
                echo $this->Form->control('status', ['empty' => __('Select Status'), 'label' => false, 'class' => 'form-control filter-select', 'templates' => [
                    'inputContainer' => '{{content}}'
                ], 'style' => 'max-width: 250px;', 'value' => $status, 'options' => ['past' => 'past', 'upcoming' => 'upcoming', 'ongoing' => 'ongoing'] ]);
                echo $this->Form->end();
            ?>
        </div>
    </div>

    <div class="recent-updates program-table mt-3">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                <th scope="col"><?= __('Event') ?></th>
                <th scope="col"><?= __('Date') ?></th>
                <th scope="col"><?= __('Interested') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Action') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($events as $event): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="<?= ($event->image && !empty($event->image)) ? $event->image : $this->Url->image('programes.jpg') ?>" alt="">
                            <div class="vol-int-name">
                                <h5><?= h($event->title) ?></h5>
                                <p><?= (($event->has('city') ? $event->city->name. ', ' : ''). $event->country->nicename) ?></p>
                            </div>
                        </div>
                    </td>
                    <td><?= $event->start_date->format('d/m/y') .' - '. $event->end_date->format('d/m/y') ?></td>
                    <td>
                        <div class="vol-program">
                            <h4><?= h($event->interests) ?></h4>
                        </div>
                    </td>
                    <td><?php
                        $now = \Cake\I18n\Time::now();
                        if ($now > $event->start_date && $now > $event->end_date) {
                            echo __('Past');
                        } elseif ($now > $event->start_date && $now < $event->end_date) {
                            echo __('Ongoing');
                        } else {
                            echo __('Upcoming');
                        }
                    ?></td>
                    <td>
                        <div class="dropdown">
                        <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?= $this->Url->image('table-menu.svg') ?>" alt="">
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="<?= $this->Url->build(['_name' => 'organization:actions', 'action' => 'event', 'id' => $organization->id, $event->id]) ?>"><?= __('View') ?></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= $this->Url->build(['_name' => 'organization:actions', 'action' => 'editEvent', 'id' => $organization->id, $event->id]) ?>"><?= __('Edit') ?></a>
                            <div class="dropdown-divider"></div>
                            <!-- <a class="dropdown-item" href="#">Suspend</a> -->
                        </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<?php $this->Html->script("https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js", ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
        $("#volunteering-categories-ids").select2()

        
        $('.filter-select').change(function () {
          $(this).closest('form').submit()
        })
    });

</script>

<?php $this->end(); ?>
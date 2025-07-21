<div class="main">
    <div class="container">
    <div class="innder-head">
        <h1><?= __('Opportunities') ?></h1>
        <div class="row justify-content-center">
            <div class="col-6">
                <div id="custom-search-input">
                    <?= $this->Form->create(false, ['type' => 'get']) ?>
                    <div class="input-group">
                        <input type="text" class="search-query form-control" value="<?= $search ?>" name="s" placeholder="<?= __('Search opportunities') ?>"/>
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
                <!-- <?php 
                    echo $this->Form->create(false, ['type' => 'Get']);
                    echo $this->Form->control('region_id', ['empty' => __('Select Region'), 'label' => false, 'class' => 'form-control filter-select', 'templates' => [
                      'inputContainer' => '{{content}}'
                    ], 'style' => 'max-width: 250px;', 'value' => $region_id ]);
                    echo $this->Form->end();
                ?> -->
                 <?php 
                    echo $this->Form->create(false, ['type' => 'Get']);
                    echo $this->Form->control('country_id', ['empty' => __('Select Country'), 'label' => false, 'class' => 'form-control filter-select', 'templates' => [
                      'inputContainer' => '{{content}}'
                    ], 'style' => 'max-width: 250px;', 'value' => $region_id ]);
                    echo $this->Form->end();
                ?>

                <!-- <ul class="">
                    <li class="header">
                        <?= __('SECTORS') ?>
                    </li>
                    <?php foreach ($volunteering_categories as $k => $v): ?>
                        <li class="">
                            <? $this->Html->link($v, ['?' => ['cat' => $k]]) ?>
                        </li>
                    <?php endforeach; ?>
                </ul> -->
            </nav>
        </div>
        <div class="col-md-9 items-list">
            <?php if ($events->count() < 1): ?>
                <div class="alert alert-info" role="alert">
                    <?= __('No record found') ?>
                </div>
            <?php endif; ?>
            <?php foreach ($events as $event): ?>
                <div class="wrap mb-4">
                        <div class="card">
                            <div class="row no-gutters d-flex align-items-stretch">
                                <div class="col-lg-3 card-img" style="background-image: url(<?= $event->image ?>);"></div>
                                <div class="col-lg-9">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-9 d-flex flex-column">
                                                <h4 class="card-title"><?= h($event->title) ?></h4>
                                                <p class="card-text"><?= $this->Text->truncate($event->description, 150, ['ellipsis' => '...']) ?></p>
                                                <div class="row list-tag align-items-end">
                                                    <div class="col-md-4">
                                                        <p class="text-muted flex-fill"><?= __('Date') ?>:
                                                        <span><?= $event->created->format('M d, Y') ?></span></p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="text-muted flex-fill"><?= __('Organizer') ?>:
                                                        <a href="volunteering-organizations/view/<?= $event->organization->id ?>" ><span><?= $event->has('organization') ? $event->organization->name .($event->organization->is_verified ? ' <span class="badge badge-success rounded-circle text-light"><i class="fa fa-check"></i><span>' : '') : '' ?></span></a></p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <span><a style="color: #2C5535; text-decoration: none;" href="<?= $event->url ?>" target="_blank" rel="noopener noreferrer">Apply Now</a></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 d-flex align-items-center justify-content-end">
                                            <?php if (isset($authUser)){ if(isset($authUser['allow_events']) && $authUser['allow_events']){ ?>
                                                <a href="<?= $this->Url->build(['controller' => 'Events', 'action' => 'view', $event->id]) ?>" class="btn btn-small"><?= __('View') ?></a>
                                            <?php }} else { ?>
                                                <div class="alert alert-info" role="alert">
                                                    <?= __('Please login or signup to view.') ?>
                                                </div>
                                            <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <div class="location">
                                    <p><img src="https://www.countryflags.io/<?= $event->country->iso ?>/flat/64.png" alt=""><?= h($event->has('city') ? $event->city->name. ', ' : '')?> <a href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'countryPage', $event->country->iso]) ?>"><?= $event->country->nicename ?></a></p>
                                </div>
                                <div class="sector d-flex align-items-center">
                                    <!--  -->
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
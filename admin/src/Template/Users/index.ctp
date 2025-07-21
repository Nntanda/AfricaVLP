<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\organization[]|\Cake\Collection\CollectionInterface $organizations
 */
?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
  <div class="kt-container  kt-container--fluid ">
    <div class="kt-subheader__main">
      <h3 class="kt-subheader__title"><?= __('Volunteers'); ?></h3>
      <span class="kt-subheader__separator kt-subheader__separator--v"></span>
      <div class="kt-subheader__wrapper pr-4">
        <?php 
          echo $this->Form->create(false, ['type' => 'Get']);
          echo $this->Form->control('status', ['empty' => 'Status', 'label' => false, 'class' => 'form-control filter-select', 'templates' => [
            'inputContainer' => '{{content}}'
          ], 'style' => 'max-width: 250px;', 'value' => $status ]);
          echo $this->Form->end();
        ?>
      </div>
      <div class="kt-subheader__wrapper pr-4">
        <?php 
          echo $this->Form->create(false, ['type' => 'Get']);
          echo $this->Form->control('region_id', ['empty' => 'Region', 'label' => false, 'class' => 'form-control filter-select', 'templates' => [
            'inputContainer' => '{{content}}'
          ], 'style' => 'max-width: 250px;', 'value' => $status ]);
          echo $this->Form->end();
        ?>
      </div>
      <span class="kt-subheader__separator kt-subheader__separator--v"></span>
      <div class="kt-subheader__toolbar" id="kt_subheader_search">
        <span class="kt-subheader__desc" id="kt_subheader_total">
          <?= $total ?> Total
        </span>

        <?= $this->Form->create(false, ['type' => 'get', 'class' => 'kt-subheader__search']) ?>
          <div class="input-group">
            <input type="text" name="s" class="form-control" value="<?= $search ?>" placeholder="Search..." id="generalSearch">
            <div class="input-group-append">
              <span class="input-group-text" id="basic-addon2">
                <i class="flaticon2-search-1"></i>
              </span>
            </div>
          </div>
        <?= $this->Form->end() ?>
        </div>&nbsp;&nbsp;
      <?php
echo $this->Html->link('Export',array('controller'=>'users','action'=>'download'), array('target'=>'_blank'));
?>
    </div>
    <div class="kt-subheader__toolbar">
      <div class="kt-subheader__wrapper">
        <!-- <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-icon- btn btn-label btn-label-brand btn-upper btn-font-sm btn-bold">
          <i class="flaticon2-plus"></i>
          <?= __('Add New') ?>
        </a> -->
      </div>
    </div>
  </div>
</div>
<!-- end:: Subheader -->

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
  <!--begin::Dashboard 4-->
    <div class="row">
    <?php foreach ($users as $user): ?>
      <div class="col-lg-6 col-xl-4">
        <!--begin::Portlet-->
        <div class="kt-portlet  kt-portlet--height-fluid">
          <div class="kt-widget kt-widget--general-2">
            <div class="kt-portlet__body kt-portlet__body--fit">
              <div class="kt-widget__top">
                <div class="kt-media kt-media--lg kt-media--circle">
                  <?php if ($user->profile_image) { ?><img src="<?= h($user->profile_image) ?>" alt="image"> <?php } ?>
                </div>
                <div class="kt-widget__wrapper">
                  <div class="kt-widget__label">
                    <span class="kt-widget__title">
                      <?= h($user->first_name .' '. $user->last_name) ?>
                    </span>
                    <span class="kt-widget__desc">
                      <?= h($user->gender) ?> &nbsp;| &nbsp;<?= h($user->date_of_birth ? $user->date_of_birth->diffInYears() .'Years' : '') ?> &nbsp;| &nbsp;<?= h($user->has('country') ? $user->country->nicename : '') ?>
                      <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                    </span>
                  </div>
                  <div class="kt-widget__toolbar">
                    <div class="kt-widget__actions">
                      <a href="<?= $this->Url->build(['action' => 'view', $user->id]) ?>" class="btn btn-default btn-sm btn-bold btn-upper">profile</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--end::Portlet-->
      </div>
    <?php endforeach; ?>
    </div>
  <!--end::Dashboard 4-->
</div>

<div class="row justify-content-center">
    <?= $this->element('Navigation/pagination') ?>
</div>

<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
        $('.filter-select').change(function () {
          $(this).closest('form').submit()
        })
    });
</script>
<?php $this->end() ?>
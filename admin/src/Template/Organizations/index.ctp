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
      <h3 class="kt-subheader__title"><?= __('Organizations'); ?></h3>
      <span class="kt-subheader__separator kt-subheader__separator--v"></span>
      <div class="kt-subheader__wrapper pr-4">
        <?php
        echo $this->Form->create(false, ['type' => 'Get']);
        echo $this->Form->control('status', ['empty' => __('Status'), 'label' => false, 'class' => 'form-control filter-select', 'templates' => [
          'inputContainer' => '{{content}}'
        ], 'style' => 'max-width: 250px;', 'value' => $status]);
        echo $this->Form->end();
        ?>
      </div>
      <div class="kt-subheader__wrapper pr-4">
        <?php
        echo $this->Form->create(false, ['type' => 'Get']);
        echo $this->Form->control('verification', ['empty' => __('Verification Status'), 'label' => false, 'class' => 'form-control filter-select', 'templates' => [
          'inputContainer' => '{{content}}'
        ], 'style' => 'max-width: 250px;', 'value' => $verification]);
        echo $this->Form->end();
        ?>
      </div>
      <div class="kt-subheader__wrapper pr-4">
        <?php
        echo $this->Form->create(false, ['type' => 'Get']);
        echo $this->Form->control('region_id', ['empty' => __('Region'), 'label' => false, 'class' => 'form-control filter-select', 'templates' => [
          'inputContainer' => '{{content}}'
        ], 'style' => 'max-width: 250px;', 'value' => $region_id]);
        echo $this->Form->end();
        ?>
      </div>
      <div class="kt-subheader__wrapper pr-4">
        <?php
        echo $this->Form->create(false, ['type' => 'Get']);
        echo $this->Form->control('country_id', ['empty' => __('Country'), 'label' => false, 'class' => 'form-control filter-select kt_select2_country', 'templates' => [
          'inputContainer' => '{{content}}'
        ], 'style' => 'max-width: 180px;', 'value' => $country_id]);
        echo $this->Form->end();
        ?>
      </div>
      <span class="kt-subheader__separator kt-subheader__separator--v"></span>
      <div class="kt-subheader__toolbar" id="kt_subheader_search">
        <span class="kt-subheader__desc" id="kt_subheader_total">
          <?= $total ?> <?= __('Total') ?>
        </span>

        <?= $this->Form->create(false, ['type' => 'get', 'class' => 'kt-subheader__search']) ?>
        <div class="input-group">
          <input type="text" name="s" class="form-control" value="<?= $search ?>" placeholder="<?= __('Search...') ?>" id="generalSearch">
          <div class="input-group-append">
            <span class="input-group-text" id="basic-addon2">
              <i class="flaticon2-search-1"></i>
            </span>
          </div>
        </div>
        <?= $this->Form->end() ?>
      </div>&nbsp;&nbsp;
      <?php
      echo $this->Html->link('Export', array('controller' => 'organizations', 'action' => 'download'), array('target' => '_blank'));
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
    <?php foreach ($organizations as $organization) : ?>
      <div class="col-lg-6 col-xl-4">
        <!--begin::Portlet-->
        <div class="kt-portlet  kt-portlet--height-fluid">
          <div class="kt-portlet__body">
            <div class="kt-widget kt-widget--general-4">
              <div class="kt-widget__head">
                <div class="kt-media kt-media--lg">
                  <?php if ($organization->logo) { ?><img src="<?= h($organization->logo) ?>" alt="image"> <?php } ?>
                </div>
                <div class="kt-widget__toolbar">
                  <!--  -->
                </div>
              </div>

              <a href="#" class="kt-widget__title">
                <?= h($organization->name) ?>
                <small>
                  - <?php if ($organization->is_verified) {
                      echo '<span class="badge badge-pill badge-success"><i class="flaticon2-check-mark"></i></span>';
                    } ?>
                </small>
              </a>

              <div class="kt-widget__desc">
                <?= h($organization->about) ?>
              </div>

              <div class="kt-widget__links">
                <div class="kt-widget__link">
                  <i class="flaticon2-send  kt-font-success"></i>
                  <a href="#"><?= h($organization->email) ?></a>
                </div>
                <div class="kt-widget__link">
                  <i class="flaticon2-world kt-font-skype"></i>
                  <a href="#"><?= h($organization->user->first_name . ' ' . $organization->user->last_name) ?></a>
                </div>
              </div>

              <div class="kt-widget__actions">
                <div class="kt-widget__left">
                  <a href="<?= $this->Url->build(['controller' => 'Support', 'action' => 'message', $organization->id]) ?>" class="btn btn-default btn-sm btn-bold btn-upper"><?= __('Message') ?></a>
                  <a href="<?= $this->Url->build(['action' => 'view', $organization->id]) ?>" class="btn btn-brand btn-sm btn-bold btn-upper"><?= __('Profile') ?></a>
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

<?= $this->Html->script('select2.js', ['block' => 'script']) ?>
<?php $this->start('scriptBlock') ?>
<script>
  $(document).ready(function() {
    $('.filter-select').change(function() {
      $(this).closest('form').submit()
    })
  });
</script>
<?php $this->end() ?>
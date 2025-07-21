<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Resource[]|\Cake\Collection\CollectionInterface $newsletterContents
 */

 function getObjectTitle($newsletterContent)
 {
   $title = '';

   if ($newsletterContent->object_model === "News") {
     $title = $newsletterContent->has('news') ? $newsletterContent->news->title : '';
   }

   return $title;
 }
?>

<?= $this->Html->css('list.css') ?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
  <div class="kt-container  kt-container--fluid ">
    <div class="kt-subheader__main">
      <h3 class="kt-subheader__title"><?= __('Newsletter Contents') ?></h3>
      <span class="kt-subheader__separator kt-subheader__separator--v"></span>
      <div class="kt-subheader__wrapper pr-4">
        <?= $this->Form->create(false, ['type' => 'Get']); ?>
          <div class="input-group">
            <?php echo $this->Form->control('range', ['empty' => 'Status', 'label' => false, 'id' => 'kt_dashboard_daterangepicker', 'class' => 'form-control filter-select', 'templates' => [
              'inputContainer' => '{{content}}'
            ], 'style' => 'max-width: 280px; width: 94%', 'value' => $range, 'readonly' => true, 'placeholder' => 'Select date range' ]);
            ?>
            <div class="input-group-append" style="width: 5%">
              <span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
            </div>
          </div>
        <?= $this->Form->end(); ?>
      </div>
      <span class="kt-subheader__separator kt-subheader__separator--v"></span>
      <div class="kt-subheader__toolbar" id="kt_subheader_search">
        <span class="kt-subheader__desc" id="kt_subheader_total">
          <?= $total ?> <?= __('Total') ?>
        </span>
      </div>
    </div>
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
          <table class="table">
            <thead class="thead-light">
              <tr>
                <th>#</th>
                <th><?= __('Content Type') ?></th>
                <th><?= $this->Paginator->sort('title') ?></th>
                <th><?= __('Created') ?></th>
              </tr>
            </thead>
            <tbody>
            <?php $sn = 1; foreach ($newsletterContents as $newsletterContent): ?>
              <tr>
                <th scope="row"><?= $sn ?></th>
                <td><?= h($newsletterContent->object_model) ?></td>
                <td><?= h(getObjectTitle($newsletterContent)) ?></td>
                <td><?= h($newsletterContent->created) ?></td>
              </tr>
            <?php $sn++; endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <!--end::Section-->
    </div>
  </div>
  <!--end::Portlet-->
<!--end::Dashboard 4-->

<div class="row justify-content-center">
    <?= $this->element('Navigation/pagination') ?>
</div>

<?= $this->Html->script('dashboard.js', ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
        $('.filter-select').change(function () {
          $(this).closest('form').submit()
        })
    });
</script>

<?php $this->end(); ?>
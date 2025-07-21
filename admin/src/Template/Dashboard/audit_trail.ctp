<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Admin[]|\Cake\Collection\CollectionInterface $admins
 */
use Cake\Utility\Inflector;

function generateMessage($log, $view) {
    $object = Inflector::humanize(Inflector::singularize($log->object_model));
    if ($object === 'Widget') $object = __('a page section');
    // $object_link = $view->Html->link($object, ['controller' => $log->object_model, 'action' => 'view', $log->object_id]);
    $object_link = $view->Html->link($object, '#');
    $action = '';
    switch ($log->action) {
        case App\Model\Entity\ActivityLog::ACTION_CREATE:
            $action = __('added'). ' '. $object_link;
            break;
        
        case App\Model\Entity\ActivityLog::ACTION_UPDATE:
            $action = __('updated'). ' '. $object_link .__(' info');
            break;
        
        default:
            # code...
            break;
    }
    return $action;
}
?>

            <!-- begin:: Subheader -->
            <div class="kt-subheader   kt-grid__item" id="kt_subheader">
              <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                  <h3 class="kt-subheader__title"><?= __('Audit Trail') ?></h3>
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
                  <!-- <div class="kt-subheader__wrapper pr-4">
                    <?php 
                    echo $this->Form->create(false, ['type' => 'Get']);
                    echo $this->Form->control('action', ['empty' => 'Action', 'label' => false, 'class' => 'form-control filter-select', 'templates' => [
                      'inputContainer' => '{{content}}'
                    ], 'style' => 'max-width: 250px;', 'value' => $action, 'options' => [
                      App\Model\Entity\ActivityLog::ACTION_CREATE => __('Added'),
                      App\Model\Entity\ActivityLog::ACTION_UPDATE => __('Updated'),
                    ] ]);
                    echo $this->Form->end();
                    ?>
                  </div> -->
                  <!-- <span class="kt-subheader__separator kt-subheader__separator--v"></span>
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
                  </div> -->
                </div>
                
              </div>
            </div>
            <!-- end:: Subheader -->
            
            <!--begin::Dashboard 4-->
              <!--begin::Portlet-->
              <div class="kt-portlet">
                <div class="kt-portlet__body">

                  <div class="kt-list">
                        <?php foreach ($logs as $log): ?>
                            <div class="kt-list__item">
                              <span class="kt-list__text">
                                  <span class="kt-font-bold"> <?= h($log->issuer) ?></span>
                                  <?= generateMessage($log, $this) ?>
                              </span>
                              <span class="kt-list__time" style="min-width: 120px"><?= h($log->created_at->timeAgoInWords([
                                  'accuracy' => ['minute' => 'minute', 'hour' => 'hour', 'week' => 'week'],
                                  'format' => 'd MMM, YYYY'
                              ])) ?></span>
                            </div>
                        <?php endforeach; ?>
                  </div>
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
<?php $this->end() ?>            

<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Admin[]|\Cake\Collection\CollectionInterface $admins
 */
use Cake\Utility\Inflector;
use Cake\Utility\Hash;

function generateLink($feedback, $view) {
    $object = Inflector::humanize(Inflector::singularize($feedback->object_model));
    $action = 'edit';
    if ($object === 'Organization') {$action = 'view';}
    return $view->Html->link(__('View reported content'), ['controller' => $feedback->object_model, 'action' => $action, $feedback->object_id]);
}

function getRating($rating) {
    $ratingData = Hash::extract(App\Model\Table\UserFeedbacksTable::$ratings, "{n}[value=$rating]");
    return $ratingData[0]['emoji']. '&nbsp; - '. $ratingData[0]['text'];
}
?>

            <!-- begin:: Subheader -->
            <div class="kt-subheader   kt-grid__item" id="kt_subheader">
              <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                  <h3 class="kt-subheader__title"><?= __('User Feedbacks') ?></h3>
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
                  <!--begin::Section-->
                  <div class="kt-section">
                    <div class="kt-section__content">
                      <table class="table">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th><?= __('User') ?></th>
                            <th><?= __('Rating') ?></th>
                            <th><?= __('Message') ?></th>
                            <th><?= __('Date') ?></th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php $sn = 1; foreach ($feedbacks as $feedback): ?>
                          <tr>
                            <th scope="row"><?= $sn ?></th>
                            <td><?= h($feedback->user->first_name. ' '. $feedback->user->last_name) ?></td>
                            <td><?= getRating($feedback->feedback_rating) ?></td>
                            <td><?= h($feedback->feedback_message) ?></td>
                            <td> <?= h($feedback->created->timeAgoInWords([
                                  'accuracy' => ['minute' => 'minute', 'hour' => 'hour', 'week' => 'week'],
                                  'format' => 'd MMM, YYYY'
                              ])) ?> </td>
                            <td><?= generateLink($feedback, $this); ?></td>
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
<?php $this->end() ?>            

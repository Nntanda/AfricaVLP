<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Resource[]|\Cake\Collection\CollectionInterface $resources
 */
?>

<?= $this->Html->css('list.css') ?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
              <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                  <h3 class="kt-subheader__title"><?= __('Resources') ?></h3>
                  <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                  <div class="kt-subheader__wrapper pr-4">
                    <?php 
                      echo $this->Form->create(false, ['type' => 'Get']);
                      echo $this->Form->control('status', ['empty' => __('Status'), 'label' => false, 'class' => 'form-control filter-select', 'templates' => [
                        'inputContainer' => '{{content}}'
                      ], 'style' => 'max-width: 250px;', 'value' => $status ]);
                      echo $this->Form->end();
                    ?>
                  </div>
                  <div class="kt-subheader__wrapper pr-4">
                    <?php 
                      echo $this->Form->create(false, ['type' => 'Get']);
                      echo $this->Form->control('resource_type', ['empty' => __('Resource Type'), 'label' => false, 'class' => 'form-control filter-select', 'templates' => [
                        'inputContainer' => '{{content}}'
                      ], 'style' => 'max-width: 250px;', 'value' => $resource_type ]);
                      echo $this->Form->end();
                    ?>
                  </div>
                  <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                  <div class="kt-subheader__wrapper pr-4">
                    <?php 
                      echo $this->Form->create(false, ['type' => 'Get']);
                      echo $this->Form->control('region_id', ['empty' => __('Region'), 'label' => false, 'class' => 'form-control filter-select', 'templates' => [
                        'inputContainer' => '{{content}}'
                      ], 'style' => 'max-width: 250px;', 'value' => $region_id ]);
                      echo $this->Form->end();
                    ?>
                  </div>
                  <div class="kt-subheader__wrapper pr-4">
                    <?php 
                      echo $this->Form->create(false, ['type' => 'Get']);
                      echo $this->Form->control('country_id', ['empty' => __('Country'), 'label' => false, 'class' => 'form-control filter-select kt_select2_country', 'templates' => [
                        'inputContainer' => '{{content}}'
                      ], 'style' => 'max-width: 180px;', 'value' => $country_id ]);
                      echo $this->Form->end();
                    ?>
                  </div>
                  <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                  <div class="kt-subheader__toolbar" id="kt_subheader_search">
                    <span class="kt-subheader__desc" id="kt_subheader_total">
                      <?= $resources->count() ?> <?= __('Total') ?>
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
                  </div>
                </div>
                <div class="kt-subheader__toolbar">
                  <div class="kt-subheader__wrapper">
                    <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-icon- btn btn-label btn-label-brand btn-upper btn-font-sm btn-bold">
                      <i class="flaticon2-plus"></i>
                      <?= __('Add New') ?>
                    </a>
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
                            <th><?= $this->Paginator->sort('title') ?></th>
                            <th><?= __('Resource Type') ?></th>
                            <th><?= __('File Type') ?></th>
                            <th><?= __('Created By') ?></th>
                            <th><?= __('Region / Country') ?></th>
                            <th><?= __('Status') ?></th>
                            <th><?= __('Actions') ?></th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php $sn = 1; foreach ($resources as $resource): ?>
                          <tr>
                            <th scope="row"><?= $sn ?></th>
                            <td><?= h($resource->title) ?></td>
                            <td><?= h($resource->has('resource_type') ? $resource->resource_type->name : '') ?></td>
                            <td><?= h($resource->file_type) ?></td>
                            <td><?= $resource->has('organization') ? $resource->organization->name : 'AU' ?></td>
                            <td><?= h($resource->has('region') ? $resource->region->name : __('All')) ?> / <?= h($resource->has('country') ? $resource->country->nicename : __('All')) ?></td>
                            <td><?= h($this->getStatusLabel($resource->status)) ?></td>
                            <td>
                              <div class="kt-widget-7__item-toolbar">
                                <div class="dropdown dropdown-inline">
                                  <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="flaticon-more-1"></i>
                                  </button>
                                  <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="kt-nav">
                                      <li class="kt-nav__item">
                                        <a href="<?= $resource->file_link ?>" class="kt-nav__link" target="_blank">
                                          <i class="kt-nav__link-icon la la-eye"></i>
                                          <span class="kt-nav__link-text"><?= __('View file') ?></span>
                                        </a>
                                      </li>
                                      <li class="kt-nav__item">
                                        <a href="<?= $this->Url->build(['action' => 'edit', $resource->id]) ?>" class="kt-nav__link">
                                          <i class="kt-nav__link-icon la la-edit"></i>
                                          <span class="kt-nav__link-text"><?= __('Edit') ?></span>
                                        </a>
                                      </li>
                                      <li class="kt-nav__item">
                                        <?php
                                          if($resource->status == STATUS_ACTIVE) {
                                            echo $this->Form->postLink(
                                              '<i class="kt-nav__link-icon la la-lock"></i><span class="kt-nav__link-text">'. __('Deactivate') .'</span>', 
                                              ['action' => 'edit', $resource->id], 
                                              ['data' => ['status' => STATUS_INACTIVE], 'class' => 'kt-nav__link', 'escape' => false]
                                            );
                                          } else {
                                            echo $this->Form->postLink(
                                              '<i class="kt-nav__link-icon la la-unlock-alt"></i><span class="kt-nav__link-text">'. __('Activate') .'</span>', 
                                              ['action' => 'edit', $resource->id], 
                                              ['data' => ['status' => STATUS_ACTIVE], 'class' => 'kt-nav__link', 'escape' => false]
                                            );
                                          }
                                        ?>
                                      </li>
                                      <li class="kt-nav__item">
                                        <?php
                                          echo $this->Form->postLink(
                                            '<i class="kt-nav__link-icon la la-trash"></i><span class="kt-nav__link-text">'. __('Delete') .'</span>', 
                                            ['action' => 'delete', $resource->id], 
                                            ['class' => 'kt-nav__link', 'confirm' => __('Are you sure you want to delete?'), 'escape' => false]
                                          );
                                        ?>
                                      </li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                            </td>
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

<?= $this->Html->script('select2.js', ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
        $('.filter-select').change(function () {
          $(this).closest('form').submit()
        })
    });
</script>

<?php $this->end(); ?>
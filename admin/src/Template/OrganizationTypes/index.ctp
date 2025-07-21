<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OrganizationType[]|\Cake\Collection\CollectionInterface $organizationTypes
 */
?>

            <!-- begin:: Subheader -->
            <div class="kt-subheader   kt-grid__item" id="kt_subheader">
              <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                  <h3 class="kt-subheader__title"><?= __('Organization Types') ?></h3>
                  <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                  <div class="kt-subheader__wrapper pr-4">
                    <select class="form-control kt-select2" name="param">
                      <option><?= __('Status') ?></option>
                      <option value="1"><?= __('Active') ?></option>
                      <option value="2"><?= __('Inactive') ?></option>
                    </select>
                  </div>
                  <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                  <div class="kt-subheader__toolbar" id="kt_subheader_search">
                    <span class="kt-subheader__desc" id="kt_subheader_total">
                      <?= $organizationTypes->count() ?> <?= __('Total') ?>
                    </span>

                    <form class="kt-subheader__search" id="kt_subheader_search_form">
                      <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search..." id="generalSearch">
                        <div class="input-group-append">
                          <span class="input-group-text" id="basic-addon2">
                            <i class="flaticon2-search-1"></i>
                          </span>
                        </div>
                      </div>
                    </form>
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
                            <th><?= $this->Paginator->sort('name') ?></th>
                            <th><?= __('Status') ?></th>
                            <th><?= $this->Paginator->sort('created') ?></th>
                            <th><?= $this->Paginator->sort('modified') ?></th>
                            <th><?= __('Actions') ?></th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php $sn = 1; foreach ($organizationTypes as $organizationType): ?>
                          <tr>
                            <th scope="row"><?= $sn ?></th>
                            <td><?= h($organizationType->name) ?></td>
                            <td><?= h($this->getStatusLabel($organizationType->status)) ?></td>
                            <td><?= h($organizationType->created) ?></td>
                            <td><?= h($organizationType->modified) ?></td>
                            <td>
                              <div class="kt-widget-7__item-toolbar">
                                <div class="dropdown dropdown-inline">
                                  <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="flaticon-more-1"></i>
                                  </button>
                                  <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="kt-nav">
                                      <li class="kt-nav__item">
                                        <a href="<?= $this->Url->build(['action' => 'edit', $organizationType->id]) ?>" class="kt-nav__link">
                                          <i class="kt-nav__link-icon la la-edit"></i>
                                          <span class="kt-nav__link-text"><?= __('Edit') ?></span>
                                        </a>
                                      </li>
                                      <li class="kt-nav__item">
                                        <?php
                                          if($organizationType->status == STATUS_ACTIVE) {
                                            echo $this->Form->postLink(
                                              '<i class="kt-nav__link-icon la la-lock"></i><span class="kt-nav__link-text">'. __('Deactivate') .'</span>', 
                                              ['action' => 'edit', $organizationType->id], 
                                              ['data' => ['status' => STATUS_INACTIVE], 'class' => 'kt-nav__link', 'escape' => false]
                                            );
                                          } else {
                                            echo $this->Form->postLink(
                                              '<i class="kt-nav__link-icon la la-unlock-alt"></i><span class="kt-nav__link-text">'. __('Activate') .'</span>', 
                                              ['action' => 'edit', $organizationType->id], 
                                              ['data' => ['status' => STATUS_ACTIVE], 'class' => 'kt-nav__link', 'escape' => false]
                                            );
                                          }
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

<!-- <div class="organizationTypes index large-9 medium-8 columns content">
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div> -->

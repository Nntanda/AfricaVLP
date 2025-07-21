<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\News[]|\Cake\Collection\CollectionInterface $news
 */
?>

<?= $this->Html->css('list.css') ?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
              <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                  <h3 class="kt-subheader__title"><?= __('Blog Posts') ?></h3>
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
                  <!-- <span class="kt-subheader__separator kt-subheader__separator--v"></span> -->
                  <!-- <div class="kt-subheader__wrapper pr-4">
                    <select class="form-control kt-select2" name="param">
                      <option value="AK">Sector</option>
                      <option value="HI">Hawaii</option>
                      <option value="CA">California</option>
                    </select>
                  </div> -->
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
                  </div>
                </div>
                <div class="kt-subheader__toolbar">
                  <div class="kt-subheader__wrapper">
                    <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-icon- btn btn-label btn-label-brand btn-upper btn-font-sm btn-bold">
                      <i class="flaticon2-plus"></i>
                      <?= __('New Blog Post') ?>
                    </a>
                  </div>
                </div>
              </div>
            </div>
            <!-- end:: Subheader -->
            
            <!--begin::Dashboard 4-->
            <?php $sn = 1; foreach ($blogPosts as $blogPost): ?>
              <!--begin::Portlet-->
              <div class="kt-portlet">
                <div class="kt-portlet__body">
                  <div class="kt-blog-list">
                    <div class="row">
                      <div class="col-xl-4">
                        <div class="kt-blog-list__head" style="background-image:url('<?= ($blogPost->image) ?>');">
                          <a href="#" class="kt-blog-list__link"><img src="<?= ($blogPost->image) ?>" class="kt-blog-list__image"/></a>
                        </div>
                      </div>
                      <div class="col-xl-8">
                        <div class="kt-blog-list__body">
                          <a href="" class="kt-blog-list__link">
                            <h2 class="kt-blog-list__title">
                              <?= h($blogPost->title) ?>
                            </h2>
                          </a>
                          <div class="kt-blog-list__meta">
                            <div class="kt-blog-list__date">
                              <?= $blogPost->created->format('M d, Y') ?>
                            </div>
                            <div class="kt-blog-list__author">
                              <?= $this->getStatusLabel($blogPost->status, 'news') ?>
                              <!-- <a href="" class="kt-blog-list__link">Admin</a> -->
                            </div>
                            <div class="kt-blog-list__comments">
                              <?= h($blogPost->has('region') ? $blogPost->region->name : '') ?>
                            </div>
                          </div>
                          <div class="kt-blog-list__content">
                            <?= $this->Text->autoParagraph($this->Text->truncate($blogPost->content, 200, ['ellipsis' => '...', 'exact' => false])) ?>
                          </div>
                          <!-- <a href="" class="kt-blog-list__link">
                            <span>Read More</span>
                          </a> -->
                        </div>
                        <div class="row align-items-center">
                          <div class="col-lg-6">
                            <!-- 15 Comments -->
                          </div>
                          <div class="col-lg-6 kt-align-right">
                            <a href="<?= $this->Url->build(['action' => 'edit', $blogPost->id]) ?>" class="btn btn-sm btn-secondary">
                                <i class="kt-nav__link-icon la la-edit"></i>
                                <span class="kt-nav__link-text"><?= __('Edit') ?></span>
                            </a>
                            <?= $this->Form->postLink(
                              '<span class="kt-nav__link-text">'. __('Delete'). '</span>',
                              ['action' => 'delete', $blogPost->id],
                              ['class' => 'btn btn-sm btn-danger', 'confirm' => __('Are you sure you want to delete?'), 'escape' => false]
                            ) ?>
                            <!-- <button type="button" class="btn btn-sm btn-danger">Delete</button> -->
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php $sn++; endforeach; ?>

              <!--end::Portlet-->

              <!-- <div class="row">
                <ul class="pagination pagination--grid">
                  <li class="page-item active">
                    <a class="page-link" href="#">1</a>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="#">2</a>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="#">3</a>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="#">4</a>
                  </li>
                </ul>
              </div> -->

            <!--end::Dashboard 4-->

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

<?php $this->end(); ?>
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
      <h3 class="kt-subheader__title"><?= __('Opportunities') ?></h3>
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
      <span class="kt-subheader__separator kt-subheader__separator--v"></span>
      <div class="kt-subheader__wrapper pr-4">
        <?php 
          echo $this->Form->create(false, ['type' => 'Get']);
          echo $this->Form->control('region_id', ['empty' => 'Region', 'label' => false, 'class' => 'form-control filter-select', 'templates' => [
            'inputContainer' => '{{content}}'
          ], 'style' => 'max-width: 250px;', 'value' => $region_id ]);
          echo $this->Form->end();
        ?>
      </div>
      <div class="kt-subheader__wrapper pr-4">
        <?php 
          echo $this->Form->create(false, ['type' => 'Get']);
          echo $this->Form->control('country_id', ['empty' => 'Country', 'label' => false, 'class' => 'form-control filter-select kt_select2_country', 'templates' => [
            'inputContainer' => '{{content}}'
          ], 'style' => 'max-width: 180px;', 'value' => $country_id ]);
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
        <!--  -->
      </div>
    </div>
  </div>
</div>
<!-- end:: Subheader -->
            
            <!--begin::Dashboard 4-->
            <?php $sn = 1; foreach ($events as $event): ?>
              <!--begin::Portlet-->
              <div class="kt-portlet">
                <div class="kt-portlet__body">
                  <div class="kt-blog-list">
                    <div class="row">
                      <div class="col-xl-4">
                        <div class="kt-blog-list__head" style="background-image:url('<?= ($event->image) ?>');">
                          <a href="#" class="kt-blog-list__link"><img src="<?= ($event->image) ?>" class="kt-blog-list__image"/></a>
                        </div>
                      </div>
                      <div class="col-xl-8">
                        <div class="kt-blog-list__body">
                          <a href="<?= $this->Url->build(['action' => 'view', $event->id]) ?>" class="kt-blog-list__link">
                            <h2 class="kt-blog-list__title">
                              <?= h($event->title) ?>
                            </h2>
                          </a>
                          <div class="kt-blog-list__meta">
                            <div class="kt-blog-list__date">
                              <?= $event->created->format('M d, Y') ?>
                            </div>
                            <div class="kt-blog-list__author">
                              By
                              <span href="" class="kt-blog-list__link"><?= h($event->organization->name) ?></span>
                            </div>
                            <div class="kt-blog-list__comments">
                              <?= h($event->city->name .', '. $event->country->nicename) ?>
                            </div>
                          </div>
                          <div class="kt-blog-list__content">
                            <?= $this->Text->autoParagraph($this->Text->truncate($event->description, 200, ['ellipsis' => '...', 'exact' => false])) ?>
                          </div>
                          <!-- <a href="blog-post.html" class="kt-blog-list__link">
                            <span>Read More</span>
                          </a> -->
                        </div>
                        <div class="row align-items-center">
                          <div class="col-lg-6">
                            <!-- 15 Comments -->
                          </div>
                          <div class="col-lg-6 kt-align-right">
                            <!-- <a href="<?= $this->Url->build(['action' => 'edit', $event->id]) ?>" class="btn btn-sm btn-secondary">
                                <i class="kt-nav__link-icon la la-edit"></i>
                                <span class="kt-nav__link-text">Edit</span>
                            </a> -->
                            <!-- <button type="button" class="">Edit</button> -->
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
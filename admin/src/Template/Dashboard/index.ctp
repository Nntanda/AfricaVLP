<?php
/**
 * @var \App\View\AppView $this
 */
?>

            <!-- begin:: Subheader -->
            <div class="kt-subheader   kt-grid__item" id="kt_subheader">
              <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                  <h3 class="kt-subheader__title"><?= __('Dashboard') ?></h3>
                  <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                  
                </div>
                
              </div>
            </div>
            <!-- end:: Subheader -->
            
            <!--begin::Dashboard 4-->
              <!--begin::Row-->
              <div class="row">
                <div class="col-lg-6 col-xl-3 order-lg-1 order-xl-1">
                  <!--begin::Portlet-->
                  <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head  kt-portlet__head--noborder">
                      <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"><?= __('Volunteers') ?></h3>
                      </div>
                    </div>
                    <div class="kt-portlet__body kt-portlet__body--fluid">
                      <div class="kt-widget-21">
                        <div class="kt-widget-21__title">
                          <div class="kt-widget-21__label"><?= $this->Number->format($volunteers) ?></div>
                        </div>
                        <div class="kt-widget-21__data">
                          <!--Doc: For the chart legend bullet colors can be changed with state helper classes: kt-bg-success, kt-bg-info, kt-bg-danger. Refer: components/custom/colors.html -->
                          <div class="kt-widget-21__legends">
                            <div class="kt-widget-21__legend">
                              <i class="kt-bg-brand"></i>
                              <span><?= __('Active') ?></span>
                            </div>
                            <div class="kt-widget-21__legend">
                              <i class="kt-shape-bg-color-4"></i>
                              <span><?= __('Inactive') ?></span>
                            </div>
                          </div>
                          <div class="kt-widget-21__chart">
                            <div class="kt-widget-21__stat"></div>
                            <!--Doc: For the chart initialization refer to "widgetTechnologiesChart" function in "src\theme\app\scripts\custom\dashboard.js" -->
                            <canvas id="kt_widget_volunteers_chart" style="height: 110px; width: 110px;" data-active="<?= $this->Number->format($volunteersActive) ?>" data-inactive="<?= $this->Number->format($volunteersInactive) ?>"></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--end::Portlet-->
                </div>
                <div class="col-lg-6 col-xl-3 order-lg-1 order-xl-1">
                  <!--begin::Portlet-->
                  <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head  kt-portlet__head--noborder">
                      <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"><?= __('Organizations') ?></h3>
                      </div>
                    </div>
                    <div class="kt-portlet__body kt-portlet__body--fluid">
                      <div class="kt-widget-21">
                        <div class="kt-widget-21__title">
                          <div class="kt-widget-21__label"><?= $this->Number->format($organizations) ?></div>
                        </div>
                        <div class="kt-widget-21__data">
                          <!--Doc: For the chart legend bullet colors can be changed with state helper classes: kt-bg-success, kt-bg-info, kt-bg-danger. Refer: components/custom/colors.html -->
                          <div class="kt-widget-21__legends">
                            <div class="kt-widget-21__legend">
                              <i class="kt-bg-brand"></i>
                              <span><?= __('Active') ?></span>
                            </div>
                            <div class="kt-widget-21__legend">
                              <i class="kt-shape-bg-color-4"></i>
                              <span><?= __('Inactive') ?></span>
                            </div>
                          </div>
                          <div class="kt-widget-21__chart">
                            <div class="kt-widget-21__stat"></div>
                            <!--Doc: For the chart initialization refer to "widgetTechnologiesChart" function in "src\theme\app\scripts\custom\dashboard.js" -->
                            <canvas id="kt_widget_organizations_chart" style="height: 110px; width: 110px;"  data-active="<?= $this->Number->format($organizationsActive) ?>" data-inactive="<?= $this->Number->format($organizationsInactive) ?>"></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--end::Portlet-->
                </div>
                <div class="col-lg-6 col-xl-3 order-lg-1 order-xl-1">
                  <!--begin::Portlet-->
                  <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head  kt-portlet__head--noborder">
                      <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"><?= __('Institutions') ?></h3>
                      </div>
                    </div>
                    <div class="kt-portlet__body kt-portlet__body--fluid">
                      <div class="kt-widget-21">
                        <div class="kt-widget-21__title">
                          <div class="kt-widget-21__label"><?= $this->Number->format($institutions) ?></div>
                        </div>
                        <div class="kt-widget-21__data">
                          <!--Doc: For the chart legend bullet colors can be changed with state helper classes: kt-bg-success, kt-bg-info, kt-bg-danger. Refer: components/custom/colors.html -->
                          <div class="kt-widget-21__legends">
                            <div class="kt-widget-21__legend">
                              <i class="kt-bg-brand"></i>
                              <span><?= __('Active') ?></span>
                            </div>
                            <div class="kt-widget-21__legend">
                              <i class="kt-shape-bg-color-4"></i>
                              <span><?= __('Inactive') ?></span>
                            </div>
                          </div>
                          <div class="kt-widget-21__chart">
                            <div class="kt-widget-21__stat"></div>
                            <!--Doc: For the chart initialization refer to "widgetTechnologiesChart" function in "src\theme\app\scripts\custom\dashboard.js" -->
                            <canvas id="kt_widget_institutions_chart" style="height: 110px; width: 110px;"  data-active="<?= $this->Number->format($institutionsActive) ?>" data-inactive="<?= $this->Number->format($institutionsInactive) ?>"></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--end::Portlet-->
                </div>
                <div class="col-lg-6 col-xl-3 order-lg-1 order-xl-1">
                  <!--begin::Portlet-->
                  <div class="kt-portlet kt-widget kt-widget--general-3 kt-portlet--height-fluid">
                    <div class="kt-portlet__head  kt-portlet__head--noborder">
                      <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"><?= __('Opportunities') ?></h3>
                      </div>
                    </div>
                    <div class="kt-portlet__body kt-portlet__body--fit">
                      <div class="kt-widget__top">
                        <div class="kt-widget__wrapper kt-widget-21">
                          <div class="kt-widget-21__title">
                            <div class="kt-widget-21__label"><?= $this->Number->format($pastEvents + $upcomingEvents) ?></div>
                          </div>
                          <div class="kt-widget__stats">
                            <div class="kt-widget__stat" href="#">
                              <span class="kt-widget__value"><?= $this->Number->format($upcomingEvents) ?></span>
                              <span class="kt-widget__caption"><?= __('Upcoming') ?></span>
                            </div>
                            <div class="kt-widget__stat" href="#">
                              <span class="kt-widget__value"><?= $this->Number->format($pastEvents) ?></span>
                              <span class="kt-widget__caption"><?= __('Past') ?></span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--end::Portlet-->
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6 col-xl-4 order-lg-1 order-xl-1">
                  <!--begin::Portlet-->
                  <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"><?= __('Latest Resources') ?></h3>
                      </div>
                      <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-actions">
                          <a href="<?= $this->Url->build(['controller' => 'Resources', 'action' => 'index']) ?>" class="btn btn-default btn-upper btn-sm btn-bold"><?= __('All FILES') ?></a>
                        </div>
                      </div>
                    </div>
                    <div class="kt-portlet__body kt-portlet__body--fit kt-portlet__body--fluid">
                    <div class="kt-widget-7">
                        <div class="kt-widget-7__items">
                        <?php foreach ($resources as $resource): ?> 
                          <div class="kt-widget-7__item">
                            <div class="kt-widget-7__item-info">
                              <a href="<?= $resource->file_link ?>" target="_blank" class="kt-widget-7__item-title">
                                <?= h($resource->title) ?>
                              </a>
                              <div class="kt-widget-7__item-desc">
                                <?= $this->Text->truncate($resource->description, 50, ['ellipsis' => '...', 'exact' => true]) ?>
                              </div>
                            </div>
                          </div>
                        <?php endforeach; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--end::Portlet-->
                </div>
                <div class="col-lg-6 col-xl-4 order-lg-1 order-xl-1">
                  <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"><?= __('Recent Blogs') ?></h3>
                      </div>
                      <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-actions">
                          <a href="<?= $this->Url->build(['controller' => 'BlogPosts', 'action' => 'index']) ?>" class="btn btn-default btn-upper btn-sm btn-bold"><?= __('All Blogs') ?></a>
                        </div>
                      </div>
                    </div>
                    <div class="kt-portlet__body">
                      <div class="kt-widget-17">
                        <?php foreach ($blogPosts as $blogPost): ?>
                        <div class="kt-widget-17__item">
                          <div class="kt-widget-17__product">
                            <div class="kt-widget-17__thumb">
                              <a href="#"><img src="<?= $blogPost->image ?>" class="kt-widget-17__image" alt="" title=""/></a>
                            </div>
                            <div class="kt-widget-17__product-desc">
                              <a href="<?= $this->Url->build(['controller' => 'BlogPosts', 'action' => 'edit', $blogPost->id]) ?>">
                                <div class="kt-widget-17__title">
                                  <?= h($blogPost->title) ?>
                                </div>
                              </a>
                              <div class="kt-widget-17__sku">
                                <!--  -->
                              </div>
                            </div>
                          </div>
                          <div class="kt-widget-17__prices">
                            <div class="kt-widget-17__total">
                              <small><?= h($blogPost->created->format('M d, Y')) ?></small>
                            </div>
                          </div>
                        </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-xl-4 order-lg-1 order-xl-1">
                  <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"><?= __('Recent News') ?></h3>
                      </div>
                      <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-actions">
                          <a href="<?= $this->Url->build(['controller' => 'News', 'action' => 'index']) ?>" class="btn btn-default btn-upper btn-sm btn-bold"><?= __('All News') ?></a>
                        </div>
                      </div>
                    </div>
                    <div class="kt-portlet__body">
                      <div class="kt-widget-17">
                        <?php foreach ($news as $newsData): ?>
                        <div class="kt-widget-17__item">
                          <div class="kt-widget-17__product">
                            <div class="kt-widget-17__thumb">
                              <a href="#"><img src="<?= $newsData->image ?>" class="kt-widget-17__image" alt="" title=""/></a>
                            </div>
                            <div class="kt-widget-17__product-desc">
                              <a href="<?= $this->Url->build(['controller' => 'News', 'action' => 'edit', $newsData->id]) ?>">
                                <div class="kt-widget-17__title">
                                  <?= h($newsData->title) ?>
                                </div>
                              </a>
                              <div class="kt-widget-17__sku">
                                <!--  -->
                              </div>
                            </div>
                          </div>
                          <div class="kt-widget-17__prices">
                            <div class="kt-widget-17__total">
                              <small><?= h($newsData->created->format('M d, Y')) ?></small>
                            </div>
                          </div>
                        </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--end::Row-->
            <!--end::Dashboard 4-->

<?= $this->Html->script('dashboard.js', ['block' => 'script']) ?>            

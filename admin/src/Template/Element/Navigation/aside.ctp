        <button class="kt-aside-close " id="kt_aside_close_btn">
          <i class="la la-close"></i>
        </button>

        <div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">
          <!-- begin::Aside Brand -->
          <div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">
            <div class="kt-aside__brand-logo">
              <a href="<?= $this->Url->build('/') ?>">
                <img alt="Logo" class="logo" src="<?= $this->Url->image('logo-color.png') ?>"/>
              </a>
            </div>

            <div class="kt-aside__brand-tools">
              <button class="kt-aside__brand-aside-toggler kt-aside__brand-aside-toggler--left" id="kt_aside_toggler">
                <span></span></button>
            </div>
          </div>
          <!-- end:: Aside Brand -->
          <!-- begin:: Aside Menu -->
          <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
            <div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">

              <ul class="kt-menu__nav ">
                <li class="kt-menu__item" aria-haspopup="true">
                  <a href="<?= $this->Url->build(['controller' => 'Dashboard', 'action' => 'index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon flaticon2-graphic"></i>
                    <span class="kt-menu__link-text"><?= __('Dashboard') ?></span>
                  </a>
                </li>
                <li class="kt-menu__item" aria-haspopup="true">
                  <a href="<?= $this->Url->build(['controller' => 'Support', 'action' => 'index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon flaticon2-mail"></i>
                    <span class="kt-menu__link-text"><?= __('Support Messages') ?></span>
                  </a>
                </li>
                <?php if(isset($authUser) && $authUser['role'] === 'super_admin'): ?>
                <li class="kt-menu__item" aria-haspopup="true">
                  <a href="<?= $this->Url->build(['controller' => 'Admins', 'action' => 'index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon flaticon2-user-1"></i>
                    <span class="kt-menu__link-text"><?= __('Admins') ?></span>
                  </a>
                </li>
                <?php endif; ?>
                <li class="kt-menu__item" aria-haspopup="true">
                  <a href="<?= $this->Url->build(['controller' => 'Dashboard', 'action' => 'auditTrail']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon flaticon2-analytics-2"></i>
                    <span class="kt-menu__link-text"><?= __('Audit Trail') ?></span>
                  </a>
                </li>
                <li class="kt-menu__item" aria-haspopup="true">
                  <a href="<?= $this->Url->build(['controller' => 'Dashboard', 'action' => 'notifications']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon flaticon2-bell-4"></i>
                    <span class="kt-menu__link-text"><?= __('Notifications') ?></span>
                    <span class="kt-menu__link-text">
                      <?php if (isset($unreadNotifications) && $unreadNotifications > 0) {?> <span class="badge badge-danger badge-rounded"><?= $unreadNotifications ?></span> <?php } ?>
                    </span>
                  </a>
                </li>
                <li class="kt-menu__item" aria-haspopup="true">
                  <a href="<?= $this->Url->build(['controller' => 'Dashboard', 'action' => 'userFeedbacks']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon flaticon-chat"></i>
                    <span class="kt-menu__link-text"><?= __('User Feedbacks') ?></span>
                  </a>
                </li>
                <li class="kt-menu__item" aria-haspopup="true">
                  <a href="<?= $this->Url->build(['controller' => 'NewsletterContents', 'action' => 'index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon flaticon-mail"></i>
                    <span class="kt-menu__link-text"><?= __('Newsletters Sent') ?></span>
                  </a>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu " aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                  <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                    <i class="kt-menu__link-icon flaticon2-pie-chart-4"></i>
                    <span class="kt-menu__link-text"><?= __('Reports') ?></span>
                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                  </a>
                  <div class="kt-menu__submenu ">
                    <span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                      <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"><?= __('Reports') ?></span>
                        </span>
                      </li>
                      <li class="kt-menu__item" aria-haspopup="true">
                        <a href="<?= $this->Url->build(['controller' => 'Dashboard', 'action' => 'volunteersReport']) ?>" class="kt-menu__link ">
                          <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                            <span></span>
                          </i>
                          <span class="kt-menu__link-text"><?= __('Volunteers') ?></span>
                        </a>
                      </li>
                      <li class="kt-menu__item " aria-haspopup="true">
                        <a href="<?= $this->Url->build(['controller' => 'Dashboard', 'action' => 'organizationsReport']) ?>" class="kt-menu__link ">
                          <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                            <span></span>
                          </i>
                          <span class="kt-menu__link-text"><?= __('Organizations') ?></span>
                        </a>
                      </li>
                      <li class="kt-menu__item " aria-haspopup="true">
                        <a href="<?= $this->Url->build(['controller' => 'Dashboard', 'action' => 'eventsReport']) ?>" class="kt-menu__link ">
                          <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                            <span></span>
                          </i>
                          <span class="kt-menu__link-text"><?= __('Opportunities') ?></span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </li>
                <li class="kt-menu__section ">
                  <h4 class="kt-menu__section-text"><?= __('CMS') ?></h4>
                  <i class="kt-menu__section-icon flaticon-more-v2"></i>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu " aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                  <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                    <i class="kt-menu__link-icon flaticon2-copy"></i>
                    <span class="kt-menu__link-text"><?= __('Pages') ?></span>
                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                  </a>
                  <div class="kt-menu__submenu ">
                    <span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                      <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"><?= __('Pages') ?></span>
                        </span>
                      </li>
                      <li class="kt-menu__item  kt-menu__item--submenu " aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                          <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                            <span></span>
                          </i>
                          <span class="kt-menu__link-text"><?= __('Home') ?></span>
                          <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu ">
                          <span class="kt-menu__arrow"></span>
                          <ul class="kt-menu__subnav">
                            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"><?= __('Home') ?></span>
                              </span>
                            </li>
                            <li class="kt-menu__item" aria-haspopup="true">
                              <a href="<?= $this->Url->build(['controller' => 'Widgets', 'action' => 'homepage', 'image_slider']) ?>" class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                  <span></span>
                                </i>
                                <span class="kt-menu__link-text"><?= __('Slider') ?></span>
                              </a>
                            </li>
                            <li class="kt-menu__item " aria-haspopup="true">
                              <a href="<?= $this->Url->build(['controller' => 'Widgets', 'action' => 'homepage', 'about_block']) ?>" class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                  <span></span>
                                </i>
                                <span class="kt-menu__link-text"><?= __('About VLP Block') ?></span>
                              </a>
                            </li>
                            <li class="kt-menu__item " aria-haspopup="true">
                              <a href="<?= $this->Url->build(['controller' => 'Widgets', 'action' => 'homepage', 'footer']) ?>" class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                  <span></span>
                                </i>
                                <span class="kt-menu__link-text"><?= __('Footer') ?></span>
                              </a>
                            </li>
                          </ul>
                        </div>
                      </li>
                      <li class="kt-menu__item  kt-menu__item--submenu " aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                          <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                            <span></span>
                          </i>
                          <span class="kt-menu__link-text"><?= __('About') ?></span>
                          <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu ">
                          <span class="kt-menu__arrow"></span>
                          <ul class="kt-menu__subnav">
                            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"><?= __('About') ?></span>
                              </span>
                            </li>
                            <li class="kt-menu__item" aria-haspopup="true">
                              <a href="<?= $this->Url->build(['controller' => 'Widgets', 'action' => 'aboutPage', 'about_page_main']) ?>" class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                  <span></span>
                                </i>
                                <span class="kt-menu__link-text"><?= __('Main') ?></span>
                              </a>
                            </li>
                            <li class="kt-menu__item " aria-haspopup="true">
                              <a href="<?= $this->Url->build(['controller' => 'Widgets', 'action' => 'aboutPage', 'about_page_subsection']) ?>" class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                  <span></span>
                                </i>
                                <span class="kt-menu__link-text"><?= __('Subsections') ?></span>
                              </a>
                            </li>
                          </ul>
                        </div>
                      </li>
                    </ul>
                  </div>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                  <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                    <i class="kt-menu__link-icon flaticon2-layers-2"></i>
                    <span class="kt-menu__link-text"><?= __('Categories') ?></span>
                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                  </a>
                  <div class="kt-menu__submenu ">
                    <span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                      <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"><?= __('Categories') ?></span>
                        </span>
                      </li>
                      <li class="kt-menu__item  kt-menu__item--submenu " aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                        <a href="<?= $this->Url->build(['controller' => 'VolunteeringCategories', 'action' => 'index']) ?>" class="kt-menu__link kt-menu__toggle">
                          <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                            <span></span>
                          </i>
                          <span class="kt-menu__link-text"><?= __('Volunteering Sectors') ?></span>
                        </a>
                      </li>
                      <li class="kt-menu__item  kt-menu__item--submenu " aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                        <a href="<?= $this->Url->build(['controller' => 'OrganizationTypes', 'action' => 'index']) ?>" class="kt-menu__link kt-menu__toggle">
                          <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                            <span></span>
                          </i>
                          <span class="kt-menu__link-text"><?= __('Organization Types') ?></span>
                        </a>
                      </li>
                      <li class="kt-menu__item  kt-menu__item--submenu " aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                        <a href="<?= $this->Url->build(['controller' => 'InstitutionTypes', 'action' => 'index']) ?>" class="kt-menu__link kt-menu__toggle">
                          <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                            <span></span>
                          </i>
                          <span class="kt-menu__link-text"><?= __('Institution Types') ?></span>
                        </a>
                      </li>
                      <li class="kt-menu__item  kt-menu__item--submenu " aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                        <a href="<?= $this->Url->build(['controller' => 'ResourceTypes', 'action' => 'index']) ?>" class="kt-menu__link kt-menu__toggle">
                          <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                            <span></span>
                          </i>
                          <span class="kt-menu__link-text"><?= __('Resource Types') ?></span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </li>
                <li class="kt-menu__item" aria-haspopup="true">
                  <a href="<?= $this->Url->build(['controller' => 'Cities', 'action' => 'index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon flaticon2-position"></i>
                    <span class="kt-menu__link-text"><?= __('Cities') ?></span>
                  </a>
                </li>
                <li class="kt-menu__item" aria-haspopup="true">
                  <a href="<?= $this->Url->build(['controller' => 'Countries', 'action' => 'index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon flaticon2-position"></i>
                    <span class="kt-menu__link-text"><?= __('Countries') ?></span>
                  </a>
                </li>
                <li class="kt-menu__item" aria-haspopup="true">
                  <a href="<?= $this->Url->build(['controller' => 'VolunteeringRoles', 'action' => 'index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon flaticon2-soft-icons-1"></i>
                    <span class="kt-menu__link-text"><?= __('Volunteering Roles') ?></span>
                  </a>
                </li>
                <li class="kt-menu__section ">
                  <h4 class="kt-menu__section-text"><?= __('Users') ?></h4>
                  <i class="kt-menu__section-icon flaticon-more-v2"></i>
                </li>
                <li class="kt-menu__item " aria-haspopup="true">
                  <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index']) ?>" class="kt-menu__link ">
                    <i class="kt-menu__link-icon flaticon2-user-outline-symbol"></i>
                    <span class="kt-menu__link-text"><?= __('Volunteers') ?></span>
                  </a>
                </li>
                <li class="kt-menu__item" aria-haspopup="true">
                  <a href="<?= $this->Url->build(['controller' => 'Organizations', 'action' => 'index']) ?>" class="kt-menu__link ">
                    <i class="kt-menu__link-icon flaticon2-group"></i>
                    <span class="kt-menu__link-text"><?= __('Organizations') ?></span>
                  </a>
                </li>
                <!-- <li class="kt-menu__item " aria-haspopup="true">
                  <a href="<?= $this->Url->build(['controller' => 'Organizations', 'action' => 'index', 2]) ?>" class="kt-menu__link ">
                    <i class="kt-menu__link-icon flaticon2-protection"></i>
                    <span class="kt-menu__link-text"><?= __('Institutions') ?></span>
                  </a>
                </li> -->
                <li class="kt-menu__section ">
                  <h4 class="kt-menu__section-text"><?= __('Posts') ?></h4>
                  <i class="kt-menu__section-icon flaticon-more-v2"></i>
                </li>
                <li class="kt-menu__item " aria-haspopup="true">
                  <a href="<?= $this->Url->build(['controller' => 'BlogPosts', 'action' => 'index']) ?>" class="kt-menu__link ">
                    <i class="kt-menu__link-icon flaticon2-writing"></i>
                    <span class="kt-menu__link-text"><?= __('Blogs') ?></span>
                  </a>
                </li>
                <li class="kt-menu__item " aria-haspopup="true">
                  <a href="<?= $this->Url->build(['controller' => 'News', 'action' => 'index']) ?>" class="kt-menu__link ">
                    <i class="kt-menu__link-icon flaticon2-speaker"></i>
                    <span class="kt-menu__link-text"><?= __('News') ?></span>
                  </a>
                </li>
                <li class="kt-menu__item " aria-haspopup="true">
                  <a href="<?= $this->Url->build(['controller' => 'Resources', 'action' => 'index']) ?>" class="kt-menu__link ">
                    <i class="kt-menu__link-icon flaticon2-files-and-folders"></i>
                    <span class="kt-menu__link-text"><?= __('Resources') ?></span>
                  </a>
                </li>
                <li class="kt-menu__item " aria-haspopup="true">
                  <a href="<?= $this->Url->build(['controller' => 'Events', 'action' => 'index']) ?>" class="kt-menu__link ">
                    <i class="kt-menu__link-icon flaticon2-calendar-2"></i>
                    <span class="kt-menu__link-text"><?= __('Opportunities') ?></span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <!-- end:: Aside Menu -->
        </div>
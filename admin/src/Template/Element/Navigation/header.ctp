<?php
use Cake\Core\Configure;
?>
        <div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">

            <!-- begin:: Header Menu -->
            <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn">
              <i class="la la-close"></i>
            </button>
            <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">

            </div>
            <!-- end:: Header Menu -->
            <!-- begin:: Header Topbar -->
            <div class="kt-header__topbar">
              <!--begin:: Languages -->
              <div class="kt-header__topbar-item kt-header__topbar-item--langs">
                <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
                  <span class="kt-header__topbar-icon">
                    <!-- <img class="" src="../../../themes/keen/theme/demo1/dist/assets/media/flags/226-united-states.svg" alt=""/> -->
                    <span class="kt-nav__link-text active"><?= $this->getCurrentLang() ?></span>
                  </span>
                </div>
                <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround">
                  <ul class="kt-nav kt-margin-t-10 kt-margin-b-10">
                  <?php foreach (Configure::read('I18n.languages') as $langCode => $lang): ?>
                    <li class="kt-nav__item">
                      <?= $this->Form->postLink(
                        '<span class="kt-nav__link-text">'. $lang['nativeName'] .'</span>', 
                        ['controller' => 'Pages', 'action' => 'chooseLanguage'], 
                        ['data' => ['lang' => $langCode], 'class' => 'kt-nav__link', 'escape' => false]
                    ) ?>
                    </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              </div>
              <!--end:: Languages -->
              <!--begin: User Bar -->
              <div class="kt-header__topbar-item kt-header__topbar-item--user">

                <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
                  <!--use "kt-rounded" class for rounded avatar style-->
                  <div class="kt-header__topbar-user">
                    <!-- <span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span> <span class="kt-header__topbar-username kt-hidden-mobile">Sean</span> -->
                    <!-- <img alt="Pic" src="<?= $this->Url->image('avatar.jpeg') ?>" class="kt-rounded-"/> -->
                    <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                    <span class="kt-badge kt-badge--username kt-badge--lg kt-badge--light kt-badge--bold"><?= strtoupper(substr($authUser['name'], 0, 2)) ?></span>
                  </div>
                </div>

                <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-sm">

                  <ul class="kt-nav kt-margin-b-10">
                    <!-- <li class="kt-nav__item">
                      <a href="#" class="kt-nav__link">
                        <span class="kt-nav__link-icon">
                          <i class="flaticon2-calendar-3"></i>
                        </span>
                        <span class="kt-nav__link-text">Change password</span>
                      </a>
                    </li> -->
                    <li class="kt-nav__separator kt-nav__separator--fit"></li>

                    <li class="kt-nav__custom kt-space-between">
                      <a href="<?= $this->Url->build(['controller' => 'Dashboard', 'action' => 'logout']) ?>" class="btn btn-label-brand btn-upper btn-sm btn-bold"><?= __('Sign Out') ?></a>
                    </li>
                  </ul>
                </div>
              </div>
              <!--end: User Bar -->

            </div>
            <!-- end:: Header Topbar -->
          </div>
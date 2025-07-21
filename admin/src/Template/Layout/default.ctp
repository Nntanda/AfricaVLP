<?php
/**
 * 
 */

$cakeDescription = 'AU - VLP ADMIN';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    
    <title>
        <?= $this->fetch('title') ?> :
        <?= $cakeDescription ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <?= $this->Html->meta('icon') ?>

    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
    <!--end::Fonts -->

    <!--begin::Global Theme Styles(used by all pages) -->
    <?= $this->Html->css('plugins.bundle.css') ?>
    <?= $this->Html->css('style.bundle.css') ?>
    <?= $this->Html->css('flaticon/flaticon.css') ?>
    <?= $this->Html->css('flaticon2/flaticon.css') ?>
    <?= $this->Html->css('line-awesome/css/line-awesome.css') ?>
    <?= $this->Html->css('style.css') ?>
    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->
    <?= $this->Html->css('base/light.css') ?>
    <?= $this->Html->css('menu/light.css') ?>
    <?= $this->Html->css('brand/light.css') ?>
    <?= $this->Html->css('aside/light.css') ?>
    <!--end::Layout Skins -->

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--fixed kt-page--loading">

    <!-- begin:: Header Mobile -->
    <?= $this->element('Navigation/header_mobile') ?>
    <!-- end:: Header Mobile -->

    <!-- begin:: Root -->
    <div class="kt-grid kt-grid--hor kt-grid--root">
      <!-- begin:: Page -->
      <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
        
        <!-- begin:: Aside -->
        <?= $this->element('Navigation/aside') ?>
        <!-- end:: Aside -->
        
        <!-- begin:: Wrapper -->
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
          <!-- begin:: Header -->
          <?= $this->element('Navigation/header') ?>
          <!-- end:: Header -->
          
          <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

            <!-- begin:: Subheader -->
              <?= $this->Flash->render() ?>
            <!-- end:: Subheader -->

            <!-- begin:: Content -->
            <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
              <?= $this->fetch('content') ?>
            </div>
            <!-- end:: Content -->
          </div>

          <!-- begin:: Footer -->
          <?= $this->element('footer') ?>
          <!-- end:: Footer -->
        </div>
        <!-- end:: Wrapper -->

      </div>
      <!-- end:: Page -->
    </div>
    <!-- end:: Root -->

    <!-- begin:: Scrolltop -->
    <div id="kt_scrolltop" class="kt-scrolltop">
      <i class="la la-arrow-up"></i>
    </div>
    <!-- end:: Scrolltop -->

    <!-- begin::Global Config(global config for global JS sciprts) -->
    <script>
      var KTAppOptions = {
        "colors": {
          "state": {
            "brand": "#1A5632",
            "metal": "#c4c5d6",
            "light": "#ffffff",
            "accent": "#00c5dc",
            "primary": "#9f2241",
            "success": "#34bfa3",
            "info": "#36a3f7",
            "warning": "#ffb822",
            "danger": "#fd3995",
            "focus": "#9816f4"
          },
          "base": {
            "label": [
              "#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"
            ],
            "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
          }
        }
      };
    
    </script>
    <!-- end::Global Config -->

    <!--begin::Global Theme Bundle(used by all pages) -->
    <?= $this->Html->script('plugins.bundle.js') ?>
    <?= $this->Html->script('scripts.bundle.js') ?>
    <!--end::Global Theme Bundle -->

    <?= $this->fetch('script') ?>
    <?= $this->fetch('scriptBlock') ?>

    <script type="text/javascript">
        var langs = ['en', 'fr', 'pt', 'ar'];
        $(document).ready(function(){
            
            var a = $('a[href="<?php echo $this->Url->build() ?>"]');
            if (!a.parent().hasClass('treeview') && !a.parent().parent().hasClass('pagination')) {
                a.parent().addClass('kt-menu__item--active').parents('.kt-menu__item--submenu').addClass('kt-menu__item--open kt-menu__item--here');
            }
            
        });
    </script>
</body>
</html>

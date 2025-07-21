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

    <!--begin::Page Custom Styles(used by this page) -->
    <?= $this->Html->css('login-v2.css') ?>
    <!--end::Page Custom Styles -->

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
<body class="kt-login-v2--enabled kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--fixed kt-page--loading">

    <!-- begin:: Root -->
    <div class="kt-grid kt-grid--ver kt-grid--root">
      <!-- begin:: Page -->
      <div class="kt-grid__item   kt-grid__item--fluid kt-grid  kt-grid kt-grid--hor kt-login-v2" id="kt_login_v2">
        <?= $this->fetch('content') ?>
      </div>
      <!-- end:: Page -->
    </div>
    <!-- end:: Root -->

    <!-- begin::Global Config(global config for global JS sciprts) -->
    <script>
      var KTAppOptions = {
        "colors": {
          "state": {
            "brand": "#1A5632",
            "metal": "#c4c5d6",
            "light": "#ffffff",
            "accent": "#00c5dc",
            "primary": "#5867dd",
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

</body>
</html>

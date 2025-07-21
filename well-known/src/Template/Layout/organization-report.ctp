<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'AU - VLP';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>
        <?= $this->fetch('title') ?>:
        <?= $cakeDescription ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">

    <?= $this->Html->css('bootstrap.css') ?>
    <?= $this->Html->css('jquery-ui.min.css') ?>
    <?= $this->Html->css('animate.min.css') ?>
    <?= $this->Html->css('main.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>
    <?= $this->element('Navigation/top') ?>

    <div class="org-dashboard wrap">
      <?= $this->element('Navigation/org-aside') ?>
        <div class="content">
          <div class="top-bar">
            <a href="#menu" class="side-menu-link burger">
              <span class='burger_inside' id='bgrOne'></span>
              <span class='burger_inside' id='bgrTwo'></span>
              <span class='burger_inside' id='bgrThree'></span>
            </a>
            <a href="<?= $this->Url->build('/') ?>" class="index-page"><?= __('Go Home') ?></a>
          </div>

          <div class="content-inner reports">
              <?= $this->Flash->render() ?>
              <?= $this->fetch('content') ?>
          </div>
          <?= $this->element('org-footer') ?>
        </div>
    </div>

    <!-- Scripts -->
    <?= $this->Html->script('jquery.min.js') ?>
    <?= $this->Html->script('popper.min.js') ?>
    <?= $this->Html->script('bootstrap.min.js') ?>
    <?= $this->Html->script('inlineSVG.js') ?>
    <?= $this->Html->script('jquery-ui.min.js') ?>
    <?= $this->Html->script('wow.min.js') ?>
    <?= $this->Html->script('main.js') ?>
    
    <?= $this->fetch('script') ?>
    <?= $this->fetch('scriptBlock') ?>

    <script>
        var langs = ['en', 'fr', 'pt', 'ar'];

        $('.newbtn').bind("click", function () {
          $('#pic').click();
        });

        function readURL(input) {
          if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
              $('#blah').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
          }
        }
    </script>
</body>
</html>

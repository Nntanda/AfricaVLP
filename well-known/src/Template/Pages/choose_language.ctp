<?php
$this->layout = false;
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">

    <?= $this->Html->css('bootstrap.css') ?>
    <?= $this->Html->css('jquery-ui.min.css') ?>
    <?= $this->Html->css('main.css') ?>
    
    <title><?= __('AU Youths | Choose Language') ?></title>
  </head>
  <body class="select-language">
    <div class="container d-flex align-items-center">
      <div class="content">
        <img src="<?= $this->Url->image('language-img.png') ?>" alt="">
        <p>Promoting Africa’s growth and economic development by championing citizen inclusion and increased cooperation and integration of African states.</p>
        <h4>Choose your language</h4>
        <div class="row">
            <?php foreach ($langs as $langCode => $lang): ?>
            <div class="col-md-4">
            <?= $this->Form->postLink(
                $lang['nativeName'], 
                ['action' => 'chooseLanguage'], 
                ['data' => ['lang' => $langCode], 'class' => 'btn']
            ) ?> 
            </div>
            <?php endforeach; ?>
          <!-- <div class="col-md-4">
            <button type="button" name="button" class="btn">Français</button>
          </div>
          <div class="col-md-4">
            <button type="button" name="button" class="btn">Português</button>
          </div> -->
        </div>
      </div>
    </div>

  </body>
</html>
<div class="main about-us">
    <div class="about-header d-flex align-items-center justify-content-center" style="background: url(<?= $this->Url->image('about.jpg') ?>) no-repeat;">
        <h1><?= __('About us') ?></h1>
    </div>
    <div class="container">
        <?= $main[0]->content ?>
        <div class="owl-carousel owl-theme about-carousel">
            <div class="item" style="background: url('<?= $this->Url->image('about-img-01.jpg') ?>') no-repeat; background-size: cover;"></div>
            <div class="item" style="background: url('<?= $this->Url->image('about-img-02.jpg') ?>') no-repeat; background-size: cover;"></div>
            <div class="item" style="background: url('<?= $this->Url->image('about-img-03.jpg') ?>') no-repeat; background-size: cover;"></div>
            <div class="item" style="background: url('<?= $this->Url->image('about-img-01.jpg') ?>') no-repeat; background-size: cover;"></div>
            <div class="item" style="background: url('<?= $this->Url->image('about-img-02.jpg') ?>') no-repeat; background-size: cover;"></div>
            <div class="item" style="background: url('<?= $this->Url->image('about-img-03.jpg') ?>') no-repeat; background-size: cover;"></div>
            <div class="item" style="background: url('<?= $this->Url->image('about-img-01.jpg') ?>') no-repeat; background-size: cover;"></div>
            <div class="item" style="background: url('<?= $this->Url->image('about-img-02.jpg') ?>') no-repeat; background-size: cover;"></div>
            <div class="item" style="background: url('<?= $this->Url->image('about-img-03.jpg') ?>') no-repeat; background-size: cover;"></div>
        </div>
        <div class="row">
        <?php foreach($subSections as $subSection): ?>
            <div class="col-md-6">
                <h4><?= h($subSection->title) ?></h4>
                <?= $subSection->content ?>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>

<?php $this->Html->css("owl.carousel.min.css", ['block' => 'css']) ?>
<?php $this->Html->css("owl.theme.default.min.css", ['block' => 'css']) ?>
<?php $this->Html->script("owl.carousel.min.js", ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
        $('.owl-carousel').owlCarousel({
            loop:true,
            nav:true,
            margin:20,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:2
                },
                1000:{
                    items:3
                }
            }
        })
    });
</script>
<?php $this->end(); ?>
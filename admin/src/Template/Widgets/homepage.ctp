<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Widget $widget
 */

$linkOptions = [
    $this->Url->build(['lang' => '', 'controller' => 'Events', 'action' => 'index']) => 'Volunteer Events',
    $this->Url->build(['lang' => '', 'controller' => 'Pages', 'action' => 'interactiveMap', '?' => ['display' => 'volunteer_organizations']]) => __('Interactive Map: Volunteer Organizations'),
    $this->Url->build(['lang' => '', 'controller' => 'Pages', 'action' => 'interactiveMap', '?' => ['display' => 'volunteer_events']]) => __('Interactive Map: Volunteer Events'),
    $this->Url->build(['lang' => '', 'controller' => 'Pages', 'action' => 'aboutUs']) => __('About Us'),
    $this->Url->build(['lang' => '', 'controller' => 'Resources', 'action' => 'index', '?' => ['resource_type_id' => '1']]) => __('Resources: Volunteer Policies'),
    $this->Url->build(['lang' => '', 'controller' => 'Resources', 'action' => 'index', '?' => ['resource_type_id' => '3']]) => __('Resources: Regional Information'),
    'custom' => __('Custom'),
];

?>
<?= $this->Html->css('fileinput/fileinput.min.css', ['block' => 'css']) ?>
<div id="alert"></div>
<?php if ($name == "image_slider"): ?>
            <!-- begin:: Subheader -->
            <div class="kt-subheader   kt-grid__item" id="kt_subheader">
              <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                  <h3 class="kt-subheader__title"><?= __('Sliders') ?></h3>
                  <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                </div>
              </div>
            </div>
            <!-- end:: Subheader -->

            <!--begin::Dashboard 4-->
            <!--begin::Row-->
            <div class="row">
            <?php $c=1; foreach ($widgets as $widget): ?>
                <div class="col-lg-6 widget-group">
                    <!--begin::Portlet-->
                        <div class="kt-portlet kt-portlet--tabs">
                            <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title"><?= __('Slide {0}', [$c]) ?> </h3>
                            </div>
                            <div class="kt-portlet__head-toolbar">
                                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-right nav-tabs-line-success nav-tabs-bold" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#en<?= $c ?>" role="tab">English</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#fr<?= $c ?>" role="tab"><?= \Cake\Core\Configure::read('I18n.languages.fr.nativeName') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#pt<?= $c ?>" role="tab"><?= \Cake\Core\Configure::read('I18n.languages.pt.nativeName') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#ar<?= $c ?>" role="tab"><?= \Cake\Core\Configure::read('I18n.languages.ar.nativeName') ?></a>
                                </li>
                                <?= $this->element('translate') ?>
                                </ul>
                            </div>
                            </div>
                            <?= $this->Form->create($widget, ['url' => ['action' => 'edit', $widget->id], 'type' => 'file', 'class' => 'bs-validate']) ?>
                            <div class="kt-portlet__body">
                                <div class="tab-content">
                                    <div class="tab-pane active en" id="en<?= $c ?>" role="tabpanel">
                                    <?php
                                        echo $this->Form->control('title', ['class' => 'w-title form-control tr-input', 'placeholder' => 'Title']);
                                        echo $this->Form->control('content', ['class' => 'w-content form-control tr-input']);
                                       
                                    ?>
                                    </div>
                                    <div class="tab-pane fade fr" id="fr<?= $c ?>" role="tabpanel">
                                    <?php
                                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.fr.locale') .'.title', ['class' => 'w-title form-control tr-input', 'placeholder' => 'Title']);
                                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.fr.locale') .'.content', ['class' => 'w-content form-control tr-input']);
                                    ?>
                                    </div>
                                    <div class="tab-pane fade pt" id="pt<?= $c ?>" role="tabpanel">
                                    <?php
                                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.pt.locale') .'.title', ['class' => 'w-title form-control tr-input', 'placeholder' => 'Title', 'label' => false]);
                                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.pt.locale') .'.content', ['class' => 'w-content form-control tr-input']);
                                    ?>
                                    </div>
                                    <div class="tab-pane fade ar" id="ar<?= $c ?>" role="tabpanel">
                                    <?php
                                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.ar.locale') .'.title', ['class' => 'w-title form-control tr-input', 'placeholder' => 'Title', 'label' => false]);
                                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.ar.locale') .'.content', ['class' => 'w-content form-control tr-input']);
                                    ?>
                                    </div>
                                </div>

                                <?php
                                    echo $this->Form->control('file', [
                                        'class' => 'fileinput', 
                                        'label' => false, 
                                        'type' => 'file',
                                        'accept' => 'image/*',
                                        'data-image' => $widget->image
                                    ]);

                                    echo $this->Form->control('url', ['class' => 'w-content form-control tr-input', 'placeholder' => 'URL']);
                                ?>
                                
                            
                            </div>
                            <div class="kt-portlet__foot">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                <?php
                                    echo $this->Form->control('status');
                                ?>
                                </div>
                                <div class="col-lg-6 kt-align-right">
                                <?= $this->Form->button(__('Save changes')) ?>
                                </div>
                            </div>
                            </div>
                            <?= $this->Form->end() ?>
                        </div>
                        <!--end::Portlet-->
                </div>
            <?php $c++; endforeach; ?>
            </div>
            <!--end::Row-->
              
            <!--end::Dashboard 4-->

<?php endif; ?>

<?php if ($name == "about_block"): ?>
            <!-- begin:: Subheader -->
            <div class="kt-subheader   kt-grid__item" id="kt_subheader">
              <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                  <h3 class="kt-subheader__title"><?= __('About Blocks') ?></h3>
                  <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                </div>
              </div>
            </div>
            <!-- end:: Subheader -->

            <!--begin::Dashboard 4-->
            <!--begin::Row-->
            <div class="row">
            <?php $c=1; foreach ($widgets as $widget): ?>
                <div class="col-lg-6 widget-group">
                    <!--begin::Portlet-->
                        <div class="kt-portlet kt-portlet--tabs">
                            <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title"><?= __('Block {0}', [$c]) ?> </h3>
                            </div>
                            <div class="kt-portlet__head-toolbar">
                                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-right nav-tabs-line-success nav-tabs-bold" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#en<?= $c ?>" role="tab">English</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#fr<?= $c ?>" role="tab"><?= \Cake\Core\Configure::read('I18n.languages.fr.nativeName') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#pt<?= $c ?>" role="tab"><?= \Cake\Core\Configure::read('I18n.languages.pt.nativeName') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#ar<?= $c ?>" role="tab"><?= \Cake\Core\Configure::read('I18n.languages.ar.nativeName') ?></a>
                                </li>
                                <?= $this->element('translate') ?>
                                </ul>
                            </div>
                            </div>
                            <?= $this->Form->create($widget, ['url' => ['action' => 'edit', $widget->id], 'class' => 'bs-validate']) ?>
                            <div class="kt-portlet__body">
                                <div class="tab-content">
                                    <div class="tab-pane active en" id="en<?= $c ?>" role="tabpanel">
                                        <?php
                                            echo $this->Form->control('title', ['class' => 'w-title form-control tr-input', 'placeholder' => 'Title']);
                                            echo $this->Form->control('content', ['class' => 'w-content form-control tr-input']);
                                        ?>
                                    </div>
                                    <div class="tab-pane fade fr" id="fr<?= $c ?>" role="tabpanel">
                                        <?php
                                            echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.fr.locale') .'.title', ['class' => 'w-title form-control tr-input', 'placeholder' => 'Title']);
                                            echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.fr.locale') .'.content', ['class' => 'w-content form-control tr-input']);
                                        ?>
                                    </div>
                                    <div class="tab-pane fade pt" id="pt<?= $c ?>" role="tabpanel">
                                        <?php
                                            echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.pt.locale') .'.title', ['class' => 'w-title form-control tr-input', 'placeholder' => 'Title']);
                                            echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.pt.locale') .'.content', ['class' => 'w-content form-control tr-input']);
                                        ?>
                                    </div>
                                    <div class="tab-pane fade ar" id="ar<?= $c ?>" role="tabpanel">
                                        <?php
                                            echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.ar.locale') .'.title', ['class' => 'w-title form-control tr-input', 'placeholder' => 'Title']);
                                            echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.ar.locale') .'.content', ['class' => 'w-content form-control tr-input']);
                                        ?>
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="col-lg-3">
                                        <?php
                                            echo $this->Form->control('link_option', ['options' => $linkOptions, 'empty' => __('None'), 'class' => "link-option form-control", 'data-id' => $c]);
                                        ?>
                                    </div>
                                    <div class="col-lg-9">
                                        <?= $this->Form->control('link', ['readonly' => true, 'id' => "link-$c"]) ?>
                                    </div>
                                </div>
                            
                            </div>
                            <div class="kt-portlet__foot">
                                <div class="row align-items-center">
                                    <div class="col-lg-6">
                                        <?php
                                            echo $this->Form->control('status');
                                        ?>
                                    </div>
                                    <div class="col-lg-6 kt-align-right">
                                    <?= $this->Form->button(__('Save changes')) ?>
                                    </div>
                                </div>
                            </div>
                            <?= $this->Form->end() ?>
                        </div>
                        <!--end::Portlet-->
                </div>
            <?php $c++; endforeach; ?>
            </div>
            <!--end::Row-->
              
            <!--end::Dashboard 4-->
        

<?php endif; ?>

<?php if ($name == "footer"): ?>
            <!-- begin:: Subheader -->
            <div class="kt-subheader   kt-grid__item" id="kt_subheader">
              <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                  <h3 class="kt-subheader__title"><?= __('Footer') ?></h3>
                  <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                </div>
              </div>
            </div>
            <!-- end:: Subheader -->

            <!--begin::Dashboard 4-->
            <!--begin::Row-->
            <div class="row">
            <?php $c=1; foreach ($widgets as $widget): ?>
                <div class="col-lg-12 widget-group">
                    <!--begin::Portlet-->
                        <div class="kt-portlet kt-portlet--tabs">
                            <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title"><?= __('Footer Text', [$c]) ?> </h3>
                            </div>
                            <div class="kt-portlet__head-toolbar">
                                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-right nav-tabs-line-success nav-tabs-bold" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#en<?= $c ?>" role="tab">English</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#fr<?= $c ?>" role="tab"><?= \Cake\Core\Configure::read('I18n.languages.fr.nativeName') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#pt<?= $c ?>" role="tab"><?= \Cake\Core\Configure::read('I18n.languages.pt.nativeName') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#ar<?= $c ?>" role="tab"><?= \Cake\Core\Configure::read('I18n.languages.ar.nativeName') ?></a>
                                </li>
                                <?= $this->element('translate') ?>
                                </ul>
                            </div>
                            </div>
                            <?= $this->Form->create($widget, ['url' => ['action' => 'edit', $widget->id], 'class' => 'bs-validate']) ?>
                            <div class="kt-portlet__body">
                            <div class="tab-content">
                                <div class="tab-pane active en" id="en<?= $c ?>" role="tabpanel">
                                <?php
                                    echo $this->Form->control('title', ['class' => 'w-title form-control tr-input', 'placeholder' => 'Title']);
                                    echo $this->Form->control('content', ['class' => 'w-content form-control tr-input']);
                                ?>
                                </div>
                                <div class="tab-pane fade fr" id="fr<?= $c ?>" role="tabpanel">
                                <?php
                                    echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.fr.locale') .'.title', ['class' => 'w-title form-control tr-input', 'placeholder' => 'Title']);
                                    echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.fr.locale') .'.content', ['class' => 'w-content form-control tr-input']);
                                ?>
                                </div>
                                <div class="tab-pane fade pt" id="pt<?= $c ?>" role="tabpanel">
                                <?php
                                    echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.pt.locale') .'.title', ['class' => 'w-title form-control tr-input', 'placeholder' => 'Title']);
                                    echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.pt.locale') .'.content', ['class' => 'w-content form-control tr-input']);
                                ?>
                                </div>
                                <div class="tab-pane fade ar" id="ar<?= $c ?>" role="tabpanel">
                                <?php
                                    echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.ar.locale') .'.title', ['class' => 'w-title form-control tr-input', 'placeholder' => 'Title']);
                                    echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.ar.locale') .'.content', ['class' => 'w-content form-control tr-input']);
                                ?>
                                </div>
                            </div>
                            
                            </div>
                            <div class="kt-portlet__foot">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                <?php
                                    //echo $this->Form->control('status');
                                ?>
                                </div>
                                <div class="col-lg-6 kt-align-right">
                                <?= $this->Form->button(__('Save changes')) ?>
                                </div>
                            </div>
                            </div>
                            <?= $this->Form->end() ?>
                        </div>
                        <!--end::Portlet-->
                </div>
            <?php $c++; endforeach; ?>
            </div>
            <!--end::Row-->
              
            <!--end::Dashboard 4-->
        

<?php endif; ?>

<?= $this->Html->script('fileinput/fileinput.js', ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<?= $this->element('translation-validation') ?>
<script>
    $(document).ready(function () {

        var inputEl = $(".fileinput");
        $.each(inputEl, function(k, input){
            console.log($(input).data('image'));
            $(input).fileinput({
                browseClass: "btn btn-primary btn-block",
                showCaption: false,
                showRemove: false,
                showUpload: false,
                initialPreviewAsData: true,
                initialPreview: [$(input).data('image')],
            });
        })

        $(".translate").click(function (e) {
            e.preventDefault();
            let trBtn = $(this);
            let wGroup = $(this).closest('.widget-group');
            let btnGrp = wGroup.find('.auto-btn');
            btnGrp.addClass('kt-spinner kt-spinner--sm kt-spinner--right kt-spinner--light');
            
            srcLang = trBtn.data('lang');
            console.log('Translating...', srcLang);

            // let langs = ['en', 'fr', 'pt'];
            let srcTexts = {}
            let transData = []

            srcTexts[0] = wGroup.find("."+srcLang+" .w-title").val();
            srcTexts[1] = wGroup.find("."+srcLang+" .w-content").val();

            langs.forEach(lang => {
                if (lang !== srcLang) {
                    transData.push({
                        'lang': lang,
                        'sourceTexts': srcTexts
                    });
                }
            })

            $.ajax({
                type: "POST",
                url: "<?= $this->Url->build('/translate') ?>",
                data: {
                    'data': transData,
                    'sourceLanguage': srcLang
                },
                success: function (data) {
                    data.forEach(result => {
                        wGroup.find("."+result.lang+" .w-title").val(result.data[0].text);
                        wGroup.find("."+result.lang+" .w-content").val(result.data[1].text);
                    })
                    
                },
                complete: function (xhr, result) {
                    btnGrp.removeClass('kt-spinner kt-spinner--sm kt-spinner--right kt-spinner--light');
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                }
            });
        })

        $(".link-option").change(function () {
            if($(this).val() == 'custom') {
                $('#link-'+$(this).data('id')).val('')
                $('#link-'+$(this).data('id')).attr('readonly', false);
            } else {
                $('#link-'+$(this).data('id')).val($(this).val())
                $('#link-'+$(this).data('id')).attr('readonly', true);

            }
        })
    });
</script>
<?php $this->end(); ?>
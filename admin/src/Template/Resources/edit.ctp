<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Resource $resource
 */
?>

<?= $this->Html->css('post.css', ['block' => 'css']) ?>
<?= $this->Html->css('fileinput/fileinput.min.css', ['block' => 'css']) ?>
<div id="alert"></div>
            <!-- begin:: Subheader -->
            <div class="kt-subheader   kt-grid__item" id="kt_subheader">
              <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                  <h3 class="kt-subheader__title"><?= __('Edit Resource') ?></h3>
                  <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                  <div class="kt-subheader__wrapper">
                    <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-icon- btn btn-label btn-label-brand btn-upper btn-font-sm btn-bold">
                    <?= __('Back to Resources') ?></a>
                  </div>
                </div>
                <div class="kt-subheader__toolbar">
                  <!--  -->
                </div>
              </div>
            </div>
            <!-- end:: Subheader -->

            <!--begin::Dashboard 4-->
            <?= $this->Form->create($resource, ['type' => 'file', 'class' => 'bs-validate']) ?>
            <div class="kt-portlet kt-portlet--tabs">
                <div class="kt-portlet__head">
                  <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title"><?= __('Resource Texts') ?> </h3>
                  </div>
                  <div class="kt-portlet__head-toolbar">
                    <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-right nav-tabs-line-success nav-tabs-bold" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#en" role="tab">English</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#fr" role="tab"><?= \Cake\Core\Configure::read('I18n.languages.fr.nativeName') ?></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#pt" role="tab"><?= \Cake\Core\Configure::read('I18n.languages.pt.nativeName') ?></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#ar" role="tab"><?= \Cake\Core\Configure::read('I18n.languages.ar.nativeName') ?></a>
                      </li>
                      <?= $this->element('translate') ?>
                    </ul>
                  </div>
                </div>
                
                <div class="kt-portlet__body">
                  <div class="tab-content">
                    <div class="tab-pane active" id="en" role="tabpanel">
                    <?php
                        echo $this->Form->control('title', ['class' => 'w-title form-control tr-input', 'placeholder' => 'Title']);
                        echo $this->Form->control('description', ['class' => 'w-description form-control tr-input', 'placeholder' => 'Description']);
                    ?>
                    </div>
                    <div class="tab-pane fade" id="fr" role="tabpanel">
                    <?php
                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.fr.locale') .'.title', ['class' => 'w-title form-control tr-input', 'placeholder' => 'Title']);
                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.fr.locale') .'.description', ['class' => 'w-description form-control tr-input', 'placeholder' => __('Description')]);
                    ?>
                    </div>
                    <div class="tab-pane fade" id="pt" role="tabpanel">
                    <?php
                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.pt.locale') .'.title', ['class' => 'w-title form-control tr-input', 'placeholder' => 'Title']);
                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.pt.locale') .'.description', ['class' => 'w-description form-control tr-input', 'placeholder' => 'Description']);
                    ?>
                    </div>
                    <div class="tab-pane fade" id="ar" role="tabpanel">
                    <?php
                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.ar.locale') .'.title', ['class' => 'w-title form-control tr-input', 'placeholder' => 'Title']);
                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.ar.locale') .'.description', ['class' => 'w-description form-control tr-input', 'placeholder' => 'Description']);
                    ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="kt-portlet kt-portlet--tabs">
                <div class="kt-portlet__head">
                  <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title"><?= __('Others') ?></h3>
                  </div>
                </div>
                <div class="kt-portlet__body">
                  <div class="row">
                    <div class="col-md-6">
                        <?php
                            echo $this->Form->control('file', [
                                'class' => 'fileinput', 
                                'label' => false, 
                                'type' => 'file',
                                'accept' => 'image/*, application/pdf, application/msword, application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.openxmlformats-officedocument.presentationml.presentation, video/mp4',
                                'data-image' => $resource->file_link
                            ]);
                        ?>
                    </div>
                    <div class="col-md-6">
                      <?php
                        echo $this->Form->control('status');
                        echo $this->Form->control('resource_type_id', ['options' => $resourceTypes, 'empty' => __('Select'), 'required' => true]);
                        echo $this->Form->control('volunteering_categories._ids', ['empty' => __('Select'), 'class' => 'form-control kt-select2-general', 'required' => true]);
                        echo $this->Form->control('region_id', ['options' => $regions, 'empty' => __('All')]);
                        echo $this->Form->control('country_id', ['disabled' => true]);
                    ?>
                    </div>
                  </div>
                </div>
                <div class="kt-portlet__foot">
                  <div class="row align-items-center">
                    <div class="col-lg-6">
                      <!-- Foot caption -->
                    </div>
                    <div class="col-lg-6 kt-align-right">
                      <?= $this->Form->button(__('Submit')) ?>
                    </div>
                  </div>
                </div>
                
              </div>
              <?= $this->Form->end() ?>
              <!--end::Dashboard 4-->
     

<!-- <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script> -->

<?= $this->Html->script('dashboard.js', ['block' => 'script']) ?>
<?= $this->Html->script('select2.js', ['block' => 'script']) ?>
<?= $this->Html->script('fileinput/fileinput.js', ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<?= $this->element('translation-validation') ?>
<script>
    $(document).ready(function () {
        $(".fileinput").fileinput({
            browseClass: "btn btn-primary btn-block",
            showCaption: false,
            showRemove: false,
            showUpload: false,
            initialPreviewAsData: true,
            initialPreview: [$(".fileinput").data('image')],
        });

        $(".translate").click(function (e) {
            e.preventDefault();
            let trBtn = $(this);
            let btnGrp = $(this).closest('.nav-item').find('.auto-btn');
            btnGrp.addClass('kt-spinner kt-spinner--sm kt-spinner--right kt-spinner--light');

            srcLang = trBtn.data('lang');
            console.log('Translating...', srcLang);

            let srcTexts = {}
            let transData = []

            srcTexts[0] = $("#"+srcLang+" .w-title").val();
            srcTexts[1] = $("#"+srcLang+" .w-description").val();

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
                        $("#"+result.lang+" .w-title").val(result.data[0].text);
                        $("#"+result.lang+" .w-description").val(result.data[1].text);
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

        $("#region-id").change(function () {
            region_id = $(this).val();
            if(region_id && region_id !== '') {
                $("#country-id").html('<option> ... </option>')
                let options = '';
                $.ajax({
                    type: "POST",
                    url: "<?= $this->Url->build('/region-country-list') ?>"+ '/' +region_id,
                    success: function (data) {
                        for (k in data) {
                            options += `<option value="${k}"> ${data[k]} </option>`;
                        };
                    },
                    complete: function (xhr, result) {
                        $("#country-id").html(options)
                        if (options !== '') {
                          $("#country-id").prop('disabled', false)
                        } else {
                          $("#country-id").prop('disabled', true)
                        }
                    },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                    }
                });
            } else {
              $("#country-id").prop('disabled', true)
              $("#country-id").html('');
            }
        })
    });
</script>

<?php $this->end(); ?>


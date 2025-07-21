<div id="alert"></div>
<div class="container">
    <div class="d-flex justify-content-between top-line align-items-center">
        <h3><?= __('Edit Resource') ?></h3>
        <ul class="nav nav-tabs d-flex" role="tablist">
            <li class="nav-item nav-line">
                <a class="nav-link active" data-toggle="tab" href="#en">English</a>
            </li>
            <li class="nav-item nav-line">
                <a class="nav-link" data-toggle="tab" href="#fr"><?= \Cake\Core\Configure::read('I18n.languages.fr.nativeName') ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#pt"><?= \Cake\Core\Configure::read('I18n.languages.pt.nativeName') ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#ar"><?= \Cake\Core\Configure::read('I18n.languages.ar.nativeName') ?></a>
            </li>
            <?= $this->element('translate') ?>
        </ul>
    </div>
    <?= $this->Form->create($resource, ['type' => 'file', 'class' => 'bs-validate']) ?>
    <div class="tab-content">
        <div id="en" class="tab-pane active basic-info">
            <div class="page-title">
                <h3>Basic Info</h3>
            </div>
            <div class="form-text">
                <?php
                    echo $this->Form->control('title', ['placeholder' => 'Title', 'class' => 'w-title form-control tr-input']);
                    echo $this->Form->control('description', ['placeholder' => 'Description', 'class' => 'w-content form-control tr-input']);
                ?>
            </div>
        </div>
        <div id="fr" class="tab-pane fade basic-info">
            <div class="page-title">
                <h3>French Basic Info</h3>
            </div>
            <div class="form-text">
                <?php
                    echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.fr.locale') .'.title', ['placeholder' => 'Title', 'class' => 'w-title form-control tr-input']);
                    echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.fr.locale') .'.description', ['placeholder' => 'Description', 'class' => 'w-content form-control tr-input']);
                ?>
            </div>
        </div>
        <div id="pt" class="tab-pane fade basic-info">
            <div class="page-title">
                <h3>Português Basic Info</h3>
            </div>
            <div class="form-text">
                <?php
                    echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.pt.locale') .'.title', ['placeholder' => 'Title', 'class' => 'w-title form-control tr-input']);
                    echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.pt.locale') .'.description', ['placeholder' => 'Description', 'class' => 'w-content form-control tr-input']);
                ?>
            </div>
        </div>
        <div id="ar" class="tab-pane fade basic-info">
            <div class="page-title">
                <h3>Ar Basic Info</h3>
            </div>
            <div class="form-text">
                <?php
                    echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.ar.locale') .'.title', ['placeholder' => 'Title', 'class' => 'w-title form-control tr-input']);
                    echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.ar.locale') .'.description', ['placeholder' => 'Description', 'class' => 'w-content form-control tr-input']);
                ?>
            </div>
        </div>
    </div>

    <div class="other-info">
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
                <div class="form-group">
                    <label for="region-id"><?= __('Resource Type') ?></label>
                    <?= $this->Form->control('resource_type_id', ['empty' => __('Select Type'), 'label' => false]) ?>
                </div>
                
                <div class="form-group">
                    <label for="region-id"><?= __('Region') ?></label>
                    <?= $this->Form->control('region_id', ['empty' => __('All Region'), 'label' => false]) ?>
                </div>

                <div class="form-group">
                    <label for="end-date"><?= __('Country') ?></label>
                    <?= $this->Form->control('country_id', ['empty' => __('All'), 'label' => false, 'options' => $countries, 'class' => 'form-control select2']) ?>
                </div>

                <div class="form-group">
                    <label for="end-date"><?= __('Volunteering Categories') ?></label>
                    <?= $this->Form->control('volunteering_categories._ids', ['label' => false, 'options' => $volunteering_categories, 'class' => 'form-control select2']) ?>
                </div>

                <div class="form-group">
                    <label for="start-date"><?= __('Status') ?></label>
                    <?= $this->Form->control('status', ['label' => false]) ?>
                </div>
            </div>
        </div>
        <div class="d-flex">
            <button type="submit" class="btn ml-auto"><?= __('Save') ?></button>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<?php $this->Html->script("https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js", ['block' => 'script']) ?>

<?= $this->Html->css('fileinput/fileinput.min.css', ['block' => 'css']) ?>
<?= $this->Html->script('fileinput/fileinput.js', ['block' => 'script']) ?>
<?= $this->Html->script('fileinput/themes/fa/theme.js', ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<?= $this->element('translation-validation') ?>
<script>
    $(document).ready(function () {
        $(".select2").select2()

        $(".fileinput").fileinput({
            theme: "fa",
            browseClass: "btn btn-primary btn-block",
            showCaption: false,
            showRemove: false,
            showUpload: false,
            initialPreviewAsData: true,
            initialPreview: [$(".fileinput").data('image')],
        });

        let initialCountries = $("#country-id").html();
        $("#region-id").change(function () {
            region_id = $(this).val();
            if(region_id && region_id !== '') {
                $("#country-id").html('<option> ... </option>')
                $("#city-id").html('')
                let options = `<option><?= __('All') ?></option>`;
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
                    },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                    }
                });
            } else {
                $("#country-id").html(initialCountries);
                $("#city-id").html(initialCities)
            }
        })

        $(".translate").click(function (e) {
            e.preventDefault();
            let trBtn = $(this);
            let btnGrp = $(this).closest('.nav-item').find('.auto-btn');
            initBtn = btnGrp.html();
            let spinner = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
            btnGrp.html(spinner + initBtn);

            srcLang = trBtn.data('lang');
            console.log('Translating...', srcLang);

            let srcTexts = {}
            let transData = []

            srcTexts[0] = $("#"+srcLang+" .w-title").val();
            srcTexts[1] = $("#"+srcLang+" .w-content").val();

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
                        $("#"+result.lang+" .w-content").val(result.data[1].text);
                    })
                },
                complete: function (xhr, result) {
                    btnGrp.html(initBtn);
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                }
            });
        })
    });

</script>

<?php $this->end(); ?>
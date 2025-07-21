<?= $this->Html->css('tagify.css', ['block' => 'css']) ?>
<div id="alert"></div>
<div class="container">
    <div class="d-flex justify-content-between top-line align-items-center">
        <h3><?= __('Post News') ?></h3>
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
    <?= $this->Form->create($news, ['type' => 'file', 'class' => 'bs-validate']) ?>
    <div class="tab-content">
        <div id="en" class="tab-pane active basic-info">
            <div class="page-title">
                <h3>Basic Info</h3>
            </div>
            <div class="form-text">
                <?php
                    echo $this->Form->control('title', ['placeholder' => 'Title', 'class' => 'w-title form-control tr-input']);
                    echo $this->Form->control('content', ['placeholder' => 'Title', 'class' => 'w-content form-control tr-input']);
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
                    echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.fr.locale') .'.content', ['placeholder' => 'Title', 'class' => 'w-content form-control tr-input']);
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
                    echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.pt.locale') .'.content', ['placeholder' => 'Title', 'class' => 'w-content form-control tr-input']);
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
                    echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.sr.locale') .'.content', ['placeholder' => 'Title', 'class' => 'w-content form-control tr-input']);
                ?>
            </div>
        </div>
    </div>

    <div class="other-info">
        <div class="row">
            <div class="col-md-6">
                <div class="upload-img">
                    <label class="newbtn">
                        <img id="blah" src="<?= $this->Url->image('upload.svg') ?>">
                        <?= $this->Form->control('file', ['type' => 'file', 'onchange' => 'readURL(this);', 'id' => 'pic', 'class' => 'pis', 'label' => false, 'accept' => 'image/*']) ?>
                    </label>
                </div>
            </div>
            <div class="col-md-6">
                <label for="region-id"><?= __('Region') ?></label>
                <?= $this->Form->control('region_id', ['empty' => __('All Region'), 'label' => false]) ?>

                <label for="start-date"><?= __('Status') ?></label>
                <?= $this->Form->control('status', ['label' => false]) ?>
                
                <label for="end-date"><?= __('Publishing Categories') ?></label>
                <?= $this->Form->control('publishing_categories._ids', ['label' => false, 'options' => $publishing_categories, 'class' => 'form-control select2']) ?>
                
                <label for="end-date"><?= __('Volunteering Categories') ?></label>
                <?= $this->Form->control('volunteering_categories._ids', ['label' => false, 'options' => $volunteering_categories, 'class' => 'form-control select2']) ?>

                <label for="end-date"><?= __('Tags (Max: 5)') ?></label>
                <?= $this->Form->control('tag_string', ['label' => false, 'class' => 'form-control tag-input']) ?>
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
<?= $this->Html->script('tagify.min.js', ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<?= $this->element('translation-validation') ?>
<script>
    var input = document.querySelector('.tag-input');
    new Tagify(input, {
      maxTags: 5,
      originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(',')
    })
    $(document).ready(function () {
        $(".select2").select2()

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
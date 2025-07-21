<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\News $news
 */
?>

<?= $this->Html->css('post.css', ['block' => 'css']) ?>
<?= $this->Html->css('tagify.css', ['block' => 'css']) ?>
<?= $this->Html->css('fileinput/fileinput.min.css', ['block' => 'css']) ?>
<div id="alert"></div>
            <!-- begin:: Subheader -->
            <div class="kt-subheader   kt-grid__item" id="kt_subheader">
              <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                  <h3 class="kt-subheader__title"><?= __('New Blog Post') ?></h3>
                  <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                  <div class="kt-subheader__wrapper">
                    <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-icon- btn btn-label btn-label-brand btn-upper btn-font-sm btn-bold">
                    <?= __('Back to Blog Posts') ?></a>
                  </div>
                </div>
                <div class="kt-subheader__toolbar">
                  <!-- <div class="kt-subheader__wrapper"> <a href="#" class="btn btn-icon- btn btn-label btn-label-brand btn-upper btn-font-sm btn-bold"> <i class="flaticon2-add-1"></i> New Blog Post</a> </div> -->
                </div>
              </div>
            </div>
            <!-- end:: Subheader -->

            <!--begin::Dashboard 4-->
            <?= $this->Form->create($blogPost, ['type' => 'file', 'class' => 'bs-validate']) ?>
            <div class="kt-portlet kt-portlet--tabs">
                <div class="kt-portlet__head">
                  <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title"><?= __('Blog Post Texts') ?> </h3>
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
                        echo $this->Form->control('content', ['class' => 'w-content tinyM tr-input', 'placeholder' => 'Content']);
                    ?>
                    </div>
                    <div class="tab-pane fade" id="fr" role="tabpanel">
                    <?php
                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.fr.locale') .'.title', ['class' => 'w-title form-control tr-input', 'placeholder' => 'Title']);
                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.fr.locale') .'.content', ['class' => 'w-content tinyM tr-input', 'placeholder' => __('Content')]);
                    ?>
                    </div>
                    <div class="tab-pane fade" id="pt" role="tabpanel">
                    <?php
                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.pt.locale') .'.title', ['class' => 'w-title form-control tr-input', 'placeholder' => 'Title']);
                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.pt.locale') .'.content', ['class' => 'w-content tinyM tr-input', 'placeholder' => 'Content']);
                    ?>
                    </div>
                    <div class="tab-pane fade" id="ar" role="tabpanel">
                    <?php
                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.ar.locale') .'.title', ['class' => 'w-title form-control tr-input', 'placeholder' => 'Title']);
                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.ar.locale') .'.content', ['class' => 'w-content tinyM tr-input', 'placeholder' => 'Content']);
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
                                'accept' => 'image/*',
                            ]);
                        ?>
                    </div>
                    <div class="col-md-6">
                      <?php
                        echo $this->Form->control('status');
                        echo $this->Form->control('region_id', ['options' => $regions, 'empty' => __('All')]);
                        echo $this->Form->control('publishing_categories._ids', ['options' => $publishingCategories, 'class' => 'form-control kt-select2-general',]);
                        echo $this->Form->control('volunteering_categories._ids', ['class' => 'form-control kt-select2-general',]);
                        echo $this->Form->control('tag_string', ['class' => 'form-control kt-select2-tag', 'type' => 'text', 'label' => __('Tags (Max: 5)')]);
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
<?= $this->Html->script('tagify.min.js', ['block' => 'script']) ?>
<?= $this->Html->script('tinymce/tinymce.min.js', ['block' => 'script']) ?>
<?= $this->Html->script('fileinput/fileinput.js', ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<?= $this->element('translation-validation') ?>
<script>
    var input = document.querySelector('.kt-select2-tag');
    new Tagify(input, {
      maxTags: 5,
      originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(',')
    })
    $(document).ready(function () {
        tinymce.init({
            selector: 'textarea.tinyM',
            height: 500,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | ' +
            ' bold italic backcolor | alignleft aligncenter ' +
            ' alignright alignjustify | bullist numlist outdent indent |' +
            ' removeformat',
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tiny.cloud/css/codepen.min.css'
            ],
            setup: function (editor) {
                editor.on('change', function (e) {
                    editor.save();
                });
            }
        });

        $(".fileinput").fileinput({
            browseClass: "btn btn-primary btn-block",
            showCaption: false,
            showRemove: false,
            showUpload: false
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
                        tinymce.get($("#"+result.lang+" .w-content").attr('id')).setContent(result.data[1].text)
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

        // $(".kt-select2-tag").select2({
        //   tags: true,
        //   placeholder: 'Tags',
        //   tokenSeparators: [',', ' ']
        // });
    });
</script>

<?php $this->end(); ?>
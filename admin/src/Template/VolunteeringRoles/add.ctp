<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\VolunteeringRole $volunteeringRole
 */
?>
<div id="alert"></div>
            <!-- begin:: Subheader -->
            <div class="kt-subheader   kt-grid__item" id="kt_subheader">
              <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                  <h3 class="kt-subheader__title"><?= __('Add Volunteering Role') ?></h3>
                  <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                  <div class="kt-subheader__wrapper">
                    <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-icon- btn btn-label btn-label-brand btn-upper btn-font-sm btn-bold">
                    <?= __('Back to Volunteering Roles') ?></a>
                  </div>
                </div>
                <div class="kt-subheader__toolbar">
                  <!-- <div class="kt-subheader__wrapper"> <a href="#" class="btn btn-icon- btn btn-label btn-label-brand btn-upper btn-font-sm btn-bold"> <i class="flaticon2-add-1"></i> New Blog Post</a> </div> -->
                </div>
              </div>
            </div>
            <!-- end:: Subheader -->

            <!--begin::Dashboard 4-->
            <div class="kt-portlet kt-portlet--tabs">
                <div class="kt-portlet__head">
                  <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title"><?= __('Volunteering Role Texts') ?> </h3>
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
                <?= $this->Form->create($volunteeringRole, ['class' => 'bs-validate']) ?>
                <div class="kt-portlet__body">
                  <div class="tab-content">
                    <div class="tab-pane active" id="en" role="tabpanel">
                    <?php
                        echo $this->Form->control('name', ['class' => 'w-name form-control tr-input', 'placeholder' => 'Name']);
                    ?>
                    </div>
                    <div class="tab-pane fade" id="fr" role="tabpanel">
                    <?php
                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.fr.locale') .'.name', ['class' => 'w-name form-control tr-input', 'placeholder' => 'Name']);
                    ?>
                    </div>
                    <div class="tab-pane fade" id="pt" role="tabpanel">
                    <?php
                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.pt.locale') .'.name', ['class' => 'w-name form-control tr-input', 'placeholder' => 'Name']);
                    ?>
                    </div>
                    <div class="tab-pane fade" id="ar" role="tabpanel">
                    <?php
                        echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.ar.locale') .'.name', ['class' => 'w-name form-control tr-input', 'placeholder' => 'Name']);
                    ?>
                    </div>
                  </div>
                  <?php
                    echo $this->Form->control('status');
                  ?>
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
                <?= $this->Form->end() ?>
              </div>
              
              <!--end::Dashboard 4-->

<?php $this->start('scriptBlock') ?>
<?= $this->element('translation-validation') ?>
<script>
    $(document).ready(function () {
        $(".translate").click(function (e) {
            e.preventDefault();
            let trBtn = $(this);
            let btnGrp = $(this).closest('.nav-item').find('.auto-btn');
            btnGrp.addClass('kt-spinner kt-spinner--sm kt-spinner--right kt-spinner--light');

            srcLang = trBtn.data('lang');
            console.log('Translating...', srcLang);

            let srcTexts = {}
            let transData = []

            srcTexts[0] = $("#"+srcLang+" .w-name").val();

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
                        $("#"+result.lang+" .w-name").val(result.data[0].text);
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
    });
</script>
<?php $this->end(); ?>

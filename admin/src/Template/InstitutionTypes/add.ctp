<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InstitutionType $institutionType
 */
?>

            <!-- begin:: Subheader -->
            <div class="kt-subheader   kt-grid__item" id="kt_subheader">
              <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                  <h3 class="kt-subheader__title"><?= __('Add Institution Type') ?></h3>
                  <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                  <div class="kt-subheader__wrapper">
                    <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-icon- btn btn-label btn-label-brand btn-upper btn-font-sm btn-bold">
                    <?= __('Back to Institution Types') ?></a>
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
                    <h3 class="kt-portlet__head-title"><?= __('Institution Type Texts') ?> </h3>
                  </div>
                  <div class="kt-portlet__head-toolbar">
                    <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-right nav-tabs-line-success nav-tabs-bold" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#en" role="tab">English</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#fr" role="tab">Français</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#pt" role="tab">Português</a>
                      </li>
                    </ul>
                  </div>
                </div>
                <?= $this->Form->create($institutionType) ?>
                <div class="kt-portlet__body">
                  <div class="tab-content">
                    <div class="tab-pane active" id="en" role="tabpanel">
                    <?php
                        echo $this->Form->control('name', ['class' => 'w-name form-control', 'placeholder' => 'Name']);
                    ?>
                    </div>
                    <div class="tab-pane fade" id="fr" role="tabpanel">
                    <?php
                        echo $this->Form->control('_translations.fr_FR.name', ['class' => 'w-name form-control', 'placeholder' => 'Name']);
                    ?>
                    </div>
                    <div class="tab-pane fade" id="pt" role="tabpanel">
                    <?php
                        echo $this->Form->control('_translations.pt_PT.name', ['class' => 'w-name form-control', 'placeholder' => 'Name']);
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

<script>
    $(document).ready(function () {
        $(".translate").click(function () {
            let trBtn = $(this);
            console.log('Translating...');
            $(this).html('Translating...');
            srcLang = trBtn.data('lang');
            let langs = ['en', 'fr', 'pt'];
            let srcTexts = {}
            let transData = {}

            srcTexts[0] = $("#"+srcLang+" .w-name").val();

            langs.forEach(lang => {
                if (lang !== srcLang) {
                    let texts = {}
                    $.ajax({
                        type: "POST",
                        url: "<?= $this->Url->build('/translate') ?>",
                        data: {'sourceTexts': srcTexts, 'targetLanguage': lang},
                        success: function (data) {
                            $("#"+lang+" .w-name").val(data[0].text);
                            trBtn.html('Translate');
                        },
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                        }
                    });
                }
            })

        })
    });
</script>


<div id="alert"></div>
<div class="container">
    <div class="d-flex justify-content-between top-line align-items-center">
        <h3><?= __('Edit Event') ?></h3>
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
    <?= $this->Form->create($event, ['type' => 'file', 'class' => 'bs-validate']) ?>
    <div class="tab-content">
        <div id="en" class="tab-pane active basic-info">
            <div class="page-title">
                <h3>Basic Info</h3>
            </div>
            <div class="form-text">
                <?php
                    echo $this->Form->control('title', ['placeholder' => 'Title', 'class' => 'w-title form-control tr-input']);
                    echo $this->Form->control('description', ['placeholder' => 'Title', 'class' => 'w-description form-control tr-input']);
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
                    echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.fr.locale') .'.description', ['placeholder' => 'Title', 'class' => 'w-description form-control tr-input']);
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
                    echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.pt.locale') .'.description', ['placeholder' => 'Title', 'class' => 'w-description form-control tr-input']);
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
                    echo $this->Form->control('_translations.'. \Cake\Core\Configure::read('I18n.languages.ar.locale') .'.description', ['placeholder' => 'Title', 'class' => 'w-description form-control tr-input']);
                ?>
            </div>
        </div>
    </div>

    <div class="other-info">
        <div class="row basic-info">
            <div class="col-md-4">
                <label for="region-id"><?= __('Region') ?></label>
                <?= $this->Form->control('region_id', ['empty' => __('All Region'), 'label' => false]) ?>
            </div>
            <div class="col-md-4">
                <label for="country-id"><?= __('Country') ?></label>
                <?= $this->Form->control('country_id', ['empty' => __('Select Country'), 'label' => false, 'value' => $organization->country_id]) ?>
            </div>
            <div class="col-md-4">
                <label for="city-id"><?= __('City') ?></label>
                <?= $this->Form->control('city_id', ['empty' => __('Select City'), 'label' => false]) ?>
            </div>
            <div class="col-md-12">
                <?= $this->Form->control('address', ['placeholder' => __('Address')]) ?>
                <?= $this->Form->hidden('lat', ['id' => 'lat']) ?>
                <?= $this->Form->hidden('lng', ['id' => 'lng']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="upload-img">
                    <label class="newbtn">
                        <img id="blah" src="<?= $event->image !== null ? $event->image : $this->Url->image('upload.svg') ?>">
                        <?= $this->Form->control('file', ['type' => 'file', 'onchange' => 'readURL(this);', 'id' => 'pic', 'class' => 'pis', 'label' => false, 'accept' => 'image/*']) ?>
                    </label>
                </div>
            </div>
            <div class="col-md-6">
                <label for="start-date"><?= __('Start Date') ?></label>
                <?= $this->Form->control('start_date', ['label' => false, 'type' => 'date']) ?>
                
                <label for="end-date"><?= __('End Date') ?></label>
                <?= $this->Form->control('end_date', ['label' => false, 'type' => 'date']) ?>
                
                <label for="end-date"><?= __('Volunteering Categories') ?></label>
                <?= $this->Form->control('volunteering_categories._ids', ['label' => false, 'options' => $volunteering_categories, 'class' => 'form-control select2',]) ?>

                <?= $this->Form->control('requesting_volunteers', ['type' => 'checkbox']); ?>
            </div>
        </div>
        <div class="d-flex">
            <button type="submit" class="btn ml-auto"><?= __('Save') ?></button>
        </div>
    </div>
    <?= $this->Form->end() ?>

    <div class="form-text">
        <div class="d-flex justify-content-between top-line align-items-center mt-3">
            <h5><?= __('Volunteering roles') ?></h5>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <?= $this->Form->control('has_remunerations', ['type' => 'checkbox']); ?>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th> <?= __('Role') ?> </th>
                    <th> <?= __('Numbers needed') ?> </th>
                    <th> <?= __('Volunteering Categories') ?> </th>
                    <th> <?= __('Actions') ?> </th>
                </tr>
            </thead>

            <tbody>
                <?php $c=0; foreach ($event->volunteering_oppurtunities as $oppurtunity): ?>
                <tr>
                    <td><?= h($oppurtunity->volunteering_role->name) ?> </td>
                    <td><?= h($oppurtunity->number) ?> </td>
                <td><?php foreach ($oppurtunity->volunteering_categories as $category) { ?> <span class="badge badge-secondary"> <?= h($category->name) ?> </span> <?php } ?> </td>
                    <td>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="#" id="add-vr" class="btn-default btn-sm ml-auto" onClick="return false" data-toggle="modal" data-target="#Modal-<?= $c ?>"><i class="fa fa-edit"></i> <?= __('edit') ?></a>
                        <!-- Modal -->
                        <div class="modal fade" id="Modal-<?= $c ?>" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel"><?= __('Edit Oppurtunity') ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <?= $this->Form->create($oppurtunity, ['url' => ['action' => 'editEventOppurtunity', 'id' => $organization->id]]) ?>
                                <div class="modal-body">
                                    <div class="form-group other-info">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for=""><?= __('Volunteering Role') ?></label>
                                                <?= $this->Form->control('volunteering_role_id', ['empty' => __('Select'), 'label' => false, 'options' => $volunteering_roles, 'class' => 'form-control select2', 'id' => 'volunteering-role-input-'.$c]) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <label for=""><?= __('Number Volunteers Needed') ?></label>
                                                <?= $this->Form->control('number', ['label' => false]) ?>
                                            </div>
                                            <div class="col-md-12">
                                                <label for=""><?= __('Volunteering Categories') ?></label>
                                                <?= $this->Form->control('volunteering_categories._ids', ['label' => false, 'options' => $volunteering_categories, 'class' => 'form-control select2', 'id' => 'volunteering-categories-input-'.$c]) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-secondary"><?= __('Save Changes') ?></button>
                                </div>
                                <?= $this->Form->end() ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    </td>
                </tr>
                <?php $c++; endforeach; ?>
            </tbody>
        </table>
        
        <div class="d-flex justify-content-between top-line align-items-center">
            <a href="#" id="add-vr" class="btn-default btn-sm ml-auto" onClick="return false" data-toggle="modal" data-target="#ModalAdd"><i class="fa fa-plus"></i> <?= __('Add more') ?></a>
            <!-- Modal -->
            <div class="modal fade" id="ModalAdd" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel"><?= __('Edit Oppurtunity') ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?= $this->Form->create(false, ['url' => ['action' => 'addEventOppurtunity', 'id' => $organization->id]]) ?>
                    <div class="modal-body">
                        <div class="form-text">
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $this->Form->hidden('event_id', ['value' => $event->id]) ?>
                                    <label for=""><?= __('Volunteering Role') ?></label>
                                    <?= $this->Form->control('volunteering_role_id', ['empty' => __('Select Role'), 'label' => false, 'options' => $volunteering_roles, 'class' => 'form-control select2', 'id' => 'volunteering-role-input-'.$c]) ?>
                                </div>
                                <div class="col-md-6">
                                    <label for=""><?= __('Number Volunteers Needed') ?></label>
                                    <?= $this->Form->control('number', ['label' => false]) ?>
                                </div>
                                <div class="col-md-12">
                                    <label for=""><?= __('Volunteering Categories') ?></label>
                                    <?= $this->Form->control('volunteering_categories._ids', ['placeholder' => __('Select Categories'), 'label' => false, 'options' => $volunteering_categories, 'class' => 'form-control select2', 'id' => 'volunteering-categories-input-'.$c]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-secondary"><?= __('Save Changes') ?></button>
                    </div>
                    <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<?php $this->Html->script("https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js", ['block' => 'script']) ?>
<?php $this->Html->script("https://maps.googleapis.com/maps/api/js?key=AIzaSyBQzkAnV6V7naTqRsuMkfGENsBjpaFSUt4&libraries=places", ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<?= $this->element('address-autocomplete') ?>
<?= $this->element('translation-validation') ?>
<script>
    $(document).ready(function () {
        $("#volunteering-categories-ids").select2()
        $(".select2").select2()

        let initialCountries = $("#country-id").html();
        let initialCities = $("#city-id").html();
        $("#region-id").change(function () {
            region_id = $(this).val();
            if(region_id && region_id !== '') {
                $("#country-id").html('<option> ... </option>')
                $("#city-id").html('')
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

        $("#country-id").change(function () {
            country_id = $(this).val();
            if(country_id && country_id !== '') {
                $("#city-id").html('<option> ... </option>')
                let options = '';
                $.ajax({
                    type: "POST",
                    url: "<?= $this->Url->build('/country-city-list') ?>"+ '/' +country_id,
                    success: function (data) {
                        for (k in data) {
                            options += `<option value="${k}"> ${data[k]} </option>`;
                        };
                    },
                    complete: function (xhr, result) {
                        $("#city-id").html(options)
                    },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                    }
                });
            } else {
                $("#city-id").html('');
            }
        })

        $("#organization-offices-country-id").change(function () {
            country_id = $(this).val();
            if(country_id && country_id !== '') {
                $("#organization-offices-city-id").html('<option> ... </option>')
                let options = '';
                $.ajax({
                    type: "POST",
                    url: "<?= $this->Url->build('/country-city-list') ?>"+ '/' +country_id,
                    success: function (data) {
                        for (k in data) {
                            options += `<option value="${k}"> ${data[k]} </option>`;
                        };
                    },
                    complete: function (xhr, result) {
                        $("#organization-offices-city-id").html(options)
                    },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                    }
                });
            } else {
                $("#city-id").html('');
            }
        })

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
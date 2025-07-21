<?php $this->layout = 'home'; ?>
<div class="main">
    <div class="container">
        <?= $this->Flash->render() ?>
        <?php if ($type === null): ?>
            <div class="card login-card reg-options text-center">
                <div class="d-flex justify-content-around">
                    <a href="<?= $this->Url->build(['youth']) ?>" class="card text-center">
                    <div class="card-body">
                        <img src="<?= $this->Url->image('user-icon.svg') ?>" alt="" class="svg">
                        <h4 class="card-title"><?= __('Volunteer') ?></h4>
                        <p class="card-text">
                        <?= __('We choose to focus on the individual child and the challenges they may face.') ?>
                        </p>
                    </div>
                    </a>
                    <a href="<?= $this->Url->build(['organization']) ?>" class="card text-center">
                    <div class="card-body">
                        <img src="<?= $this->Url->image('org.svg') ?>" alt="" class="svg">
                        <h4 class="card-title"><?= __('Organization') ?></h4>
                        <p class="card-text">
                        <?= __('We choose to focus on the individual child and the challenges they may face.') ?>
                        </p>
                    </div>
                    </a>
                </div>
                <p class="login-text"><?= __('Already have an account?') ?> <a href="<?= $this->Url->build(['action' => 'login']) ?>"><?= __('Login') ?></a></p>
            </div>
        <?php else: ?>
            <!--  -->
            <div class="card login-card org-reg">
                <?php switch ($type) { 
                    case 'success': ?>
                        <div class="row no-gutters">
                            <div class="col-md-6 img-container">
                                <div class="d-flex align-items-center">
                                    <div class="main-text align-self-center">
                                    <h1 class="wow slideInRight" data-wow-duration="1s" data-wow-delay="0.2s"><?= __('Organizations and volunteers working together for peace and development') ?></h1>
                                    <p class="long-text wow slideInRight" data-wow-duration="1s" data-wow-delay="0.3s"><?= __('The African Union-VLP contributes to peace and development through volunteerism across the Africa continent.') ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="card-body email-sent">
                                    <img src="<?= $this->Url->image('email-sent.svg')?>" alt="">
                                    <h3 style="text-align: left;"><?= __('Verify Email') ?></h3>
                                    <p><?= __('We sent you an email containing verification instructions. After verification, kindly log in and complete your organisation’s profile.')?></p>
                                    <!-- <a href="https://www.surveymonkey.com/r/TVFS9QP" class="btn btn-small mr-3"><?= __('Proceed to VLP Survey') ?></a> -->
                                    <a href="<?= $this->Url->build(['action' => 'login']) ?>" class="btn btn-small mr-3"><?= __('Login') ?></a>
                                </div>
                            </div>
                        </div>
                <?php break;
                    case 'organization': ?>
                        <h3><?= __('Organization Registration') ?></h3>
                        <?php switch ($level) {
                            case 'user-details': ?>
                            <div class="stepwizard">
                                <div class="stepwizard-row setup-panel">
                                    <div class="stepwizard-step">
                                        <a href="#step-1" type="button" class="btn btn-default btn-circle" disabled="disabled">1</a>
                                        <p><?= __('Step 1') ?></p>
                                    </div>
                                    <div class="stepwizard-step">
                                        <a href="#step-2" type="button" class="btn btn-primary btn-circle">2</a>
                                        <p><?= __('Step 2') ?></p>
                                    </div>
                                </div>
                            </div>

                            <?= $this->Form->create($user, ['url' => [$type, $level], 'type' => 'file']) ?>
                            <div class="row align-items-stretch">
                                <div class="col-lg-6">
                                    <div class="d-flex align-items-center">
                                        <div class="card-body basic-info">
                                            <h5 class="card-title"><?= __('Create an account') ?></h5>
                                            <div class="form-group">
                                                <?php
                                                    echo $this->Form->control('first_name', ['placeholder' => __('First name'), 'required' => true]);
                                                    echo $this->Form->control('last_name', ['placeholder' => __('Last name'), 'required' => true]);
                                                    echo $this->Form->control('email', ['placeholder' => __('Email address'), 'required' => true]);
                                                    echo $this->Form->control('password', ['placeholder' => __('Password'), 'required' => true]);
                                                    echo $this->Form->control('confirm_password', ['placeholder' => __('Confirm password'), 'type' => 'password', 'required' => true]);
                                                    echo $this->Form->control('Gender', ['type' => 'select', 'options' => ['Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other'], 'empty' => __('Select Gender'), 'required' => true]);
                                                    echo $this->Form->control('Date_of_Birth', ['type' => 'date', 'maxYear' => date('Y'), 'minYear' => date('Y')-100, 'required' => true]);
                                                ?>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label><?= __('Preferred language') ?></label>
                                                    <?= $this->Form->control('preferred_language', ['options' => $languages, 'label' => false, 'type' => 'radio', 'class' => 'mr-1', 'required' => true]) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6 d-flex align-items-center">
                                    <div class="card-body basic-info">
                                        <h5 class="card-title"><?= __('What is your interest on the Continental Platform') ?></h5>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <?php
                                                    echo $this->Form->control('platform_interests._ids', ['label' => false, 'multiple' => 'checkbox', 'value' => [3], 'required' => true]);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="reg-btn d-flex align-items-center justify-content-center">
                                <button href="submit" class="btn"><?= __('Submit') ?></button>
                                <a href="<?= $this->Url->build(['organization','upload-document']) ?>" class="btn btn-small"><?= __('Back') ?></a>
                                <p><?= __('Already have an account?') ?> <br> <a href="<?= $this->Url->build(['action' => 'login']) ?>"><?= __('Login') ?></a></p>
                            </div>
                            <?= $this->Form->end() ?>
                        <?php break;
                            case 'upload-document': ?>
                            <h5><?= __('Upload your registration form') ?></h5>
                            <?= $this->Form->create($user, ['url' => [$type, $level], 'type' => 'file']) ?>
                            <div class="form-group">
                                <?= $this->Form->control('organizations.0.file', ['type' => 'file', 'label' => __('Upload your registration form'), 'required' => true, 'class' => 'fileinput']) ?>
                            </div>
                            <div class="form-group">
                                <?= $this->Form->control('organizations.0.category_id', ['label' => __('Organization Sector'), 'options' => $organizationTypes, 'empty' => __('Select sector'), 'required' => true]) ?>
                            </div>
                            <div class="reg-btn d-flex align-items-center justify-content-center">
                                <button href="submit" class="btn"><?= __('Submit') ?></button>
                                <a href="<?= $this->Url->build(['organization','user-details']) ?>" class="btn btn-small"><?= __('Back') ?></a>
                            </div>
                            <?= $this->Form->end() ?>
                        <?php break;
                            default: ?>
                            <div class="stepwizard">
                                <div class="stepwizard-row setup-panel">
                                    <div class="stepwizard-step">
                                        <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
                                        <p><?= __('Step 1') ?></p>
                                    </div>
                                    <div class="stepwizard-step">
                                        <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
                                        <p><?= __('Step 2') ?></p>
                                    </div>
                                </div>
                            </div>

                            <?= $this->Form->create($user, ['url' => [$type, $level], 'type' => 'file']) ?>
                            <div class="row align-items-stretch">
                                <div class="col-lg-6">
                                    <div class="d-flex align-items-center">
                                        <div class="card-body basic-info">
                                            <h5 class="card-title"><?= __('Organization details') ?></h5>
                                            <div class="form-group">
                                                <?php
                                                    echo $this->Form->control('organizations.0.name', ['placeholder' => __('Organization name'), 'required' => true]);
                                                    echo $this->Form->control('organizations.0.about', ['placeholder' => __('Short description'), 'rows' => 3, 'required' => true]);
                                                    echo $this->Form->control('organizations.0.address', ['placeholder' => __('Address'), 'autocomplete' => 'off', 'id' => 'address', 'required' => true]);
                                                    echo $this->Form->hidden('organizations.0.lat', ['id' => 'lat']);
                                                    echo $this->Form->hidden('organizations.0.lng', ['id' => 'lng']);
                                                    echo $this->Form->control('organizations.0.country_id', ['empty' => __('Select country'), 'required' => true, 'id' => 'country-id', 'label' => false]);
                                                    echo $this->Form->control('organizations.0.city_id', ['empty' => __('Select city'), 'required' => true, 'id' => 'city-id', 'label' => false]);
                                                    echo $this->Form->control('organizations.0.phone_number', ['placeholder' => __('Phone Number'), 'required' => true]);
                                                    echo $this->Form->control('organizations.0.website', ['placeholder' => __('Website')]);
                                                    echo $this->Form->control('organizations.0.Organisation_sector',['label' => __('Organization Sector'), 'options' => $organizationTypes, 'empty' => __('Select sector'), 'required' => true]);
                                                    echo $this->Form->control('organizations.0.Registration_Form',['label' => __('Upload your Registration form'), 'type' => 'file', 'required' => true, 'class' => 'fileinput']);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="col-lg-6 d-flex align-items-center">
                                    <div class="card-body basic-info">
                                        <h5 class="card-title"><?= __('Type of Organization') ?></h5>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <?php
                                                    echo $this->Form->control('organizations.0.organization_type_id', ['label' => false, 'type' => 'radio', 'class' => 'mr-1', 'required' => true]);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <h5 class="card-title"><?= __('Date of establishment') ?></h5>
                                            <?= $this->Form->control('organizations.0.date_of_establishment', ['label' => false, 'maxYear' => date('Y'), 'minYear' => date('Y')-100, 'required' => true]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="reg-btn d-flex align-items-center justify-content-center">
                                <button href="submit" class="btn"><?= __('Next') ?></button>
                                <p><?= __('Already have an account?') ?> <br> <a href="<?= $this->Url->build(['action' => 'login']) ?>"><?= __('Login') ?></a></p></p>
                            </div>
                            <?= $this->Form->end() ?>
                            <?php break;
                        }
                        ?>
                                    
                    <?php break;
                    default: ?>
                        <h3><?= __('Volunteer Registration') ?></h3>
                        <div class="stepwizard">
                            <div class="stepwizard-row setup-panel">
                                <div class="stepwizard-step">
                                    <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
                                    <p><?= __('Step 1') ?></p>
                                </div>
                                <!-- <div class="stepwizard-step">
                                    <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
                                    <p><?= __('Step 2') ?></p>
                                </div> -->
                            </div>
                        </div>
                        <?= $this->Form->create($user) ?>
                        <div class="row align-items-stretch">
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center">
                                    <div class="card-body basic-info">
                                        <h5 class="card-title"><?= __('Create an account') ?></h5>
                                        <div class="form-group">
                                            <?php
                                                echo $this->Form->control('first_name', ['placeholder' => __('First name'), 'required' => true]);
                                                echo $this->Form->control('last_name', ['placeholder' => __('Last name'), 'required' => true]);
                                                echo $this->Form->control('email', ['placeholder' => __('Email address'), 'required' => true]);
                                                echo $this->Form->control('password', ['placeholder' => __('Password'), 'required' => true]);
                                                echo $this->Form->control('confirm_password', ['placeholder' => __('Confirm password'), 'type' => 'password', 'required' => true]);
                                                echo $this->Form->control('Gender', ['type' => 'select', 'options' => ['Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other'], 'empty' => __('Select Gender'), 'required' => true]);
                                                echo $this->Form->control('Date_of_Birth', ['type' => 'date', 'maxYear' => date('Y'), 'minYear' => date('Y')-100, 'required' => true]);
                                            ?>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label><?= __('Preferred language') ?></label>
                                                <?= $this->Form->control('preferred_language', ['options' => $languages, 'label' => false, 'type' => 'radio', 'class' => 'mr-1', 'required' => true]) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-6 d-flex align-items-center">
                                <div class="card-body basic-info">
                                    <h5 class="card-title"><?= __('What is your interest on the Continental Platform') ?></h5>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <?php
                                                echo $this->Form->control('platform_interests._ids', ['label' => false, 'multiple' => 'checkbox', 'required' => true]);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="reg-btn d-flex align-items-center justify-content-center">
                            <button href="submit" class="btn"><?= __('Register') ?></button>
                            <p><?= __('Already have an account?') ?> <br> <a href="<?= $this->Url->build(['action' => 'login']) ?>"><?= __('Login') ?></a></p>
                        </div>
                        <?= $this->Form->end() ?>
                <?php break; 
                    } 
                    ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<?php $this->Html->script("https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js", ['block' => 'script']) ?>
<?php $this->Html->script("https://maps.googleapis.com/maps/api/js?key=AIzaSyBQzkAnV6V7naTqRsuMkfGENsBjpaFSUt4&libraries=places", ['block' => 'script']) ?>

<?= $this->Html->css('fileinput/fileinput.min.css', ['block' => 'css']) ?>
<?= $this->Html->css('Users/create_account_custom.css', ['block' => 'css']) ?>
<?= $this->Html->script('fileinput/fileinput.js', ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<?= $this->element('address-autocomplete') ?>
<script>
    $(document).ready(function () {
        $(".fileinput").fileinput({
            theme: "fa",
            browseClass: "btn btn-primary btn-block",
            showCaption: false,
            showRemove: false,
            showUpload: false,
        });

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
    });
</script>

<?php $this->end(); ?>

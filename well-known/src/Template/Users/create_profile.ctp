<?php $this->layout = 'home'; ?>
<div class="main">
    <div class="container">
        <?= $this->Flash->render() ?>
        <!--  -->
        <?php if ($level === 'success'): ?>
            <div class="card login-card">
                    <div class="row no-gutters">
                        <div class="col-lg-6 img-container">
                            <div class="d-flex align-items-center">
                                <div class="main-text align-self-center">
                                <h1 class="wow slideInRight" data-wow-duration="1s" data-wow-delay="0.2s"><?= __('Organizations and volunteers working together for peace and development') ?></h1>
                                <p class="long-text wow slideInRight" data-wow-duration="1s" data-wow-delay="0.3s"><?= __('The African Union-VLP contributes to peace and development through volunteerism across the Africa continent.') ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex align-items-center">
                            <div class="card-body email-sent">
                                <img src="<?= $this->Url->image('email-sent.svg')?>" alt="">
                                <h3><?= __('Verify Email') ?></h3>
                                <p><?= ('We sent you an email containing verification instruction. Please follow the instructions to verify your email.') ?></p>
                                <a href="<?= $this->Url->build(['action' => 'login']) ?>" class="btn btn-small mr-3"><?= __('Login') ?></a>
                            </div>
                        </div>
                    </div>
            </div>
            <?php else: ?>
                <div class="card login-card org-reg">    
                    <h3><?= __('User Registration') ?></h3>
                    <?= $this->Form->create($user) ?>
                    <div class="row align-items-stretch">
                        <div class="col-lg-6">
                            <div class="d-flex align-items-center">
                                <div class="card-body basic-info">
                                    <h5 class="card-title"><?= __('Create Profile') ?></h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label> <?= __('Current Nationality') ?> </label>
                                            <?= $this->Form->control('current_nationality', ['options' => $countries, 'label' => false, 'required' => true]) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label><?= __('Gender') ?></label>
                                            <?= $this->Form->control('gender', ['options' => ['Male' => __('Male'), 'Female' => __('Female')], 'label' => false, 'type' => 'radio', 'required' => true]) ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->control('short_profile', ['placeholder' => __('Short Profile'), 'rows' => 3, 'maxlength' => 255, 'required' => true]);
                                            echo $this->Form->control('has_volunteering_experience', ['label' => __('Do you have volunteering experience')]);
                                        ?>
                                        <h6><?= __('Which event did you volunteer with') ?></h6>
                                        <?php
                                            echo $this->Form->control('volunteered_program', ['placeholder' => __('Event name'), 'label' => __('Event name')]);
                                        ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label><?= __('Year of service') ?></label>
                                            <?= $this->Form->control('year_of_service', ['type' => 'year', 'empty' => __('Select year'), 'label' => false]) ?>
                                        </div>
                                        <div class="col-md-12">
                                            <label><?= __('Country Served in') ?></label>
                                            <?= $this->Form->control('country_served_in', ['options' => $countries, 'label' => false]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="card-body basic-info">
                                <h5 class="card-title"><?= __('Volunteering Interest') ?></h5>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->control('volunteering_categories._ids', ['label' => false, 'multiple' => 'checkbox']);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="reg-btn d-flex align-items-center justify-content-center">
                        <button href="submit" class="btn"><?= __('Submit') ?></button>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
        <?php endif; ?>
        
    </div>
</div>


<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
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

        $("#volunteered-program").attr('disabled', true);
        $("#year-of-service").attr('disabled', true);
        $("#country-served-in").attr('disabled', true);

        $("#has-volunteering-experience").change(function () {
            if ($(this).is(":checked")) {
                $("#volunteered-program").attr('disabled', false);
                $("#year-of-service").attr('disabled', false);
                $("#country-served-in").attr('disabled', false);
            } else {
                $("#volunteered-program").attr('disabled', true);
                $("#year-of-service").attr('disabled', true);
                $("#country-served-in").attr('disabled', true);
            }
        });
    });
</script>

<?php $this->end(); ?>
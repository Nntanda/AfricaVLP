<div class="container org-profile">
    <div class="d-flex justify-content-between top-line align-items-center">
        <h3><?= __('Edit Organization') ?></h3>
    </div>
    <?= $this->Form->create($organization, ['type' => 'file']) ?>
    <div class="basic-info">
        <div class="row other-info">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-6 col-sm-4">
                        <div class="img-container">
                            <a href="#" class="btn py-1 px-2 m-1" data-toggle="modal" data-target="#imageModal" style="position: absolute; top:auto"><?= __('Edit') ?></a>
                            <img src="<?= (!empty($organization->logo) && $organization->logo !== null) ? $organization->logo : $this->Url->image('no-logo.jpg') ?>" alt="" class="img-thumbnail rounded mb-1">
                            <!-- Modal -->
                            <div class="modal fade" id="imageModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel"><?= __('Edit Logo') ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group other-info basic-info">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div id="upload-image"></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="upload-img">
                                                            <label class="newbtn">
                                                                <?= $this->Form->control('file', ['type' => 'file', 'id' => 'pic', 'class' => 'pis', 'label' => false, 'accept' => 'image/*']) ?>
                                                                <small class="font-weight-lighter"> <i class="fa fa-edit"></i> <?= __('Select image') ?></small>
                                                            </label>
                                                        </div>
                                                        <div id="img-help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary crop_image"><?= __('Upload') ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-text">
            <?php
            echo $this->Form->control('name');
            echo $this->Form->control('about');
            ?>
        </div>
    </div>
    <div class="other-info">
        <div class="row">
            <div class="col-md-12">
                <label for=""><?= __('Volunteering Categories') ?></label>
                <?= $this->Form->control('volunteering_categories._ids', ['label' => false, 'options' => $volunteering_categories]) ?>
            </div>
        </div>

        <div class="card office basic-info">
            <div class="card-header">
                <?= __('Contact Details') ?>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="country-id"><?= __('Country') ?></label>
                        <?= $this->Form->control('country_id', ['label' => false]) ?>
                    </div>
                    <div class="col-md-6">
                        <label for="city-id"><?= __('City') ?></label>
                        <?= $this->Form->control('city_id', ['label' => false]) ?>
                    </div>
                </div>
                <?= $this->Form->control('address', ['placeholder' => __('Address'), 'autocomplete' => 'off']) ?>
                <?= $this->Form->hidden('lat', ['id' => 'lat']) ?>
                <?= $this->Form->hidden('lng', ['id' => 'lng']) ?>
                <div class="row">
                    <div class="col-md-6">
                        <?= $this->Form->control('email', ['placeholder' => __('Email')]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('phone_number', ['placeholder' => __('Phone Number')]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card office basic-info ">
            <div class="card-header">
                <?= __('Contact Links') ?>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $this->Form->control('website', ['placeholder' => __('Website')]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('facebook_url', ['placeholder' => __('Facebook Url')]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('instagram_url', ['placeholder' => __('Instagram Url')]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('twitter_url', ['placeholder' => __('Twitter Url')]) ?>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <label for=""><?= __('Areas of Engagement') ?></label>
        <div class="card office basic-info">
            <div class="card-header">
                <?= __('Is your organization/program engaging volunteers in working towards:') ?>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <label for="pan_africanism"><?= __('* Pan Africanism, Continental free trade area, Global partnerships') ?></label>
                        <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                $attributes = array('legend' => 'False');
                                echo $this->Form->radio('type', $options, $attributes);
                                ?> -->
                        <input type="radio" name="pan_africanism" value="Yes" <?php echo ($organization->pan_africanism == 'Yes') ?  "checked" : "";  ?> onChange="getPanValue(this)" required> Yes<br>
                        <input type="radio" name="pan_africanism" value="No" <?php echo ($organization->pan_africanism == 'No') ?  "checked" : "";  ?> onChange="getPanValue(this)"> No<br><br>
                    </div>
                    <div class="col-md-6">
                        <label for="education_skills"><?= __('* Education, skills revolution; Science, Technology & Innovation') ?></label><br>
                        <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                $attributes = array('legend' => 'False');
                                echo $this->Form->radio('type', $options, $attributes);
                                ?> -->
                        <input type="radio" name="education_skills" value="Yes" <?php echo ($organization->education_skills == 'Yes') ?  "checked" : "";  ?> onChange="getEduValue(this)" required> Yes<br>
                        <input type="radio" name="education_skills" value="No" <?php echo ($organization->education_skills == 'No') ?  "checked" : "";  ?> onChange="getEduValue(this)"> No<br>
                    </div>
                    <div class="col-md-6">
                        <label for="health_wellbeing"><?= __('* Health and wellbeing of citizens') ?></label><br>
                        <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                $attributes = array('legend' => 'False');
                                echo $this->Form->radio('type', $options, $attributes);
                                ?> -->
                        <input type="radio" name="health_wellbeing" value="Yes" <?php echo ($organization->health_wellbeing == 'Yes') ?  "checked" : "";  ?> onChange="getHealthValue(this)" required> Yes<br>
                        <input type="radio" name="health_wellbeing" value="No" <?php echo ($organization->health_wellbeing == 'No') ?  "checked" : "";  ?> onChange="getHealthValue(this)"> No<br><br>
                    </div>
                    <div class="col-md-6">
                        <label for="no_poverty"><?= __('* No poverty, Decent work, Economic growth & transformation') ?></label><br>
                        <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                $attributes = array('legend' => 'False');
                                echo $this->Form->radio('type', $options, $attributes);
                                ?> -->

                        <input type="radio" name="no_poverty" value="Yes" <?php echo ($organization->no_poverty == 'Yes') ?  "checked" : "";  ?> onChange="getPovertyValue(this)" required> Yes<br>
                        <input type="radio" name="no_poverty" value="No" <?php echo ($organization->no_poverty == 'No') ?  "checked" : "";  ?> onChange="getPovertyValue(this)"> No<br>
                    </div>
                    <div class="col-md-6">
                        <label for="agriculture_rural"><?= __('* Agriculture, Rural development, Blue/Ocean economy') ?></label><br>
                        <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                $attributes = array('legend' => 'False');
                                echo $this->Form->radio('type', $options, $attributes);
                                ?> -->

                        <input type="radio" name="agriculture_rural" value="Yes" <?php echo ($organization->agriculture_rural == 'Yes') ?  "checked" : "";  ?> onChange="getAgricValue(this)" required> Yes<br>
                        <input type="radio" name="agriculture_rural" value="No" <?php echo ($organization->agriculture_rural == 'No') ?  "checked" : "";  ?> onChange="getAgricValue(this)"> No<br><br>
                    </div>
                    <div class="col-md-6">
                        <label for="democratic_values"><?= __('* Democratic values, Human rights, Justice and the Rule of law') ?></label><br>
                        <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                $attributes = array('legend' => 'False');
                                echo $this->Form->radio('type', $options, $attributes);
                                ?> -->

                        <input type="radio" name="democratic_values" value="Yes" <?php echo ($organization->democratic_values == 'Yes') ?  "checked" : "";  ?> onChange="getDemoValue(this)" required> Yes<br>
                        <input type="radio" name="democratic_values" value="No" <?php echo ($organization->democratic_values == 'No') ?  "checked" : "";  ?> onChange="getDemoValue(this)"> No<br>
                    </div>
                    <div class="col-md-6">
                        <label for="environmental_sustainability"><?= __('* Environmental sustainability, Climate change, Water & Sanitation') ?></label><br>
                        <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                $attributes = array('legend' => 'False');
                                echo $this->Form->radio('type', $options, $attributes);
                                ?> -->

                        <input type="radio" name="environmental_sustainability" value="Yes" <?php echo ($organization->environmental_sustainability == 'Yes') ?  "checked" : "";  ?> onChange="getEnvironValue(this)" required> Yes<br>
                        <input type="radio" name="environmental_sustainability" value="No" <?php echo ($organization->environmental_sustainability == 'No') ?  "checked" : "";  ?> onChange="getEnvironValue(this)"> No<br><br>
                    </div>
                    <div class="col-md-6">
                        <label for="infrastructure_development"><?= __('* Infrastructure development, Access to affordable & clean energy, Industrialization') ?></label><br>
                        <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                $attributes = array('legend' => 'False');
                                echo $this->Form->radio('type', $options, $attributes);
                                ?> -->

                        <input type="radio" name="infrastructure_development" value="Yes" <?php echo ($organization->infrastructure_development == 'Yes') ?  "checked" : "";  ?> onChange="getInfraValue(this)" required> Yes<br>
                        <input type="radio" name="infrastructure_development" value="No" <?php echo ($organization->infrastructure_development == 'No') ?  "checked" : "";  ?> onChange="getInfraValue(this)"> No<br>
                    </div>
                    <div class="col-md-6">
                        <label for="peace_security"><?= __('* Peace and Security') ?></label><br>
                        <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                $attributes = array('legend' => 'False');
                                echo $this->Form->radio('type', $options, $attributes);
                                ?> -->

                        <input type="radio" name="peace_security" value="Yes" <?php echo ($organization->peace_security == 'Yes') ?  "checked" : "";  ?> onChange="getPeaceValue(this)" required> Yes<br>
                        <input type="radio" name="peace_security" value="No" <?php echo ($organization->peace_security == 'No') ?  "checked" : "";  ?> onChange="getPeaceValue(this)"> No<br><br>
                    </div>
                    <div class="col-md-6">
                        <label for="culture"><?= __('* Culture') ?></label><br>
                        <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                $attributes = array('legend' => 'False');
                                echo $this->Form->radio('type', $options, $attributes);
                                ?> -->

                        <input type="radio" name="culture" value="Yes" <?php echo ($organization->culture == 'Yes') ?  "checked" : "";  ?> onChange="getCultureValue(this)" required> Yes<br>
                        <input type="radio" name="culture" value="No" <?php echo ($organization->culture == 'No') ?  "checked" : "";  ?> onChange="getCultureValue(this)"> No<br>
                    </div>
                    <div class="col-md-6">
                        <label for="gender_inequality"><?= __('* Gender equality & Women empowerment') ?></label><br>
                        <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                $attributes = array('legend' => 'False');
                                echo $this->Form->radio('type', $options, $attributes);
                                ?> -->

                        <input type="radio" name="gender_inequality" value="Yes" <?php echo ($organization->gender_inequality == 'Yes') ?  "checked" : "";  ?> onChange="getGenValue(this)" required> Yes<br>
                        <input type="radio" name="gender_inequality" value="No" <?php echo ($organization->gender_inequality == 'No') ?  "checked" : "";  ?> onChange="getGenValue(this)"> No<br><br>
                    </div>
                    <div class="col-md-6">
                        <label for="youth_empowerment"><?= __('* Youth Development & Empowerment') ?></label><br>
                        <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                $attributes = array('legend' => 'False');
                                echo $this->Form->radio('type', $options, $attributes);
                                ?> -->

                        <input type="radio" name="youth_empowerment" value="Yes" <?php echo ($organization->youth_empowerment == 'Yes') ?  "checked" : "";  ?> onChange="getYouthValue(this)" required> Yes<br>
                        <input type="radio" name="youth_empowerment" value="No" <?php echo ($organization->youth_empowerment == 'No') ?  "checked" : "";  ?> onChange="getYouthValue(this)"> No<br>
                    </div>
                    <div class="col-md-6">
                        <label for="reduced_inequality"><?= __('* Reduced inequality') ?></label><br>
                        <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                $attributes = array('legend' => 'False');
                                echo $this->Form->radio('type', $options, $attributes);
                                ?> -->

                        <input type="radio" name="reduced_inequality" value="Yes" <?php echo ($organization->reduced_inequality == 'Yes') ?  "checked" : "";  ?> onChange="getRedValue(this)" required> Yes<br>
                        <input type="radio" name="reduced_inequality" value="No" <?php echo ($organization->reduced_inequality == 'No') ?  "checked" : "";  ?> onChange="getRedValue(this)"> No<br><br>
                    </div>
                    <div class="col-md-6">
                        <label for="sustainable_city"><?= __('* Sustainable Cities & Communities') ?></label><br>
                        <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                $attributes = array('legend' => 'False');
                                echo $this->Form->radio('type', $options, $attributes);
                                ?> -->

                        <input type="radio" name="sustainable_city" value="Yes" <?php echo ($organization->sustainable_city == 'Yes') ?  "checked" : "";  ?> onChange="getSusValue(this)" required> Yes<br>
                        <input type="radio" name="sustainable_city" value="No" <?php echo ($organization->sustainable_city == 'No') ?  "checked" : "";  ?> onChange="getSusValue(this)"> No<br>
                    </div>
                    <div class="col-md-6">
                        <label for="responsible_consumption"><?= __('* Responsible Consumption and Production') ?></label><br>
                        <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                $attributes = array('legend' => 'False');
                                echo $this->Form->radio('type', $options, $attributes);
                                ?> -->

                        <input type="radio" name="responsible_consumption" value="Yes" <?php echo ($organization->responsible_consumption == 'Yes') ?  "checked" : "";  ?> onChange="getRespValue(this)" required> Yes<br>
                        <input type="radio" name="responsible_consumption" value="No" <?php echo ($organization->responsible_consumption == 'No') ?  "checked" : "";  ?> onChange="getRespValue(this)"> No<br>
                    </div>

                </div>
            </div>
        </div>

        <div class="card office basic-info">
            <div id="pan_africanism" style="display:none;">
                <div class="card-header">
                    <?= __('Pan Africanism, Continental free trade area, Global partnerships') ?>
                </div>
                <div class="card-body">
                    <h6>Please provide your best estimates to the questions below.</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="pan_africanism"><?= __('* How many male volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="pan_africanism_pan" min="1" max="500" value="<?php echo $organization->pan_africanism_pan; ?>" required /><br><br>
                            </div>
                            <div class="col-md-6">
                                <label for="education_skills"><?= __('* How many female volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="education_skills_pan" min="1" max="500" value="<?php echo $organization->education_skills_pan; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="citizen_health"><?= __('* What percentage of the volunteers above are under the age of 35?') ?></label><br> 0 - 100 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="citizen_health_pan" min="1" max="100" value="<?php echo $organization->citizen_health_pan; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="poverty"><?= __('* On average, how many volunteer hours are contributed towards this work in a month?') ?></label><br> 0 - 10000 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="poverty_pan" min="1" max="10000" value="<?php echo $organization->poverty_pan; ?>" required />
                            </div>
                        </div>
                </div>
            </div>

            <div id="education_skills" style="display:none;">
                <div class="card-header">
                    <?= __('Education, skills revolution; Science, Technology & Innovation') ?>
                </div>
                <div class="card-body">
                    <h6>Please provide your best estimates to the questions below.</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="pan_africanism"><?= __('* How many male volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="pan_africanism_edu" min="1" max="500" value="<?php echo $organization->pan_africanism_edu; ?>" required /><br><br>
                            </div>
                            <div class="col-md-6">
                                <label for="education_skills"><?= __('* How many female volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="education_skills_edu" min="1" max="500" value="<?php echo $organization->education_skills_edu; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="citizen_health"><?= __('* What percentage of the volunteers above are under the age of 35?') ?></label><br> 0 - 100 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="citizen_health_edu" min="1" max="100" value="<?php echo $organization->citizen_health_edu; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="poverty"><?= __('* On average, how many volunteer hours are contributed towards this work in a month?') ?></label><br> 0 - 10000 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="poverty_edu" min="1" max="10000" value="<?php echo $organization->poverty_edu; ?>" required />
                            </div>
                        </div>
                </div>
            </div>

            <div id="health_wellbeing" style="display:none;">
                <div class="card-header">
                    <?= __('Health and wellbeing of citizens') ?>
                </div>
                <div class="card-body">
                    <h6>Please provide your best estimates to the questions below.</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="pan_africanism"><?= __('* How many male volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="pan_africanism_health" min="1" max="500" value="<?php echo $organization->pan_africanism_health; ?>" required /><br><br>
                            </div>
                            <div class="col-md-6">
                                <label for="education_skills"><?= __('* How many female volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="education_skills_health" min="1" max="500" value="<?php echo $organization->education_skills_health; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="citizen_health"><?= __('* What percentage of the volunteers above are under the age of 35?') ?></label><br> 0 - 100 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="citizen_health_health" min="1" max="100" value="<?php echo $organization->citizen_health_health; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="poverty"><?= __('* On average, how many volunteer hours are contributed towards this work in a month?') ?></label><br> 0 - 10000 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="poverty_health" min="1" max="10000" value="<?php echo $organization->poverty_health; ?>" required />
                            </div>
                        </div>
                </div>
            </div>

            <div id="no_poverty" style="display:none;">
                <div class="card-header">
                    <?= __('No Poverty, Decent work, Economic growth & Transformation') ?>
                </div>
                <div class="card-body">
                    <h6>Please provide your best estimates to the questions below.</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="pan_africanism"><?= __('* How many male volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="pan_africanism_nopov" min="1" max="500" value="<?php echo $organization->pan_africanism_nopov; ?>" required /><br><br>
                            </div>
                            <div class="col-md-6">
                                <label for="education_skills"><?= __('* How many female volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="education_skills_nopov" min="1" max="500" value="<?php echo $organization->education_skills_nopov; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="citizen_health"><?= __('* What percentage of the volunteers above are under the age of 35?') ?></label><br> 0 - 100 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="citizen_health_nopov" min="1" max="100" value="<?php echo $organization->citizen_health_nopov; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="poverty"><?= __('* On average, how many volunteer hours are contributed towards this work in a month?') ?></label><br> 0 - 10000 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="poverty_nopov" min="1" max="10000" value="<?php echo $organization->poverty_nopov; ?>" required />
                            </div>
                        </div>
                </div>
            </div>

            <div id="agriculture_rural" style="display:none;">
                <div class="card-header">
                    <?= __('Agriculture, Rural development, Blue/Ocean economy') ?>
                </div>
                <div class="card-body">
                    <h6>Please provide your best estimates to the questions below.</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="pan_africanism"><?= __('* How many male volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="pan_africanism_agric" min="1" max="500" value="<?php echo $organization->pan_africanism_agric; ?>" required /><br><br>
                            </div>
                            <div class="col-md-6">
                                <label for="education_skills"><?= __('* How many female volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="education_skills_agric" min="1" max="500" value="<?php echo $organization->education_skills_agric; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="citizen_health"><?= __('* What percentage of the volunteers above are under the age of 35?') ?></label><br> 0 - 100 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="citizen_health_agric" min="1" max="100" value="<?php echo $organization->citizen_health_agric; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="poverty"><?= __('* On average, how many volunteer hours are contributed towards this work in a month?') ?></label><br> 0 - 10000 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="poverty_agric" min="1" max="10000" value="<?php echo $organization->poverty_agric; ?>" required />
                            </div>
                        </div>
                </div>
            </div>

            <div id="democratic_values" style="display:none;">
                <div class="card-header">
                    <?= __('Democratic values, Human rights, Justice and the Rule of law') ?>
                </div>
                <div class="card-body">
                    <h6>Please provide your best estimates to the questions below.</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="pan_africanism"><?= __('* How many male volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="pan_africanism_demo" min="1" max="500" value="<?php echo $organization->pan_africanism_demo; ?>" required /><br><br>
                            </div>
                            <div class="col-md-6">
                                <label for="education_skills"><?= __('* How many female volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="education_skills_demo" min="1" max="500" value="<?php echo $organization->education_skills_demo; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="citizen_health"><?= __('* What percentage of the volunteers above are under the age of 35?') ?></label><br> 0 - 100 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="citizen_health_demo" min="1" max="100" value="<?php echo $organization->citizen_health_demo; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="poverty"><?= __('* On average, how many volunteer hours are contributed towards this work in a month?') ?></label><br> 0 - 10000 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="poverty_demo" min="1" max="10000" value="<?php echo $organization->poverty_demo; ?>" required />
                            </div>
                        </div>
                </div>
            </div>

            <div id="environmental_sustainability" style="display:none;">
                <div class="card-header">
                    <?= __('Environmental sustainability, Climate change, Water & Sanitation') ?>
                </div>
                <div class="card-body">
                    <h6>Please provide your best estimates to the questions below.</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="pan_africanism"><?= __('* How many male volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="pan_africanism_enviro" min="1" max="500" value="<?php echo $organization->pan_africanism_enviro; ?>" required /><br><br>
                            </div>
                            <div class="col-md-6">
                                <label for="education_skills"><?= __('* How many female volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="education_skills_enviro" min="1" max="500" value="<?php echo $organization->education_skills_enviro; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="citizen_health"><?= __('* What percentage of the volunteers above are under the age of 35?') ?></label><br> 0 - 100 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="citizen_health_enviro" min="1" max="100" value="<?php echo $organization->citizen_health_enviro; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="poverty"><?= __('* On average, how many volunteer hours are contributed towards this work in a month?') ?></label><br> 0 - 10000 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="poverty_enviro" min="1" max="10000" value="<?php echo $organization->poverty_enviro; ?>" required />
                            </div>
                        </div>
                </div>
            </div>

            <div id="infrastructure_development" style="display:none;">
                <div class="card-header">
                    <?= __('Infrastructure development, Access to affordable & clean energy, Industrialization') ?>
                </div>
                <div class="card-body">
                    <h6>Please provide your best estimates to the questions below.</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="pan_africanism"><?= __('* How many male volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="pan_africanism_infra" min="1" max="500" value="<?php echo $organization->pan_africanism_infra; ?>" required /><br><br>
                            </div>
                            <div class="col-md-6">
                                <label for="education_skills"><?= __('* How many female volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="education_skills_infra" min="1" max="500" value="<?php echo $organization->education_skills_infra; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="citizen_health"><?= __('* What percentage of the volunteers above are under the age of 35?') ?></label><br> 0 - 100 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="citizen_health_infra" min="1" max="100" value="<?php echo $organization->citizen_health_infra; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="poverty"><?= __('* On average, how many volunteer hours are contributed towards this work in a month?') ?></label><br> 0 - 10000 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="poverty_infra" min="1" max="10000" value="<?php echo $organization->poverty_infra; ?>" required />
                            </div>
                        </div>
                </div>
            </div>

            <div id="peace_security" style="display:none;">
                <div class="card-header">
                    <?= __('Peace and Security') ?>
                </div>
                <div class="card-body">
                    <h6>Please provide your best estimates to the questions below.</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="pan_africanism"><?= __('* How many male volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="pan_africanism_peace" min="1" max="500" value="<?php echo $organization->pan_africanism_peace; ?>" required /><br><br>
                            </div>
                            <div class="col-md-6">
                                <label for="education_skills"><?= __('* How many female volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="education_skills_peace" min="1" max="500" value="<?php echo $organization->education_skills_peace; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="citizen_health"><?= __('* What percentage of the volunteers above are under the age of 35?') ?></label><br> 0 - 100 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="citizen_health_peace" min="1" max="100" value="<?php echo $organization->citizen_health_peace; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="poverty"><?= __('* On average, how many volunteer hours are contributed towards this work in a month?') ?></label><br> 0 - 10000 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="poverty_peace" min="1" max="10000" value="<?php echo $organization->poverty_peace; ?>" required />
                            </div>
                        </div>
                </div>
            </div>

            <div id="culture" style="display:none;">
                <div class="card-header">
                    <?= __('Culture') ?>
                </div>
                <div class="card-body">
                    <h6>Please provide your best estimates to the questions below.</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="pan_africanism"><?= __('* How many male volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="pan_africanism_culture" min="1" max="500" value="<?php echo $organization->pan_africanism_culture; ?>" required /><br><br>
                            </div>
                            <div class="col-md-6">
                                <label for="education_skills"><?= __('* How many female volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="education_skills_culture" min="1" max="500" value="<?php echo $organization->education_skills_culture; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="citizen_health"><?= __('* What percentage of the volunteers above are under the age of 35?') ?></label><br> 0 - 100 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="citizen_health_culture" min="1" max="100" value="<?php echo $organization->citizen_health_culture; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="poverty"><?= __('* On average, how many volunteer hours are contributed towards this work in a month?') ?></label><br> 0 - 10000 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="poverty_culture" min="1" max="10000" value="<?php echo $organization->poverty_culture; ?>" required />
                            </div>
                        </div>
                </div>
            </div>

            <div id="gender_inequality" style="display:none;">
                <div class="card-header">
                    <?= __('Gender equality & Women empowerment') ?>
                </div>
                <div class="card-body">
                    <h6>Please provide your best estimates to the questions below.</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="pan_africanism"><?= __('* How many male volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="pan_africanism_gender" min="1" max="500" value="<?php echo $organization->pan_africanism_gender; ?>" required /><br><br>
                            </div>
                            <div class="col-md-6">
                                <label for="education_skills"><?= __('* How many female volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="education_skills_gender" min="1" max="500" value="<?php echo $organization->education_skills_gender; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="citizen_health"><?= __('* What percentage of the volunteers above are under the age of 35?') ?></label><br> 0 - 100 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="citizen_health_gender" min="1" max="100" value="<?php echo $organization->citizen_health_gender; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="poverty"><?= __('* On average, how many volunteer hours are contributed towards this work in a month?') ?></label><br> 0 - 10000 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="poverty_gender" min="1" max="10000" value="<?php echo $organization->poverty_gender; ?>" required />
                            </div>
                        </div>
                </div>
            </div>

            <div id="youth_empowerment" style="display:none;">
                <div class="card-header">
                    <?= __('Youth Development & Empowerment') ?>
                </div>
                <div class="card-body">
                    <h6>Please provide your best estimates to the questions below.</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="pan_africanism"><?= __('* How many male volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="pan_africanism_youth" min="1" max="500" value="<?php echo $organization->pan_africanism_youth; ?>" required /><br><br>
                            </div>
                            <div class="col-md-6">
                                <label for="education_skills"><?= __('* How many female volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="education_skills_youth" min="1" max="500" value="<?php echo $organization->education_skills_youth; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="citizen_health"><?= __('* What percentage of the volunteers above are under the age of 35?') ?></label><br> 0 - 100 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="citizen_health_youth" min="1" max="100" value="<?php echo $organization->citizen_health_youth; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="poverty"><?= __('* On average, how many volunteer hours are contributed towards this work in a month?') ?></label><br> 0 - 10000 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="poverty_youth" min="1" max="10000" value="<?php echo $organization->poverty_youth; ?>" required />
                            </div>
                        </div>
                </div>
            </div>

            <div id="reduced_inequality" style="display:none;">
                <div class="card-header">
                    <?= __('Reduced inequality') ?>
                </div>
                <div class="card-body">
                    <h6>Please provide your best estimates to the questions below.</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="pan_africanism"><?= __('* How many male volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="pan_africanism_reduced" min="1" max="500" value="<?php echo $organization->pan_africanism_reduced; ?>" required /><br><br>
                            </div>
                            <div class="col-md-6">
                                <label for="education_skills"><?= __('* How many female volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="education_skills_reduced" min="1" max="500" value="<?php echo $organization->education_skills_reduced; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="citizen_health"><?= __('* What percentage of the volunteers above are under the age of 35?') ?></label><br> 0 - 100 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="citizen_health_reduced" min="1" max="100" value="<?php echo $organization->citizen_health_reduced; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="poverty"><?= __('* On average, how many volunteer hours are contributed towards this work in a month?') ?></label><br> 0 - 10000 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="poverty_reduced" min="1" max="10000" value="<?php echo $organization->poverty_reduced; ?>" required />
                            </div>
                        </div>
                </div>
            </div>

            <div id="sustainable_city" style="display:none;">
                <div class="card-header">
                    <?= __('Sustainable Cities & Communities') ?>
                </div>
                <div class="card-body">
                    <h6>Please provide your best estimates to the questions below.</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="pan_africanism"><?= __('* How many male volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="pan_africanism_sustainable" min="1" max="500" value="<?php echo $organization->pan_africanism_sustainable; ?>" required /><br><br>
                            </div>
                            <div class="col-md-6">
                                <label for="education_skills"><?= __('* How many female volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="education_skills_sustainable" min="1" max="500" value="<?php echo $organization->education_skills_sustainable; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="citizen_health"><?= __('* What percentage of the volunteers above are under the age of 35?') ?></label><br> 0 - 100 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="citizen_health_sustainable" min="1" max="100" value="<?php echo $organization->citizen_health_sustainable; ?>" required />
                            </div>
                            <div class="col-md-6">
                                <label for="poverty"><?= __('* On average, how many volunteer hours are contributed towards this work in a month?') ?></label><br> 0 - 10000 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="poverty_sustainable" min="1" max="10000" value="<?php echo $organization->poverty_sustainable; ?>" required />
                            </div>
                        </div>
                </div>
            </div>

            <div id="responsible_consumption" style="display:none;">
                <div class="card-header">
                    <?= __('Responsible Consumption and Production') ?>
                </div>
                <div class="card-body">
                    <h6>Please provide your best estimates to the questions below.</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="pan_africanism"><?= __('* How many male volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="pan_africanism_responsible" min="1" max="500" value="<?php echo $organization->pan_africanism_responsible; ?>" required /><br><br>
                                <!-- <input id="pan_africanism" type="range" name="pan_africanism_responsible" min="0" max="5000" step="1" value="0">
                                <div class="input-amount">
                                    <input id="input_pan" name="pan_africanism_responsible" value="0">
                                    <span class="unit"></span>
                                </div> -->
                            </div>
                            <div class="col-md-6">
                                <label for="education_skills"><?= __('* How many female volunteers are engaged in this work annually?') ?></label><br> 0 - 500 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="education_skills_responsible" min="1" max="500" value="<?php echo $organization->education_skills_responsible; ?>" required />
                                <!-- <input id="education_skills" type="range" name="education_skills_responsible" min="0" max="500" step="1" value="0">
                                <div class="input-amount">
                                    <input id="input_education" name="education_skills_responsible" value="0">
                                    <span class="unit"></span>
                                </div> -->
                            </div>
                            <div class="col-md-6">
                                <label for="citizen_health"><?= __('* What percentage of the volunteers above are under the age of 35?') ?></label><br> 0 - 100 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="citizen_health_responsible" min="1" max="100" value="<?php echo $organization->citizen_health_responsible; ?>" required />
                                <!-- <?php echo $this->Form->input('citizen_health_responsible', array('type' => 'number')); ?> -->
                                <!-- <input id="citizen_health" type="range" name="citizen_health_responsible" min="0" max="100" step="1" value="0">
                                <div class="input-amount">
                                    <input id="input_citizen" name="citizen_health_responsible" value="0">
                                    <span class="unit"></span>
                                </div> -->
                            </div>
                            <div class="col-md-6">
                                <label for="poverty"><?= __('* On average, how many volunteer hours are contributed towards this work in a month?') ?></label><br> 0 - 10000 <br>
                                <!-- <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                                        $attributes = array('legend' => 'False');
                                        echo $this->Form->radio('type', $options, $attributes);
                                        ?> -->
                                <input type="number" name="poverty_responsible" min="1" max="10000" value="<?php echo $organization->poverty_responsible; ?>" required />

                                <!-- <input id="slide-range" type="range" name="poverty_responsible" min="0" max="10000" step="1" value="0">
                                <div class="input-amount">
                                    <input id="input-Amount" name="poverty_responsible" value="0">
                                    <span class="unit"></span>
                                </div> -->
                                <!-- <input type="range" min="1" max="100" value="50" class="slider" id="myRange"> -->
                            </div>
                        </div>
                </div>
            </div>

        </div>
        <!-- <br>
        <label for=""><?= __('Areas of Engagement') ?></label> -->
        <div class="card office basic-info">
            <div class="card-header">
                <?= __('Volunteer Exchanges') ?>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <label for="volunteer_exchange_region"><?= __('* Does your program engage in regional exchanges? (sending/receiving volunteers within Africa)') ?></label>
                        <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                        $attributes = array('legend' => 'False', 'name' => 'volunteer_exchange_region', 'required' => 'required', 'value' => $organization->volunteer_exchange_region);
                        echo $this->Form->radio('type', $options, $attributes);
                        ?>
                    </div>
                    <div class="col-md-6">
                        <label for="volunteer_exchange_intern"><?= __('* Does your program engage in international exchanges? (sending/receiving volunteers to/from countries outside of Africa)') ?></label>
                        <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                        $attributes = array('legend' => 'False', 'name' => 'volunteer_exchange_intern', 'required' => 'required', 'value' => $organization->volunteer_exchange_intern);
                        echo $this->Form->radio('type', $options, $attributes);
                        ?>
                    </div>
                </div>
            </div>

        </div>
        <!-- <br>
        <label for=""><?= __('Areas of Engagement') ?></label> -->
        <div class="card office basic-info">
            <div class="card-header">
                <?= __('Resources and materials') ?>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">
                        <label for="pan_africanism_resources"><?= __('* Does your country have a national volunteer policy/framework?') ?></label>
                        <?php $options = array('Yes' => 'Yes', 'No' => 'No', "In Progress" => "In Progress", "Don't Know" => "Don't Know");
                        $attributes = array('legend' => 'False', 'name' => 'pan_africanism_resources', 'required' => 'required', 'value' => $organization->pan_africanism_resources);
                        echo $this->Form->radio('type', $options, $attributes);
                        ?>
                    </div>
                </div>
                <br>
                <label for="country_national_file"><?= __('If yes, please upload a copy of your country\'s national volunteer policy/framework.') ?></label>
                <br>
                <h6>Supported Formats: PDF, DOC, DOCX (5MB Max)</h6>

                <div>
                    <?php
                    echo $this->Form->input('upload_country_national_file', ['type' => 'file', 'accept' => 'image/*, .pdf, .doc, .docx']);
                    if (!empty($organization->pan_africanism_country_file)) {
                    ?>
                        <p class="download__preview"> <a target="_blank" href="<?php echo $organization->pan_africanism_country_file; ?>"><?php echo $organization->pan_africanism_country_file; ?></a></p>
                    <?php
                    }
                    ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="pan_africanism_organiz_pol"><?= __('* Does your organization have a volunteer policy/framework?') ?></label>
                        <?php $options = array('Yes' => 'Yes', 'No' => 'No', "In Progress" => "In Progress", "Don't Know" => "Don't Know");
                        $attributes = array('legend' => 'False', 'name' => 'pan_africanism_organiz_pol', 'required' => 'required', 'value' => $organization->pan_africanism_organiz_pol);
                        echo $this->Form->radio('type', $options, $attributes);
                        ?>
                    </div>
                </div>
                <br>
                <label for="organization_policy_file"><?= __('If yes, please upload a copy of your organization\'s volunteer policy/framework.') ?></label>
                <br>
                <h6>Supported Formats: PDF, DOC, DOCX (5MB Max)</h6>

                <div>
                    <?php
                    echo $this->Form->input('upload_organization_policy_file', ['type' => 'file', 'accept' => 'image/*, .pdf, .doc, .docx']);
                    if (!empty($organization->pan_africanism_organiz_pol_file)) {
                    ?>
                        <p class="download__preview"> <a target="_blank" href="<?php echo $organization->pan_africanism_organiz_pol_file; ?>"><?php echo $organization->pan_africanism_organiz_pol_file; ?></a></p>
                    <?php
                    }
                    ?>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label for="pan_africanism_organiz_annu"><?= __('* Does your organization have an annual report?') ?></label>
                        <?php $options = array('Yes' => 'Yes', 'No' => 'No');
                        $attributes = array('legend' => 'False', 'name' => 'pan_africanism_organiz_annu', 'required' => 'required', 'value' => $organization->pan_africanism_organiz_annu);
                        echo $this->Form->radio('type', $options, $attributes);
                        ?>
                    </div>
                </div>
                <br>
                <label for="pan_africanism_organiz_annu_file"><?= __('If yes, please upload a copy of your organization\'s most recent annual report.') ?></label>
                <br>
                <h6>Supported Formats: PDF, DOC, DOCX (5MB Max)</h6>

                <div>
                    <?php
                    echo $this->Form->input('upload_organization_report_file', ['type' => 'file', 'accept' => 'image/*, .pdf, .doc, .docx']);
                    if (!empty($organization->pan_africanism_organiz_annu_file)) {
                    ?>
                        <p class="download__preview"> <a target="_blank" href="<?php echo $organization->pan_africanism_organiz_annu_file; ?>"><?php echo $organization->pan_africanism_organiz_annu_file; ?></a></p>
                    <?php
                    }
                    ?>
                </div>

                <br>
                <label for="additional_file"><?= __('Please upload any additional resources, tools, manuals you would like to share with us. ') ?></label>
                <br>
                <h6>Supported Formats: PDF, DOC, DOCX (5MB Max)</h6>
                <div>
                    <?php
                    echo $this->Form->input('upload_additional_file', ['type' => 'file', 'accept' => 'image/*, .pdf, .doc, .docx']);
                    if (!empty($organization->additional_file)) {
                    ?>
                        <p class="download__preview"> <a target="_blank" href="<?php echo $organization->additional_file; ?>"><?php echo $organization->additional_file; ?></a></p>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>


        <div class="d-flex">
            <button type="submit" class="btn ml-auto"><?= __('Save Changes') ?></button>
        </div>

    </div>
    <?= $this->Form->end() ?>
    <hr />
    <div class="d-flex justify-content-between top-line align-items-center">
        <h4><?= __('Other Offices') ?></h4>
        <a href="#" class="btn btn-small btn-sm ml-auto" data-toggle="modal" data-target="#officeModal"><?= __('Add Office') ?></a>
        <!-- Modal -->
        <div class="modal fade" id="officeModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel"><?= __('Add Office') ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?= $this->Form->create($organization, ['url' => ['action' => 'addOffice']]) ?>
                    <div class="modal-body">
                        <div class="form-group other-info basic-info">
                            <div class="row">
                                <div class="col-md-12">
                                    <?= $this->Form->control('organization_offices.address', ['placeholder' => __('Address'), 'id' => 'office-address']) ?>
                                    <?= $this->Form->hidden('organization_offices.lat', ['id' => 'office-lat']) ?>
                                    <?= $this->Form->hidden('organization_offices.lng', ['id' => 'office-lng']) ?>
                                </div>
                                <div class="col-md-6">
                                    <label><?= __('Country') ?></label>
                                    <?= $this->Form->control('organization_offices.country_id', ['label' => false, 'empty' => __('Select Country')]) ?>
                                </div>
                                <div class="col-md-6">
                                    <label><?= __('City') ?></label>
                                    <?= $this->Form->control('organization_offices.city_id', ['label' => false, 'options' => []]) ?>
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

    <?php foreach ($organization->organization_offices as $office) : ?>
        <div class="card office basic-info ">
            <div class="card-header d-flex">
                <span>
                    <?= h($office->address) ?>
                    <br>
                    <small><?= h($office->has('city') ? $office->city->name . ', ' : '' . $office->country->nicename) ?></small>
                </span>

                <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'deleteOffice', 'id' => $organization->id], ['data' => ['id' => $office->id], 'class' => 'btn btn-sm ml-auto', 'escape' => false, 'confirm' => __('Delete selected office?')]) ?>
                <a href="#" class=""></a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<style>
    .pac-container {
        background-color: #FFF;
        z-index: 20;
        position: fixed;
        display: inline-block;
        float: left;
    }

    .modal {
        z-index: 20;
    }

    .modal-backdrop {
        z-index: 10;
    }

    ​
    /* */
</style>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<?php $this->Html->css("https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css", ['block' => 'css']) ?>
<?php $this->Html->script("https://cdn.jsdelivr.net/npm/exif-js", ['block' => 'script']) ?>
<?php $this->Html->script("https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.js", ['block' => 'script']) ?>
<?php $this->Html->script("https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js", ['block' => 'script']) ?>
<?php $this->Html->script("https://maps.googleapis.com/maps/api/js?key=AIzaSyBQzkAnV6V7naTqRsuMkfGENsBjpaFSUt4&libraries=places", ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<?= $this->element('address-autocomplete') ?>
<script>
    var officeAddressInput = document.getElementById('office-address');
    var officeAddressOptions = {
        bounds: defaultBounds,
        types: ['geocode']
    };
    var officeAddressSelected = false

    officeAddressAutocomplete = new google.maps.places.Autocomplete(officeAddressInput, officeAddressOptions);
    // autocomplete.setFields(['address_components', 'formatted_address', 'geometry', 'name']);
    officeAddressAutocomplete.addListener('place_changed', onOfficeAddressPlaceChanged);

    function onOfficeAddressPlaceChanged() {
        officeAddressSelected = true
        var place = officeAddressAutocomplete.getPlace();
        if (place.geometry) {
            document.getElementById('office-lat').value = place.geometry.location.lat();
            document.getElementById('office-lng').value = place.geometry.location.lng();
        }
    }

    $(document).ready(function() {
        $('#office-address').focus(function() {
            officeAddressSelected = false
        });

        $("#office-address").keypress(function(event) {
            if (event.keyCode == 13 || event.keyCode == 9) {
                $(event.target).blur();
                if ($(".pac-container .pac-item:first span:eq(3)").text() == "")
                    firstValue = $(".pac-container .pac-item:first .pac-item-query").text();
                else
                    firstValue = $(".pac-container .pac-item:first .pac-item-query").text() + ", " + $(".pac-container .pac-item:first span:eq(3)").text();
                event.target.value = firstValue;

            } else
                return true;
        });

        $('#office-address').blur(function() {
            if (!officeAddressSelected) {
                $(this).val('');
            }
        });

        $("#volunteering-categories-ids").select2()

        $("#country-id").change(function() {
            country_id = $(this).val();
            if (country_id && country_id !== '') {
                $("#city-id").html('<option> ... </option>')
                let options = '';
                $.ajax({
                    type: "POST",
                    url: "<?= $this->Url->build('/country-city-list') ?>" + '/' + country_id,
                    success: function(data) {
                        for (k in data) {
                            options += `<option value="${k}"> ${data[k]} </option>`;
                        };
                    },
                    complete: function(xhr, result) {
                        $("#city-id").html(options)
                    },
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                    }
                });
            } else {
                $("#city-id").html('');
            }
        })

        $("#organization-offices-country-id").change(function() {
            country_id = $(this).val();
            if (country_id && country_id !== '') {
                $("#organization-offices-city-id").html('<option> ... </option>')
                let options = '';
                $.ajax({
                    type: "POST",
                    url: "<?= $this->Url->build('/country-city-list') ?>" + '/' + country_id,
                    success: function(data) {
                        for (k in data) {
                            options += `<option value="${k}"> ${data[k]} </option>`;
                        };
                    },
                    complete: function(xhr, result) {
                        $("#organization-offices-city-id").html(options)
                    },
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                    }
                });
            } else {
                $("#city-id").html('');
            }
        })

        $('.newbtn').bind("click", function() {
            $('#pic').click();
        });

        $image_crop = $('#upload-image').croppie({
            enableExif: true,
            viewport: {
                width: 150,
                height: 150,
                type: 'square'
            },
            boundary: {
                width: 180,
                height: 180
            },
            enforceBoundary: true
        });

        $('#pic').on('change', function() {
            var reader = new FileReader();
            reader.onload = function(e) {
                $image_crop.croppie('bind', {
                    url: e.target.result
                }).then(function() {
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('.crop_image').on('click', function(ev) {
            ev.preventDefault()

            if ($('#pic').val() === '') {
                $('#img-help-block').html('<small>* <?= __('Please select an image') ?></small>').attr('style', 'color: red;')
            } else {
                $('#img-help-block').html('')
                $btn = $(this)
                $btn.attr('disabled', true);
                $image_crop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function(response) {
                    var uploadUrl = "<?= $this->Url->build(['_name' => 'organization:actions', 'action' => 'uploadProfileImage', 'id' => $organization->id]) ?>"
                    $.ajax({
                        type: 'POST',
                        data: {
                            image: response
                        },
                        url: uploadUrl,
                        success: function(data) {
                            if (data.status == 'success') {
                                location.reload()
                            } else {
                                $('#img-help-block').html(`<small>* ${data.message}</small>`).attr('style', 'color: red;')
                            }
                        },
                        complete: function(xhr, result) {
                            $btn.attr('disabled', false);
                        },
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                        }
                    });
                });
            }
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    });
</script>

<script type="text/javascript">
    function getPanValue(x) {
        if (x.value == 'No') {
            document.getElementById("pan_africanism").style.display = 'none';
            $('#pan_africanism').hide().find(':input').attr('required', false);

        } else {
            document.getElementById("pan_africanism").style.display = 'block';
        }
    }

    function getEduValue(x) {
        if (x.value == 'No') {
            document.getElementById("education_skills").style.display = 'none';
            $('#education_skills').hide().find(':input').attr('required', false);
        } else {
            document.getElementById("education_skills").style.display = 'block';
        }
    }

    function getHealthValue(x) {
        if (x.value == 'No') {
            document.getElementById("health_wellbeing").style.display = 'none';
            $('#health_wellbeing').hide().find(':input').attr('required', false);
        } else {
            document.getElementById("health_wellbeing").style.display = 'block';
        }
    }

    function getPovertyValue(x) {
        if (x.value == 'No') {
            document.getElementById("no_poverty").style.display = 'none';
            $('#no_poverty').hide().find(':input').attr('required', false);
        } else {
            document.getElementById("no_poverty").style.display = 'block';
        }
    }

    function getAgricValue(x) {
        if (x.value == 'No') {
            document.getElementById("agriculture_rural").style.display = 'none';
            $('#agriculture_rural').hide().find(':input').attr('required', false);
        } else {
            document.getElementById("agriculture_rural").style.display = 'block';
        }
    }

    function getDemoValue(x) {
        if (x.value == 'No') {
            document.getElementById("democratic_values").style.display = 'none';
            $('#democratic_values').hide().find(':input').attr('required', false);
        } else {
            document.getElementById("democratic_values").style.display = 'block';
        }
    }

    function getEnvironValue(x) {
        if (x.value == 'No') {
            document.getElementById("environmental_sustainability").style.display = 'none';
            $('#environmental_sustainability').hide().find(':input').attr('required', false);
        } else {
            document.getElementById("environmental_sustainability").style.display = 'block';
        }
    }

    function getInfraValue(x) {
        if (x.value == 'No') {
            document.getElementById("infrastructure_development").style.display = 'none';
            $('#infrastructure_development').hide().find(':input').attr('required', false);
        } else {
            document.getElementById("infrastructure_development").style.display = 'block';
        }
    }

    function getPeaceValue(x) {
        if (x.value == 'No') {
            document.getElementById("peace_security").style.display = 'none';
            $('#peace_security').hide().find(':input').attr('required', false);
        } else {
            document.getElementById("peace_security").style.display = 'block';
        }
    }

    function getCultureValue(x) {
        if (x.value == 'No') {
            document.getElementById("culture").style.display = 'none';
            $('#culture').hide().find(':input').attr('required', false);
        } else {
            document.getElementById("culture").style.display = 'block';
        }
    }

    function getGenValue(x) {
        if (x.value == 'No') {
            document.getElementById("gender_inequality").style.display = 'none';
            $('#gender_inequality').hide().find(':input').attr('required', false);
        } else {
            document.getElementById("gender_inequality").style.display = 'block';
        }
    }

    function getYouthValue(x) {
        if (x.value == 'No') {
            document.getElementById("youth_empowerment").style.display = 'none';
            $('#youth_empowerment').hide().find(':input').attr('required', false);
        } else {
            document.getElementById("youth_empowerment").style.display = 'block';
        }
    }

    function getRedValue(x) {
        if (x.value == 'No') {
            document.getElementById("reduced_inequality").style.display = 'none';
            $('#reduced_inequality').hide().find(':input').attr('required', false);
        } else {
            document.getElementById("reduced_inequality").style.display = 'block';
        }
    }

    function getSusValue(x) {
        if (x.value == 'No') {
            document.getElementById("sustainable_city").style.display = 'none';
            $('#sustainable_city').hide().find(':input').attr('required', false);
        } else {
            document.getElementById("sustainable_city").style.display = 'block';
        }
    }

    function getRespValue(x) {
        if (x.value == 'No') {
            document.getElementById("responsible_consumption").style.display = 'none';
            $('#responsible_consumption').hide().find(':input').attr('required', false);
        } else {
            document.getElementById("responsible_consumption").style.display = 'block';
        }
    }

    // $('#slide-range').on('input', function() {

    //     var newVal = $(this).val();

    //     $("#input-Amount").val(newVal);
    // });
    // $('#input-Amount').on('input', function() {
    //     //console.log($(this).val())
    //     $('#slide-range').val($(this).val())
    // });

    // $('#citizen_health').on('input', function() {

    //     var newVal = $(this).val();

    //     $("#input_citizen").val(newVal);
    // });
    // $('#input_citizen').on('input', function() {
    //     //console.log($(this).val())
    //     $('#citizen_health').val($(this).val())
    // });

    // $('#education_skills').on('input', function() {

    //     var newVal = $(this).val();

    //     $("#input_education").val(newVal);
    // });
    // $('#input_education').on('input', function() {
    //     //console.log($(this).val())
    //     $('#education_skills').val($(this).val())
    // });

    // $('#pan_africanism').on('input', function() {

    //     var newVal = $(this).val();

    //     $("#input_pan").val(newVal);
    // });
    // $('#input_pan').on('input', function() {
    //     //console.log($(this).val())
    //     $('#pan_africanism').val($(this).val())
    // });
</script>

<?php
if (isset($organization['pan_africanism'])) {
    if ($organization['pan_africanism'] == "Yes") {
?>
        <script type="text/javascript">
            document.getElementById("pan_africanism").style.display = "block";
        </script>
    <?php
    } else {
    ?>
        <script type="text/javascript">
            document.getElementById("pan_africanism").style.display = "none";
        </script>
    <?php
    }
}

if (isset($organization['education_skills'])) {
    if ($organization['education_skills'] == "Yes") {
    ?>
        <script type="text/javascript">
            document.getElementById("education_skills").style.display = "block";
        </script>
    <?php
    } else {
    ?>
        <script type="text/javascript">
            document.getElementById("education_skills").style.display = "none";
        </script>
    <?php
    }
}

if (isset($organization['health_wellbeing'])) {
    if ($organization['health_wellbeing'] == "Yes") {
    ?>
        <script type="text/javascript">
            document.getElementById("health_wellbeing").style.display = "block";
        </script>
    <?php
    } else {
    ?>
        <script type="text/javascript">
            document.getElementById("health_wellbeing").style.display = "none";
        </script>
    <?php
    }
}

if (isset($organization['no_poverty'])) {
    if ($organization['no_poverty'] == "Yes") {
    ?>
        <script type="text/javascript">
            document.getElementById("no_poverty").style.display = "block";
        </script>
    <?php
    } else {
    ?>
        <script type="text/javascript">
            document.getElementById("no_poverty").style.display = "none";
        </script>
    <?php
    }
}

if (isset($organization['agriculture_rural'])) {
    if ($organization['agriculture_rural'] == "Yes") {
    ?>
        <script type="text/javascript">
            document.getElementById("agriculture_rural").style.display = "block";
        </script>
    <?php
    } else {
    ?>
        <script type="text/javascript">
            document.getElementById("agriculture_rural").style.display = "none";
        </script>
    <?php
    }
}

if (isset($organization['democratic_values'])) {
    if ($organization['democratic_values'] == "Yes") {
    ?>
        <script type="text/javascript">
            document.getElementById("democratic_values").style.display = "block";
        </script>
    <?php
    } else {
    ?>
        <script type="text/javascript">
            document.getElementById("democratic_values").style.display = "none";
        </script>
    <?php
    }
}

if (isset($organization['environmental_sustainability'])) {
    if ($organization['environmental_sustainability'] == "Yes") {
    ?>
        <script type="text/javascript">
            document.getElementById("environmental_sustainability").style.display = "block";
        </script>
    <?php
    } else {
    ?>
        <script type="text/javascript">
            document.getElementById("environmental_sustainability").style.display = "none";
        </script>
    <?php
    }
}

if (isset($organization['infrastructure_development'])) {
    if ($organization['infrastructure_development'] == "Yes") {
    ?>
        <script type="text/javascript">
            document.getElementById("infrastructure_development").style.display = "block";
        </script>
    <?php
    } else {
    ?>
        <script type="text/javascript">
            document.getElementById("infrastructure_development").style.display = "none";
        </script>
    <?php
    }
}

if (isset($organization['peace_security'])) {
    if ($organization['peace_security'] == "Yes") {
    ?>
        <script type="text/javascript">
            document.getElementById("peace_security").style.display = "block";
        </script>
    <?php
    } else {
    ?>
        <script type="text/javascript">
            document.getElementById("peace_security").style.display = "none";
        </script>
    <?php
    }
}

if (isset($organization['culture'])) {
    if ($organization['culture'] == "Yes") {
    ?>
        <script type="text/javascript">
            document.getElementById("culture").style.display = "block";
        </script>
    <?php
    } else {
    ?>
        <script type="text/javascript">
            document.getElementById("culture").style.display = "none";
        </script>
    <?php
    }
}

if (isset($organization['gender_inequality'])) {
    if ($organization['gender_inequality'] == "Yes") {
    ?>
        <script type="text/javascript">
            document.getElementById("gender_inequality").style.display = "block";
        </script>
    <?php
    } else {
    ?>
        <script type="text/javascript">
            document.getElementById("gender_inequality").style.display = "none";
        </script>
    <?php
    }
}

if (isset($organization['youth_empowerment'])) {
    if ($organization['youth_empowerment'] == "Yes") {
    ?>
        <script type="text/javascript">
            document.getElementById("youth_empowerment").style.display = "block";
        </script>
    <?php
    } else {
    ?>
        <script type="text/javascript">
            document.getElementById("youth_empowerment").style.display = "none";
        </script>
    <?php
    }
}

if (isset($organization['reduced_inequality'])) {
    if ($organization['reduced_inequality'] == "Yes") {
    ?>
        <script type="text/javascript">
            document.getElementById("reduced_inequality").style.display = "block";
        </script>
    <?php
    } else {
    ?>
        <script type="text/javascript">
            document.getElementById("reduced_inequality").style.display = "none";
        </script>
    <?php
    }
}

if (isset($organization['sustainable_city'])) {
    if ($organization['sustainable_city'] == "Yes") {
    ?>
        <script type="text/javascript">
            document.getElementById("sustainable_city").style.display = "block";
        </script>
    <?php
    } else {
    ?>
        <script type="text/javascript">
            document.getElementById("sustainable_city").style.display = "none";
        </script>
    <?php
    }
}

if (isset($organization['responsible_consumption'])) {
    if ($organization['responsible_consumption'] == "Yes") {
    ?>
        <script type="text/javascript">
            document.getElementById("responsible_consumption").style.display = "block";
        </script>
    <?php
    } else {
    ?>
        <script type="text/javascript">
            document.getElementById("responsible_consumption").style.display = "none";
        </script>
<?php
    }
}

?>

<?php $this->end(); ?>
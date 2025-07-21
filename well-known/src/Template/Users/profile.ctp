<div class="main">
  <div class="container organization">
    <?php if(!$user->is_email_verified): ?>
      <div class="alert alert-danger">
          <h4><i class="icon fa fa-ban"></i> <?= __('Email Unverified') ?>!</h4>
          <?= h('Please follow the email verification link sent to your email for verification.') ?><br />
          <?= $this->Html->link('Resend Verification link',['controller'=>'Users','action'=>'resendEmailValidation']) ?>
      </div>
    <?php endif; ?>
    <div class="card user-header">
      <div class="card-body row align-items-center">
        <div class="img-container col-lg-2 col-md-3 col-sm-4">
          <a href="#" class="btn py-1 px-2 m-1" data-toggle="modal" data-target="#imageModal" style="position: absolute; top:auto"><?= __('Edit') ?></a>
          <img src="<?= (!empty($user->profile_image) && $user->profile_image !== null) ? $user->profile_image : $this->Url->image('no-image.jpg') ?>" alt="" class="rounded mb-1">
          <!-- Modal -->
          <div class="modal fade" id="imageModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="staticBackdropLabel"><?= __('Edit Profile Image') ?></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <?= $this->Form->create($user, ['type' => 'file']) ?>
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
                            <?= $this->Form->control('file', ['type' => 'file', 'id' => 'pic', 'class' => 'pis', 'label' => false, 'accept' => 'image/*', 'required' => true]) ?>
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
                <?= $this->Form->end() ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-9 col-sm-8 user-info">
          <div class="name-info">
            <h4><?= h($user->first_name. ' ' .$user->last_name) ?></h4>
            <p>
              <span><?= __(h($user->gender)) ?></span>|<span class="left"><?= ($user->date_of_birth != null && !empty($user->date_of_birth)) ? $user->date_of_birth->diffInYears(\Cake\I18n\Date::now()) .__(' Years') : '' ?></span>|<span class="left"><?= $user->has('country') ? h($user->country->nicename) : '' ?></span>
            </p>
            <div class="user-pro-btn">
              <a href="#" class="btn btn-small mr-3" data-toggle="modal" data-target="#profileModal"><?= __('Edit Profile') ?></a>
              <a href="#" class="btn" data-toggle="modal" data-target="#shareModal"><?= __('Share') ?></a>
              <!-- Modal -->
              <div class="modal fade" id="profileModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel"><?= __('Edit Profile') ?></h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <?= $this->Form->create($user, ['type' => 'file']) ?>
                    <div class="modal-body">
                      <div class="form-group other-info basic-info">
                        <div class="row">
                          <div class="col-md-6">
                              <?= $this->Form->control('first_name') ?>
                          </div>
                          <div class="col-md-6">
                              <?= $this->Form->control('last_name') ?>
                          </div>
                          <div class="col-md-5">
                            <label><?= __('Date of birth') ?></label>
                            <?= $this->Form->date('date_of_birth', ['label' => false, 'minYear' => date('Y') - 100, 'maxYear' => date('Y') - 14]) ?>
                          </div>
                          <div class="col-md-3">
                              <label><?= __('Gender') ?></label>
                              <?= $this->Form->control('gender', ['options' => ['Male' => __('Male'), 'Female' => __('Female')], 'label' => false]) ?>
                          </div>
                          <div class="col-md-4">
                              <label><?= __('Marital Status') ?></label>
                              <?= $this->Form->control('marital_status', ['options' => ['Single' => __('Single'), 'Married' => __('Married')], 'label' => false]) ?>
                          </div>
                          <div class="col-md-4">
                              <label><?= __('Availability') ?></label>
                              <?= $this->Form->control('availability', ['options' => ['Part time' => __('Part time'), 'Full time' => __('Full time')], 'label' => false]) ?>
                          </div>
                          <div class="col-md-4">
                              <label><?= __('Nationality at birth') ?></label>
                              <?= $this->Form->control('nationality_at_birth', ['options' => $allCountries, 'label' => false]) ?>
                          </div>
                          <div class="col-md-4">
                              <label><?= __('Current Nationality') ?></label>
                              <?= $this->Form->control('current_nationality', ['options' => $allCountries, 'label' => false]) ?>
                          </div>
                          <div class="col-md-4">
                              <?= $this->Form->control('place_of_birth', ['placeholder' => __('Place of birth')]) ?>
                          </div>
                          <div class="col-md-3">
                              <?= $this->Form->control('phone_number', ['placeholder' => __('Phone number')]) ?>
                          </div>
                          <div class="col-md-5">
                              <?= $this->Form->control('current_address', ['placeholder' => __('Current address')]) ?>
                          </div>
                          <div class="col-md-6">
                              <label><?= __('Resident Country') ?></label>
                              <?= $this->Form->control('resident_country_id', ['options' => $africanCountries, 'label' => false]) ?>
                          </div>
                          <div class="col-md-6">
                              <label><?= __('City') ?></label>
                              <?= $this->Form->control('city_id', ['options' => $cities, 'label' => false]) ?>
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
              <div class="modal fade" id="shareModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel"><?= __('Share Profile') ?></h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <strong><?= __('Here is the link to your profile') ?></strong>
                      <div class="input-group mb-3">
                        <input type="text" id="profile-link" class="form-control" readonly aria-describedby="button-addon2" value="<?= $this->Url->build(['action' => 'userProfile', $user->id], true) ?>">
                        <div class="input-group-append">
                          <button class="btn btn-outline-secondary" id="copy-btn" type="button" data-toggle="tooltip" title="<?= __('Copy to clipboard') ?>"><i class="fa fa-copy"></i></button>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= __('Close') ?></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="more-info">
            <div class="row">
              <div class="col-sm-5 col-6">
                <p class="label"><?= __('National By Birth') ?>:</p>
              </div>
              <div class="col-sm-7 col-6">
                <p class=""><?= h($user->has('birth_country') ? $user->birth_country->nicename : '') ?></p>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-5 col-6">
                <p class="label"><?= __('Location') ?>:</p>
              </div>
              <div class="col-sm-7 col-6">
                <p class=""><?= h($user->has('city') ? $user->city->name : ''). ', '. h($user->has('country') ? $user->country->nicename : '') ?></p>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-5 col-6">
                <p class="label"><?= __('Marital Status') ?>:</p>
              </div>
              <div class="col-sm-7 col-6">
                <p class=""><?= __(h($user->marital_status)) ?></p>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-5 col-6">
                <p class="label"><?= __('Availability') ?>:</p>
              </div>
              <div class="col-sm-7 col-6">
                <p class=""><?= __(h($user->availability)) ?></p>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-5 col-6">
                <p class="label"><?= __('Member Since') ?>:</p>
              </div>
              <div class="col-sm-7 col-6">
                <p class=""><?= $user->created->format('M, Y') ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3 organization-side">
        <h4><?= __('Contact Informations') ?></h4>
        <div class="card">
          <div class="card-body">
            <ul class="contact">
              <li class="d-flex">
                <img src="<?= $this->Url->image('address.svg') ?>" alt="" class="svg">
                <p><?= h($user->current_address) ?></p>
              </li>
              <li>
                <img src="<?= $this->Url->image('email.svg') ?>" alt="" class="svg">
                <p><?= h($user->email) ?></p>
              </li>
            </ul>
          </div>
        </div>
        <h4><?= __('Alumni Forum') ?></h4>
        <div class="card quick-link">
          <ul class="list-group list-group-flush">
          <?php foreach ($user->organization_alumni as $alumni): ?>
            <li class="list-group-item d-flex justify-content-between">
              <a href="<?= $this->Url->build(['controller' => 'AlumniForums', 'action' => 'index', $alumni->organization_id]) ?>"> <?= h($alumni->organization->name) ?> </a>
              <span>(-)</span>
            </li>
          <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <div class="col-md-9">
        <div class="card mb-4">
          <div class="card-header d-flex justify-content-between">
            <?= __('About') ?>
            <a href="#" data-toggle="modal" data-target="#aboutModal"><img src="<?= $this->Url->image('edit-icon.svg') ?>" alt="" class="svg"></a>
            <!-- Modal -->
            <div class="modal fade" id="aboutModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel"><?= __('Edit About') ?></h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <?= $this->Form->create($user) ?>
                    <div class="modal-body">
                      <div class="form-group other-info basic-info">
                        <?= $this->Form->control('short_profile', ['placeholder' => __('Short Profile'), 'rows' => 3, 'maxlength' => 255, 'required' => true]) ?>
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
          <div class="card-body">
            <?= $this->Text->autoParagraph($user->short_profile) ?>
          </div>
        </div>
        <div class="card mb-4">
          <div class="card-header d-flex justify-content-between">
            <?= __('Platform Interests') ?>
            <a href="#" data-toggle="modal" data-target="#interestsModal"><img src="<?= $this->Url->image('edit-icon.svg') ?>" alt="" class="svg"></a>
            <!-- Modal -->
            <div class="modal fade" id="interestsModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel"><?= __('Select Platform Interests') ?></h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <?= $this->Form->create($user) ?>
                    <div class="modal-body">
                      <div class="form-group other-info p-3">
                        <?= $this->Form->control('platform_interests._ids', ['label' => false, 'multiple' => 'checkbox']) ?>
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
          <div class="card-body">
            <?php foreach ($user->platform_interests as $interest) {?> <span class="badge bg-light"> <?= ($interest->name) ?> </span> <?php } ?>
          </div>
        </div>
        <div class="card mb-4">
          <div class="card-header">
            <?= __('Volunteering Experience') ?>
          </div>
          <div class="card-body">
            <div class="accordion" id="accordionExample">
            <?php foreach ($volunteeringHistoryCategory as $category): ?>
              <div class="card">
                <div class="card-header d-flex align-items-center" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  <img src="<?= $this->Url->image('badge.svg') ?>" alt="" class="mr-3">
                  <div class="badge-text">
                    <h4 class="mb-0"> <?= h($category->name) ?> </h4>
                    <p class="mb-0"><?= count($category->volunteering_oppurtunities) ?> <?= __('Badges') ?></p>
                  </div>
                </div>

                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                  <div class="card-body">
                    <ul class="timeline">
                    <?php foreach ($category->volunteering_oppurtunities as $oppurtunity): ?>
                      <li>
                        <a href="#"><?= h($oppurtunity->volunteering_role->name) ?></a>
                        <a href="#" class="float-right"><?= h($oppurtunity->event->start_date->format('d M, Y')) ?> </a>
                        <p> <strong><?= h($oppurtunity->event->title) ?></strong>  - <?= $this->Text->truncate($oppurtunity->event->description, 150, ['ellipsis' => '...']) ?></p>
                      </li>
                    <?php endforeach; ?>
                    </ul>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
            </div>
          </div>
        </div>
        <div class="card org-postlist mb-4">
          <div class="card-header">
            <?= __('Volunteering Interest History') ?>
          </div>
          <div class="card-body">
            <?php
              foreach ($user->volunteering_interests as $interest):
                $eventData = $interest->volunteering_oppurtunity->event;
            ?>
            <div class="wrap mb-4">
                <div class="card">
                    <div class="row no-gutters d-flex align-items-stretch">
                        <div class="col-lg-3 card-img" style="background-image: url(<?= $eventData->image ?>);"></div>
                        <div class="col-lg-9">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-9 d-flex flex-column">
                                        <h4 class="card-title"><?= h($eventData->title) ?></h4>
                                        <p class="card-text"><?= $this->Text->truncate($eventData->description, 150, ['ellipsis' => '...']) ?></p>
                                        <div class="row list-tag align-items-end">
                                            <div class="col-md-6">
                                                <p class="text-muted flex-fill"><?= __('Date') ?>:
                                                <span><?= $eventData->created->format('M d, Y') ?></span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="text-muted flex-fill"><?= __('Organizer') ?>:
                                                <span><?= $eventData->has('organization') ? $eventData->organization->name .($eventData->organization->is_verified ? ' <small>- verified</small>' : '') : '' ?></span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 d-flex align-items-center justify-content-end">
                                        <a href="<?= $this->Url->build(['controller' => 'Events', 'action' => 'view', $eventData->id]) ?>" class="btn btn-small"><?= __('View') ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <div class="location">
                            <p><img src="https://www.countryflags.io/<?= $eventData->country->iso ?>/flat/64.png" alt=""><?= h($eventData->city->name. ', '. $eventData->country->nicename) ?></p>
                        </div>
                        <div class="sector d-flex align-items-center">
                            <!--  -->
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if ($user->volunteering_interests && !empty($user->volunteering_interests)): if (count($user->volunteering_interests) > 5): ?>
            <a href="<?= $this->Url->build(['action' => 'volunteeringInterests']) ?>" class="more"><?= __('SEE MORE') ?>
              <i class="fas fa-caret-right"></i>
            </a>
            <?php endif; endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->Html->css("https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css", ['block' => 'css']) ?>
<?php $this->Html->script("https://cdn.jsdelivr.net/npm/exif-js", ['block' => 'script']) ?>
<?php $this->Html->script("https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.js", ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
        $('#copy-btn').tooltip();
        $('#copy-btn').click(function (e) {
          var copyText = document.getElementById("profile-link");
          /* Select the text field */
          copyText.select();
          copyText.setSelectionRange(0, 99999); /*For mobile devices*/

          /* Copy the text inside the text field */
          document.execCommand("copy");
          $(this).attr('title', "<?= __('Copied to clipboard!') ?>");
          $('#copy-btn').tooltip();
        });

        $("#resident-country-id").change(function () {
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

        $('.newbtn').bind("click", function () {
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

        $('#pic').on('change', function () { 
          var reader = new FileReader();
          reader.onload = function (e) {
            $image_crop.croppie('bind', {
              url: e.target.result
            }).then(function(){
              console.log('jQuery bind complete');
            });
          }
          reader.readAsDataURL(this.files[0]);
        });

        $('.crop_image').on('click', function (ev) {
          ev.preventDefault()
          
          if ($('#pic').val() === '') {
            $('#img-help-block').html('<small>* <?= __("Please select an image") ?> </small>').attr('style', 'color: red;')
          } else {
            $('#img-help-block').html('')
            $btn = $(this)
            $btn.attr('disabled', true);
            $image_crop.croppie('result', {
              type: 'canvas',
              size: 'viewport'
            }).then(function (response) {
              var uploadUrl = "<?= $this->Url->build(['action' => 'uploadProfileImage']) ?>"
              $.ajax({
                type:'POST',
                data: { image:response },
                url: uploadUrl,
                success: function (data) {
                  if (data.status == 'success') {
                    location.reload()
                  } else {
                    $('#img-help-block').html(`<small>* ${data.message}</small>`).attr('style', 'color: red;')
                  }
                },
                complete: function (xhr, result) {
                    $btn.attr('disabled', false);
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                }
              });
            });
          }
        });

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
</script>

<?php $this->end(); ?>
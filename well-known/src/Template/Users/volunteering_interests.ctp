<div class="main">
  <div class="container organization">
    <div class="card user-header">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="flex-shrink-1">
            <div class="img-container">
              <img src="<?= (!empty($user->profile_image) && $user->profile_image === null) ? $user->profile_image : $this->Url->image('no-image.png') ?>" alt="">
            </div>
          </div>
          <div class="flex-grow-1 user-info">
            <div class="d-flex align-items-center">
              <div class="flex-fill">
                <h4><?= h($user->first_name. ' ' .$user->last_name) ?></h4>
                <p>
                  <span><?= __(h($user->gender)) ?></span>|<span class="left"><?= ($user->date_of_birth != null && !empty($user->date_of_birth)) ? $user->date_of_birth->diffInYears(\Cake\I18n\Date::now()) .__(' Years') : '' ?></span>|<span class="left"><?= $user->has('country') ? h($user->country->nicename) : '' ?></span>
                </p>
              </div>
              <div class="flex-fill more-info">
                <div class="row">
                  <div class="col-md-4">
                    <p class="label flex-fill"><?= __('Location') ?>:</p>
                  </div>
                  <div class="col-md-8">
                    <p class="flex-fill"><?= h($user->has('city') ? $user->city->name : ''). ', '. h($user->has('country') ? $user->country->nicename : '') ?></p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <p class="label flex-fill"><?= __('Availability') ?>:</p>
                  </div>
                  <div class="col-md-8">
                    <p class="flex-fill"><?= __(h($user->availability)) ?></p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <p class="label flex-fill"><?= __('Member Since') ?>:</p>
                  </div>
                  <div class="col-md-8">
                    <p class="flex-fill"><?= $user->created->format('M, Y') ?></p>
                  </div>
                </div>
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
        <div class="card org-postlist mb-4">
          <div class="card-header">
            <?= __('Volunteering Interest History') ?>
          </div>
          <div class="card-body">
            <?php
              foreach ($volunteeringInterests as $interest):
                $eventData = $interest->volunteering_oppurtunity->event;
            ?>
            <a href="#">
              <div class="card mb-4">
                <div class="card-body">
                  <div class="d-flex align-items-stretch">
                    <div class="img-container">
                      <img src="<?= $eventData->image ?>" alt="">
                    </div>
                    <div class="card-content d-flex flex-column">
                      <h4 class="card-title"><?= h($eventData->title) ?></h4>
                      <p class="card-text">
                        <?= $this->Text->truncate($this->Text->autoParagraph($eventData->description), 150, ['ellipsis' => '...']) ?>
                      </p>
                    </div>
                  </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                  <div class="location">
                    <p><img src="https://www.countryflags.io/<?= $eventData->country->iso ?>/flat/64.png" alt=""><?= h($eventData->city->name. ', '. $eventData->country->nicename) ?></p>
                  </div>
                  <div class="sector d-flex align-items-center">
                    <?php if ($eventData->has('volunteering_categories')) {foreach ($eventData->volunteering_categories as $volunteering_category): ?><span><?= h($volunteering_category->name) ?></span><?php endforeach;} ?>
                  </div>
                </div>
              </div>
            </a>
            <?php endforeach; ?>

            <?php if ($user->volunteering_interests && !empty($user->volunteering_interests)): if (count($user->volunteering_interests) > 5): ?>
            <a href="#" class="more">SEE MORE
              <i class="fas fa-caret-right"></i>
            </a>
            <?php endif; endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
        // $(".fileinput").fileinput({
        //     browseClass: "btn btn-primary btn-block",
        //     showCaption: false,
        //     showRemove: false,
        //     showUpload: false,
        // });

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
    });
</script>

<?php $this->end(); ?>
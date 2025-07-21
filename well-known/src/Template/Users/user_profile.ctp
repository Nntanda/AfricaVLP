<div class="main">
  <div class="container organization">
    <div class="card user-header">
      <div class="card-body row align-items-center">
        <div class="img-container col-lg-2 col-md-3 col-sm-4">
          <img src="<?= (!empty($user->profile_image) && $user->profile_image !== null) ? $user->profile_image : $this->Url->image('no-image.png') ?>" alt="">
        </div>
        <div class="col-lg-4 col-md-9 col-sm-8 user-info">
          <div class="name-info">
            <h4><?= h($user->first_name. ' ' .$user->last_name) ?></h4>
            <p>
              <span><?= __(h($user->gender)) ?></span>|<span class="left"><?= ($user->date_of_birth != null && !empty($user->date_of_birth)) ? $user->date_of_birth->diffInYears(\Cake\I18n\Date::now()) .__(' Years') : '' ?></span>|<span class="left"><?= $user->has('country') ? h($user->country->nicename) : '' ?></span>
            </p>
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
      </div>
      <div class="col-md-9">
        <div class="card mb-4">
          <div class="card-header d-flex justify-content-between">
            <?= __('About') ?>
          </div>
          <div class="card-body">
            <?= $this->Text->autoParagraph($user->short_profile) ?>
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

          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {

    });
</script>

<?php $this->end(); ?>
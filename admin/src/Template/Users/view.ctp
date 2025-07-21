<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?= __('Volunteer Profile') ?></h3>
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            <div class="kt-subheader__wrapper">
            <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-icon- btn btn-label btn-label-brand btn-upper btn-font-sm btn-bold">
            <?= __('Back to Volunteers') ?></a>
            </div>
        </div>
        <div class="kt-subheader__toolbar">
            <!--  -->
        </div>
    </div>
</div>
<!-- end:: Subheader -->

<!--Begin::App-->
<div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">
    <!--Begin:: App Aside Mobile Toggle-->
    <button class="kt-app__aside-close" id="kt_profile_aside_close">
        <i class="la la-close"></i>
    </button>
    <!--End:: App Aside Mobile Toggle-->

    <!--Begin:: App Aside-->
    <div class="kt-grid__item kt-app__toggle kt-app__aside kt-app__aside--sm kt-app__aside--fit" id="kt_profile_aside">
        <!--Begin:: Portlet-->
        <div class="kt-portlet">
            <div class="kt-portlet__body">
                <div class="kt-widget kt-widget--general-1">
                <div class="kt-media kt-media--brand kt-media--md kt-media--circle">
                    <?php if ($user->profile_image) { ?><img src="<?= h($user->profile_image) ?>" alt="image"> <?php } ?>
                </div>
                <div class="kt-widget__wrapper">
                    <div class="kt-widget__label">
                        <span href="#" class="kt-widget__title">
                            <?= h($user->first_name .' '. $user->last_name) ?>
                        </span>
                    </div>
                </div>
                </div>
            </div>

            <div class="kt-portlet__separator"></div>

            <div class="kt-portlet__body">
                <ul class="kt-nav kt-nav--bolder kt-nav--fit-ver kt-nav--v4" role="tablist">
                <li class="kt-nav__item">
                    <a class="kt-nav__link active" href="<?= $this->Url->build(['action' => 'view', $user->id]) ?>" role="tab">
                    <span class="kt-nav__link-icon">
                        <i class="flaticon2-user"></i>
                    </span>
                    <span class="kt-nav__link-text"><?= __('Personal Information') ?></span>
                    </a>
                </li>
                <li class="kt-nav__item  ">
                    <a class="kt-nav__link" href="<?= $this->Url->build(['action' => 'view', $user->id, 'volunteering_experience']) ?>" role="tab">
                    <span class="kt-nav__link-icon">
                        <i class="flaticon-medal"></i>
                    </span>
                    <span class="kt-nav__link-text"><?= __('Volunteering Experience') ?></span>
                    </a>
                </li>
                <li class="kt-nav__item  ">
                    <a class="kt-nav__link" href="<?= $this->Url->build(['action' => 'view', $user->id, 'volunteering_interests']) ?>" role="tab">
                    <span class="kt-nav__link-icon">
                        <i class="flaticon2-digital-marketing"></i>
                    </span>
                    <span class="kt-nav__link-text"><?= __('Volunteering Interests') ?></span>
                    </a>
                </li>
                </ul>
            </div>
        
        </div>
        <!--End:: Portlet-->
    </div>
    <!--End:: App Aside-->

    <!--Begin:: App Content-->
    <div class="kt-grid__item kt-grid__item--fluid kt-app__content">
        <?php
        switch ($sub) {
            case 'volunteering_experience':
            ?>
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"><?= __('Volunteering Experience') ?></h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <!--begin::Accordion-->
                    <div class="accordion accordion-outline" id="accordionExample6">
                    <?php foreach ($user->volunteer_badges as $badge): ?>
                        <div class="card">
                            <div class="card-header" id="headingOne6">
                                <div class="card-title" data-toggle="collapse" data-target="#collapseOne6" aria-expanded="true" aria-controls="collapseOne6">
                                    <i class="flaticon-medal"></i>
                                    <?= h($badge->name) ?> &nbsp;<span class="badge badge-pill badge-primary"><?= count($badge->volunteering_oppurtunities) ?> Badges</span>
                                </div>
                            </div>
                            <div id="collapseOne6" class="card-body-wrapper collapse show" aria-labelledby="headingOne6" data-parent="#accordionExample6">
                                <div class="card-body">
                                    <div class="kt-scroll" data-scroll="true" data-mobile-height="764" style="height: 400px; overflow: hidden;">
                                        <!--Begin::Timeline -->
                                        <div class="kt-timeline">
                                        <?php foreach ($badge->volunteering_oppurtunities as $oppurtunity): ?>
                                            <!--Begin::Item -->
                                            <div class="kt-timeline__item kt-timeline__item--accent">
                                                <div class="kt-timeline__item-section">
                                                    <div class="kt-timeline__item-section-border">
                                                        <div class="kt-timeline__item-section-icon">
                                                            <i class="flaticon2-checkmark kt-font-success"></i>
                                                        </div>
                                                    </div>
                                                    <span class="kt-timeline__item-datetime"><?= h($oppurtunity->event->start_date->format('d M, Y')) ?></span>
                                                </div>
                                                <span href="" class="kt-timeline__item-text">
                                                    <?= h($oppurtunity->event->title) ?>
                                                </span>
                                                <div class="kt-timeline__item-info">
                                                    <?= h($oppurtunity->volunteering_role->name) ?>
                                                </div>
                                            </div>
                                            <!--End::Item -->
                                        <?php endforeach; ?>
                                        </div>
                                        <!--End::Timeline 1 -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                    <!--end::Accordion-->
                </div>
            </div>
            <?php break;
            case 'volunteering_interests':
            ?>
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"><?= __('Volunteering Interests') ?></h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget-17">
                    <?php foreach ($user->volunteering_interests as $interest): ?>
                        <div class="kt-widget-17__item">
                          <div class="kt-widget-17__product">
                            <div class="kt-widget-17__thumb">
                              <a href="#"><img src="<?= $interest->volunteering_oppurtunity->event->image ?>" class="kt-widget-17__image" alt="" title=""/></a>
                            </div>
                            <div class="kt-widget-17__product-desc">
                              <a href="#">
                                <div class="kt-widget-17__title">
                                  <?= h($interest->volunteering_oppurtunity->event->title) ?>
                                </div>
                              </a>
                              <div class="kt-widget-17__sku">
                                <?= h($interest->volunteering_oppurtunity->volunteering_role->name) ?>
                              </div>
                            </div>
                          </div>
                          <div class="kt-widget-17__prices">
                            <div class="kt-widget-17__total">
                              <?= h($interest->created->format('M d, Y')) ?>
                            </div>
                          </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php break;

            default:
        ?>
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title"><?= __('Personal Information') ?></h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <?php
                            echo $this->Form->create($user, ['url' => ['action' => 'edit']]);
                            echo $this->Form->control('status', ['label' => false, 'class' => 'form-control filter-select', 'templates' => [
                                'inputContainer' => '{{content}}'
                                ], 'empty' => 'Set status', 'required' => true]);
                            echo $this->Form->end();
                        ?>
                    </div>
                </div>
            </div>
            <!-- <form class="kt-form kt-form--label-right" id="kt_profile_form"> -->
            <div class="kt-portlet__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body">
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Profile Image</label>
                            <div class="col-lg-9 col-xl-6">
                                <div class="kt-avatar kt-avatar--outline kt-avatar--circle-" id="kt_profile_avatar">
                                    <div class="kt-avatar__holder" style="background-image: url('<?= $user->profile_image ?>');"></div>
                                    <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" data-original-title="Cancel avatar">
                                    <i class="fa fa-times"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">First Name</label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($user->first_name) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Last Name</label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($user->last_name) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Age</label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($user->date_of_birth ? $user->date_of_birth->diffInYears() .'Years' : '') ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Gender</label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($user->gender) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Location</label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h(($user->has('city') ? $user->city->name .', ' : ''). $user->country->nicename) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Member Since</label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($user->created->format('M d, Y')) ?>">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-xl-3"></label>
                            <div class="col-lg-9 col-xl-6">
                                <h3 class="kt-section__title kt-section__title-sm">Applicants Information:</h3>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Contact Phone</label>
                            <div class="col-lg-9 col-xl-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="la la-phone"></i></span></div>
                                    <input type="text" class="form-control" readonly value="<?= h($user->phone_number) ?>" placeholder="Email" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-last row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Email Address</label>
                            <div class="col-lg-9 col-xl-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="la la-at"></i></span></div>
                                    <input type="text" class="form-control" readonly value="<?= h($user->email) ?>" placeholder="Email" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- </form> -->
        </div>
        <?php
            break;
        }
        ?>
    </div>
    <!--End:: App Content-->
</div>
<!--End::App-->

<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
        $('.filter-select').change(function () {
          $(this).closest('form').submit()
        })
    });
</script>
<?php $this->end() ?>
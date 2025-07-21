<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?= __('Organization Profile'); ?> </h3>
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            <div class="kt-subheader__wrapper">
            <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-icon- btn btn-label btn-label-brand btn-upper btn-font-sm btn-bold">
            <?= __('Back to Organizations') ?></a>
            </div>&nbsp;&nbsp;
      <?php
      echo $this->Html->link('Export', array('controller' => 'organizations', 'action' => 'download_survey', 'id' => $organization->id), array('target' => '_blank'));
      ?>
        </div>
        <div class="kt-subheader__toolbar">
            <!-- <div class="kt-subheader__wrapper"> <a href="#" class="btn btn-icon- btn btn-label btn-label-brand btn-upper btn-font-sm btn-bold"> <i class="flaticon2-add-1"></i> New Blog Post</a> </div> -->
        </div>
    </div>
</div>
<!-- end:: Subheader -->

<!--begin::Dashboard 4-->
<div class="kt-portlet kt-widget kt-widget--fit kt-widget--general-3">
    <div class="kt-portlet__body">
        <div class="kt-widget__top">
            <div class="kt-media kt-media--xl">
                <?php if ($organization->logo) { ?><img src="<?= h($organization->logo) ?>" alt="image"> <?php } ?>
            </div>
            <div class="kt-widget__wrapper">
                <div class="kt-widget__label">
                    <span href="#" class="kt-widget__title">
                        <?= h($organization->name) ?>
                        <small>
                            - <?php if ($organization->is_verified) {
                                echo '<span class="badge badge-pill badge-success"><i class="flaticon2-check-mark"></i></span>';
                            } else {
                                echo 'Unverified ';
                                echo $this->Form->postLink(__('(Mark as Verified)'), ['action' => 'edit', $organization->id], ['data' => ['is_verified' => true]]);
                            } ?>
                        </small>
                    </span>
                    <span class="kt-widget__desc">
                        <?= h($organization->about) ?>
                    </span>
                </div>
                <div class="kt-widget__links">
                    <div class="kt-widget__cont">
                        <div class="kt-widget__link">
                            <i class="flaticon2-send  kt-font-success"></i>
                            <a href="#"><?= h($organization->email) ?></a>
                        </div>
                        <div class="kt-widget__link">
                            <i class="flaticon2-world kt-font-skype"></i>
                            <a href="#"><?= h($organization->user->first_name. ' '. $organization->user->last_name) ?></a>
                        </div>
                    </div>
                </div>
                <div class="kt-widget__stats">
                    <div class="kt-widget__stat" href="#">
                        <span class="kt-widget__value"><?= h($organization->event_count) ?></span>
                        <span class="kt-widget__caption"><?= __('Events') ?></span>
                    </div>
                    <div class="kt-widget__stat" href="#">
                        <span class="kt-widget__value"><?= h($organization->volunteer_count) ?></span>
                        <span class="kt-widget__caption"><?= __('Volunteers') ?></span>
                    </div>
                    <!-- <div class="kt-widget__stat" href="#">
                        <span class="kt-widget__value">21</span>
                        <span class="kt-widget__caption">Initiatives</span>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Dashboard 4-->

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
            <ul class="kt-nav kt-nav--bolder kt-nav--fit-ver kt-nav--v4" role="tablist">
            <li class="kt-nav__item">
                <a class="kt-nav__link active" href="<?= $this->Url->build(['action' => 'view', $organization->id]) ?>" role="tab">
                <span class="kt-nav__link-icon">
                    <i class="flaticon2-file"></i>
                </span>
                <span class="kt-nav__link-text"><?= __('Organizations Information') ?></span>
                </a>
            </li>
            <?php if ($organization->organization_type_id === \App\Model\Table\OrganizationsTable::VOLUNTEERING_ORG): ?>
            <li class="kt-nav__item  ">
                <a class="kt-nav__link" href="<?= $this->Url->build(['action' => 'view', $organization->id, 'volunteers']) ?>" role="tab">
                <span class="kt-nav__link-icon">
                    <i class="flaticon-users-1"></i>
                </span>
                <span class="kt-nav__link-text"><?= __('Volunteers') ?></span>
                </a>
            </li>
            <li class="kt-nav__item  ">
                <a class="kt-nav__link" href="<?= $this->Url->build(['action' => 'view', $organization->id, 'events']) ?>" role="tab">
                <span class="kt-nav__link-icon">
                    <i class="flaticon-event-calendar-symbol"></i>
                </span>
                <span class="kt-nav__link-text"><?= __('Events & Initiatives') ?></span>
                </a>
            </li>
            <?php endif; ?>
            <li class="kt-nav__item  ">
                <a class="kt-nav__link" href="<?= $this->Url->build(['action' => 'view', $organization->id, 'news']) ?>" role="tab">
                <span class="kt-nav__link-icon">
                    <i class="flaticon-sound"></i>
                </span>
                <span class="kt-nav__link-text"><?= __('News') ?></span>
                </a>
            </li>
            <li class="kt-nav__item  ">
                <a class="kt-nav__link" href="<?= $this->Url->build(['action' => 'view', $organization->id, 'resources']) ?>" role="tab">
                <span class="kt-nav__link-icon">
                    <i class="flaticon-layer"></i>
                </span>
                <span class="kt-nav__link-text"><?= __('Resources') ?></span>
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
            case 'news':
            ?>
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"><?= __('News') ?></h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget-17">
                    <?php foreach ($organization->news as $newsData): ?>
                        <div class="kt-widget-17__item">
                          <div class="kt-widget-17__product">
                            <div class="kt-widget-17__thumb">
                              <a href="#"><img src="<?= $newsData->image ?>" class="kt-widget-17__image" alt="" title=""/></a>
                            </div>
                            <div class="kt-widget-17__product-desc">
                              <a href="<?= $this->Url->build(['controller' => 'News', 'action' => 'edit', $newsData->id]) ?>">
                                <div class="kt-widget-17__title">
                                  <?= h($newsData->title) ?>
                                </div>
                              </a>
                              <div class="kt-widget-17__sku">
                                <?php foreach ($newsData->publishing_categories as $publishing_category) { echo $publishing_category->name. ', '; }?> 
                              </div>
                            </div>
                          </div>
                          <div class="kt-widget-17__prices">
                            <div class="kt-widget-17__total">
                              <?= h($newsData->created->format('M d, Y')) ?>
                            </div>
                          </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php break;
            case 'resources':
            ?>
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"><?= __('Resources') ?></h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget-17">
                    <?php foreach ($organization->resources as $resource): ?>
                        <div class="kt-widget-17__item">
                          <div class="kt-widget-17__product">
                            <div class="kt-widget-17__product-desc">
                              <a href="<?= $resource->file_link ?>" target="_blank">
                                <div class="kt-widget-17__title">
                                  <?= h($resource->title) ?>
                                </div>
                              </a>
                              <div class="kt-widget-17__sku">
                                <?= h($resource->resource_type->name) ?>
                              </div>
                            </div>
                          </div>
                          <div class="kt-widget-17__prices">
                            <div class="kt-widget-17__total">
                              <?= h($resource->created->format('M d, Y')) ?>
                            </div>
                          </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php break;
            case 'events':
            if ($organization->organization_type_id === \App\Model\Table\OrganizationsTable::VOLUNTEERING_ORG) {
            ?>
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"><?= __('Events') ?></h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget-17">
                    <?php foreach ($organization->events as $event): ?>
                        <div class="kt-widget-17__item">
                          <div class="kt-widget-17__product">
                            <div class="kt-widget-17__thumb">
                              <a href="#"><img src="<?= $event->image ?>" class="kt-widget-17__image" alt="" title=""/></a>
                            </div>
                            <div class="kt-widget-17__product-desc">
                              <a href="<?= $this->Url->build(['controller' => 'events', 'action' => 'view', $event->id]) ?>">
                                <div class="kt-widget-17__title">
                                  <?= h($event->title) ?>
                                </div>
                              </a>
                              <div class="kt-widget-17__sku">
                                <?php //echo h($event->city->name .', '. $event->country->nicename) ?>
                              </div>
                            </div>
                          </div>
                          <div class="kt-widget-17__prices">
                            <div class="kt-widget-17__total">
                              <?= h($event->created->format('M d, Y')) ?>
                            </div>
                          </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php break;
            }
            case 'volunteers':
            if ($organization->organization_type_id === \App\Model\Table\OrganizationsTable::VOLUNTEERING_ORG) {
            ?>
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"><?= __('Organizations Volunteers') ?></h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <!--begin:: Widgets/User Card 1 -->
                    <?php foreach ($organization->volunteers as $volunteer): ?>
                    <div class="kt-widget kt-widget--general-1">
                        <div class="kt-media kt-media--brand kt-media--lg kt-media--circle">
                            <img src="<?= $volunteer->user->profile_image ? $volunteer->user->profile_image : $this->Url->image("user.png") ?>" alt="image">
                        </div>
                        <div class="kt-widget__wrapper">
                            <div class="kt-widget__label">
                                <a href="#" class="kt-widget__title">
                                    <?= h($volunteer->user->first_name. ' '. $volunteer->user->last_name) ?>
                                </a>
                                <span class="kt-widget__desc">
                                <!--  -->
                                </span>
                            </div>
                            <div class="kt-widget__toolbar">
                                <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'view', $volunteer->user->id]) ?>" class="btn btn-default btn-sm btn-bold btn-upper"><?= __('profile') ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-separator kt-separator--space-md kt-separator--border-dashed"></div>
                    <?php endforeach; ?>
                    <!--end:: Widgets/User Card 1 -->
                </div>
            </div>
            <?php break;
            }

            default:
        ?>
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title"><?= __('Organization Information') ?></h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <?php
                            echo $this->Form->create($organization, ['url' => ['action' => 'edit']]);
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
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Logo') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <div class="kt-avatar kt-avatar--outline kt-avatar--circle-" id="kt_profile_avatar">
                                    <div class="kt-avatar__holder" style="background-image: url('<?= $organization->logo ?>');"></div>
                                    <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" data-original-title="Cancel avatar">
                                    <i class="fa fa-times"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Name') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->name) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Description') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <textarea class="form-control" id="exampleTextarea" rows="3" readonly><?= $organization->about ?></textarea>
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Region</label>
                            <div class="col-lg-9 col-xl-6">
                            <input class="form-control" type="text" value="West Africa">
                            </div>
                        </div> -->
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Location') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->country->nicename) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Date Registered') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->created->format('M d, Y')) ?>">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-xl-3"></label>
                            <div class="col-lg-9 col-xl-6">
                                <h3 class="kt-section__title kt-section__title-sm"><?= __('Applicants Information:') ?></h3>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('First Name') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->user->first_name) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Last Name') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->user->last_name) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Member Since') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->user->created->format('M d, Y')) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Address') ?></label>
                            <div class="col-lg-9 col-xl-6">
                            <?php
                            echo $this->Form->create($organization, ['url' => ['action' => 'edit']]);
                            echo $this->Form->control('address', ['label' => false, 'id' => 'address', 'autocomplete' => 'off', 'required' => true]);
                            echo $this->Form->hidden('lat', ['id' => 'lat', "value" => $organization->lat]);
                            echo $this->Form->hidden('lng', ['id' => 'lng', "value" => $organization->lng]);
                            echo $this->Form->button('Save', ['type' => 'submit', 'name' => 'save', 'id' => 'save']);
                            echo $this->Form->end();
                        ?>
                                <!-- <input class="form-control" type="text" readonly value="<?= h($organization->address) ?>"> -->
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Country') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->country->name) ? h($organization->country->name) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('City') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->city->name) ? h($organization->city->name) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Phone Number') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->phone_number) ? h($organization->phone_number) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Website') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->website) ? h($organization->website) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Volunteering Category') ?></label>
                            <div class="col-lg-9 col-xl-6">
                            <?php foreach ($organization->volunteering_categories as $volunteering_category) { echo $volunteering_category->name. ', '; }?> 
                            
                                <!-- <input class="form-control" type="text" readonly value="<?= h($organization->volunteering_category->name) ? h($organization->volunteering_category->name) : 'N/A' ?>"> -->
                            </div>
                        </div>
                        <div class="form-group form-group-last row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Email Address') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="la la-at"></i></span></div>
                                    <input type="text" class="form-control" readonly value="<?= h($organization->user->email) ?>" placeholder="<?= __('Email') ?>" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div><br>

                        <div class="row">
                            <label class="col-xl-3"></label>
                            <div class="col-lg-9 col-xl-6">
                                <h3 class="kt-section__title kt-section__title-sm"><?= __('Areas of Engagement Information:') ?></h3>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Pan Africanism') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->pan_africanism) ? h($organization->pan_africanism) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Education, skills revolution') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->education_skills) ? h($organization->education_skills) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Health and wellbeing') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->health_wellbeing) ? h($organization->health_wellbeing) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('No poverty, Decent work') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->no_poverty) ? h($organization->no_poverty) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Agriculture, Rural development') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->agriculture_rural) ? h($organization->agriculture_rural) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Democratic values') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->democratic_values) ? h($organization->democratic_values) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Environmental sustainability') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->environmental_sustainability) ? h($organization->environmental_sustainability) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Infrastructure development') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->infrastructure_development) ? h($organization->infrastructure_development) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Peace and Security') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->peace_security) ? h($organization->peace_security) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Culture') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->culture) ? h($organization->culture) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Gender equality') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->gender_inequality) ? h($organization->gender_inequality) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Youth Development') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->youth_empowerment) ? h($organization->youth_empowerment) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Reduced inequality') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->reduced_inequality) ? h($organization->reduced_inequality) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Sustainable Cities') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->sustainable_city) ? h($organization->sustainable_city) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Responsible Consumption') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->responsible_consumption) ? h($organization->responsible_consumption) : 'N/A' ?>">
                            </div>
                        </div><br>
                        <div class="row">
                            <label class="col-xl-3"></label>
                            <div class="col-lg-9 col-xl-6">
                                <h3 class="kt-section__title kt-section__title-sm"><?= __('Resources and materials Information:') ?></h3>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Does your country have a national volunteer policy/framework?') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->pan_africanism_resources) ? h($organization->pan_africanism_resources) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Does your country have a national volunteer policy/framework?') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->pan_africanism_organiz_pol) ? h($organization->pan_africanism_organiz_pol) : 'N/A' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"><?= __('Does your organization have an annual report?') ?></label>
                            <div class="col-lg-9 col-xl-6">
                                <input class="form-control" type="text" readonly value="<?= h($organization->pan_africanism_organiz_annu) ? h($organization->pan_africanism_organiz_annu) : 'N/A' ?>">
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

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<?php $this->Html->css("https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css", ['block' => 'css']) ?>
<?php $this->Html->script("https://cdn.jsdelivr.net/npm/exif-js", ['block' => 'script']) ?>
<?php $this->Html->script("https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.js", ['block' => 'script']) ?>
<?php $this->Html->script("https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js", ['block' => 'script']) ?>
<?php $this->Html->script("https://maps.googleapis.com/maps/api/js?key=AIzaSyBQzkAnV6V7naTqRsuMkfGENsBjpaFSUt4&libraries=places", ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<?= $this->element('address-autocomplete') ?>

<script>
    $(document).ready(function () {
        $('.filter-select').change(function () {
          $(this).closest('form').submit()
        })
    });
</script>
<?php $this->end() ?>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\City $city
 */
?>

            <!-- begin:: Subheader -->
            <div class="kt-subheader   kt-grid__item" id="kt_subheader">
              <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                  <h3 class="kt-subheader__title"><?= __('Add City') ?></h3>
                  <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                  <div class="kt-subheader__wrapper">
                    <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-icon- btn btn-label btn-label-brand btn-upper btn-font-sm btn-bold">
                    <?= __('Back to Cities') ?></a>
                  </div>
                </div>
                <div class="kt-subheader__toolbar">
                  <!-- <div class="kt-subheader__wrapper"> <a href="#" class="btn btn-icon- btn btn-label btn-label-brand btn-upper btn-font-sm btn-bold"> <i class="flaticon2-add-1"></i> New Blog Post</a> </div> -->
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

                <!--Begin:: App Content-->
                <div class="kt-grid__item kt-grid__item--fluid kt-app__content">
                  <div class="kt-portlet">
                    <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"><?= __('Add City') ?></h3>
                      </div>
                      <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                        </div>
                      </div>
                    </div>
                    <?= $this->Form->create($city) ?>
                    <!-- <form class="kt-form kt-form--label-right" id="kt_profile_form"> -->
                      <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                          <div class="kt-section__body">
                          <?php
                                echo $this->Form->control('country_id', ['options' => $countries, 'empty' => true]);
                                echo $this->Form->control('name');
                                echo $this->Form->control('status');
                            ?>
                            
                          </div>
                        </div>

                      </div>

                      <div class="kt-portlet__foot">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                            <!-- Foot caption -->
                            </div>
                            <div class="col-lg-6 kt-align-right">
                            <?= $this->Form->button(__('Submit')) ?>
                            <!-- <button type="button" class="btn btn-primary">Save Changes</button> -->
                            </div>
                        </div>
                        </div>
                    <!-- </form> -->
                    <?= $this->Form->end() ?>
                  </div>
                </div>
                <!--End:: App Content-->
              </div>
              <!--End::App-->


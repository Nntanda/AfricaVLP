<?php
/**
 * @var \App\View\AppView $this
 */
$this->layout = 'login';
?>
        <!--begin::Item-->
        <div class="kt-grid__item  kt-grid  kt-grid--ver  kt-grid__item--fluid">
          <!--begin::Body-->
          <div class="kt-login-v2__body">
            <!--begin::Wrapper-->
            <div class="kt-login-v2__wrapper">
              <div class="kt-login-v2__container">
                <div class="kt-login-v2__title">
                  <h3><?= __('Sign to Account') ?></h3>
                </div>
                <?= $this->Flash->render() ?>

                <!--begin::Form-->
                <?= $this->Form->create(false, ['class' => 'kt-login-v2__form kt-form']) ?>
                <?php
                    echo $this->Form->control('email', ['label' => false, 'placeholder' => 'Email']);
                    echo $this->Form->control('password', ['label' => false, 'placeholder' => 'Password']);
                ?>
                <!--begin::Action-->
                <div class="kt-login-v2__actions">
                  <a href="#" class="kt-link kt-link--brand">
                    <!-- Forgot Password ? -->
                  </a>
                  <?= $this->Form->button(__('Sign In'), ['class' => 'btn btn-brand btn-elevate', 'id' => 'kt_login_submit']) ?>
                </div>
                <!--end::Action-->
                <?= $this->Form->end() ?>
                </form>
                <!--end::Form-->
              </div>
            </div>
            <!--end::Wrapper-->

            <!--begin::Image-->
            <div class="kt-login-v2__image d-flex flex-column">
              <img src="<?= $this->Url->image('logo-color.png') ?>" alt="" class="login-logo">
              <p><?= __('AU-VLP is a continental development program that recruits and works with youth volunteers, to work in all 54 countries across the African Union') ?></p>
            </div>
            <!--begin::Image-->
          </div>
          <!--begin::Body-->
        </div>
        <!--end::Item-->

        <!--begin::Item-->
        <div class="kt-grid__item">
          <div class="kt-login-v2__footer">
            <div class="kt-login-v2__info">
              <a href="#" class="kt-link">&copy; <? __('2018 African Union') ?></a>
            </div>
          </div>
        </div>
        <!--end::Item-->


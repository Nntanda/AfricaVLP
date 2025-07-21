<?php $this->layout = 'home'; ?>
<div class="main">
    <div class="container">
        <div class="card login-card">
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
                    <div class="card-body basic-info">
                        <h5 class="card-title"><?= __('Change Password') ?></h5>
                        <?= $this->Flash->render() ?>
                        <?= $this->Form->create() ?>
                        <div class="form-group">
                            <?php
                                if ($validatePassword) {
                                    echo $this->Form->control('current_password', ['placeholder' => __('Current Password'), 'label' => __('Current Password'), 'type' => 'password', 'required' => true]);
                                }
                                echo $this->Form->control('password', ['placeholder' => __('New Password'), 'label' => __('New Password'), 'required' => true]);
                                echo $this->Form->control('confirm_password', ['placeholder' => __('Confirm Password'), 'label' => __('Confirm Password'), 'type' => 'password', 'required' => true]);
                            ?>
                        </div>
                        <div class="btn-group d-flex align-items-center">
                            <button href="submit" class="btn"><?= __('Submit') ?></button>
                            <p>
                                <?= __("Don’t have an account yet?") ?> <a href="<?= $this->Url->build(['action' => 'createAccount']) ?>"><?= __('Register') ?></a> <br/>
                                | <a href="<?= $this->Url->build(['action' => 'login']) ?>"><?= __('Login') ?></a>
                            </p>
                        </div>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

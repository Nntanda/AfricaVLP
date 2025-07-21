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
                        <h5 class="card-title"><?= __('Request Reset Password') ?></h5>
                        <?= $this->Flash->render() ?>
                        <?= $this->Form->create() ?>
                        <div class="form-group">
                            <?php
                                echo $this->Form->control('reference', ['placeholder' => __('Email address'), 'label' => __('Email address'), 'required' => true]);
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

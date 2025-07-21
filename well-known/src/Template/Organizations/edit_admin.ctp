<div class="container">
    <div class="d-flex justify-content-between top-line align-items-center">
        <h3><?= __('Edit Admin') ?></h3>
    </div>
    <div class="page-title">
        <p></p>
    </div>
    <?= $this->Form->create($organizationUser) ?>
    <div class="row basic-info">
        <div class="col-md-8">
            <?= $this->Form->control('email', ['placeholder' => __('Email'), 'disabled' => true]) ?>
        </div>
        <div class="col-md-8">
            <label for="#role"><?= __('Role') ?></label>
            <?= $this->Form->control('role', ['empty' => __('Select role'), 'label' => false, 'options' => ['admin' => 'Admin', 'basic' => 'Basic'], 'required' => true ]) ?>
        </div>
        <div class="col-md-8">
            <label for="#role"><?= __('Status') ?></label>
            <?= $this->Form->control('status', ['label' => false, 'required' => true ]) ?>
        </div>
    </div>
    <div class="other-info">
        <div class="d-flex">
            <button type="submit" class="btn ml-auto"><?= __('Save') ?></button>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>

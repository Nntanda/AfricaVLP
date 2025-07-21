<div class="container">
    <div class="d-flex justify-content-between top-line align-items-center">
        <h3><?= __('Add Admin') ?></h3>
    </div>
    <div class="page-title">
        <p></p>
    </div>
    <?= $this->Form->create() ?>
    <div class="row basic-info">
        <div class="col-md-8">
            <?= $this->Form->control('email', ['placeholder' => 'Email', 'required' => true]) ?>
        </div>
        <div class="col-md-4">
            <!-- <label for=""> Volunteering Role </label> -->
            <?= $this->Form->control('role', ['empty' => __('Select role'), 'label' => false, 'options' => ['admin' => __('Admin'), 'basic' => __('Basic')] ]) ?>
        </div>
    </div>
    <div class="other-info">
        <div class="d-flex">
            <button type="submit" class="btn ml-auto"><?= __('Add Admin') ?></button>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>

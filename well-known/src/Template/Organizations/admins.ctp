
<div class="container">
    <div class="d-flex justify-content-between top-line align-items-center">
        <h3><?= __('Admins') ?></h3>
    </div>

    <div class="row">
        <div class="col-md-8">
            <label for=""><?= __('Search') ?></label>
            <input type="text" class="form-control" placeholder="Search">
        </div>
        <div class="col-md-4">
            <label for=""><?= __('Status') ?></label>
            <select name="" id="" class="form-control"></select>
        </div>
    </div>

    <div class="recent-updates program-table mt-3">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                <th scope="col"><?= __('Admin') ?></th>
                <th scope="col"><?= __('Role') ?></th>
                <th scope="col"><?= __('Date added') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Action') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($admins as $admin): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="<?= ($admin->user->profile_image && !empty($admin->user->profile_image)) ? $admin->image : $this->Url->image('programes.jpg') ?>" alt="">
                            <div class="vol-int-name">
                                <h5><?= h($admin->user->details) ?></h5>
                                <p>
                                    <!--  -->
                                </p>
                            </div>
                        </div>
                    </td>
                    <td><?= h($admin->role) ?></td>
                    <td><?= $admin->created->format('d/m/y') ?></td>
                    <td><?= $this->getStatusLabel($admin->status) ?></td>
                    <td>
                        <div class="dropdown">
                        <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?= $this->Url->image('table-menu.svg') ?>" alt="">
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                            <!-- <a class="dropdown-item" href="#">View</a> -->
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= $this->Url->build(['_name' => 'organization:actions', 'action' => 'editAdmin', 'id' => $organization->id, $admin->id]) ?>"><?= __('Edit') ?></a>
                            <div class="dropdown-divider"></div>
                            <!-- <a class="dropdown-item" href="#">Deactive</a> -->
                        </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($admins->count() < 1): ?>
        <div class="alert alert-info" role="alert">
            <?= __('No record found') ?>
        </div>
    <?php else: ?>
    <div class="row justify-content-center">
        <?= $this->element('Navigation/pagination') ?>
    </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center">
        <h3><?= __('Pending Invites') ?></h3>
    </div>
    <div class="recent-updates program-table mt-3">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                <th scope="col"><?= __('Email') ?></th>
                <th scope="col"><?= __('Role') ?></th>
                <th scope="col"><?= __('Date sent') ?></th>
                <th scope="col"><?= __('Action') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($pendingInvites as $invite): ?>
                <tr>
                    <td><?= h($invite->email) ?></td>
                    <td><?= h($invite->role) ?></td>
                    <td><?= $invite->created->format('d/m/y') ?></td>
                    <td>
                        <div class="dropdown">
                            <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="<?= $this->Url->image('table-menu.svg') ?>" alt="">
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="<?= $this->Url->build(['_name' => 'organization:actions', 'action' => 'editAdmin', 'id' => $organization->id, $admin->id]) ?>"><?= __('Edit') ?></a>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($pendingInvites->count() < 1): ?>
        <div class="alert alert-info" role="alert">
            <?= __('No record found') ?>
        </div>
    <?php endif; ?>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<?php $this->Html->script("https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js", ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
        $("#volunteering-categories-ids").select2()

    });

</script>

<?php $this->end(); ?>
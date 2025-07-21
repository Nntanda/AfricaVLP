
<div class="container">
    <div class="d-flex justify-content-between top-line align-items-center">
        <h3><?= __('Resources') ?></h3>
    </div>

    <div class="row">
        <div class="col-md-8">
            <?= $this->Form->create(false, ['type' => 'get']) ?>
                <label for=""><?= __('Search') ?></label>
                <input type="text" class="search-query form-control" value="<?= $search ?>" name="s" placeholder="<?= __('Search resource') ?>"/>
            <?= $this->Form->end() ?>
        </div>
        <div class="col-md-4">
            <label for=""><?= __('Resource type') ?></label>
            <?php 
                echo $this->Form->create(false, ['type' => 'Get']);
                echo $this->Form->control('resource_type', ['empty' => __('Select Type'), 'label' => false, 'class' => 'form-control filter-select', 'templates' => [
                    'inputContainer' => '{{content}}'
                ], 'style' => 'max-width: 250px;', 'value' => $resource_type,  ]);
                echo $this->Form->end();
            ?>
        </div>
    </div>

    <div class="recent-updates program-table mt-3">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                <th scope="col"><?= __('Title') ?></th>
                <th scope="col"><?= __('Type') ?></th>
                <th scope="col"><?= __('Country') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Action') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($resources as $resource): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="vol-int-name">
                                <h5><?= h($resource->title) ?></h5>
                                <p>
                                    <?= h($resource->created->format('d/m/y')) ?>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td><?= $resource->resource_type->name; ?></td>
                    <td><?= $resource->has('country') ? $resource->country->nicename : __('All') ?></td>
                    <td><?= $this->getStatusLabel($resource->status) ?></td>
                    <td>
                        <div class="dropdown">
                            <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="<?= $this->Url->image('table-menu.svg') ?>" alt="">
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="<?= $resource->file_link ?>" target="_blank"><?= __('View') ?></a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= $this->Url->build(['_name' => 'organization:actions', 'action' => 'editResource', 'id' => $organization->id, $resource->id]) ?>"><?= __('Edit') ?></a>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($resources->count() < 1): ?>
        <div class="alert alert-info" role="alert">
            <?= __('No record found') ?>
        </div>
    <?php else: ?>
    <div class="row justify-content-center">
        <?= $this->element('Navigation/pagination') ?>
    </div>
    <?php endif; ?>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<?php $this->Html->script("https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js", ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
        $("#volunteering-categories-ids").select2()

        
        $('.filter-select').change(function () {
          $(this).closest('form').submit()
        })
    });

</script>

<?php $this->end(); ?>
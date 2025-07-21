
<div class="container">
    <div class="d-flex justify-content-between top-line align-items-center">
        <h3><?= __('News') ?></h3>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $this->Form->create(false, ['type' => 'get']) ?>
                <label for=""><?= __('Search') ?></label>
                <input type="text" class="search-query form-control" value="<?= $search ?>" name="s" placeholder="<?= __('Search news') ?>"/>
            <?= $this->Form->end() ?>
        </div>
        <div class="col-md-3">
            <label for=""><?= __('News status') ?></label>
            <?php 
                echo $this->Form->create(false, ['type' => 'Get']);
                echo $this->Form->control('status', ['empty' => __('Select Status'), 'label' => false, 'class' => 'form-control filter-select', 'templates' => [
                    'inputContainer' => '{{content}}'
                ], 'style' => 'max-width: 250px;', 'value' => $status, ]);
                echo $this->Form->end();
            ?>
        </div>
        <div class="col-md-3">
            <label for=""><?= __('Volunteering category') ?></label>
            <?php 
                echo $this->Form->create(false, ['type' => 'Get']);
                echo $this->Form->control('cat', ['empty' => __('Select Category'), 'label' => false, 'class' => 'form-control filter-select', 'templates' => [
                    'inputContainer' => '{{content}}'
                ], 'style' => 'max-width: 250px;', 'value' => $category_id, 'options' => $volunteering_categories ]);
                echo $this->Form->end();
            ?>
        </div>
    </div>

    <div class="recent-updates program-table mt-3">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                <th scope="col"><?= __('Title') ?></th>
                <th scope="col"><?= __('Date Posted') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Action') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($news as $newsData): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <?php if ($newsData->image && !empty($newsData->image)) { ?><img src="<?= $newsData->image ?>" alt=""> <?php } ?>
                            <div class="vol-int-name">
                                <h5><?= h($newsData->title) ?></h5>
                                <p>
                                    <?php foreach ($newsData->publishing_categories as $publishing_category): ?><span><?= h($publishing_category->name) ?>, </span><?php endforeach; ?>
                                    |
                                    <?php foreach ($newsData->volunteering_categories as $volunteering_category): ?><span><?= h($volunteering_category->name) ?>, </span><?php endforeach; ?>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td><?= $newsData->created->format('d/m/y') ?></td>
                    <td><?= $this->getStatusLabel($newsData->status, 'news') ?></td>
                    <td>
                        <div class="dropdown">
                        <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?= $this->Url->image('table-menu.svg') ?>" alt="">
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                            <!-- <a class="dropdown-item" href="">View</a>
                            <div class="dropdown-divider"></div> -->
                            <a class="dropdown-item" href="<?= $this->Url->build(['_name' => 'organization:actions', 'action' => 'editNews', 'id' => $organization->id, $newsData->id]) ?>"><?= __('Edit') ?></a>
                            <div class="dropdown-divider"></div>
                            <!-- <a class="dropdown-item" href="#">Suspend</a> -->
                        </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($news->count() < 1): ?>
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

<div class="container">
    <?= $this->Form->create($event, ['type' => 'file']) ?>
    <div class="">
        <div class="page-title top-line">
            <h3><?= __('Volunteering Info') ?></h3>
        </div>
        <div class="form-text">
            <div class="row">
                <div class="col-md-4">
                    <?= $this->Form->control('has_remunerations', ['type' => 'checkbox']); ?>
                </div>
            </div>

            <div class="d-flex justify-content-between top-line align-items-center mt-3">
                <h5><?= __('Volunteering roles') ?></h5>
            </div>
            
            <div id="volunteering-roles">
                <div class="row role top-line">
                    <div class="col-md-3">
                        <label for=""><?= __('Volunteering Role') ?></label>
                        <?= $this->Form->control('volunteering_oppurtunities.0.volunteering_role_id', ['empty' => __('Select'), 'label' => false, 'options' => $volunteering_roles, 'class' => 'form-control select2', 'id' => 'volunteering-role-input']) ?>
                    </div>
                    <div class="col-md-3">
                        <label for=""><?= __('Number Volunteers Needed') ?></label>
                        <?= $this->Form->control('volunteering_oppurtunities.0.number', ['label' => false]) ?>
                    </div>
                    <div class="col-md-3">
                        <label for=""><?= __('Volunteering Categories') ?></label>
                        <?= $this->Form->control('volunteering_oppurtunities.0.volunteering_categories._ids', ['label' => false, 'options' => $volunteering_categories, 'class' => 'form-control select2', 'id' => 'volunteering-categories-input']) ?>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between top-line align-items-center">
                <a href="#" id="add-vr" class="btn-default btn-sm ml-auto" onClick="return false"><i class="fa fa-plus"></i><?= __('Add more') ?></a>
            </div>
        </div>
    </div>

    <div class="other-info basic-info">
        <div class="d-flex">
            <button type="submit" class="btn ml-auto"><?= __('Save') ?></button>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>

<?php $this->Html->css("https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css", ['block' => 'css']) ?>
<?php $this->Html->script("https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js", ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
        $(".select2").select2()

        let c = 1;
        $("#add-vr").click(function () {
            roles = $("#volunteering-role-input").html();
            categories = $("#volunteering-categories-input").html();

            roleDiv = document.createElement("div");

            roleDiv.className = "row role top-line";
            volunteering_role = `
            <div class="col-md-3">
                <label for=""><?= __('Number Volunteers Needed') ?></label>
                <div class="form-label-group input select">
                    <select name="volunteering_oppurtunities[`+c+`][volunteering_role_id]" class="form-control select2">`+roles+`</select>
                </div>
            </div>
            `;
            volunteering_categories = `
            <div class="col-md-3">
                <label for=""><?= __('Volunteering Categories') ?></label>
                <div class="form-label-group input select">
                    <select name="volunteering_oppurtunities[`+c+`][volunteering_categories][_ids][]" class="form-control select2" multiple>`+categories+`</select>
                </div>
            </div>
            `;
            number = `
            <div class="col-md-3">
                <label for=""><?= __('Number Volunteers Needed') ?></label>
                <div class="form-label-group input number"><input type="number" name="volunteering_oppurtunities[`+c+`][number]" class="form-control"></div>
            </div>
            `;
            removebtn = `
            <div class="col-md-3 align-self-end d-flex">
                <a href="#" class="btn-default btn-sm ml-auto rmvBtn" onClick="return false">
                    <img src="<?= $this->Url->image('close-dark.svg') ?>" alt="">
                </a>
            </div>
            `;

            roleDiv.innerHTML = volunteering_role + number + volunteering_categories + removebtn
            
            $('#volunteering-roles').append(roleDiv)
            $(".select2").select2()
            c++;
        })

        $("#volunteering-roles").on('click', '.rmvBtn', function() {
            $(this).closest('.role').remove()
        });
    });

</script>

<?php $this->end(); ?>
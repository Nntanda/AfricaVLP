<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CategoryOfResource $categoryOfResource
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $categoryOfResource->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $categoryOfResource->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Category Of Resources'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="categoryOfResources form large-9 medium-8 columns content">
    <?= $this->Form->create($categoryOfResource) ?>
    <fieldset>
        <legend><?= __('Edit Category Of Resource') ?></legend>
        <ul class="nav nav-tabs mt-2" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#en" role="tab" aria-controls="home" aria-selected="true">English</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#fr" role="tab" aria-controls="profile" aria-selected="false">Français</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#pt" role="tab" aria-controls="contact" aria-selected="false">Português</a>
            </li>
        </ul>
        <div class="tab-content mb-2" id="myTabContent">
            <div class="tab-pane fade show active" id="en" role="tabpanel" aria-labelledby="home-tab">
            <button type="button" class="translate" data-lang="en">Translate</button>
                <?php
                    echo $this->Form->control('name', ['class' => 'w-name']);
                    echo $this->Form->control('status');
                ?>
            </div>
            <div class="tab-pane fade" id="fr" role="tabpanel" aria-labelledby="profile-tab">
            <button type="button" class="translate" data-lang="en">Translate</button>
                <?php
                    echo $this->Form->control('_translations.fr_FR.name', ['class' => 'w-name']);
                ?>
            </div>
            <div class="tab-pane fade" id="pt" role="tabpanel" aria-labelledby="contact-tab">
            <button type="button" class="translate" data-lang="en">Translate</button>
                <?php
                    echo $this->Form->control('_translations.pt_PT.name', ['class' => 'w-name']);
                ?>
            </div>
        </div>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script>
    $(document).ready(function () {
        $(".translate").click(function () {
            let trBtn = $(this);
            console.log('Translating...');
            $(this).html('Translating...');
            srcLang = trBtn.data('lang');
            let langs = ['en', 'fr', 'pt'];
            let srcTexts = {}
            let transData = {}

            srcTexts[0] = $("#"+srcLang+" .w-name").val();

            langs.forEach(lang => {
                if (lang !== srcLang) {
                    let texts = {}
                    $.ajax({
                        type: "POST",
                        url: "<?= $this->Url->build('/translate') ?>",
                        data: {'sourceTexts': srcTexts, 'targetLanguage': lang},
                        success: function (data) {
                            $("#"+lang+" .w-name").val(data[0].text);
                            trBtn.html('Translate');
                        },
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                        }
                    });
                }
            })

        })
    });
</script>
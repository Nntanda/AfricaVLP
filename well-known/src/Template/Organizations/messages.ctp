
<div class="container">
    <div class="d-flex justify-content-between top-line align-items-center">
        <h3><?= __('Messages') ?></h3>
        <small><button class="btn btn-small" data-toggle="modal" data-target="#messageModal"> <i class="fa fa-plus"></i> <?= __('New message') ?></button></small>
    </div>

    <div class="modal fade" id="messageModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><?= __('New message') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= $this->Form->create($conversation, ['url' => ['action' => 'newMessage', 'id' => $organization->id]]) ?>
            <div class="modal-body">
                <div class="form-group other-info basic-info">
                    <div class="row">
                        <div class="col-md-12">
                            <label><?= __('Organization') ?></label>
                            <?= $this->Form->control('conversation_participants.organization_id', ['label' => false, 'empty' => __('Select Organization'), 'id' => 'organization-id', ]) ?>
                        </div>
                        <div class="col-md-12">
                            <?= $this->Form->control('conversation_messages.message', ['placeholder' => __('Message'), 'label' => __('Message'), 'row' => 5]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-secondary"> <?= __('Send') ?> </button>
            </div>
            <?= $this->Form->end() ?>
            </div>
        </div>
    </div>

    <!-- <div class="row">
        <div class="col-md-8">
            <label for="">Search</label>
            <input type="text" class="form-control" placeholder="Search message">
        </div>
    </div> -->

    <div class="updates alumni-list">
        <div class="container updates-tab">
        <?php foreach($conversations as $conversation): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row no-gutters d-flex align-items-stretch">
                        <div class="col-md-9">
                            <div class="card-content d-flex flex-column">
                                <h4 class="card-title"><?= h($conversation->conversation_participants[0]->organization->name) ?></h4>
                                <p class="card-text">
                                    <?= ($conversation->conversation_messages[0]->organization_id === $organization->id) ? "<strong>". __('Me'). ": </strong>" : '' ?>
                                    <?= $this->Text->truncate($conversation->conversation_messages[0]->message, 150, ['ellipsis' => '...']) ?>
                                </p>
                                <p class="card-text d-flex mt-auto">
                                    <small class="text-muted flex-fill"><?= __('Date Created') ?>:
                                    <span><?= ($conversation->conversation_messages[0]->created->format('M d, Y')) ?></span></small>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-end">
                            <a href="<?= $this->Url->build(['action' => 'message', 'id' => $organization->id, $conversation->id]) ?>" class="btn btn-small"><?= __('Read Message') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>

    </div>
    <?php if ($conversations->count() < 1): ?>
        <div class="alert alert-info" role="alert">
            <?= __('No record found') ?>
        </div>
    <?php else: ?>
    <div class="row justify-content-center">
        <?= $this->element('Navigation/pagination') ?>
    </div>
    <?php endif; ?>
</div>

<?php $this->Html->css("https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css", ['block' => 'css']) ?>
<?php $this->Html->script("https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js", ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
        $("#organization-id").select2({
            dropdownParent: $('#messageModal'),
            width: '80%'
        });
    });

</script>

<?php $this->end(); ?>
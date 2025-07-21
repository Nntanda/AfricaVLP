
<div class="container">
    <div class="d-flex justify-content-between top-line align-items-center">
        <h3><?= __('Message') ?></h3>
        <span><a href="<?= $this->Url->build(['action' => 'messages', 'id' => $organization->id]) ?>"><?= __('Back to messages') ?></a></span>
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
                            <?= $this->Form->control('conversation_participants.organization_id', ['label' => false, 'empty' => 'Select Organization', 'id' => 'organization-id']) ?>
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

    <div class="updates alumni-list">
        <div class="container updates-tab">
            <div class="card mb-4 forum-card">
                <div class="card-body">
                    <div class="row no-gutters d-flex align-items-stretch">
                        <div class="col-md-10">
                            <div class="card-content d-flex flex-column">
                                <h4 class="card-title"><?= h($conversation->conversation_participants[0]->organization->name) ?></h4>
                                <p class="card-text"><?= h($conversation->conversation_participants[0]->organization->about) ?></p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php foreach($conversation->conversation_messages as $date => $messages): ?>
                        <div class="date-line d-flex justify-content-center">
                            <div class="line"></div>
                            <div class=""> <?= h($date) ?> </div>
                            <div class="line"></div>
                        </div>
                        <?php foreach ($messages as $messageData): ?>
                        <div class="chat-user d-flex">
                            <div class="user-img">
                                <img src="<?= ($messageData->user->profile_image && !empty($messageData->user->profile_image)) ? $messageData->user->profile_image : $this->Url->image('no-image.jpg') ?>" alt="">
                            </div>
                            <div class="name-side">
                                <h5><?= h($messageData->organization->name) ?>
                                    <small><?= h('('.$messageData->user->details.')') ?></small>
                                    <span class="time">- <?= ($messageData->created->format('g:iA')) ?></span></h5>
                                <p><?= h($messageData->message) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>

                <?= $this->Form->create() ?>
                <div class="card-footer d-flex justify-content-between align-items-start basic-info">
                    <div class="user-img">
                        <img src="<?= ($authUser['profile_image'] && !empty($authUser['profile_image'])) ? $authUser['profile_image'] : $this->Url->image('no-image.jpg') ?>" alt="">
                    </div>
                    <div class="flex-grow-1 px-2" style="margin-bottom: -18px">
                        <?= $this->Form->control('message', ['type' => 'textarea', 'placeholder' => __('Message'), 'required' => true, 'style' => 'margin: 0;']) ?>
                    </div>
                    <button type="submit" class="align-self-end border-0 d-flex flex-column align-items-center">
                        <img src="<?= $this->Url->image('send.svg') ?>" alt="" class="svg">
                        <span><?= __('Send') ?></span>
                    </button>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>

    </div>
</div>

<div class="container">
    <div class="d-flex justify-content-between top-line align-items-center">
        <h3><?= __('AU Messages') ?></h3>
    </div>

    <div class="updates alumni-list">
        <div class="container updates-tab">
            <div class="card mb-4 forum-card">
                <div class="card-body">
                    <div class="row no-gutters d-flex align-items-stretch">
                        <div class="col-md-10">
                            <div class="card-content d-flex flex-column">
                                <h4 class="card-title"><?= __('AU Support') ?></h4>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php foreach($conversation->admin_support_messages as $date => $messages): ?>
                        <div class="date-line d-flex justify-content-center">
                            <div class="line"></div>
                            <div class=""> <?= h($date) ?> </div>
                            <div class="line"></div>
                        </div>
                        <?php foreach ($messages as $messageData): ?>
                        <div class="chat-user d-flex">
                            <!-- <div class="user-img">
                                <img src="" alt="">
                            </div> -->
                            <div class="name-side">
                                <h5><?= h($messageData->sender === 'au' ? __('AU Support') : $messageData->sender_user->details) ?>
                                    <span class="time">- <?= ($messageData->created->format('g:iA')) ?></span></h5>
                                <p><?= $this->Text->autoParagraph($messageData->message) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>

                <?= $this->Form->create($supportMessage) ?>
                <div class="card-footer d-flex justify-content-between align-items-start basic-info">
                    <div class="user-img">
                        <img src="<?= ($authUser['profile_image'] && !empty($authUser['profile_image'])) ? $authUser['profile_image'] : $this->Url->image('no-image.jpg') ?>" alt="">
                    </div>
                    <div class="flex-grow-1 px-2" style="margin-bottom: -18px">
                        <?= $this->Form->control('message', ['type' => 'textarea', 'placeholder' => 'Message', 'required' => true, 'style' => 'margin: 0;']) ?>
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

<div class="main program">
    <div class="container organization">
    <div class="row">
        <div class="col-md-9">
            <div class="card program-main">
                <div class="img-container">
                    <img src="<?= $event->image ?>" class="card-img-top" alt="...">
                </div>
                <div class="card-footer">
                    <h3 class="card-title"><?= h($event->title) ?></h3>
                    <div class="program-tags d-flex justify-content-between">
                        <div class="">
                            <p><img src="https://www.countryflags.io/<?= $event->country->iso ?>/flat/64.png" alt=""><?= h(($event->has('city') ? $event->city->name. ', ' : ''). $event->country->nicename) ?></p>
                        </div>
                        <div class="">
                            <p><img src="<?= $this->Url->image('date.svg') ?>" alt="" class="svg"><?= $event->created->format('M d, Y') ?></p>
                        </div>
                        <div class="org">
                            <p>
                                <img src="<?= $this->Url->image('org-icon.svg') ?>" alt="" class="svg">
                                <?php if ($event->has('volunteering_categories')) {foreach ($event->volunteering_categories as $volunteering_category): ?><span><?= h($volunteering_category->name) ?></span><?php endforeach;} ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-content">
                <?= $this->Text->autoParagraph($event->description) ?>
            </p>
            <?= $this->element('user-feedback', [
                'object_id' => $event->id,
                'object_model' => 'Events'
            ]); ?>
        </div>

        <div class="col-md-3 organization-side">
            <h4><?= __('About Publisher') ?></h4>
            <?php if ($event->has('organization')): ?>
            <div class="publisher">
                <div class="img-container">
                    <img src="<?= (!empty($event->organization->logo) && $event->organization->logo !== null) ? $event->organization->logo : $this->Url->image('no-logo.jpg') ?>" alt="">
                </div>
                <div class="name">
                    <h2><?= h($event->organization->name) .($event->organization->is_verified ? ' <span class="badge badge-success rounded-circle text-light"><i class="fa fa-check"></i><span>' : '') ?></h2>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <p class="card-text">
                        <?= $this->Text->autoParagraph($event->organization->about) ?>
                    </p>
                </div>
            </div>
            <!-- <a href="#" class="btn btn-long">View Publisher's Page</a> -->
            <?php else: ?>
                <div class="publisher">
                    <div class="img-container">
                        <img src="<?= $this->Url->image('organizer.jpg') ?>" alt="">
                    </div>
                    <div class="name">
                        <h2>AU</h2>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($event->requesting_volunteers): $options = []; ?>
                <h4><?= __('Volunteering Oppurtunnities') ?></h4>
                <div class="card quick-link">
                    <ul class="list-group list-group-flush">
                    <?php foreach ($event->volunteering_oppurtunities as $volunteering_oppurtunity): 
                        $options[$volunteering_oppurtunity->id] = $volunteering_oppurtunity->volunteering_role->name; ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <?= h($volunteering_oppurtunity->volunteering_role->name) ?>
                            <span>(<?= h($volunteering_oppurtunity->number) ?>)</span>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </div>

                <?php if (isset($authUser)): ?>
                    <h4 class="top-line"><?= __('Show interest') ?></h4>
                    <div>
                        <?= $this->Form->create(false, ['url' => ['action' => 'showInterest']]) ?>
                        <label for=""><?= __('Volunteering oppurtunity roles') ?></label>
                        <?= $this->Form->control('volunteering_oppurtunity_id', ['empty' => __('Select Role'), 'label' => false, 'options' => $options, 'required' => true]) ?>
                        <button class="btn btn-long mt-2" type="submit"><?= __('Submit Interest') ?></button>
                        <?= $this->Form->end() ?>
                    </div>
                <?php else: ?>
                <div class="alert alert-info" role="alert">
                    <?= __('Login to show interest') ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9 basic-info">
            <hr>
            <p class="comment-cnt"><?= (!empty($event->event_comments) && $event->event_comments !== null) ? count($event->event_comments) : 0 ?> <?= __('Comments') ?></p>
            <hr>
            <?php foreach ($event->event_comments as $comment): ?>
            <div class="chat-user d-flex">
                <div class="user-img">
                    <img src="<?= ($comment->user->profile_image && !empty($comment->user->profile_image)) ? $comment->user->profile_image : $this->Url->image('no-image.jpg') ?>" alt="">
                </div>
                <div class="name-side">
                    <h5><?= h($comment->user->details) ?>
                        <span class="time">- <?= $comment->created->nice() ?></span></h5>
                    <p><?= h($comment->comment_body) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="more-container mt-4">
                <a href="#" class="more"><?= __('SEE MORE') ?>
                <i class="fas fa-caret-down"></i>
                </a>
            </div>
            <hr>
            <?php if (isset($authUser)): ?>
            <?= $this->Form->create($event) ?>
                <?= $this->Form->control('event_comments.comment_body', ['placeholder' => 'Write comment', 'label' => 'Write comment']) ?>
                <button type="submit" name="button" class="btn"><?= __('Post Comment') ?></button>
            <?= $this->Form->end() ?>
            <?php else: ?>
                <div class="alert alert-info" role="alert">
                    <?= __('Login to write comment') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    </div>
</div>
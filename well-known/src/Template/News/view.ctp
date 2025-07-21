
<div class="main program">
    <div class="container organization">
    <div class="row">
        <div class="col-md-9">
            <div class="card program-main">
                <div class="img-container">
                    <img src="<?= $news->image ?>" class="card-img-top" alt="...">
                </div>
                <div class="card-footer">
                    <h3 class="card-title"><?= h($news->title) ?></h3>
                    <div class="program-tags d-flex justify-content-between">
                        <div class="">
                            <p><img src="<?= $this->Url->image('date.svg') ?>" alt="" class="svg"><?= $news->created->format('M d, Y') ?></p>
                        </div>
                        <div class="org">
                            <p>
                                <img src="<?= $this->Url->image('org-icon.svg') ?>" alt="" class="svg">
                                <?php if ($news->has('volunteering_categories')) {foreach ($news->volunteering_categories as $volunteering_category): ?><span><?= h($volunteering_category->name) ?></span><?php endforeach;} ?> |
                                <?php if ($news->has('publishing_categories')) {foreach ($news->publishing_categories as $publishing_category): ?><span><?= h($publishing_category->name) ?></span><?php endforeach;} ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-content">
                <?= $this->Text->autoParagraph($news->content) ?>
            </p>
            <p>
                <?php if (!empty($news->tags)): ?>
                    <?php foreach ($news->tags as $tag): ?>
                        <span class="badge badge-info text-white font-weight-normal">
                            <a href="<?= $this->Url->build(['action' => 'tagged', $tag->title]) ?>" class="text-reset"><?= h($tag->title) ?></a>
                        </span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </p>
            <?= $this->element('user-feedback', [
                'object_id' => $news->id,
                'object_model' => 'News'
            ]); ?>
            <hr>
        </div>

        <div class="col-md-3 organization-side">
            <h4><?= __('About Publisher') ?></h4>
            <?php if ($news->has('organization')): ?>
            <div class="publisher">
                <div class="img-container">
                    <img src="<?= (!empty($news->organization->logo) && $news->organization->logo !== null) ? $news->organization->logo : $this->Url->image('no-logo.jpg') ?>" alt="">
                </div>
                <div class="name">
                    <h2><?= h($news->organization->name) .($news->organization->is_verified ? ' <span class="badge badge-success rounded-circle text-light"><i class="fa fa-check"></i><span>' : '') ?></h2>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <p class="card-text">
                        <?= $this->Text->autoParagraph($news->organization->about) ?>
                    </p>
                </div>
            </div>
            <a href="#" class="btn btn-long"><?= __("View Publisher's Page") ?></a>
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
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            <hr>
            <p class="comment-cnt"><?= (!empty($news->news_comments) && $news->news_comments !== null) ? count($news->news_comments) : 0 ?> <?= __('Comments') ?></p>
            <hr>
            <?php foreach ($news->news_comments as $comment): ?>
            <div class="chat-user d-flex">
                <div class="user-img">
                    <img src="<?= ($comment->user->profile_image && !empty($comment->user->profile_image)) ? $comment->user->profile_image : $this->Url->image('no-image.jpg') ?>" alt="">
                </div>
                <div class="name-side">
                    <h5><?= h($comment->user->details) ?>
                        <span class="time">- <?= $comment->created->nice() ?></span></h5>
                    <p><?= h($comment->comment) ?></p>
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
            <?= $this->Form->create($news) ?>
                <?= $this->Form->control('news_comments.comment', ['placeholder' => 'Write comment', 'label' => false]) ?>
                <button type="submit" name="button" class="btn"><?= __('Post Comment') ?></button>
            </form>
            <?php else: ?>
                <div class="alert alert-info" role="alert">
                    <?= __('Login to write comment') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    </div>
</div>

<div class="main program">
    <div class="container organization">
    <div class="row">
        <div class="col-md-9">
            <div class="card program-main">
                <div class="img-container">
                    <img src="<?= $blogPost->image ?>" class="card-img-top" alt="...">
                </div>
                <div class="card-footer">
                    <h3 class="card-title"><?= h($blogPost->title) ?></h3>
                    <div class="program-tags d-flex justify-content-between">
                        <div class="">
                            <p><img src="<?= $this->Url->image('date.svg') ?>" alt="" class="svg"><?= $blogPost->created->format('M d, Y') ?></p>
                        </div>
                        <div class="org">
                            <p>
                                <img src="<?= $this->Url->image('org-icon.svg') ?>" alt="" class="svg">
                                <?php if ($blogPost->has('volunteering_categories')) {foreach ($blogPost->volunteering_categories as $volunteering_category): ?><span><?= h($volunteering_category->name) ?></span><?php endforeach;} ?> |
                                <?php if ($blogPost->has('publishing_categories')) {foreach ($blogPost->publishing_categories as $publishing_category): ?><span><?= h($publishing_category->name) ?></span><?php endforeach;} ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-content">
                <?= $this->Text->autoParagraph($blogPost->content) ?>
            </p>
            <p>
                <?php if (!empty($blogPost->tags)): ?>
                    <?php foreach ($blogPost->tags as $tag): ?>
                        <span class="badge badge-info text-white font-weight-normal">
                            <a href="<?= $this->Url->build(['action' => 'tagged', $tag->title]) ?>" class="text-reset"><?= h($tag->title) ?></a>
                        </span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </p>
            <?= $this->element('user-feedback', [
                'object_id' => $blogPost->id,
                'object_model' => 'BlogPosts'
            ]); ?>
            <hr>
        </div>

        <div class="col-md-3 organization-side">
            <h4><?= __('About Publisher') ?></h4>
            <?php if ($blogPost->has('organization')): ?>
            <div class="publisher">
                <div class="img-container">
                    <img src="<?= (!empty($blogPost->organization->logo) && $blogPost->organization->logo !== null) ? $blogPost->organization->logo : $this->Url->image('no-logo.jpg') ?>" alt="">
                </div>
                <div class="name">
                    <h2><?= h($blogPost->organization->name) ?></h2>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <p class="card-text">
                        <?= $this->Text->autoParagraph($blogPost->organization->about) ?>
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
            <p class="comment-cnt"><?= (!empty($blogPost->blog_post_comments) && $blogPost->blog_post_comments !== null) ? count($blogPost->blog_post_comments) : 0 ?> <?= __('Comments') ?></p>
            <hr>
            <?php foreach ($blogPost->blog_post_comments as $comment): ?>
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
            <?= $this->Form->create($blogPost) ?>
                <div class="basic-info">
                    <?= $this->Form->control('blog_post_comments.comment', ['placeholder' => __('Write comment'), 'label' => __('Write comment')]) ?>
                    <button type="submit" name="button" class="btn"><?= __('Post Comment') ?></button>
                </div>
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
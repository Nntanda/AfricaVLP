<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\News $news
 */
?>
<?= $this->Html->css('post.css', ['block' => 'css']) ?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title"><?= __('Event Post') ?></h3>
        <span class="kt-subheader__separator kt-subheader__separator--v"></span>
        <div class="kt-subheader__wrapper">
        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-icon- btn btn-label btn-label-brand btn-upper btn-font-sm btn-bold">
            <?= __('Back to Events') ?></a>
        </div>
    </div>
    <div class="kt-subheader__toolbar">
        <div class="kt-subheader__wrapper">
        <!-- <a href="#" class="btn btn-icon- btn btn-label btn-label-brand btn-upper btn-font-sm btn-bold"> <i class="flaticon2-add-1"></i> Deactivate</a> -->
        <?php if ($event->status === STATUS_ACTIVE): 
            echo $this->Form->postLink(
                __('Deactivate'), 
                ['action' => 'edit', $event->id], 
                ['data' => ['status' => STATUS_INACTIVE], 'class' => 'btn btn-sm btn-danger', 'escape' => false]
              );
        else: 
            echo $this->Form->postLink(
                __('Activate'), 
                ['action' => 'edit', $event->id], 
                ['data' => ['status' => STATUS_ACTIVE], 'class' => 'btn btn-sm btn-info', 'escape' => false]
              );
        endif; ?>
        </div>
    </div>
    </div>
</div>
<!-- end:: Subheader -->
<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <!--begin::Dashboard 4-->
    <div class="kt-portlet">
    <div class="kt-portlet__body">
        <div class="kt-blog-post">
        <div class="kt-blog-post__hero-image">
            <img src="<?= $event->image ?>" class="kt-blog-post__image"/>
        </div>
        <h1 class="kt-blog-post__title kt-heading kt-heading--lg kt-heading--medium">
            <?= h($event->title) ?>
        </h1>
        <div class="kt-blog-post__meta">
            <div class="kt-blog-post__date">
                <?= h($event->created->format('M d, Y')) ?>
            </div>
            <div class="kt-blog-post__author">
                <?= __('By') ?>
                <a href="#" class="kt-blog-post__link"><?= h($event->organization->name) ?></a>
            </div>
            <div class="kt-blog-post__author">
                <?= h($event->city->name. ', '. $event->country->nicename) ?>
            </div>
            <div class="kt-blog-post__comments">
                <a href="#" class="kt-blog-post__link" data-toggle="modal" data-target=".bd-example-modal-lg"><?= h(($event->volunteering_oppurtunities !== null && !empty($event->volunteering_oppurtunities)) ? count($event->volunteering_oppurtunities) : 0) .__(' Volunteering opportunities') ?> </a>
            </div>
            <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?= __('Volunteering opportunities') ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="kt-section">
                        <div class="kt-section__info">
                            <?= __('Volunteering opportunities and numbers of volunteers needed per skill') ?>
                        </div>
                        <div class="kt-section__content">
                            <ul class="list-group">
                                <?php foreach ($event->volunteering_oppurtunities as $oppurtunity): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= h($oppurtunity->volunteering_role->name) ?>
                                    <span class="badge badge-primary badge-pill"><?= h($oppurtunity->number) ?></span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-brand" data-dismiss="modal"><?= __('Close') ?></button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-blog-post__content">
            <?= $this->Text->autoParagraph($event->description) ?>
        </div>
        <div class="kt-blog-post__comments">
            <div class="kt-blog-post__input">
                <div class="kt-blog-post__input-title">
                    <?= h(($event->event_comments !== null && !empty($event->event_comments)) ? count($event->event_comments) : 0) .__(' Comments') ?>
                </div>
            </div>
            <div class="kt-blog-post__threads">
                <?php foreach ($event->event_comments as $eventComment): ?>
                <div class="kt-blog-post__thread">
                    <div class="kt-blog-post__head">
                        <img src="<?= ($eventComment->user->profile_image && !empty($eventComment->user->profile_image)) ? $eventComment->user->profile_image : $this->Url->image('user.png') ?>"/>
                    </div>
                    <div class="kt-blog-post__body">
                        <div class="kt-blog-post__top">
                            <div class="kt-blog-post__author">
                            <div class="kt-blog-post__label">
                                <span><?= $eventComment->user->first_name .' '. $eventComment->user->last_name ?>,</span>
                                <?= $eventComment->created->format('g:iA') ?>
                            </div>
                            </div>
                            <!-- <a href="" class="kt-blog-post__link">Delete</a> -->
                        </div>
                        <div class="kt-blog-post__content">
                            <?= $this->Text->autoParagraph($eventComment->comment_body) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        </div>
    </div>
    </div>
    <!--end::Dashboard 4-->
</div>
<!-- end:: Content -->
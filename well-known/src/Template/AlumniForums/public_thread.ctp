<div class="main">
    <div class="container organization"> 
        <div class="updates alumni-list forum">
          <div class="container updates-tab">
            <ul class="nav nav-tabs d-flex" role="tablist">
              <li class="nav-item">
                <a class="nav-link" href="<?= $this->Url->build(['action' => 'publicThreads']) ?>"><?= __('Back To Discussions') ?></a>
              </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
              <div class="tab-pane active"><br>
                <div class="card mb-4 forum-card">
                  <div class="card-body">
                    <div class="row no-gutters d-flex align-items-stretch">
                      <div class="col-md-10">
                        <div class="card-content d-flex flex-column">
                          <h4 class="card-title"><?= h($thread->title) ?></h4>
                          <p class="card-text"><?= h($thread->description) ?></p>
                          <p class="card-text d-flex mt-auto">
                            <small class="text-muted flex-fill"><?= __('Date Created') ?>:
                              <span><?= h($thread->created->format('M d, Y')) ?></span></small>
                            <small class="text-muted flex-fill"><?= __('By')?>:
                              <span><?= h($thread->user->details) ?></span></small>
                            <small class="text-muted flex-fill"><?= __('People') ?>:
                              <span>-</span></small>
                          </p>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <?php if ($comments->count() < 1): ?>
                        <div class="alert alert-info" role="alert">
                            <?= __('No comments yet') ?>
                        </div>
                    <?php endif; ?>
                    <?php foreach ($comments as $date => $dateComments): ?>
                    <div class="date-line d-flex justify-content-center">
                      <div class="line"></div>
                      <div class=""><?= $date ?></div>
                      <div class="line"></div>
                    </div>
                        <?php foreach ($dateComments as $comment): ?>
                        <div class="chat-user d-flex">
                          <div class="user-img">
                            <img src="<?= ($comment->user->profile_image && !empty($comment->user->profile_image)) ? $comment->user->profile_image : $this->Url->image('no-image.jpg') ?>" alt="">
                          </div>
                          <div class="name-side">
                            <h5><?= h($comment->user->details) ?>
                              <span class="time">- <?= h($comment->created->format('g:iA')) ?> </span></h5>
                            <?= $this->Text->autoParagraph($comment->content) ?>
                          </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                  </div>
                  <?= $this->Form->create($thread) ?>
                    <div class="card-footer d-flex justify-content-between align-items-start basic-info">
                        <div class="user-img">
                            <img src="<?= ($authUser['profile_image'] && !empty($authUser['profile_image'])) ? $authUser['profile_image'] : $this->Url->image('no-image.jpg') ?>" alt="">
                        </div>
                        <div class="flex-grow-1 px-2" style="margin-bottom: -18px">
                            <?= $this->Form->control('content', ['type' => 'textarea', 'placeholder' => __('Reply'), 'label' => __('Reply'), 'required' => true, 'style' => 'margin: 0;', 'class' => 'tinyM']) ?>
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
        </div>
    </div>
</div>

<?= $this->Html->script('tinymce/tinymce.min.js', ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
        tinymce.init({
            selector: 'textarea.tinyM',
            height: 300,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | ' +
            ' bold italic backcolor | alignleft aligncenter ' +
            ' alignright alignjustify | bullist numlist outdent indent |' +
            ' removeformat',
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tiny.cloud/css/codepen.min.css'
            ],
            setup: function (editor) {
                editor.on('change', function (e) {
                    editor.save();
                });
            }
        });
    });
</script>

<?php $this->end(); ?>
<div class="main">
    <div class="container organization"> 
        <?php $organization = $thread->organization; ?>
        <div class="card organization-header">
            <div class="card-header">
                <h2><?= h($organization->name) ?></h2>
            </div>
            <div class="card-body d-flex align-items-stretch">
                <div class="img-container">
                    <img src="<?= (!empty($organization->logo) && $organization->logo !== null) ? $organization->logo : $this->Url->image('no-logo.jpg') ?>" alt="">
                </div>
                <div class="card-content">
                    <?= $this->Text->truncate($this->Text->autoParagraph($organization->about), 150, ['ellipsis' => '...']) ?>
                    <div class="location">
                        <p><img src="https://www.countryflags.io/<?= $organization->country->iso ?>/flat/64.png" alt=""><?= h($organization->city->name. ', '. $organization->country->nicename) ?></p>
                    </div>
                </div>
                <div class=" d-flex align-items-center ml-auto">
                    <a href="#" class="btn" onClick="return false" data-toggle="modal" data-target="#Modal"><?= __('Create New') ?></a>
                    <!-- Modal -->
                    <div class="modal fade" id="Modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel"><?= __('New Discusion Thread') ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <?= $this->Form->create(false, ['url' => ['action' => 'addNewThread', $organization->id]]) ?>
                                <div class="modal-body">
                                    <div class="form-group other-info basic-info">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?= $this->Form->control('title', ['required' => true, 'label' => __('Title'), 'placeholder' => __('Title')]) ?>
                                            </div>
                                            <div class="col-md-12">
                                                <?= $this->Form->control('description', ['required' => true, 'rows' => 3, 'label' => __('Description'), 'placeholder' => __('Description')]) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-secondary"><?= __('Submit') ?></button>
                                </div>
                                <?= $this->Form->end() ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="updates alumni-list forum">
          <div class="container updates-tab">
            <ul class="nav nav-tabs d-flex" role="tablist">
              <li class="nav-item">
                <a class="nav-link" href="<?= $this->Url->build(['action' => 'index', $organization->id]) ?>"><?= __('Back To Discussions') ?></a>
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
                            <small class="text-muted flex-fill"><?= __('By') ?>:
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
                        <!-- <textarea name="name" class="form-control" placeholder="Type something..."></textarea> -->
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
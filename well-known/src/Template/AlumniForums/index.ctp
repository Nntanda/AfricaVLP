<div class="main">
    <div class="container organization">
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
                                                <?= $this->Form->control('description', ['required' => true, 'rows' => 3, 'placeholder' => __('Description'), 'placeholder' => __('Description')]) ?>
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
        <div class="updates alumni-list">
            <div class="container updates-tab">
                <ul class="nav nav-tabs d-flex" role="tablist">
                    <li class="nav-item nav-line">
                        <a class="nav-link active" data-toggle="tab" href="#programs-tab"><?= __('All Discussions') ?></a>
                    </li>
                    <li class="nav-item nav-line">
                        <a class="nav-link" data-toggle="tab" href="#news-tab"><?= __('Joined Discussions') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#resources-tab"><?= __('My Discussions') ?></a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div id="programs-tab" class="tab-pane active"><br>
                    <?php foreach ($threads as $thread): ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row no-gutters d-flex align-items-stretch">
                                    <div class="col-md-9">
                                        <div class="card-content d-flex flex-column">
                                            <h4 class="card-title"><?= h($thread->title) ?></h4>
                                            <p class="card-text"><?= h($thread->description) ?></p>
                                            <p class="card-text d-flex mt-auto">
                                            <small class="text-muted flex-fill"><?= __('Date Created') ?>:
                                                <span><?= h($thread->created->format('M d, Y')) ?></span></small>
                                            <small class="text-muted flex-fill"><?= __('By')?>:
                                                <span><?= h($thread->user->details) ?></span></small>
                                            <small class="text-muted flex-fill"><?= __('People') ?>:
                                                <span><?= h($thread->users_count) ?></span></small>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-center justify-content-end">
                                        <a href="<?= $this->Url->build(['action' => 'thread', $thread->id]) ?>" class="btn btn-small"><?= __('View Discussion') ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if ($threads->count() < 1): ?>
                        <div class="alert alert-info" role="alert">
                            <?= __('No record found') ?>
                        </div>
                    <?php endif; ?>
                    </div>
                    <div id="news-tab" class="tab-pane fade"><br>
                    <?php foreach ($joinedThreads as $thread): ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row no-gutters d-flex align-items-stretch">
                                    <div class="col-md-9">
                                        <div class="card-content d-flex flex-column">
                                            <h4 class="card-title"><?= h($thread->title) ?></h4>
                                            <p class="card-text"><?= h($thread->description) ?></p>
                                            <p class="card-text d-flex mt-auto">
                                            <small class="text-muted flex-fill"><?= __('Date Created') ?>:
                                                <span><?= h($thread->created->format('M d, Y')) ?></span></small>
                                            <small class="text-muted flex-fill"><?= __('By')?>:
                                                <span><?= h($thread->user->details) ?></span></small>
                                            <small class="text-muted flex-fill"><?= __('People') ?>:
                                                <span><?= h($thread->users_count) ?></span></small>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-center justify-content-end">
                                        <a href="<?= $this->Url->build(['action' => 'thread', $thread->id]) ?>" class="btn btn-small"><?= __('View Discussion') ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if ($threads->count() < 1): ?>
                        <div class="alert alert-info" role="alert">
                            <?= __('No record found') ?>
                        </div>
                    <?php endif; ?>
                    </div>
                    <div id="resources-tab" class="tab-pane fade"><br>
                    <?php foreach ($myThreads as $thread): ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row no-gutters d-flex align-items-stretch">
                                    <div class="col-md-9">
                                        <div class="card-content d-flex flex-column">
                                            <h4 class="card-title"><?= h($thread->title) ?></h4>
                                            <p class="card-text"><?= h($thread->description) ?></p>
                                            <p class="card-text d-flex mt-auto">
                                            <small class="text-muted flex-fill"><?= __('Date Created') ?>:
                                                <span><?= h($thread->created->format('M d, Y')) ?></span></small>
                                            <small class="text-muted flex-fill"><?= __('By')?>:
                                                <span><?= h($thread->user->details) ?></span></small>
                                            <small class="text-muted flex-fill"><?= __('People') ?>:
                                                <span><?= h($thread->users_count) ?></span></small>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-center justify-content-end">
                                        <a href="<?= $this->Url->build(['action' => 'thread', $thread->id]) ?>" class="btn btn-small"><?= __('View Discussion') ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if ($threads->count() < 1): ?>
                        <div class="alert alert-info" role="alert">
                            <?= __('No record found') ?>
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
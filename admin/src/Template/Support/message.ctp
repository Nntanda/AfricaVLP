<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\News[]|\Cake\Collection\CollectionInterface $news
 */
?>
<?= $this->Html->css('inbox.css', ['block' => 'css']) ?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item p-2" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?= __('Support Message') ?></h3>
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
        </div>
        <div class="kt-subheader__toolbar">
            <!--  -->
        </div>
    </div>
</div>
<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
  <!--begin::Dashboard 4-->
  <div class="kt-grid kt-grid--desktop kt-grid--ver-desktop  kt-inbox" id="kt_inbox">
    <!--Begin::Aside Mobile Toggle-->
    <button class="kt-inbox__aside-close" id="kt_inbox_aside_close">
      <i class="la la-close"></i>
    </button>
    <!--End:: Aside Mobile Toggle-->

    <!--Begin:: Inbox Aside-->
    <!--End::Aside-->

    <!--Begin:: Inbox View-->
    <div class="kt-grid__item kt-grid__item--fluid kt-portlet kt-inbox__view kt-inbox__view--shown" id="kt_inbox_view">
      <div class="kt-portlet__head">
        <div class="kt-inbox__toolbar">
          <div class="kt-inbox__actions">
            <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="kt-inbox__icon">
              <i class="flaticon2-left-arrow-1"></i>
            </a>
          </div>
          <div class="kt-inbox__controls">
            <!--  -->
          </div>
        </div>
      </div>

      <div class="kt-portlet__body kt-portlet__body--fit-x">
        <div class="kt-inbox__subject">
          <div class="kt-inbox__title">
            <span class="kt-media kt-media--circle kt-media--sm kt-media--brand pr-2 mr-2" style="background-image: url('<?= $support->organization->logo ?>')">
              <span></span>
            </span>
            <h3 class="kt-inbox__text"><?= h($support->organization->name) ?></h3>
          </div>
          <div class="kt-inbox__actions">
            <!--  -->
          </div>
        </div>

        <div class="kt-inbox__messages">
            <?php $c=1; foreach ($support->admin_support_messages as $message): ?>
            <div class="kt-inbox__message <?= $c == count($support->admin_support_messages) ?'kt-inbox__message--expanded' : ''?> ">
                <div class="kt-inbox__head">
                    <div class="kt-inbox__info">
                        <div class="kt-inbox__author" data-toggle="expand">
                            <a href="#" class="kt-inbox__name"><?php
                                if ($message->sender === 'au') {
                                    echo 'AU Support <small>-'. $message->sender_admin->name .'</small>';
                                } else {
                                    echo h($message->sender_user->first_name .' '. $message->sender_user->last_name );
                                }
                            ?></a>
                        </div>
                        <div class="kt-inbox__details">
                            <div class="kt-inbox__desc" data-toggle="expand">
                                <?= $this->Text->truncate(strip_tags($message->message), 80, ['ellipsis' => '...']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="kt-inbox__actions">
                        <div class="kt-inbox__datetime" data-toggle="expand">
                        <?= $message->created->format('M d, Y, g:iA') ?>
                        </div>

                        <div class="kt-inbox__group">
                            <!--  -->
                        </div>
                    </div>
                </div>
                <div class="kt-inbox__body">
                    <div class="kt-inbox__text">
                        <?= $this->Text->autoParagraph($message->message) ?>
                    </div>
                </div>
            </div>
            <?php $c++; endforeach; ?>
        </div>

        <div class="kt-inbox__reply kt-inbox__reply--on">

          <div class="kt-inbox__form" id="kt_inbox_reply_form">
            <?= $this->Form->create($supportMessage) ?>
            <div class="kt-inbox__body">
              <style>
                .tox-tinymce {
                  border: #eee;
                }
              </style>                              
                <?= $this->Form->control('message', ['class' => 'w-content tinyM', 'placeholder' => 'Type message', 'label' => false, 'required' => true]); ?>
            </div>
            <div class="kt-inbox__foot">
              <div class="kt-inbox__primary">
                <div class="btn-group">
                  <button type="submit" class="btn btn-brand btn-bold">
                    <?= __('Send') ?>
                  </button>
                </div>
              </div>
            </div>
            <?= $this->Form->end() ?>

          </div>
        </div>
      </div>
    </div>
    <!--End:: Inbox View-->
  </div>
  <!--end::Dashboard 4-->
</div>
<!-- end:: Content -->

<?= $this->Html->script('inbox.js', ['block' => 'script']) ?>
<?= $this->Html->script('tinymce/tinymce.min.js', ['block' => 'script']) ?>
<?= $this->Html->script('fileinput/fileinput.js', ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
        tinymce.init({
            selector: 'textarea.tinyM',
            placeholder: 'Type message',
            height: 100,
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
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
            <h3 class="kt-subheader__title"><?= __('Support Messages') ?></h3>
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
        </div>
        <div class="kt-subheader__toolbar">
            <!-- <div class="kt-subheader__wrapper">
              <a href="#" class="btn btn-icon- btn btn-label btn-label-brand btn-upper btn-font-sm btn-bold"> <i class="flaticon2-add-1"></i> New Message</a>
            </div> -->
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

    <!--Begin:: Inbox List-->
    <div class="kt-grid__item kt-grid__item--fluid    kt-portlet    kt-inbox__list kt-inbox__list--shown" id="kt_inbox_list">
      <div class="kt-portlet__head">
        <div class="kt-inbox__toolbar kt-inbox__toolbar--extended">
          <div class="kt-inbox__actions kt-inbox__actions--expanded">
            <div class="kt-inbox__check">

              <div class="btn-group">
                <button type="button" class="kt-inbox__icon kt-inbox__icon--light kt-inbox__icon--sm" data-toggle="dropdown">
                  <i class="flaticon2-down"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-left dropdown-menu-fit dropdown-menu-xs">
                  <ul class="kt-nav">
                    <li class="kt-nav__item kt-nav__item--active">
                      <a href="#" class="kt-nav__link">
                        <span class="kt-nav__link-text"><?= __('All') ?></span>
                      </a>
                    </li>
                    <li class="kt-nav__item">
                      <a href="#" class="kt-nav__link">
                        <span class="kt-nav__link-text"><?= __('Read') ?></span>
                      </a>
                    </li>
                    <li class="kt-nav__item">
                      <a href="#" class="kt-nav__link">
                        <span class="kt-nav__link-text"><?= __('Unread') ?></span>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>

              <!-- <button type="button" class="kt-inbox__icon" data-toggle="kt-tooltip" title="Reload list">
                <i class="flaticon2-refresh-button"></i>
              </button> -->
            </div>
          </div>
          <!-- <div class="kt-inbox__search">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search">
              <div class="input-group-append">
                <span class="input-group-text">
                  <i class="flaticon2-magnifier-tool"></i>
                </span>
              </div>
            </div>
          </div> -->
          <div class="kt-inbox__controls">
            <!-- <div class="kt-inbox__pages" data-toggle="kt-tooltip" title="Records per page">
              <span class="kt-inbox__perpage" data-toggle="dropdown">1 - 50 of 235</span>
              <div class="dropdown-menu dropdown-menu-right dropdown-menu-fit dropdown-menu-xs">
                <ul class="kt-nav">
                  <li class="kt-nav__item">
                    <a href="#" class="kt-nav__link">
                      <span class="kt-nav__link-text">20 per page</span>
                    </a>
                  </li>
                  <li class="kt-nav__item kt-nav__item--active">
                    <a href="#" class="kt-nav__link">
                      <span class="kt-nav__link-text">50 par page</span>
                    </a>
                  </li>
                  <li class="kt-nav__item">
                    <a href="#" class="kt-nav__link">
                      <span class="kt-nav__link-text">100 per page</span>
                    </a>
                  </li>
                </ul>
              </div>
            </div>

            <button class="kt-inbox__icon kt-inbox__icon--sm" data-toggle="kt-tooltip" title="Previose page">
              <i class="flaticon2-left-arrow"></i>
            </button>

            <button class="kt-inbox__icon kt-inbox__icon--sm" data-toggle="kt-tooltip" title="Next page">
              <i class="flaticon2-right-arrow"></i>
            </button>

            <div class="kt-inbox__sort" data-toggle="kt-tooltip" title="Sort">
              <button type="button" class="kt-inbox__icon" data-toggle="dropdown">
                <i class="flaticon2-console"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-right dropdown-menu-fit dropdown-menu-xs">
                <ul class="kt-nav">
                  <li class="kt-nav__item kt-nav__item--active">
                    <a href="#" class="kt-nav__link">
                      <span class="kt-nav__link-text">Newest</span>
                    </a>
                  </li>
                  <li class="kt-nav__item">
                    <a href="#" class="kt-nav__link">
                      <span class="kt-nav__link-text">Olders</span>
                    </a>
                  </li>
                  <li class="kt-nav__item">
                    <a href="#" class="kt-nav__link">
                      <span class="kt-nav__link-text">Unread</span>
                    </a>
                  </li>
                </ul>
              </div>
            </div> -->

          </div>
        </div>
      </div>
      <div class="kt-portlet__body kt-portlet__body--fit-x">
        <div class="kt-inbox__items" data-type="inbox">
            <?php foreach ($supports as $message): ?>
              <div class="kt-inbox__item <?= ($message->admin_support_messages[0]->sender !== 'au' &&!$message->admin_support_messages[0]->is_read) ? 'kt-inbox__item--unread' : '' ?>" data-id="1" data-type="inbox">
                <div class="kt-inbox__info">
                    <div class="kt-inbox__sender" data-toggle="view">
                      <?php if ($message->admin_support_messages[0]->sender === 'au'): ?>
                        <span class="kt-media kt-media--circle kt-media--sm kt-media--danger">
                        <span>AU</span>
                        </span>
                        <a href="#" class="kt-inbox__author"><?= h('Support') ?></a>
                      <?php else: ?>
                        <span class="kt-media kt-media--circle kt-media--sm kt-media--danger" style="background-image: url('<?= $message->admin_support_messages[0]->sender_user->profile_image ?>')">
                        <span></span>
                        </span>
                        <a href="#" class="kt-inbox__author"><?= h($message->admin_support_messages[0]->sender_user->first_name) ?></a>
                      <?php endif; ?>
                    </div>
                </div>
                <div class="kt-inbox__details" data-toggle="view">
                    <div class="kt-inbox__message">
                        <a href="<?= $this->Url->build(['action' => 'message', $message->organization_id]) ?>">
                          <span class="kt-inbox__subject"><?= h($message->organization->name) ?> <br />
                          </span>
                          <span class="kt-inbox__summary"><?= $this->Text->truncate(strip_tags($message->admin_support_messages[0]->message), 80, ['ellipsis' => '...']) ?></span>
                        </a>
                    </div>
                </div>
                <div class="kt-inbox__datetime" data-toggle="view">
                  <?= h($message->admin_support_messages[0]->created->isToday() ? $message->admin_support_messages[0]->created->format('g:i A') : $message->admin_support_messages[0]->created->format('M d, Y g:i A')) ?>
                </div>
              </div>
            <?php endforeach; ?>
        </div>
      </div>
    </div>
    <!--End:: Inbox List-->
  </div>
  <!--end::Dashboard 4-->
</div>
<!-- end:: Content -->

<!-- <div class="news index large-9 medium-8 columns content">
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ') ?>
            <?= $this->Paginator->prev('< ') ?>
            <?= $this->Paginator->next(' >') ?>
            <?= $this->Paginator->last(' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div> -->

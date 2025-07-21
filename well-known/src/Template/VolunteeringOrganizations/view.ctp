<div class="main">
      <div class="container organization">
        <div class="card organization-header">
          <div class="card-header">
            <h2><?= h($organization->name) ?></h2>
          </div>
          <div class="card-body d-flex align-items-center flex-wrap">
            <div class="img-container">
              <img class="rounded" src="<?= $organization->logo ?>" alt="">
            </div>
            <div class="card-content">
              <p><?= $this->Text->truncate(strip_tags($organization->about), 150, ['ellipsis' => '...']) ?></p>
              <div class="location">
                <p><img src="https://www.countryflags.io/<?= $organization->country->iso ?>/flat/64.png" alt=""><?= h($organization->has('city') ? $organization->city->name. ', ' : '')?> <a href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'countryPage', $organization->country->iso]) ?>"><?= $organization->country->nicename ?></a></p>
              </div>
            </div>
            <!-- <div class=" d-flex align-items-center ml-lg-auto">
              <a href="#" class="btn">Send Message</a>
            </div> -->
          </div>
        </div>
        <div class="row">
          <div class="col-md-3 organization-side">
            <h4><?= __('Contact Informations') ?></h4>
            <div class="card">
              <div class="card-body">
                <ul class="contact">
                  <li class="d-flex">
                    <img src="<?= $this->Url->image('address.svg') ?>" alt="" class="svg">
                    <p><?= h($organization->address) ?></p>
                  </li>
                  <li class="d-flex">
                    <img src="<?= $this->Url->image('web.svg') ?>" alt="" class="svg">
                    <p><?= h($organization->website) ?></p>
                  </li>
                  <li class="d-flex">
                    <img src="<?= $this->Url->image('email.svg') ?>" alt="" class="svg">
                    <p><?= h($organization->email) ?></p>
                  </li>
                </ul>
                <div class="connect">
                  <h6><?= __('CONNECT WITH US') ?></h6>
                  <div class="social">
                    <a href="<?= $organization->twitter_url ?>"><img src="<?= $this->Url->image('twitter.svg') ?>" alt="" class="svg"></a>
                    <a href="<?= $organization->facebook_url ?>"><img src="<?= $this->Url->image('facebook.svg') ?>" alt="" class="svg"></a>
                    <!-- <a href="#"><img src="assets/img/youtube.svg" alt="" class="svg"></a> -->
                  </div>
                </div>
              </div>
            </div>
            <!-- <h4>Quick Links</h4>
            <div class="card quick-link">
              <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                  <a href="#">Exchange programs</a>
                  <span>(5)</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <a href="#">Resources</a>
                  <span>(3)</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <a href="#">News</a>
                  <span>(73)</span>
                </li>
              </ul>
            </div>
            <h4>Alumni Forum Topics</h4>
            <div class="card quick-link">
              <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                  <a href="alumni-discussion.html">General Discussion</a>
                  <span>(75)</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <a href="alumni-discussion.html">Tech</a>
                  <span>(13)</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <a href="alumni-discussion.html">Fast Tracking</a>
                  <span>(10)</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <a href="alumni-list.html" class="more">See All</a>
                </li>
              </ul>
            </div> -->
          </div>
          <div class="col-md-9 organization-main">
            <div class="card overview mb-4">
              <div class="card-header">
                <?= __('Overview') ?>
              </div>
              <div class="card-body">
                <div class="card">
                  <div class="card-body d-flex">
                    <div class="flex-fill">
                      <h3><?= $organization->volunteer_count ?></h3>
                      <p><?= __('Volunteers') ?></p>
                    </div>
                    <div class="flex-fill">
                      <h3><?= $organization->volunteer_count < 1 ? 0 : round(($organization->volunteer_male_count / $organization->volunteer_count) * 100, 2) ?>%</h3>
                      <p><?= __('Male') ?></p>
                    </div>
                    <div class="flex-fill">
                      <h3><?= $organization->volunteer_count < 1 ? 0 : round(($organization->volunteer_female_count / $organization->volunteer_count) * 100, 2) ?>%</h3>
                      <p><?= __('Female') ?></p>
                    </div>
                    <div class="flex-fill">
                      <h3><?= $organization->has('volunteering_categories') ? count($organization->volunteering_categories) : 0 ?></h3>
                      <p><?= __('Sectors') ?></p>
                    </div>
                  </div>
                </div>
                <!-- <a href="#" class="more">SEE MORE
                  <i class="fas fa-caret-right"></i>
                </a> -->
              </div>
            </div>
            <div class="card about mb-4">
              <div class="card-header">
                <?= __('About') ?>
              </div>
              <div class="card-body">
                <p>
                <?= h($organization->about) ?>
                </p>
                <!-- <div class="report">
                  <h6>Annual Report</h6>
                  <a href="#"><img src="assets/img/pdf.png" alt=""></a>
                </div> -->
              </div>
            </div>
            <div class="card org-postlist mb-4">
              <div class="card-header">
                <?= __('Events') ?>
              </div>
              <div class="card-body">
              <?php foreach ($organization->events as $event): ?>
                <div class="wrap mb-4">
                    <div class="card">
                        <div class="row no-gutters d-flex align-items-stretch">
                            <div class="col-lg-3 card-img" style="background-image: url(<?= $event->image ?>); background-position: center;">
                            </div>
                            <div class="col-lg-9">
                                <div class="card-body">
                                    <div class="d-flex flex-column">
                                        <h4 class="card-title"><?= h($event->title) ?></h4>
                                        <p class="card-text"><?= $this->Text->truncate($event->description, 150, ['ellipsis' => '...']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <div class="location">
                                <p><img src="https://www.countryflags.io/<?= $event->country->iso ?>/flat/64.png" alt=""><?= h($event->has('city') ? $event->city->name. ', ' : '')?> <a href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'countryPage', $event->country->iso]) ?>"><?= $event->country->nicename ?></a></p>
                            </div>
                            <div class="sector d-flex align-items-center">
                                <!--  -->
                            </div>
                        </div>
                    </div>
                </div>
              <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php $this->start('scriptBlock') ?>
<script>
    
</script>

<?php $this->end(); ?>
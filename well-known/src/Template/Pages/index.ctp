<?php 
$this->layout = 'home'; 
$icons = [
    1 => 'Volunteer-Opportunities.svg',
    2 => 'Impact-of-Volunteerism.svg',
    3 => 'Policy.svg',
    4 => 'Country-profile.svg',
];
?>
<div class="main">
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <?php for ($i=0; $i<count($slides); $i++): ?>
            <li data-target="#myCarousel" data-slide-to="<?= $i ?>" <?= $i === 0 ? 'class="Active"' : '' ?>></li>
            <?php endfor; ?>
        </ol>
        <div class="carousel-inner">
            <?php for ($i=0; $i<count($slides); $i++): ?>
            <?php if ($i === 0): ?>
            <div class="carousel-item active" data-interval="10000" style="background: url(<?= $slides[$i]->image ?>) no-repeat; background-size: cover;">
            <?php else: ?>
            <div class="carousel-item" <?php if(!empty($slides[$i]->image)): ?> style="background: url(<?= $slides[$i]->image ?>) no-repeat; background-size: cover;" <?php endif; ?>>
            <?php endif; ?>
                <div class="container d-flex align-items-center">
                    <div class="main-text">
                        <h1 class="wow slideInRight" data-wow-duration="1s" data-wow-delay="0.2s"><?= h($slides[$i]->title) ?></h1>
                        <p class="long-text wow slideInRight" data-wow-duration="1s" data-wow-delay="0.3s"><?= h($slides[$i]->content) ?></p>
                        <a href="<?= h($slides[$i]->url)  ?>" class="btn wow slideInRight" data-wow-duration="1s" data-wow-offset="0"><?= __('Read More') ?></a>
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</div>
<div class="about-boxes" id="about">
    <div class="container">
    <div class="row d-flex align-items-stretch">
        <?php foreach($about_blocks as $about_block): ?>
        <div class="col-md-3">
            <div class="card d-flex flex-column">
                <div class="icon">
                    <img src="<?= $this->Url->image($icons[$about_block->id]) ?>" alt="" class="svg">
                </div>
        <?php if($about_block->link != null && !empty($about_block->link)){ ?><a href="<?= $this->Url->build($lang.$about_block->link) ?>"> <?php } ?>
                    <h4><?= h($about_block->title) ?></h4>
                    <p><?= h($about_block->content) ?></p>
                    <?php if($about_block->link != null && !empty($about_block->link)){ ?></a > <?php } ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    </div>
</div>
<div class="updates">
    <div class="container">
        <h1><?= __('Latest Updates') ?></h1>
        <div class="updates-tab">
            <div class="container">
                <ul class="nav nav-tabs d-flex justify-content-center" role="tablist">
                    <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#programs-tab"><?= __('Opportunities') ?></a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#news-tab"><?= __('News') ?></a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#resources-tab"><?= __('Resources') ?></a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#blogs-tab"> <?= __('Blogs') ?></a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div id="programs-tab" class="tab-pane active"><br>
                        <?php foreach ($events as $event): ?>
                        <div class="wrap mb-4">
                            <div class="card">
                                <div class="row no-gutters d-flex align-items-stretch">
                                    <div class="col-lg-3 card-img" style="background-image: url(<?= $event->image ?>);"></div>
                                    <div class="col-lg-9">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="<?php $col = 'col-lg-12'; if (isset($authUser)){ if(isset($authUser['allow_events']) && $authUser['allow_events']){ $col = 'col-lg-9'; }} echo $col; ?> d-flex flex-column">
                                                    <h4 class="card-title"><?= h($event->title) ?></h4>
                                                    <p class="card-text"><?= $this->Text->truncate($event->description, 150, ['ellipsis' => '...']) ?></p>
                                                    <div class="row list-tag align-items-end">
                                                        <div class="col-md-4">
                                                            <p class="text-muted flex-fill"><?= __('Date') ?>:
                                                            <span><?= $event->created->format('M d, Y') ?></span></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p class="text-muted flex-fill"><?= __('Organizer') ?>:
                                                            <a href="volunteering-organizations/view/<?= $event->organization->id ?>" ><span><?= $event->has('organization') ? $event->organization->name .($event->organization->is_verified ? ' <span class="badge badge-success rounded-circle text-light"><i class="fa fa-check"></i><span>' : '') : '' ?></span></a></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                        <span><a style="color: #2C5535; text-decoration: none;" href="<?= $event->url ?>" target="_blank" rel="noopener noreferrer">Apply Now</a></span>
                                                    </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 d-flex align-items-center justify-content-end">
                                                    <?php if (isset($authUser)){ if(isset($authUser['allow_events']) && $authUser['allow_events']){ ?>
                                                    <a href="<?= $this->Url->build(['controller' => 'Events', 'action' => 'view', $event->id]) ?>" class="btn btn-small"><?= __('View') ?></a>
                                                    <?php }} ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <div class="location">
                                        <p><img src="https://www.countryflags.io/<?= $event->country->iso ?>/flat/64.png" alt=""><?= h($event->has('city') ? $event->city->name. ', ' : '')?> <a href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'countryPage', $event->country->iso]) ?>"><?= $event->country->nicename ?></a></p>
                                    </div>
                                    <div class="sector d-flex align-items-center">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>

                        <?php if ($events->count() === 4): ?>
                        <a href="<?= $this->Url->build(['controller' => 'Events', 'action' => 'index', $event->id]) ?>" class="more"><?= __('SEE MORE') ?>
                            <i class="fas fa-caret-right"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <div id="news-tab" class="tab-pane fade"><br>
                        <?php foreach ($news as $newsData): ?>
                        <div class="wrap mb-4">
                            <div class="card">
                                <div class="row no-gutters d-flex align-items-stretch">
                                    <div class="col-lg-3 card-img" style="background-image: url(<?= $newsData->image ?>);"></div>
                                    <div class="col-lg-9">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-9 d-flex flex-column">
                                                    <h4 class="card-title"><?= h($newsData->title) ?></h4>
                                                    <p class="card-text"><?= $this->Text->truncate(strip_tags($newsData->content), 150, ['ellipsis' => '...']) ?></p>
                                                    <div class="row list-tag align-items-end">
                                                        <div class="col-md-6">
                                                            <p class="text-muted flex-fill"><?= __('Date') ?>:
                                                            <span><?= $newsData->created->format('M d, Y') ?></span></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="text-muted flex-fill"><?= __('Organizer') ?>:
                                                            <span><?= $newsData->has('organization') ? $newsData->organization->name .($newsData->organization->is_verified ? ' <span class="badge badge-success rounded-circle text-light"><i class="fa fa-check"></i><span>' : '') : 'AU' ?></span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 d-flex align-items-center justify-content-end">
                                                    <a href="<?= $this->Url->build(['controller' => 'News', 'action' => 'view', $newsData->id]) ?>" class="btn btn-small"><?= __('Read More') ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <div class="location">
                                        <!--  -->
                                    </div>
                                    <div class="sector d-flex align-items-center">
                                        <?php foreach ($newsData->volunteering_categories as $volunteering_category): ?><span><?= h($volunteering_category->name) ?></span><?php endforeach; ?> |
                                        <?php foreach ($newsData->publishing_categories as $publishing_category): ?><span><?= h($publishing_category->name) ?></span><?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if ($news->count() === 4): ?>
                        <a href="<?= $this->Url->build(['controller' => 'News', 'action' => 'index']) ?>" class="more"><?= __('SEE MORE') ?>
                            <i class="fas fa-caret-right"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <div id="resources-tab" class="tab-pane fade"><br>
                        <?php foreach ($resources as $resource): ?>
                        <div class="wrap mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-9 d-flex flex-column">
                                            <h4 class="card-title"><?= h($resource->title) ?></h4>
                                            <p class="card-text"><?= $this->Text->truncate($resource->description, 150, ['ellipsis' => '...']) ?></p>
                                            <div class="row list-tag align-items-end">
                                                <div class="col-md-6">
                                                    <p class="text-muted flex-fill"><?= __('Date') ?>:
                                                    <span><?= $resource->created->format('M d, Y') ?></span></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="text-muted flex-fill"><?= __('Organizer') ?>:
                                                    <span><?= $resource->has('organization') ? $resource->organization->name .($resource->organization->is_verified ? ' <span class="badge badge-success rounded-circle text-light"><i class="fa fa-check"></i><span>' : '') : 'AU' ?></span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 d-flex align-items-center justify-content-end">
                                            <?php if (isset($authUser)){ if(isset($authUser['allow_resources']) && $authUser['allow_resources']){ ?>
                                            <a href="<?= $resource->file_link ?>" class="btn btn-small" target="_blank"><?= __('View') ?></a>
                                            <?php }} else { ?>
                                            <div class="alert alert-info" role="alert">
                                                <?= __('Please login or signup to view resources') ?>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <div class="location">
                                        <?php if ($resource->has('country')) { ?><p> <img src="https://www.countryflags.io/<?= $resource->country->iso ?>/flat/64.png" alt=""> <a href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'countryPage', $resource->country->iso]) ?>"><?= $resource->country->nicename ?></a> </p><?php } ?>
                                    </div>
                                    <div class="sector d-flex align-items-center">
                                        <span><?= h($resource->resource_type->name) ?> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if ($resources->count() === 4): ?>
                        <a href="<?= $this->Url->build(['controller' => 'Resources', 'action' => 'index']) ?>" class="more"><?= __('SEE MORE') ?>
                            <i class="fas fa-caret-right"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <div id="blogs-tab" class="tab-pane fade"><br>
                        <?php foreach ($blogPosts as $blogPost): ?>
                        <div class="wrap mb-4">
                            <div class="card">
                                <div class="row no-gutters d-flex align-items-stretch">
                                    <div class="col-lg-3 card-img" style="background-image: url(<?= $blogPost->image ?>);"></div>
                                    <div class="col-lg-9">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-9 d-flex flex-column">
                                                    <h4 class="card-title"><?= h($blogPost->title) ?></h4>
                                                    <p class="card-text"><?= $this->Text->truncate(strip_tags($blogPost->content), 150, ['ellipsis' => '...']) ?></p>
                                                    <div class="row list-tag align-items-end">
                                                        <div class="col-md-6">
                                                            <p class="text-muted flex-fill"><?= __('Date') ?>:
                                                            <span><?= $blogPost->created->format('M d, Y') ?></span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 d-flex align-items-center justify-content-end">
                                                    <a href="<?= $this->Url->build(['controller' => 'BlogPosts', 'action' => 'view', $blogPost->id]) ?>" class="btn btn-small"><?= __('Read More') ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <div class="location">
                                        <!--  -->
                                    </div>
                                    <div class="sector d-flex align-items-center">
                                        <?php foreach ($blogPost->volunteering_categories as $volunteering_category): ?><span><?= h($volunteering_category->name) ?></span><?php endforeach; ?> |
                                        <?php foreach ($blogPost->publishing_categories as $publishing_category): ?><span><?= h($publishing_category->name) ?></span><?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if ($blogPosts->count() === 4): ?>
                        <a href="<?= $this->Url->build(['controller' => 'BlogPosts', 'action' => 'index']) ?>" class="more"><?= __('SEE MORE') ?>
                            <i class="fas fa-caret-right"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="socials">
    <div class="container">
    <div class="row">
        <div class="col-md-6">
        <div class="tweeter">
            <a class="twitter-timeline" data-height="350" href="https://twitter.com/AUVolunteer?ref_src=twsrc%5Etfw"><?= __('Tweets by') ?> AUVolunteer</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        </div>
        </div>
        <div class="col-md-6">
            <div class="connect">
                <h6><?= __('CONNECT WITH US') ?></h6>
                <div class="social">
                <a href="https://twitter.com/AUVolunteer" target="_blank" rel="noopener noreferrer"><img src="<?= $this->Url->image('twitter.svg') ?>" alt="" class="svg"></a>
                <a href="https://www.facebook.com/auyvc/" target="_blank" rel="noopener noreferrer"><img src="<?= $this->Url->image('facebook.svg') ?>" alt="" class="svg"></a>
                <!-- <a href="#"><img src="<?= $this->Url->image('youtube.svg') ?>" alt="" class="svg"></a>
                <a href="#"><img src="<?= $this->Url->image('rss.svg') ?>" alt="" class="svg"></a> -->
                </div>
            </div>
            <div class="newsletter">
                <h6><?= __('Newsletter') ?></h6>
                <p><?= __('Select the newsletter(s) to which you want to subscribe or unsubscribe.') ?></p>
                <?= $this->Form->create(false, ['id' => 'newsletterForm']) ?>
                    <div class="input-group">
                        <input name="email" type="email" class="form-control" placeholder="<?= __('Enter your email') ?>" required id="newsletterEmail">
                        <span class="input-group-btn">
                            <button class="btn" type="submit" disabled>
                                <?= __('Subscribe') ?>
                                <span class="spinner-border spinner-border-sm" role="status" id="newsletterSpinner">
                                    <span class="sr-only"><?= __('Loading...') ?></span>
                                </span>
                            </button>
                        </span>
                    </div>
                    <div class="form-row p-1" id="newsletterSchedules">
                        <div class="form-group col">
                            <div class="form-check">
                                <input class="form-check-input" checked type="checkbox" value="1" name="weekly" id="weekly">
                                <label class="form-check-label" for="defaultCheck1">
                                    <?= __('Weekly') ?>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="monthly">
                                <label class="form-check-label" for="defaultCheck1">
                                    <?= __('Monthly') ?>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="quarterly">
                                <label class="form-check-label" for="defaultCheck1">
                                    <?= __('Quarterly') ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="newletterResponse"></div>
                <?= $this->Form->end() ?>
                <p class="newsletter-text"><?= __('AU-VLP is a continental development program that recruits and works with youth volunteers, to work in all 54 countries across the African Union') ?></p>
            </div>
        </div>
    </div>
    </div>
</div>


<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
        $("#newsletterSpinner").hide()
        $("#newsletterSchedules").hide()

        function checkSchedules() {
            let schedules = $('#newsletterForm input[type=checkbox]');
            let disabled = true
            schedules.each(element => {
                if ($(schedules[element]).is(":checked")) {
                    disabled = false;
                    return false
                }
            });
            $('#newsletterForm button[type=submit]').attr('disabled', disabled);
        }

        $('#newsletterForm input[type=checkbox]').change(function () {
            checkSchedules()
        })

        $("#newsletterEmail").on("change keyup", function () {
            if ($(this).val().length > 5) {
                $("#newsletterSchedules").show()
                checkSchedules()
            } else {
                $("#newsletterSchedules").hide()
                $('#newsletterForm button[type=submit]').attr('disabled', true);
            }
        })
        $("#newsletterForm").on( "submit", function( event ) {
            event.preventDefault();
            $("#newsletterSpinner").show()
            $("#newletterResponse").html('')
            $('#newsletterForm button[type=submit]').attr('disabled', true);

            var formdata = $(this).serialize()
            var url = "<?= $this->Url->build(['action' => 'subscribeNewsletter']) ?>"

            $.ajax({
                type: "POST",
                url: url,
                data: formdata,
                success: function (res) {
                    if (res.status == 'success') {
                        $("#newsletterForm").trigger("reset")
                    }
                    $("#newletterResponse").html(res.message)
                },
                complete: function (xhr, result) {
                    $("#newsletterSpinner").hide();
                    $('#newsletterForm button[type=submit]').attr('disabled', false);
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                }
            });
        });

        
    });
</script>
<?php $this->end(); ?>
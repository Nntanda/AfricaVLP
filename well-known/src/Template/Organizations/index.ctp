<div class="container">
    <div class="d-flex justify-content-between top-line">
        <h4><?= __('Upcoming Opportunities') ?></h4>
        <a href="<?= $this->Url->build(['action' => 'events', 'id' => $organization->id]) ?>"><?= __('See All') ?></a>
    </div>
    <div class="row">
        <?php foreach ($events as $event): ?>
        <div class="col-lg-4 col-md-6">
            <div class="card org-upcoming">
                <div class="card-body">
                    <img src="<?= ($event->image && !empty($event->image)) ? $event->image : $this->Url->image('no-image.jpg') ?>" alt="">
                    <h4><?= h($event->title) ?></h4>
                    <div class="vol-interest">
                        <h1><?= h($event->interests) ?></h1>
                        <p><?= __('Volunteers Interested') ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="recent-updates">
        <div class="top-line">
            <h4><?= __('Recent Updates') ?></h4>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <h5><?= __('News') ?></h5>
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                        <th scope="col"><?= __('Title') ?></th>
                        <th scope="col"><?= __('Date Posted') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($news as $newsData): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?= ($newsData->image && !empty($newsData->image)) ? $newsData->image : $this->Url->image('no-image.jpg') ?>" alt=""> 
                                    <div class="vol-int-name">
                                        <h5><?= h($newsData->title) ?></h5>
                                        <p>
                                            <?php foreach ($newsData->publishing_categories as $publishing_category): ?><span><?= h($publishing_category->name) ?>, </span><?php endforeach; ?>
                                            |
                                            <?php foreach ($newsData->volunteering_categories as $volunteering_category): ?><span><?= h($volunteering_category->name) ?>, </span><?php endforeach; ?>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td><?= $newsData->created->format('d/m/y') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-6">
                <h5><?= __('Resources') ?></h5>
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                        <th scope="col"><?= __('Title') ?></th>
                        <th scope="col"><?= __('Type') ?></th>
                        <th scope="col"><?= __('Country') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($resources as $resource): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="vol-int-name">
                                        <h5><?= h($resource->title) ?></h5>
                                        <p>
                                            <?= h($resource->created->format('d/m/y')) ?>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td><?= $resource->resource_type->name; ?></td>
                            <td><?= $resource->has('country') ? $resource->country->nicename : __('All') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <h6><?= __('Quick Links') ?></h6>
                <ul>
                    <li>
                    <a href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'aboutUs']) ?>"><?= __('About Us') ?></a>
                    </li>
                    <li>
                        <a href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'countryList']) ?>"><?= __('Countries') ?></a>
                    </li>
                    <li>
                        <a href="<?= $this->Url->build(['controller' => 'BlogPosts', 'action' => 'index']) ?>"><?= __('Blogs') ?></a>
                    </li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6><?= __('Resources') ?></h6>
                <ul>
                <?php foreach ($resourceTypes as $resourceType): ?>
                    <li>
                        <a href="<?= $this->Url->build(['controller' => 'Resources', 'action' => 'index', '?' => ['resource_type_id' => $resourceType->id]]) ?>"><?= h($resourceType->name) ?></a>
                    </li>
                <?php endforeach;?>
                </ul>
            </div>
            <div class="col-md-6">
            <img src="<?= $this->Url->image('logo-footer.png') ?>" alt="">
            <p><?= h($footer->content) ?></p>
            </div>
        </div>
    </div>
</footer>
<?php
const CONTROLLER = 'controller';
const ACTION = 'action';
const INDEX = 'index';
?>
<nav class="navbar navbar-expand-lg">
    <div class="container d-flex">
        <a class="navbar-brand d-flex align-items-start" href="<?= $this->Url->build('/') ?>" style="margin-right: 5%">
            <img src="<?= $this->Url->image('logo.png') ?>" alt="" class="align-self-center">
            <p><?= __('Volunteering') ?><br><?= __('Linkage Platform') ?></p>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                <i class="fas fa-bars"></i>
            </span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $this->Url->build('/') ?>"><?= __('Home') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $this->Url->build([CONTROLLER => 'Pages', ACTION => 'aboutUs']) ?>"><?= __('About Us') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $this->Url->build([CONTROLLER => 'Pages', ACTION => 'interactiveMap']) ?>"><?= __('Interactive Map') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $this->Url->build([CONTROLLER => 'VolunteeringOrganizations', ACTION => INDEX]) ?>"><?= __('Organizations') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $this->Url->build([CONTROLLER => 'News', ACTION => INDEX]) ?>"><?= __('News') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $this->Url->build([CONTROLLER => 'Events', ACTION => INDEX]) ?>"><?= __('Opportunities') ?></a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="<?= $this->Url->build([CONTROLLER => 'BlogPosts', ACTION => INDEX]) ?>"><?= __('Blogs') ?></a>
                </li> -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?= __('Resources') ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?= $this->cell('UserOrganizations::resourceTypeLinks') ?>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://au.int/" target="_blank"><?= __('AU Home') ?></a>
</ul>
            <!-- <div class="login_bar">
                <ul>
                    <li class="search">
                    <a href="#">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="search_bar">
                        <form action="#">
                        <input type="text" name="search" placeholder="Search">
                        <span class="search_icon">
                            <i class="fas fa-search"></i>
                        </span>
                        </form>
                    </div>
                    </li>
                </ul>
            </div> -->
        </div>
    </div>
</nav>
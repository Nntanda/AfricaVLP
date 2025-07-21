<?php
$routeName = '_name';
$routeAction = 'organization:actions';
$actionKey = 'action';
?>

<aside id="side-menu" class="aside" role="navigation">
    <ul class="toplist">
        <div class="org-profile d-flex align-items-start flex-column">
            <img src="<?= ($organization->logo !== null && !empty($organization->logo)) ? $organization->logo : $this->Url->image('no-logo.jpg') ?>" alt="" class="rounded">
            <div class="">
                <h5><?= h($organization->name) ?></h5>
                <a href="<?= $this->Url->build([$routeName => $routeAction, $actionKey => 'profile', 'id' => $organization->id]) ?>"><?= __('Edit Profile') ?></a>
            </div>
        </div>
        <li class="active">
            <a href="<?= $this->Url->build([$routeName => 'organization:home', 'id' => $organization->id]) ?>"><?= __('Overview') ?></a>
        </li>
        <li>
            <a href="<?= $this->Url->build([$routeName => $routeAction, $actionKey => 'messages', 'id' => $organization->id]) ?>"><?= __('Messages') ?></a>
        </li>
        <li>
            <a href="<?= $this->Url->build([$routeName => $routeAction, $actionKey => 'auMessages', 'id' => $organization->id]) ?>"><?= __('Support Messages') ?></a>
        </li>
    </ul>

    <ul class="list">
        <p><?= __('Opportunity management') ?></p>
        <li>
            <a href="<?= $this->Url->build([$routeName => $routeAction, $actionKey => 'createEvent', 'id' => $organization->id]) ?>"><?= __('Create New Opportunity') ?></a>
        </li>
        <li>
            <a href="<?= $this->Url->build([$routeName => $routeAction, $actionKey => 'events', 'id' => $organization->id]) ?>"><?= __('Opportunities') ?></a>
        </li>
    </ul>

    <ul class="list">
        <p><?= __('News management') ?></p>
        <li>
            <a href="<?= $this->Url->build([$routeName => $routeAction, $actionKey => 'postNews', 'id' => $organization->id]) ?>"><?= __('Post News') ?></a>
        </li>
        <li>
            <a href="<?= $this->Url->build([$routeName => $routeAction, $actionKey => 'news', 'id' => $organization->id]) ?>"><?= __('News') ?></a>
        </li>
        </ul>
        <ul class="list">
        <p><?= __('Resources management') ?></p>
        <li>
            <a href="<?= $this->Url->build([$routeName => $routeAction, $actionKey => 'addResource', 'id' => $organization->id]) ?>"><?= __('Upload Resource') ?></a>
        </li>
        <li>
            <a href="<?= $this->Url->build([$routeName => $routeAction, $actionKey => 'resources', 'id' => $organization->id]) ?>"><?= __('Resources') ?></a>
        </li>
    </ul>
    <ul class="list">
        <p><?= __('Admin management') ?></p>
        <li>
            <a href="<?= $this->Url->build([$routeName => $routeAction, $actionKey => 'addAdmin', 'id' => $organization->id]) ?>"><?= __('New Admin') ?></a>
        </li>
        <li>
            <a href="<?= $this->Url->build([$routeName => $routeAction, $actionKey => 'admins', 'id' => $organization->id]) ?>"><?= __('Admins') ?></a>
        </li>
    </ul>
    <ul class="list">
        <p><?= __('Reports') ?></p>
        <li>
            <a href="<?= $this->Url->build([$routeName => $routeAction, $actionKey => 'volunteersReport', 'id' => $organization->id]) ?>"><?= __('Volunteers') ?></a>
        </li>
        <!-- <li>
            <a href="<?= $this->Url->build([$routeName => $routeAction, $actionKey => 'organizationsReport', 'id' => $organization->id]) ?>"><?= __('Organizations') ?></a>
        </li>
        <li>
            <a href="<?= $this->Url->build([$routeName => $routeAction, $actionKey => 'eventsReport', 'id' => $organization->id]) ?>"><?= __('Events') ?></a>
        </li> -->
    </ul>
        <ul class="list">
        <p><?= __('VLP Surveys') ?></p>
        <li>
            <a href="https://www.surveymonkey.com/r/TVFS9QP" target=”_blank”><?= __('Africa Volunteer Mapping') ?></a>
        </li>
    </ul>
</aside>
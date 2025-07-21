<?php
use Cake\Core\Configure;
?>
<div class="top d-flex justify-content-between align-items-center">
    <div class="lang">
        <?php $i=0; foreach (Configure::read('I18n.languages') as $langCode => $lang): ?>
        <?= $this->Form->postLink(
            $lang['nativeName'], 
            ['controller' => 'pages', 'action' => 'chooseLanguage'], 
            ['data' => ['lang' => $langCode], 'class' => $i === 0 ? '' : 'line']
        ) ?> 
        <?php $i++; endforeach; ?>
    </div>
    <div class="reg">
        <?php if (isset($authUser) && !empty($authUser)): ?>
            
            <div class="dropdown">
                <a href="#" class="dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php if ($authUser['profile_image'] !== null && !empty($authUser['profile_image'])): ?>
                    <img src="<?= $authUser['profile_image'] ?>" alt="">
                    <?php else: echo $authUser['first_name']; endif; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                    <div class="card border-0">
                        <div class="list-group list-group-flush">
                            <li class="list-group-item">
                            <div class="chat-user d-flex">
                                <div class="user-img">
                                <img src="<?= ($authUser['profile_image'] !== null && !empty($authUser['profile_image'])) ? $authUser['profile_image'] : $this->Url->image('no-image.jpg') ?>" alt="">
                                </div>
                                <div class="name-side">
                                <h5><?= h($authUser['first_name']. ' '. $authUser['last_name']) ?></h5>
                                <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'profile']) ?>"><?= __('View Profile') ?></a> | <a href="<?= $this->Url->build(['controller' => 'AlumniForums', 'action' => 'publicThreads']) ?>"><?= __('Public Forums') ?></a>
                                </div>
                            </div>
                            </li>
                        </div>
                        <div class="card-header">
                            <?= __('Manage Accounts') ?>
                        </div>
                        
                        <?php if (isset($authUser)){ if(isset($authUser['allow_organizations']) && $authUser['allow_organizations']){ echo $this->cell('UserOrganizations', [$authUser['id']]); }} ?>
                        <div class="card-footer border-0 d-flex">
                            <!-- <a class="more-org" href="org-profile.html"><?= __('Add Organization') ?></a> -->
                            <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'logout']) ?>"><?= __('Sign Out') ?></a>
                        </div>
                    </div>
                    <!-- <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'profile']) ?>" class="dropdown-item"><?= __('Profile') ?></a> -->
                    
                    <!-- <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'logout']) ?>" class="dropdown-item"><?= __('Logout') ?></a> -->
                </div>
            </div>
        <?php else: ?>
            <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login']) ?>" class="btn btn-small mr-md-3 top-login"><?= __('Login') ?></a>
            <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'createAccount']) ?>" class="btn top-reg"><?= __('Register') ?></a>
        <?php endif; ?>
    </div>
</div>
<li class="nav-item">
    <div class="dropdown btn-group">
        <button class="dropdown-toggle auto-btn" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?= __('Translate') ?>
        </button>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
            <p class="pl-3 m-0 text-muted"><?= __('Translate from:') ?></p>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item translate" href="#" data-lang="en" onClick="return false;">English</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item translate" href="#" data-lang="fr" onClick="return false;"><?= \Cake\Core\Configure::read('I18n.languages.fr.nativeName') ?></a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item translate" href="#" data-lang="pt" onClick="return false;"><?= \Cake\Core\Configure::read('I18n.languages.pt.nativeName') ?></a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item translate" href="#" data-lang="ar" onClick="return false;"><?= \Cake\Core\Configure::read('I18n.languages.ar.nativeName') ?></a>
        </div>
    </div>
</li>
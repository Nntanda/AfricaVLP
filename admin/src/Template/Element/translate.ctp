<li class="nav-item">
    <div class="btn-group" data-toggle="kt-tooltip">
    <button type="button" class="btn auto-btn" data-toggle="dropdown">
        <?= __('Translate') ?>
    </button>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-fit dropdown-menu-md">
        <!--begin::Nav-->
        <ul class="kt-nav">
        <li class="kt-nav__section">
            <span class="kt-nav__section-text">
                <?= __('Translate From') ?>
            </span>
        </li>
        <!-- <li class="kt-nav__separator"></li> -->
        <li class="kt-nav__item">
            <a href="#" class="kt-nav__link translate" data-lang="en" onClick="return false;">
            <span class="kt-nav__link-text">English</span>
            </a>
        </li>
        <li class="kt-nav__item">
            <a href="#" class="kt-nav__link translate" data-lang="fr" onClick="return false;">
            <span class="kt-nav__link-text"><?= \Cake\Core\Configure::read('I18n.languages.fr.nativeName') ?></span>
            </a>
        </li>
        <li class="kt-nav__item">
            <a href="#" class="kt-nav__link translate" data-lang="pt" onClick="return false;">
            <span class="kt-nav__link-text"><?= \Cake\Core\Configure::read('I18n.languages.pt.nativeName') ?></span>
            </a>
        </li>
        <li class="kt-nav__item">
            <a href="#" class="kt-nav__link translate" data-lang="ar" onClick="return false;">
            <span class="kt-nav__link-text"><?= \Cake\Core\Configure::read('I18n.languages.ar.nativeName') ?></span>
            </a>
        </li>
        </ul>
        <!--end::Nav-->
    </div>
    </div>
</li>
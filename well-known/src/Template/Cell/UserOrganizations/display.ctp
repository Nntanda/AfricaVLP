<?php foreach ($userOrganizations as $orgnizationData): ?>
    <li class="list-group-item">
        <a href="<?= $this->Url->build(['_name' => 'organization:home', 'id' => $orgnizationData->organization_id]) ?>">
            <div class="org-profile d-flex align-items-center">
                <?php if ($orgnizationData->organization->logo) { ?><img src="<?= $orgnizationData->organization->logo ?>" alt=""> <?php } ?>
                <div class="">
                    <h5><?= $this->Text->truncate($orgnizationData->organization->name, 20, ['ellipsis' => '...']) ?></h5>
                    <span><?= h($orgnizationData->role) ?></span>
                </div>
            </div>
        </a>
    </li>
    <div class="dropdown-divider"></div>
    <!-- <a href="<?= $this->Url->build(['_name' => 'organization:home', 'id' => $orgnizationData->organization_id]) ?>" class="dropdown-item"><?= h($orgnizationData->organization->name) ?></a> -->
<?php endforeach; ?>
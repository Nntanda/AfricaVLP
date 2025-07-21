<?php foreach ($resourceTypes as $resourceType): ?>
    <a href="<?= $this->Url->build(['controller' => 'Resources', 'action' => 'index', '?' => ['resource_type_id' => $resourceType->id]]) ?>" class="dropdown-item"><?= h($resourceType->name) ?></a>
<?php endforeach; ?>
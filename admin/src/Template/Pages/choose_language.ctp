<?php foreach ($langs as $langCode => $lang): ?>
<?= $this->Form->postLink(
    $lang['nativeName']. '(' .$lang['englishName'] .') ', 
    ['action' => 'chooseLanguage'], 
    ['data' => ['lang' => $langCode]]
) ?> 
<?php endforeach; ?>
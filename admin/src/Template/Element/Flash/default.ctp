<?php
$class = 'alert alert-bold';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class=" <?= h($class) ?>" role="alert">
    <div class="alert-text text-center"><?= $message ?></div>
</div>
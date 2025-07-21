<?php
$class = 'alert alert-dismissible fade show';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
} else {
    $class .= 'alert-info';
}
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="<?= h($class) ?>" role="alert">
    <?= $message ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

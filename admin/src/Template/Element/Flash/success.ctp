<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="alert alert-solid-success alert-bold" role="alert">
    <div class="alert-text text-center"><?= $message ?></div>
</div>

<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= $message ?> <br/>
    <?= $this->Form->postLink(__('Click here to resend verification mail'), ['controller' => 'Users','action' => 'resendEmailValidation'], ['data' => ['id' => $params['id']]]); ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

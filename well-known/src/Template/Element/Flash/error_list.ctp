<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= __('The following fields are invalid:') ?> <br/>
    <?php foreach($message as $field => $errors) {
        echo " - $field (". $this->Text->toList($errors) .') <br/>';
    } ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


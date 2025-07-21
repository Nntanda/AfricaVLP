<script>
    $(document).ready(function () {
        $('.bs-validate button[type="submit"]').click(function (e) {
            $('#alert').html('');
            transValid = true;
            $(this).parents('form').find('.tr-input').each(function () {
                if ($(this).val()) return true;
                transValid = false;
                return false;
            })

            if (!transValid) {
                e.preventDefault()
                alert = `
                <div class="alert alert-solid-danger alert-dismissible fade show" role="alert">
                    <?= __('Translations are required. Please translate.') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                `;
                $('#alert').html(alert);
            }
        })

    });

</script>
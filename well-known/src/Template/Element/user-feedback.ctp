<style>
    .feedback-box {
        border-radius: 8px;
        box-shadow: 0 0px 15px rgba(0, 0, 0, 0.07);
    }
    .form-check .form-check-label {
        font-size: 28px;
        opacity: 0.7;
        cursor: pointer;
    }
    .form-check.selected .form-check-label {
        font-size: 30px;
        opacity: 1;
    }
    .feedback-link {
        font-size: 13px;
        font-weight: 500;
    }
</style>

<?php $feedbackRatings = \App\Model\Table\UserFeedbacksTable::$ratings; ?>
<?php if (isset($authUser)): ?>
<a href="#" onClick="return false;"><span class="badge bg-light feedback-link"><?= __('Send feedback') ?></span></a>

<div class="row">
    <div class="col-11 col-sm-6 col-md-5 mx-auto p-3 feedback-box">
        <div class="text-center">
            <h5><?= __('Your feedback help us to improve.') ?></h5>
        </div>
        <?= $this->Form->create(false, ['id' => 'feedback-form']) ?>
            <div class="form-group text-center" id="feedback-rating">
                <div class="d-flex justify-content-center">
                    <?php $c=1; foreach ($feedbackRatings as $feedbackRating): ?>
                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input d-none"
                                type="radio"
                                name="feedback_rating"
                                id="inlineRadio<?= $c ?>"
                                value="<?= $feedbackRating['value'] ?>"
                            />
                            <label class="form-check-label" for="inlineRadio<?= $c ?>"><?= $feedbackRating['emoji'] ?></label>
                        </div>
                    <?php $c++; endforeach; ?>
                </div>
                <small class="form-feedback"></small>
            </div>

            <div class="form-group">
                <label for="feedback-message"><?= __('Any feedback would be appreciated.') ?></label>
                <textarea name="feedback_message" class="form-control" id="feedback-message" rows="3" placeholder="Message" required></textarea>
                <small class="form-feedback"></small>
            </div>
            <input type="hidden" name="user_id" value="<?= $authUser['id'] ?>">
            <input type="hidden" name="object_id" value="<?= $object_id ?>">
            <input type="hidden" name="object_model" value="<?= $object_model ?>">
            <button type="submit" class="btn btn-success btn-block feedback-submit">
                <span class="form-loader">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span class="sr-only"><?= __('Loading...') ?></span>
                </span>
                <?= __('Submit') ?>
            </button>
            <small class="d-block form-submit-feedback"></small>
        <?= $this->Form->end() ?>
        <div class="text-center d-none" id="feedback-success">
            <h5 class="text-success"><?= __('Feedback submitted') ?></h5>
            <p><?= __('Thank you for sending us your feedback.') ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Scripts -->
<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
        $('.feedback-box').hide();
        $('.form-loader').hide();

        $(".form-check-input").change(function () {
            $(".form-check").removeClass("selected");
            $(this).parent(".form-check").addClass("selected");

            $('#feedback-rating .form-feedback').html('');
            $('#feedback-rating .form-feedback').removeClass('text-danger');
        });

        $('.feedback-link').click(function () {
            $('.feedback-box').toggle();
        })

        $('#feedback-form').submit(function name(e) {
            e.preventDefault();
            let errors = false;
            if (!$('input[name="feedback_rating"]:checked').val()) {
                $('#feedback-rating .form-feedback').addClass('text-danger');
                $('#feedback-rating .form-feedback').html('<?= __('Please choose a rating') ?>');
                error = true;
            }

            if (!$('#feedback-message').val() || $('#feedback-message').val().trim() == '' || $('#feedback-message').val().trim().length < 4) {
                $('#feedback-message').siblings('.form-feedback').addClass('text-danger');
                $('#feedback-message').siblings('.form-feedback').html('<?= __('Feedback message is required') ?>');
                errors = true;
            } else {
                $('#feedback-message').siblings('.form-feedback').removeClass('text-danger');
                $('#feedback-message').siblings('.form-feedback').html('');
            }

            if (errors) return;
            
            $(this).find('.feedback-submit').attr('disabled', true);
            $(this).find('.form-loader').show();
            $('#feedback-form .form-submit-feedback').html('');

            $.ajax({
                type: "POST",
                url: "<?= $this->Url->build(['controller' => 'App', 'action' => 'sendFeedback']) ?>",
                data: $(this).serialize(),
                success: function (data) {
                    if (data && data.status === 'success') {
                        console.log('-- Success --');
                        $('#feedback-form').hide()
                        $('#feedback-success').removeClass('d-none');
                    } else {
                        $('#feedback-form .form-submit-feedback').addClass('text-danger');
                        $('#feedback-form .form-submit-feedback').html('<?= __('Error sending feedback') ?>');
                    }
                },
                error: function (xhr, status, data) {
                    $('#feedback-form .form-submit-feedback').addClass('text-danger');
                    $('#feedback-form .form-submit-feedback').html('<?= __('Error submitting feedback') ?>');
                },
                complete: function (xhr, result) {
                    $('#feedback-form .form-loader').hide();
                    $('#feedback-form .feedback-submit').attr('disabled', false);
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                }
            });
        })
    });
</script>
<?php $this->end() ?> 

<div class="container">
    <div class="top-line items-list">
        <div class="wrap mb-4">
            <div class="card">
                <div class="row no-gutters d-flex align-items-stretch">
                    <div class="col-lg-3 card-img" style="background-image: url(<?= ($event->image && !empty($event->image)) ? $event->image : $this->Url->image('programes.jpg') ?>);"></div>
                    <div class="col-lg-9">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-9">
                                    <div class="card-content d-flex flex-column">
                                        <h4 class="card-title"><?= h($event->title) ?></h4>
                                        <p class="card-text"><?= h($event->description) ?></p>
                                        <p class="card-text d-flex mt-auto">
                                            <small class="text-muted flex-fill"><?= __('Date') ?>:
                                            <span><?= $event->created->format('M d, Y') ?></span></small>
                                            <small class="text-muted flex-fill"><?= __('Location') ?>:
                                            <span><?= (($event->has('city') ? $event->city->name. ', ' : ''). $event->country->nicename) ?></span></small>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-3 d-flex align-items-center justify-content-end">
                                    <a href="<?= $this->Url->build(['action' => 'editEvent', 'id' => $organization->id, $event->id]) ?>" class="btn btn-small"><?= __('Edit Event') ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <div class="location">
                    <h3><?= count($interests) ?> <span><?= __('Volunteers Interested') ?></span></h3>
                    </div>
                    <div class="sector d-flex align-items-center">
                    <h3><?= ($event->event_comments && !empty($event->event_comments) ? count($event->event_comments) : 0) ?> <span><?= __('Comments') ?></span></h3>
                    </div>
                </div>
            </div>
        </div>

        <label class="form-label" for=""><?= __('Event link') ?></label>
        <div class="input-group mb-3">
            <input type="text" id="profile-link" class="form-control" readonly aria-describedby="button-addon2" value="<?= $this->Url->build(['controller' => 'events', 'action' => 'view', $event->id], true) ?>">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" id="copy-btn" type="button" data-toggle="tooltip" title="Copy to clipboard"><i class="fa fa-copy"></i></button>
            </div>
        </div>
    </div>

    <div class="recent-updates program-table mt-3">
        <h5><?= __('Volunteering Interests') ?></h5>
        <!-- <div class="row">
            <div class="col-md-8">
                <label for="">Search</label>
                <input type="text" class="form-control" placeholder="Search Program">
            </div>
            <div class="col-md-4">
                <label for="">Event status</label>
                <select name="" id="" class="form-control"></select>
            </div>
        </div> -->
        <table class="table table-hover">
            <tbody>
                <?php foreach($interests as $interest): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php $profile_image = $interest['user']['profile_image']; ?>
                                <img src="<?= ($profile_image && !empty($profile_image)) ? $profile_image : $this->Url->image('no-image.jpg') ?>" alt="">
                                <div class="vol-int-name">
                                    <h5><?= h($interest['user']['first_name'] .' '. $interest['user']['last_name']) ?></h5>
                                    <p><?= h($interest['created']->format('d/m/y - g:iA')) ?> </p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="vol-program">
                                <p>
                                    <span><?= h($interest['volunteering_oppurtunity']['volunteering_role']['name']) ?></span>
                                </p>
                            </div>
                        </td>
                        <td>
                            <div class="dropdown">
                                <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="<?= $this->Url->image('table-menu.svg') ?>" alt="">
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'publicProfile', $interest['user']['id']]) ?>" target="_blank"><?= __('View Profile') ?></a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?= $this->Url->build(['action' => 'approveInterest', 'id' => $organization->id, $interest['id']]) ?>"><?= __('Check-in/Approve Request') ?></a>
                                    <div class="dropdown-divider"></div>
                                    <!-- <a class="dropdown-item" href="#"><?= __('Decline Request') ?></a> -->
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<?php $this->Html->script("https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js", ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<script>
    $(document).ready(function () {
        $('#copy-btn').tooltip();
        $('#copy-btn').click(function (e) {
          var copyText = document.getElementById("profile-link");
          /* Select the text field */
          copyText.select();
          copyText.setSelectionRange(0, 99999); /*For mobile devices*/

          /* Copy the text inside the text field */
          document.execCommand("copy");
          $(this).attr('title', "<?= __('Copied to clipboard!') ?>");
          $('#copy-btn').tooltip();
        });

        $("#volunteering-categories-ids").select2()

        let initialCountries = $("#country-id").html();
        let initialCities = $("#city-id").html();
        $("#region-id").change(function () {
            region_id = $(this).val();
            if(region_id && region_id !== '') {
                $("#country-id").html('<option> ... </option>')
                $("#city-id").html('')
                let options = '';
                $.ajax({
                    type: "POST",
                    url: "<?= $this->Url->build('/region-country-list') ?>"+ '/' +region_id,
                    success: function (data) {
                        for (k in data) {
                            options += `<option value="${k}"> ${data[k]} </option>`;
                        };
                    },
                    complete: function (xhr, result) {
                        $("#country-id").html(options)
                    },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                    }
                });
            } else {
                $("#country-id").html(initialCountries);
                $("#city-id").html(initialCities)
            }
        })

        $("#country-id").change(function () {
            country_id = $(this).val();
            if(country_id && country_id !== '') {
                $("#city-id").html('<option> ... </option>')
                let options = '';
                $.ajax({
                    type: "POST",
                    url: "<?= $this->Url->build('/country-city-list') ?>"+ '/' +country_id,
                    success: function (data) {
                        for (k in data) {
                            options += `<option value="${k}"> ${data[k]} </option>`;
                        };
                    },
                    complete: function (xhr, result) {
                        $("#city-id").html(options)
                    },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                    }
                });
            } else {
                $("#city-id").html('');
            }
        })
    });

</script>

<?php $this->end(); ?>
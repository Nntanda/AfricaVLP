<div class="main organization">
    
    <div class="organization-side map-results" id="countriesDiv">
        <div class="container">
            <div class="innder-head">
                <h1><?= __('Countries') ?></h1>
            </div>
            <div class="row">
                <?php foreach($countries as $country): ?>
                <div class="col-md-4">
                    <div class="card quick-link">
                        <ul class="list-group list-group-flush" id="topList">
                            <a href="<?= $this->Url->build(['action' => 'countryPage', $country->iso]) ?>">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <img src="https://www.countryflags.io/<?= $country->iso ?>/flat/64.png" alt="">
                                    <p><?= $country->nicename ?></p>
                                </li>
                            </a>
                        </ul>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php $this->start('scriptBlock') ?>
<script>

    $(document).ready(function () {

        function truncateString(str, num) {
            if (str.length <= num) {
                return str
            }
            return str.slice(0, num) + '...'
        }
        
    });
</script>
<?php $this->end(); ?>
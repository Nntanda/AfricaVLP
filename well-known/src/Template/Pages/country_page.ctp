
<div class="main">
    <div class="container organization country-page">
        <div class="card organization-header">
          <div class="card-body d-flex align-items-center">
            <img src="https://www.countryflags.io/<?= $country->iso ?>/flat/64.png" alt="" class="card-img">
            <div class="country-title">
              <h3><?= h($country->nicename) ?></h3>
              <p class="capital"><?= __('Capital') ?>:
                <b><?= h($country->capital) ?></b>
              </p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3 organization-side">
            <h4><?= __('Country Informations') ?></h4>
            <div class="card quick-link">
              <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                  <p><?= __('Location') ?></p>
                  <span>
                    <em><?= h($country->region->name) ?></em>
                  </span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <p><?= __('Population') ?></p>
                  <span>
                    <em><?= h($country->population) ?></em>
                  </span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <p><?= __('Official Language') ?></p>
                  <span>
                    <em><?= h($country->official_language) ?></em>
                  </span>
                </li>
              </ul>
            </div>
            <h4><?= __('Quick Links') ?></h4>
            <div class="card quick-link">
              <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                  <a href="<?= $this->Url->build(['controller' => 'Resources', 'action' => 'index', '?' => ['country_id' => $country->id]]) ?>"><?= __('Country Resources') ?></a>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <a href="<?= $this->Url->build(['controller' => 'News', 'action' => 'index', '?' => ['region_id' => "all"]]) ?>"><?= __('News') ?></a>
                  <span>(<?= $newsCount ?>)</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <a href="<?= $this->Url->build(['controller' => 'BlogPosts', 'action' => 'index', '?' => ['region_id' => "all"]]) ?>"><?= __('Blogs') ?></a>
                  <span>(<?= $blogsCount ?>)</span>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-md-9 organization-main">
            <!-- <div class="card overview mb-4">
              <div class="card-header">
                <?= __('Volunteerism Impact') ?>
              </div>
              <div class="card-body">
                <div class="card">
                  <div class="card-body d-flex">
                    <div class="flex-fill">
                      <h3>-</h3>
                      <p><?= __('Hours Volunteered') ?></p>
                    </div>
                    <div class="flex-fill">
                      <h3>-</h3>
                      <p><?= __('GDP Contribution') ?></p>
                    </div>
                    <div class="flex-fill">
                      <h3>-</h3>
                      <p><?= __('People Reached') ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="card overview stats mb-4">
              <div class="card-header">
                <?= __('Volunteerism Statistics') ?>
              </div>
              <div class="card-body">
                <div class="card mb-4">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-lg-4 mb-4">
                        <div class="flex-fill mb-4">
                          <h3><?= h($organizations) ?></h3>
                          <p><?= __('Volunteering Organizations') ?> (<a href="<?= $this->Url->build(['controller' => 'VolunteeringOrganizations', 'action' => 'index', '?' => ['country_id' => $country->id]]) ?>"><?= __('See All') ?></a>)</p>
                        </div>
                        <div class="flex-fill">
                          <h3><?= h($events) ?></h3>
                          <p><?= __('Volunteering Events') ?> (<a href="<?= $this->Url->build(['controller' => 'Events', 'action' => 'index', '?' => ['country_id' => $country->id]]) ?>"><?= __('See All') ?></a>)</p>
                        </div>
                      </div>
                      <div class="col-lg-8 border">
                        <div class="form-group">
                          <label for=""><?= __('Select Chart Goal') ?></label>
                          <span class="spinner-border spinner-border-sm" role="status" id="spinner">
                              <span class="sr-only"><?= __('Loading...') ?></span>
                          </span>
                          <?= $this->Form->control('cat', ['empty' => __('All'), 'options' => $volunteeringCategories, 'label' => false]); ?>
                        </div>
                        <div id="sdgs_div"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card mb-4">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-lg-5 mb-4">
                        <div class="flex-fill mb-4">
                          <h3><?= h($totalVolunteers) ?></h3>
                          <p><?= __('Number of Volunteers') ?></p>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="gender d-flex align-items-center">
                              <img src="<?= $this->Url->image('female.svg') ?>" alt="">
                              <div class="detail">
                                <p><?= __('Female') ?></p>
                                <h4><?= ($totalVolunteers === 0) ? 0 : round(($femaleVolunteers/$totalVolunteers) * 100, 2) ?>%</h4>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6 mb-4">
                            <div class="gender d-flex align-items-center">
                              <img src="assets/img/male.svg" alt="">
                              <div class="detail">
                                <p><?= __('Male') ?></p>
                                <h4><?= ($totalVolunteers === 0) ? 0 : round(($maleVolunteers/$totalVolunteers) * 100, 2) ?>%</h4>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-7">
                      <div class="age-contribution">
                        <h4><?= __('Age Contribution') ?></h4>
                        <div class="d-flex align-items-center">
                          <label for="">15-19</label>
                          <?php $_15_19 = ($totalVolunteers === 0) ? 0 : round(($ageRanges['15-19']/$totalVolunteers) * 100, 2) ?>
                          <div class="progress flex-fill">
                            <div class="progress-bar" role="progressbar" style="width: <?= $_15_19 ?>%;" aria-valuenow="<?= $_15_19 ?>" aria-valuemin="0" aria-valuemax="100"><?= $_15_19 ?>%</div>
                          </div>
                        </div>
                        <div class="d-flex align-items-center">
                          <label for="">20-24</label>
                          <?php $_20_24 = ($totalVolunteers === 0) ? 0 : round(($ageRanges['20-24']/$totalVolunteers) * 100, 2) ?>
                          <div class="progress flex-fill">
                            <div class="progress-bar" role="progressbar" style="width: <?= $_20_24 ?>%;" aria-valuenow="<?= $_20_24 ?>" aria-valuemin="0" aria-valuemax="100"><?= $_20_24 ?>%</div>
                          </div>
                        </div>
                        <div class="d-flex align-items-center">
                          <label for="">25-29</label>
                          <?php $_25_29 = ($totalVolunteers === 0) ? 0 : round(($ageRanges['25-29']/$totalVolunteers) * 100, 2) ?>
                          <div class="progress flex-fill">
                            <div class="progress-bar" role="progressbar" style="width: <?= $_25_29 ?>%;" aria-valuenow="<?= $_25_29 ?>" aria-valuemin="0" aria-valuemax="100"><?= $_25_29 ?>%</div>
                          </div>
                        </div>
                        <div class="d-flex align-items-center">
                          <label for="">30-35</label>
                          <?php $_30_35 = ($totalVolunteers === 0) ? 0 : round(($ageRanges['30-35']/$totalVolunteers) * 100, 2) ?>
                          <div class="progress flex-fill">
                            <div class="progress-bar" role="progressbar" style="width: <?= $_30_35 ?>%;" aria-valuenow="<?= $_30_35 ?>" aria-valuemin="0" aria-valuemax="100"><?= $_30_35 ?>%</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>


<?= $this->Html->script('chart-loader.js', ['block' => 'script']) ?>
<?php $this->start('scriptBlock') ?>
<script>
    var chartLoaded = false

    // Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages': ['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(loadChartData);

    // Callback that creates and populates a data table, instantiates the pie chart, passes in the data and draws it.
    function drawChart(data, text = '') {
      var rows = data.map(function(item) {
        return [item.name, item.events_count]
      })
      // Create the data table.
      var data = new google.visualization.DataTable();
      data.addColumn('string', "<?= __('OrganizationsTypes') ?>");
      data.addColumn('number', "<?= __('Events') ?>");
      data.addRows(rows);

      // Set chart options
      var agenda = {
        'title': "<?= __('Agenda 2063') ?>" + ` - ${text}`
      };

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.PieChart(document.getElementById('sdgs_div'));
      chart.draw(data, agenda);
    }

    function loadChartData(category = '', categoryText = '') {
      $("#spinner").show();
      url = "<?= $this->Url->build(['action' => 'getAgendaData', $country->iso]) ?>?"
      $.ajax({
            type: "GET",
            url: url+ '&cat=' +category,
            success: function (res) {
                if (res.data) {
                    drawChart(res.data, categoryText);
                }
            },
            complete: function (xhr, result) {
                $("#spinner").hide();
            }
        });
    }

    $(document).ready(function () {
        $("#spinner").hide();

        $("#cat").change(function () {
          loadChartData($(this).val(), $("#cat option:selected").text());
        })
    });
</script>
<?php $this->end(); ?>
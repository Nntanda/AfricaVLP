<div class="main organization map-page">
    <div class="map-container" id="map">
    </div>
    <div class="card ml-2 mt-1 col-xs-6 col-sm-3 p-0" id="controlDiv" style="font-size: 0.9rem">
        <div class="card-header">
            <?= __('Select Country') ?>
            <?= $this->Form->control('country', ['empty' => __('All of Africa'), 'label' => false]) ?>
        </div>
        <div class="card-body">
            <div class="">
                <span><?= __('Volunteering Programs in Africa') ?></span>
                <?= $this->Form->control('display', ['value' => $display, 'options' => ['volunteer_organizations' => __('Volunteer Organizations'), 'volunteer_events' => __('Volunteer Opportunities') ], 'label' => false, 'type' => 'radio', 'templates' => ['radioContainer' => '<div class="form-check">{{content}}</div>',]]) ?>
            </div>
            <div class="my-3">
                <span><?= __('Select Sector') ?></span>
                <?= $this->Form->control('volunteering_category', ['empty' => __('All Sectors'), 'label' => false]) ?>
            </div>
            <div class="d-flex">
                <div class="flex-fill">
                <h3 id="data-stat">-</h3>
                <p id="data-text">-</p>
                </div>
                <div class="flex-fill">
                    <span class="spinner-border spinner-border-sm" role="status" id="spinner">
                        <span class="sr-only"><?= __('Loading...') ?></span>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="organization-side map-results" id="countriesDiv">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h4><?= __('Top 10 Nations') ?></h4>
                    <div class="card quick-link">
                        <ul class="list-group list-group-flush" id="topList">
                            <!--  -->
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <h4><?= __('Bottom 10 Nations') ?></h4>
                    <div class="card quick-link">
                        <ul class="list-group list-group-flush" id="buttomList">
                            <!--  -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="items-list map-results" id="dataDiv">
        <div class="container" id="dataContainer">
            <!--  -->
        </div>
    </div>
</div>

<div class="socials">
    <div class="container">
    <div class="row">
        <div class="col-md-6">
        <div class="tweeter">
            <a class="twitter-timeline" data-height="350" href="https://twitter.com/AUVolunteer?ref_src=twsrc%5Etfw"><?= __('Tweets by') ?> AUVolunteer</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        </div>
        </div>
        <div class="col-md-6">
            <div class="connect">
                <h6><?= __('CONNECT WITH US') ?></h6>
                <div class="social">
                <a href="https://twitter.com/AUVolunteer" target="_blank" rel="noopener noreferrer"><img src="<?= $this->Url->image('twitter.svg') ?>" alt="" class="svg"></a>
                <a href="https://www.facebook.com/auyvc/" target="_blank" rel="noopener noreferrer"><img src="<?= $this->Url->image('facebook.svg') ?>" alt="" class="svg"></a>
                </div>
            </div>
            <div class="newsletter">
                <h6><?= __('NewsLetter') ?></h6>
                <p><?= __('Select the newsletter(s) to which you want to subscribe or unsubscribe.') ?></p>
                <div class="input-group">
                <input type="email" class="form-control" placeholder="<?= __('Enter your email') ?>">
                <span class="input-group-btn">
                    <button class="btn" type="submit"><?= __('Subscribe') ?></button>
                </span>
                </div>
                <p class="newsletter-text"><?= __('AU-VLP is a continental development program that recruits and works with youth volunteers, to work in all 54 countries across the African Union') ?></p>
            </div>
        </div>
    </div>
    </div>
</div>

<?php $this->Html->script("https://maps.googleapis.com/maps/api/js?key=AIzaSyBQzkAnV6V7naTqRsuMkfGENsBjpaFSUt4&callback=initMap", ['block' => 'script', 'async' => true, 'defer' => true]) ?>
<?php $this->Html->script("https://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js", ['block' => 'script']) ?>

<?php $this->start('scriptBlock') ?>
<script>
    var map;
    var markers = [];
    var markerCluster = null;
    var infoWindow

    // Initialize and add the map
    function initMap() {
        // The location of Uluru
        // var africa = {lat: 11.5024338, lng: 17.7578122};
        var africa = {lat: 3.8802191583624013, lng: 21.151900945117184};
        // The map, centered at Uluru
        map = new google.maps.Map(
            document.getElementById('map'), {
                zoom: 3.5, 
                center: africa, 
                streetViewControl: false,
                // mapTypeControl: false,
            });
        // The marker, positioned at Uluru
        // var marker = new google.maps.Marker({position: uluru, map: map});

        infoWindow = new google.maps.InfoWindow;

        // Add control
        var controlDiv = document.getElementById('controlDiv');
        // var myControl = new MyControl(controlDiv);

        map.controls[google.maps.ControlPosition.LEFT_TOP].push(controlDiv);
    }

    // Sets the map on all markers in the array.
    function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }

    // Removes the markers from the map, but keeps them in the array.
    function clearMarkers() {
        setMapOnAll(null);
        if (markerCluster) {
            markerCluster.clearMarkers();
        }
    }

    function loadMakers(type, data) {
        clearMarkers()
        markers = [];
        if (type === 'volunteer_organizations') {
            markers = data.map(function(location, i) {
                var infowincontent = document.createElement('div');
                var strong = document.createElement('strong');
                strong.textContent = location.name
                var anchor = document.createElement('a');
                anchor.href = "<?= $this->Url->build(['controller' => 'VolunteeringOrganizations', 'action' => 'view']) ?>/"+location.id;
                anchor.target = "_blank"
                anchor.appendChild(strong);
                infowincontent.appendChild(anchor);
                infowincontent.appendChild(document.createElement('br'));

                var text = document.createElement('text');
                text.textContent = location.address
                infowincontent.appendChild(text);
                
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(parseFloat(location.lat), parseFloat(location.lng)),
                });
                marker.addListener('click', function() {
                    infoWindow.setContent(infowincontent);
                    infoWindow.open(map, this);
                });

                return marker;
            });
        } else {
            markers = data.map(function(location, i) {
                var infowincontent = document.createElement('div');
                var strong = document.createElement('strong');
                strong.textContent = location.title
                infowincontent.appendChild(strong);
                infowincontent.appendChild(document.createElement('br'));

                var text = document.createElement('text');
                text.textContent = location.address
                infowincontent.appendChild(text);
                infowincontent.appendChild(document.createElement('br'));

                var anchor = document.createElement('a');
                anchor.href = "<?= $this->Url->build(['controller' => 'Events', 'action' => 'view']) ?>/"+location.id;
                anchor.target = "_blank"
                anchor.textContent = "<?= __('View Progam') ?>"
                infowincontent.appendChild(anchor);
                
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(parseFloat(location.lat), parseFloat(location.lng)),
                });
                marker.addListener('click', function() {
                    infoWindow.setContent(infowincontent);
                    infoWindow.open(map, this);
                });

                return marker;
            });
        }
        markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
    }

    $(document).ready(function () {
        $("#spinner").hide();
        $("#countriesDiv").hide();
        $("#dataDiv").hide();

        function getData(dataType = '', country = '', sector = '') {
            $("#spinner").show();
            var data;
            var url;
            if(dataType === 'volunteer_organizations') {
                url = "<?= $this->Url->build(['action' => 'getOrganizationsLocations']) ?>?"
                $("#data-text").html("<?= __('Volunteering Organizations') ?>")
            } else {
                url = "<?= $this->Url->build(['action' => 'getEventsLocations']) ?>?"
                // data = laodEventData(dataType,country,sector)
                $("#data-text").html("<?= __('Volunteer Events') ?>")
            }

            $.ajax({
                type: "GET",
                url: url+ '&country_id=' +country +'&cat=' +sector,
                success: function (res) {
                    if (res.data) {
                        loadMakers(dataType, res.data);
                        $("#data-stat").html(res.data.length)
                        renderResult(dataType, res)
                    }
                },
                complete: function (xhr, result) {
                    $("#spinner").hide();
                }
            });
            return data;
        }
        getData('<?= $display ?>')
        
        $("input[type=radio][name=display]").change(function () {
            displayVal = $(this).val();
            country = $("#country").val();
            cat = $("#volunteering-category").val();
            $("#data-stat").html('-')
            getData(displayVal, country, cat)
        })
        
        $("#country").change(function () {
            displayVal = $("input[type=radio][name=display]").val();
            country = $(this).val();
            cat = $("#volunteering-category").val();
            $("#data-stat").html('-')
            getData(displayVal, country, cat)
        })
        
        $("#volunteering-category").change(function () {
            displayVal = $("input[type=radio][name=display]").val();
            country = $("#country").val();
            cat = $(this).val();
            $("#data-stat").html('-')
            console.log(displayVal, country, cat)
            getData(displayVal, country, cat)
        })

        function truncateString(str, num) {
            if (str.length <= num) {
                return str
            }
            return str.slice(0, num) + '...'
        }

        function renderResult(type = '', res) {
            if (res.country_id && res.country_id !== "") {
                $("#countriesDiv").hide();
                $("#dataDiv").show();

                var dataItems = `
                    <div class="d-flex align-items-center">
                        <h4> ${res.country.nicename} </h4>
                        <small class="ml-2"><a href="<?= $this->Url->build(['action' => 'countryPage']) ?>/${res.country.iso}">View country page</a></small>
                    </div>
                `;
                if (type === 'volunteer_organizations') {
                    res.data.slice(0,10).map(data => {
                        dataItems += `
                        <div class="wrap mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-lg-2 img-container">
                                            <img src="`+ data.logo +`" alt="">
                                        </div>
                                        <div class="col-lg-10 d-flex flex-column">
                                            <h4 class="card-title">`+ data.name +`</h4>
                                            <p class="card-text">`+ truncateString(data.about, 150) +`</p>
                                            <div class="row list-tag align-items-end">
                                                <div class="col-md-6">
                                                    <p class="text-muted flex-fill">Date:
                                                    <span>`+ new Date(data.created).toDateString() +`</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <div class="location">
                                        <p><img src="https://www.countryflags.io/`+ data.country.iso +`/flat/64.png" alt="">`+ (data.city != null && data.city.name+`, `)  + data.country.nicename +`</p>
                                    </div>
                                    <div class="sector d-flex align-items-center">
                                    </div>
                                </div>
                            </div>
                        </div>
                        `
                    });
                    $("#dataContainer").html(dataItems);
                } else {
                    res.data.slice(0,10).map(data => {
                        dataItems += `
                        <div class="wrap mb-4">
                            <div class="card">
                                <div class="row no-gutters d-flex align-items-stretch">
                                    <div class="col-lg-3 card-img" style="background-image: url(`+ data.image +`)"></div>
                                    <div class="col-lg-9">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-9 d-flex flex-column">
                                                    <h4 class="card-title">`+ data.title +`</h4>
                                                    <p class="card-text">`+ truncateString(data.description, 150) +`</p>
                                                    <div class="row list-tag align-items-end">
                                                        <div class="col-md-6">
                                                            <p class="text-muted flex-fill">Date:
                                                            <span>`+ new Date(data.created).toDateString() +`</span></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="text-muted flex-fill">Organizer:
                                                            <span>`+ data.organization.name +`(`+(data.organization.is_verified && ' <small>- verified</small>') +`) </span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 d-flex align-items-center justify-content-end">
                                                    <a href="<?= $this->Url->build(['controller' => 'Events', 'action' => 'view', '']) ?>`+ data.id +`" class="btn btn-small">View</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <div class="location">
                                        <p><img src="https://www.countryflags.io/`+ data.country.iso +`/flat/64.png" alt="">`+ (data.city != null && data.city.name+`, `) + data.country.nicename +`</p>
                                    </div>
                                    <div class="sector d-flex align-items-center">
                                    </div>
                                </div>
                            </div>
                        </div>
                        `
                    });
                    $("#dataContainer").html(dataItems);
                }
            } else {
                $("#countriesDiv").show();
                $("#dataDiv").hide();
                var topList = '';
                var buttomList = '';
                res.top10countries.map(country => {
                    topList += `
                        <li class="list-group-item d-flex justify-content-between">
                            <p><a href="<?= $this->Url->build(['action' => 'countryPage']) ?>/${country.iso}">`+country.nicename+`</a></p>
                            <span>(`+country.data_count+`)</span>
                        </li>`;
                })
                res.buttom10countries.map(country => {
                    buttomList += `
                        <li class="list-group-item d-flex justify-content-between">
                            <p><a href="<?= $this->Url->build(['action' => 'countryPage']) ?>/${country.iso}">`+country.nicename+`</a></p>
                            <span>(`+country.data_count+`)</span>
                        </li>`;
                })
                $("#topList").html(topList);
                $("#buttomList").html(buttomList);
            }
        }
        
    });
</script>
<?php $this->end(); ?>
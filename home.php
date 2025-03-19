<?php include 'db_connect.php' ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        var data = google.visualization.arrayToDataTable([
            ['Category', 'Sold Per Day'],
            ['Bordeaux', 6],
            ['White Wein', 5],
            ['French', 2],
            ['Ticino', 3],
            ['Others', 7]
        ]);

        var options = {
            title: 'Recent Sale'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }
</script>
<style>
    span.float-right.summary_icon {
        font-size: 3rem;
        position: absolute;
        right: 1rem;
        top: 0;
    }

    .imgs {
        margin: .5em;
        max-width: calc(100%);
        max-height: calc(100%);
    }

    .imgs img {
        max-width: calc(100%);
        max-height: calc(100%);
        cursor: pointer;
    }

    #imagesCarousel,
    #imagesCarousel .carousel-inner,
    #imagesCarousel .carousel-item {
        height: 60vh !important;
        background: black;
    }

    #imagesCarousel .carousel-item.active {
        display: flex !important;
    }

    #imagesCarousel .carousel-item-next {
        display: flex !important;
    }

    #imagesCarousel .carousel-item img {
        margin: auto;
    }

    #imagesCarousel img {
        width: auto !important;
        height: auto !important;
        max-height: calc(100%) !important;
        max-width: calc(100%) !important;
    }
</style>

<div class="containe-fluid">
    <div class="row mt-3 ml-3 mr-3 dashcard">
        <!-- <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <?php echo "Welcome back" . $_SESSION['login_name'] . "!"  ?>
                    <hr>
                </div>
            </div>      			
        </div> -->
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card bg-white border-0 circle-primary theme-circle">
                        <div class="card-body">
                            <h4 class="text-dark ">Category</h4>
                            <div class="mt-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-dark mr-3">
                                        <h3 class="">30</h3>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card bg-white border-0 circle-secondary theme-circle">
                        <div class="card-body">
                            <h4 class="text-dark ">Orders</h4>
                            <div class="mt-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-dark mr-3">
                                        <h3 class="">20</h3>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card bg-white border-0 circle-success theme-circle">
                        <div class="card-body">
                            <h4 class="text-dark ">Product</h4>
                            <div class="mt-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-dark mr-3">
                                        <h3 class="">120</h3>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card bg-white border-0 circle-info theme-circle">
                        <div class="card-body">
                            <h4 class="text-dark ">User</h4>
                            <div class="mt-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-dark mr-3">
                                        <h3 class="">4</h3>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card border-0">
                <div class="card-body">
                    <div id="piechart" style="width: 100%;height:350px;"></div>
                </div>
            </div>
        </div>
        <!-- Table Panel -->

        <!-- Table Panel -->
    </div>
</div>


<script>
    $('#manage-records').submit(function(e) {
        e.preventDefault()
        start_load()
        $.ajax({
            url: 'ajax.php?action=save_track',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                resp = JSON.parse(resp)
                if (resp.status == 1) {
                    alert_toast("Data successfully saved", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 800)

                }

            }
        })
    })
    $('#tracking_id').on('keypress', function(e) {
        if (e.which == 13) {
            get_person()
        }
    })
    $('#check').on('click', function(e) {
        get_person()
    })

    function get_person() {
        start_load()
        $.ajax({
            url: 'ajax.php?action=get_pdetails',
            method: "POST",
            data: {
                tracking_id: $('#tracking_id').val()
            },
            success: function(resp) {
                if (resp) {
                    resp = JSON.parse(resp)
                    if (resp.status == 1) {
                        $('#name').html(resp.name)
                        $('#address').html(resp.address)
                        $('[name="person_id"]').val(resp.id)
                        $('#details').show()
                        end_load()

                    } else if (resp.status == 2) {
                        alert_toast("Unknow tracking id.", 'danger');
                        end_load();
                    }
                }
            }
        })
    }
</script>
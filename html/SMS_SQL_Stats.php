<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SMS from SQL - Summary</title>

    <!-- Chrome/android APP settings -->
    <meta name="theme-color" content="#4287f5">
    <link rel="icon" href="img/icon.png" sizes="192x192">
    <!-- end of Chrome/Android App Settings  -->

    <!-- Bootstrap // you can use hosted CDN here-->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
    <link href="css/dataTables.bootstrap5.css" rel="stylesheet">
    <!-- end of bootstrap -->

</head>

<body class="bg-primary">
    <!-- Page Start -->
    <div class="pt-3 container bg-white rounded">

        <!-- NAV START -->
        <nav class="navbar navbar-dark bg-dark rounded">
            <a class="navbar-brand" href="index.php">
                <img src="img/single.jpg" width="30" height="30" class="d-inline-block align-top bg-white rounded" alt="">
                SMS from SQL - Summary
            </a>
        </nav>
        <!-- NAV END -->

        <!-- Banner -->
        <section class="d-none d-md-block">
            <div class="row bg-white">
                <div class="col-12 bg-white text-center">
                    <div class="bg-dark p-1 my-1 rounded inline" style="margin: auto;">
                        <div class="row justify-content-center" id="mini-gallery-row">
                            <div class="hide-custom3"> <img class="img-fluid" src="img/Header2-880x480.jpg" alt="Header2" style="max-height:150px;" />
                            </div>
                            <div class="hide-custom2"> <img class="img-fluid" src="img/Header-1680x600.jpg" alt="Header1" style="max-height:150px;" />
                            </div>
                            <div class="hide-custom"> <img class="img-fluid" src="img/Header2-880x480.jpg" alt="Header2" style="max-height:150px;" />
                            </div>
                            <div class="d-none d-md-block"> <img class="img-fluid" src="img/Header-1680x600.jpg" alt="Header1" style="max-height:150px;" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content Start-->
        <?php
        include_once('config.php');
        ?>
        <!-- Form Summary -->
        <div class="card my-3">
            <div class="card-header bg-dark text-white">
                Last Month SMS from SQL Report:
            </div>
            <div class="card-body bg-light">
                <!-- Filters -->
                <!-- <div>
                    <b>Toggle column:</b>
                    <a class="toggle-vis" data-column="1">Start</a> |
                    <a class="toggle-vis" data-column="2">End</a> |
                    <a class="toggle-vis" data-column="3">Duration</a> |
                    <a class="toggle-vis" data-column="3">Type</a> |
                    <a class="toggle-vis" data-column="4">Equipment</a> |
                    <a class="toggle-vis" data-column="5">Desc</a>
                    <a class="toggle-vis" data-column="6">Work</a>
                </div> -->
                <!-- Table Start -->
                <div id="Loader">
                    <img src="img/Preloader_11.gif" alt="Loading" width="90px"><br>
                    Please be patient while
                    your data is being prepared.
                </div>
                <!-- Table End -->
            </div>
        </div>
        <a class="btn btn-outline-primary btn-lg form-control" href="<?php
                                                                        if (isset($_GET['fDate'])) {
                                                                            $sdate = $_GET['fDate'];
                                                                            $sdate = explode("-", $sdate);
                                                                            $time = strtotime($sdate[0] . '/' . ($sdate[1] - 1) . '/01 00:00:00');
                                                                            $fDate = date('Y-m', $time);

                                                                            $Nr = '';
                                                                            if (isset($_GET['Nr'])) {
                                                                                $Nr = '&Nr=' . $_GET['Nr'];
                                                                            }
                                                                        } else {


                                                                            $date = date_create()->modify('-60 days');
                                                                            $fDate = date_format($date, 'Y-m');
                                                                        }
                                                                        echo $url . 'SMS_SQL_Stats.php?fDate=' . $fDate;
                                                                        ?>">
            View Previous Month</a>
        <a class="btn btn-outline-primary btn-lg form-control" href="index.php">Home</a>
        <!-- Form Summary -->
        <br><br>
        <!-- Main Content Start-->

    </div>
    <!-- Page End -->

    <!-- Start of Bootstrap JS -->
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/dataTables.js"></script>
    <script src="js/dataTables.bootstrap5.js"></script>
    <!-- end of Bootstrap JS -->
    <!-- Page Level Scripts -->
    <script src="js/table2CSV.js"></script>
    <script src="js/browserCheck.js"></script>
    <script src="js/loader_sms_sql.js"></script>


</body>

</html>
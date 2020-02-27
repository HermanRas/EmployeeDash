<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mining Delays</title>

    <!-- Chrome/android APP settings -->
    <meta name="theme-color" content="#4287f5">
    <link rel="icon" href="img/icon.png" sizes="192x192">
    <!-- end of Chrome/Android App Settings  -->

    <!-- Bootstrap // you can use hosted CDN here-->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
    <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- end of bootstrap -->

</head>

<body class="bg-primary">
    <!-- Page Start -->
    <div class="pt-3 container bg-white rounded">

        <!-- NAV START -->
        <nav class="navbar navbar-dark bg-dark rounded">
            <a class="navbar-brand" href="index.php">
                <img src="img/icon.png" width="30" height="30" class="d-inline-block align-top bg-white rounded" alt="">
                Employee Status Summary
            </a>
        </nav>
        <!-- NAV END -->

        <!-- Banner -->
        <section>
            <div class="row bg-white">
                <div class="col-12 bg-white text-center">
                    <div class="bg-dark p-1 my-1 rounded" style="margin: auto;">
                        <img src="img/Header-1680x600.jpg" class="img-fluid rounded" style="max-height: 250px;"
                            alt="Header">
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
                Your status and Employees reporting to you on VIP:
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
        <a class="btn btn-outline-primary btn-lg form-control" href="printing.php">Printing Bills</a>
        <a class="btn btn-outline-primary btn-lg form-control" href="telephone.php">Telephone Bills</a>
        <!-- Form Summary -->
        <br><br>
        <!-- Main Content Start-->

    </div>
    <!-- Page End -->

    <!-- Start of Bootstrap JS -->
    <script src="js/jquery-3.3.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>
    <!-- end of Bootstrap JS -->
    <!-- Page Level Scripts -->
    <script src="js/browserCheck.js"></script>
    <script src="js/loader_emp.js"></script>


</body>

</html>
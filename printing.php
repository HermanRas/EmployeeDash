<?php
include_once('config.php');
?>

<div id="Loader"><img src="img/Preloader_11.gif" alt="Loading" width="90px"><br>
    Please be patient while
    your data is being prepared. </div>

<a href="<?php
            $date = date_create()->modify('-60 days');
            $fDate = date_format($date, 'Y/m');

            echo $url.'printing.php?manager='.$manager.'&fDate='.$fDate;
                ?>">
    View Previous</a>
<a href="./">HOME</a>

<script src="js/loader_prn.js"></script>
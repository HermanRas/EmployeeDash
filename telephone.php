<?php
include_once('config.php');
?>
<div id="Loader"><img src="img/Preloader_11.gif" alt="Loading" width="90px"><br>
    Please be patient while
    your data is being prepared. </div>

<a href="<?php

        if(isset($_GET['fDate'])){
        $sdate = $_GET['fDate']; 
        $sdate= explode("-",$sdate);
        $time = strtotime($sdate[0].'/'.($sdate[1]-1).'/01 00:00:00');
        $fDate = date('Y-m',$time);
        
        $Nr = '';
        if(isset($_GET['Nr'])){
            $Nr = '&Nr='.$_GET['Nr'];      
        }

        } else{

        
        $date = date_create()->modify('-60 days');
        $fDate = date_format($date, 'Y-m');
        }
        echo $url.'telephone.php?fDate='.$fDate;
            ?>">
    View Previous</a>
<a href="./">HOME</a>

<script src="js/loader_tel.js"></script>
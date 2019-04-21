<?php
   session_start();

   $ident = $_POST['ident'];
   $vid = $_POST['vid'];
   $vname = $_POST['vname'];
   $accessToken = $_POST['accessToken'];

   if($ident && $vid && $vname && $accessToken){
    $_SESSION['ident'] = $ident;
    $_SESSION['vid'] = $vid;
    $_SESSION['vname'] = $vname;
    $_SESSION['accessToken'] = $accessToken;

   }else{
   	echo "erro";
   }

?>
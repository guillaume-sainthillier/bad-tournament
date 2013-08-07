<?php

session_start();
include("connection.php");

mysql_query("DELETE from tournoi where idtournoi='".$_SESSION['idtournoi']."'");

header('Location:index.php');
?>
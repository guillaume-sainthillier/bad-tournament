<?php
session_start();
		if(isset($_POST['tournoi']))
			$_SESSION['idtournoi']=$_POST['tournoi'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>BadTournament - IUT Blagnac 2011</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script>
function confirmation()
{
	if(confirm('Voulez-vous supprimer ce tournoi ?'))
	{
		window.location.href="supprimer.php";
	}
}

</script>
</head>
<body>

	<?php 
	include("connection.php"); 
	include("banniere.php"); 
	include("menu.php"); 
	?>

	<div id="contenu">
	
	<?php
			
			//on récupère l'id du tournoi
			echo "tournoi :".$_SESSION['idtournoi']."<br />";
			if(!isset($_SESSION['idtournoi']))
			{
				if(!isset($_COOKIE['idtournoi']))
					echo 'Veuillez recharger le tournoi <br />';
				else
					$tournoi = $_COOKIE['idtournoi'];			
			}else				
				$tournoi = $_SESSION['idtournoi'];
			
			
			
			$query=mysql_query("select nomtournoi from tournoi where idtournoi='".$tournoi."'") OR DIE (mysql_error());
			$result = mysql_fetch_array($query);
			$nomtournoi = $result['nomtournoi'];
			
			setcookie("idtournoi",$tournoi,time()+3600*24*7);
	?>
			
	<h1>Bienvenue sur le tournoi <?php echo $nomtournoi;?></h1><br/>
	
	<p>Gérez comme vous le sentez ce petit tournoi ;p<br/><br/><br/><br/><br/><br/><br/><br/><br/></p>
	</div>

	<?php include("pied_de_page.php"); ?></body>

</html>

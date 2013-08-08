<?php
SESSION_START();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BadTournament - IUT Blagnac 2011</title>
<link href="style.css" rel="stylesheet" type="text/css" />

</head>
<body>

	<?php 
	include("connection.php"); 
	include("banniere.php"); 
	include("menu.php");
	
	echo '<div id="contenu">';
	
	
		$nbEq = 5;
		$nbTerrain = 3;
		for($i = 0;$i< 5;$i++)
		{
			echo 'INSERT INTO tournoi(idtournoi,nomtournoi,nbterrain,nbpoule) VALUES ('.(4+$i).',\'Tournoi '.(4+$i).'\','.$nbTerrain.','.(2+$i).')<br /> <br /> <br />';
			
			for($j = 0;$j < $nbEq;$j++)
			{
				echo 'INSERT INTO equipe(nomequipe,idtournoi) VALUES (\'Equipe '.($j+1).'\','.(4+$i).') <br />';
			}
			
			echo '<br />';
			$nbEq = $nbEq +3;
		}
	
	
	
	echo '</div>';
	include("pied_de_page.php"); ?>
		
</body>

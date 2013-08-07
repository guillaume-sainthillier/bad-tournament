<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>BadTournament - IUT Blagnac 2011</title>
<link href="style.css" rel="stylesheet" type="text/css" />

</head>
<body>

	<?php include("connection.php"); ?>
	
	<?php include("banniere.php"); ?>

	<div id="contenu">
		<div id="ind">
		<br/><br/>
		<a href='creerTournoi.php' >Créer un nouveau tournoi</a>
		<p>OU</P>
		<h3>Charger un tournoi :</h3>
		<form method='post' action='accueil.php'>
			<select name='tournoi' size='10'>
				<?php
				
				$requete=mysql_query("SELECT * FROM tournoi");
				$cpt=0;
				if(mysql_num_rows($requete)!=0){
					while($table=mysql_fetch_array($requete)){
						if($cpt==0){
							echo "<option value='".$table['idtournoi']."' selected>".$table['nomtournoi']."</option>";
						}else{
							echo "<option value='".$table['idtournoi']."'>".$table['nomtournoi']."</option>";
						}
						$cpt=$cpt+1;
					}
					echo "</select><br/>";
					echo "<input type='submit' value='Charger'/>";
				}else{
					echo "<option>Aucun tournoi n'a été créé</option></select><br/>";
					echo "<input type='submit' value='Charger' disabled/>";
				}
				?>
			
		</form>
		</div>
	</div><br/><br/>

	<?php include("pied_de_page.php"); ?>
	</body>
</html>

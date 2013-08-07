<?php
SESSION_START();
unset($_SESSION['deja']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BadTournament - IUT Blagnac 2011</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link rel="icon" href="favicon.ico"/>

<script type="text/javascript">
		function confirmation() 
		{
		var msg = "Êtes-vous sur de vouloir supprimer cette équipe ?";
		return (confirm(msg));
		}		
		</script>

</head>
<body>

	<?php include("banniere.php"); ?>

	<?php include("menu.php");
			include("connection.php");
	?>

	<div id="contenu">
		<center><h2>Liste des equipes</h2></center>
			<?php		

			if(isset($_GET['ideq']))
			{
			$query=mysql_query("DELETE FROM participant WHERE idequipe ='".$_GET['ideq']."'") OR DIE (mysql_error());
			$query2=mysql_query("DELETE FROM equipe WHERE idequipe ='".$_GET['ideq']."'") OR DIE (mysql_error());
					
			echo "<b><center><FONT color='red'>Suppression effectué !!</FONT></center></b>";
			echo "<br/>";
			}
			
				$result=mysql_query("select idequipe,nomequipe,idpoule ,nbvictoire,nbdefaite from equipe  where idtournoi='".$_SESSION['idtournoi']."' ");
				
			if (mysql_errno()==0)
			{ 
				if (mysql_num_rows($result)!=0)
				{
					echo "Nombre d'équipes : ".mysql_num_rows($result);
					?>
					
					<br>
					<br>
					<table id="liste_equipe">
					<tr>
					   <th>Nom equipe</th>
					   <th>Poule</th>
					   <th>Nombre de victoire</th>
					   <th>Nombre de defaite</th>
					   <th>Detail</th>
					   <th>Supprimer</th>
					</tr>
					<?php
					
					while($row = mysql_fetch_array($result)) 
					{
						echo "<tr><td>";
						echo $row['nomequipe'];
						echo "</td><td>";
						echo $row['idpoule'];
						echo "</td><td>";
						echo "".$row['nbvictoire'];
						echo "</td><td>";
						echo $row['nbdefaite'];
						echo "</td><td>";
						echo "<a href='detailequipe.php?id=".$row['idequipe']."' title='Details'><input type='button'  value='Details'/></a>";
						echo "</td><td>";
						
						echo "<form action='listeequipe.php?id=".$_SESSION['idtournoi']."' id='formusuppr' method='post' onSubmit='return estFormulaireValide(this)'>";
						echo "<a href='listeequipe.php?id=".$_SESSION['idtournoi']."&ideq=".$row['idequipe']."' onclick=return(confirmation());><IMG SRC='images/supprimer.png' ALT='supprimer' ></a>";
						echo "</form>";
						echo "</td></tr>";
					}
					echo "</table>";
				}
				else
				{
					echo "Aucun equipe inscrite.";
				}
			}
			
		?>
		
	<br/><br/>
	</div>

	<?php include("pied_de_page.php"); ?></body>

</html>






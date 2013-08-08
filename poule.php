<?php
SESSION_START();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BadTournament - IUT Blagnac 2011</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link rel="icon" href="favicon.ico"/>


<body>

	<?php include("banniere.php"); 
			include("connection.php");?>
			
	<?php include("menu.php"); ?>

	<div id="contenu">
			
   
		<center><h2>Poule </h2></center>
		
		<?php
		
		$result=mysql_query("select * from poule where idtournoi='".$_SESSION['idtournoi']."'");  
		?>
				
		<form action="poule.php" method="post" >
		<center><select name="poule">
		<option value="choixpoule">Choisir une poule...</option> 
		<?php 
			while($row=mysql_fetch_array($result))
			{ 
				if ($_POST['poule'] == $row['idpoule'] )
				{
					echo "<option value='".$row['idpoule']."' selected='selected'>".$row['nompoule']."</option>";
				}
				else
				{
					echo "<option value='".$row['idpoule']."'>".$row['nompoule']."</option>";
				}
			}?>
			</select></center><br/>
			<center><input type ="submit" name="afficher" value="Afficher"/></center>
		<br/><br/><br/>
		</form>	
		
		
		
	<?php		
	if (isset($_POST['poule']) && $_POST['poule'] != 'choixpoule')
	{
			$result=mysql_query("select idequipe,nomequipe,nbvictoire,nbdefaite from equipe where idpoule='".$_POST['poule']."' order by nbvictoire desc");
				
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
					   <th>Classement</th>
					   <th>Points</th>
					   <th>Nom equipe</th>
					   <th>Nombre de victoire</th>
					   <th>Nombre de defaite</th>
					   <th>Nombre de match joues</th>
					   <th>Details</th>
					</tr>
					
					
					<?php
					$i=1;
					
					while($row = mysql_fetch_array($result)) 
					{
						echo "<tr><td>";
						echo $i;
						echo "</td><td>";
						echo 3*($row['nbvictoire']);
						echo "</td><td>";
						echo $row['nomequipe'];
						echo "</td><td>";
						echo $row['nbvictoire'];
						echo "</td><td>";
						echo $row['nbdefaite'];
						echo "</td><td>";
						echo ($row['nbdefaite']+$row['nbvictoire']);
						echo "</td><td>";
						echo "<a href='detailequipe.php?id=".$row['idequipe']."' title='details'><input type='button'  value='Details'/></a>";
						echo "</td></tr>";
						
						$i++;
					}
					echo "</table>";
				}
				else
				{
					echo "Aucune équipe dans la poule.";
				}
			}
			
		?>
		<br/><br/>
		<?php
	
	    $result31=mysql_query("select m.idequipe1,m.score1,m.idequipe2,m.score2 from matchs m,equipe e  where e.idequipe=m.idequipe1 and e.idpoule='".$_POST['poule']."' ") OR DIE (mysql_error());
		echo "<center>";
		echo "Nombre de matchs : ".mysql_num_rows($result31);
		echo "</center>";
		
		echo"<br/>";
		echo "<table id='liste_equipe'>";
					
					   if(mysql_num_rows($result31)!=0){?>
					   <tr>
						   <th>Nom equipe 1</th>
						   <th>Score equipe 1</th>
						   <th>Nom equipe 2</th>
						   <th>Score equipe 2</th>
				 
				        </tr>
						<?php
						}
						while($row31 = mysql_fetch_array($result31)) 
						{
							echo "<tr>";
							
							//nom equipe1
							$result32=mysql_query("select nomequipe from equipe  where idequipe='".$row31['idequipe1']."' ") OR DIE (mysql_error());
							$row32 = mysql_fetch_array($result32);
							echo "<td>";
							echo $row32['nomequipe'];
							echo "</td>";
							
							echo "<td>";
							echo $row31['score1'];
							echo "</td>";
							
							//nomequipe2
							$result33=mysql_query("select nomequipe from equipe  where idequipe='".$row31['idequipe2']."' ") OR DIE (mysql_error());
							$row33 = mysql_fetch_array($result33);
							echo "<td>";
							echo $row33['nomequipe'];
							echo "</td>";
							
							echo "<td>";
							echo $row31['score2'];
							echo "</td>";
							echo "</tr>";
						}
						echo "</table>";
	}
	else
	{
		echo "<center>Veuillez choisir une poule</center>";
	}
	?>
	</div>

	<?php echo"<br/>";
	echo"<br/>";
	include("pied_de_page.php"); ?></body>

</html>






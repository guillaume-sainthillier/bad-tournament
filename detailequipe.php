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
</head>
<body>
<?php include("banniere.php"); ?>

	<?php include("menu.php"); ?>
<?php include("connection.php"); ?>
	<div id="contenu">
	
	<!-- on récupère l'id du tournoi et sa catégorie -->
		<?php			

			//on récupère l'id du tournoi qui est dans notre variable de session
			$tournoi = $_SESSION['idtournoi'];
			//on récupère la catégorie du tournoi
			$query=mysql_query("select idcat from tournoi where idtournoi='".$tournoi."'") OR DIE (mysql_error());
			$result = mysql_fetch_array($query);
			$cat = $result['idcat'];
			
			$result1=mysql_query("select e.nomequipe,p.nomparticipant,p.prenomparticipant,e.nbvictoire,e.nbdefaite from equipe e,participant p where e.idequipe='".$_GET['id']."' AND p.idequipe=e.idequipe");
			$result2=mysql_query("select idequipe,nomequipe from equipe where idequipe='".$_GET['id']."'");
			$result4=mysql_query("select idequipe,nomequipe from equipe where idequipe='".$_GET['id']."'");
			$result5=mysql_query("select idequipe,nomequipe from equipe where idequipe='".$_GET['id']."'");
			
			$row2 = mysql_fetch_array($result2);
			$row4 = mysql_fetch_array($result4);
			$ideq = $row4['idequipe'];
		?>
		<br/>
		<center>
		<h2>Details d'une equipe</h2>
		</center>
		
		<fieldset id="field_inscr_equipe">
		<?php 	
		echo "<h3><center>".$row2['nomequipe']."</center></h3>";
		
			

		if(mysql_num_rows($result1)==1)
		{
			if($cat=='cat2')
			{
				$row1 = mysql_fetch_array($result1);
				echo "<table id='liste_equipe'>";
				echo "<tr>";
				echo "<td>";
				echo "<img src='images/femme.png' alt='femme'/>";
				echo "</td>";
				echo "<td>";
				echo $row1['nomparticipant'];
				echo "</td>";
				echo "<td>";
				echo $row1['prenomparticipant'];
				echo "</td>";
				echo "</tr>";
				echo "</table>";
				echo "<br/>";
			}
			else
			{
				$row1 = mysql_fetch_array($result1);
				echo "<table id='liste_equipe'>";
				echo "<tr>";
				echo "<td>";
				echo "<img src='images/homme.png' alt='homme'/>";
				echo "</td>";
				echo "<td>";
				echo $row1['nomparticipant'];
				echo "</td>";
				echo "<td>";
				echo $row1['prenomparticipant'];
				echo "</td>";
				echo "</tr>";
				echo "</table>";
				echo "<br/>";
			}
			echo"<br/>";
		}
		else
		{
			if($cat=='cat5')
			{
				$i=0;
				echo "<table id='liste_equipe'>";
				echo "<tr>";
				echo "<td>";
				echo "<img src='images/femme.png' alt='femme'/>";
				echo "</td>";
				while($row1 = mysql_fetch_array($result1)) 
				{
					
					echo "<td>";
					echo $row1['nomparticipant'];
					echo "</td>";
					echo "<td>";
					echo $row1['prenomparticipant'];
					echo "</td>";
					echo "<br/>";
					
					echo "<tr/>";
					if($i==0)
					{
						echo "<tr>";
						echo "<td>";
						echo "<img src='images/homme.png' alt='homme'/> ";
						echo "</td>";
						$i++;
					}
				}
				echo "</br>";
				echo "</table>";
			}
			else
			{
				if($cat=='cat4')
				{
					$i=0;
					echo "<table id='liste_equipe'>";
					echo "<tr>";
					echo "<td>";
					echo "<img src='images/femme.png' alt='femme'/>";
					echo "</td>";
					while($row1 = mysql_fetch_array($result1)) 
					{
						
						echo "<td>";
						echo $row1['nomparticipant'];
						echo "</td>";
						echo "<td>";
						echo $row1['prenomparticipant'];
						echo "</td>";
						echo "<br/>";
						
						echo "<tr/>";
						if($i==0)
						{
							echo "<tr>";
							echo "<td>";
							echo "<img src='images/femme.png' alt='femme'/> ";
							echo "</td>";
							$i++;
						}
					}
					echo "</br>";
					echo "</table>";
				}
				else
				{
					$i=0;
				echo "<table id='liste_equipe'>";
				echo "<tr>";
				echo "<td>";
				echo "<img src='images/homme.png' alt='homme'/>";
				echo "</td>";
				while($row1 = mysql_fetch_array($result1)) 
				{
					
					echo "<td>";
					echo $row1['nomparticipant'];
					echo "</td>";
					echo "<td>";
					echo $row1['prenomparticipant'];
					echo "</td>";
					echo "<br/>";
					
					echo "<tr/>";
					if($i==0)
					{
						echo "<tr>";
						echo "<td>";
						echo "<img src='images/homme.png' alt='homme'/> ";
						echo "</td>";
						$i++;
					}
					}
					echo "</br>";
					echo "</table>";
				}

			}
			echo"<br/>";
		}
		
		
		?>	
			<br/>
				
			</fieldset>
			<br/>
		<!--<FORM> <INPUT TYPE="button" VALUE="Retour" onClick="history.back()"> </FORM> -->
		 <?php echo "<center><a href='modifierequipe.php?id=".$row4['idequipe']."'  title='Modifier'><input type='button'  value='Modifier'/></a></center>"; 
		echo"<br/><br/>";
		
		$result31=mysql_query("select idequipe1,score1,idequipe2,score2 from matchs  where idequipe1=".$ideq." OR  idequipe2=".$ideq."") OR DIE (mysql_error());
		echo "<center>";
		echo "Nombre de matchs : ".mysql_num_rows($result31);
		echo "</center>";
		
		echo"<br/><br/>";
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
	echo"<br/><br/>";
	?>
	</div>

	<?php include("pied_de_page.php"); ?></body>

</html>

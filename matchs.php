<?php
	session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Creer un tournoi</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="design" href="style.css" />
	   
	   <?php
			include("connection.php");
		?>
		
		<script langage="javascript">
		
   
				function estFormulaireValide(nbMatchs)
				{
					
					
					
					var i = 0;
					var retour=true;
					while(i < nbMatchs && retour)
					{
						var retour=false;
						var doc1 = document.getElementById('sc1'+i);
						var doc2 = document.getElementById('sc2'+i);
						if(doc1.value=="")
							alert("La valeur de la première equipe du matchs saisi n°"+(i+1)+" n'est pas remplie !");
						else
							if(isNaN(doc1.value))
								alert("La valeur de la première equipe du matchs saisi n°"+(i+1)+" n'est pas un nombre !");
							else
								if(doc2.value=="")
									alert("La valeur de la seconde equipe du matchs saisi n°"+(i+1)+" n'est pas remplie !");
								else
									if(isNaN(doc2.value))
										alert("La valeur de la seconde equipe du matchs saisi n°"+(i+1)+" n'est pas un nombre !");
									else
									retour = true;
							
						i++;
					}					
					return retour;
				}
		</script>
	 </head>
   <body>
   	
   		<?php include("banniere.php"); 
			  include("menu.php"); ?>

	<div id="contenu">
	<?php
		
	//Si le nombre de matchs n'est pas établit , on l'initialise à 0
	if(!isset($_SESSION['nbMatchs']))
		$_SESSION['nbMatchs'] = 0;	
	
	
	if(!isset($_SESSION['lastAction'])) //lastAction represente l'avancée dans la page (affichage des matchs -> saisie score -> validation)
		$_SESSION['lastAction'] = 0;
	
	if(!isset($_SESSION['idtournoi']))
		$_SESSION['idtournoi'] = 1;
		
	$idtournoi = $_SESSION['idtournoi'];
	
	
	
	//matchs.php?fini=1/2/3
	if(isset($_GET['fini']))
	{


		//Si on  a envoyé la demande de saisie des scores (3ème étape)
		if(isset($_POST['valider']))
		{
			if(!($_SESSION['lastAction'] == 2))// Si jamais l'utilisateur a voulu faire précédent avec le navigateur
				echo "L'action précedent est impossible pour ce traitement , veuillez rafraichir la page.";	
			else
			{
				$_SESSION['lastAction'] = 3;
				
				for($i=0;$i<$_SESSION['nbMatchs'];$i++)
				{
							
					//On indique que le match courant est terminé
					mysql_query("UPDATE matchs SET estfini = 1 ,estEnCours = 0 WHERE idtournoi = '".$idtournoi."' AND idmatch = '".$_SESSION['tabMatchs'][$i]."' ;")
						OR DIE(mysql_error());	
					
					//On indique le résultat du match dans la table matchs et equipe (nbVictoires/defaites)
					
					 $label1 = "p".$i; //Name du label de saisie des scores de l'équipe 1
					 $label2 = "q".$i; //Name du label de saisie des scores de l'équipe 2
					 
					 $score1 = $_POST[$label1]; //Score équipe1
					 $score2 = $_POST[$label2]; //Score équipe2
					 
					 //On met le résultat dans la table matchs
					 mysql_query("UPDATE matchs SET score1 =".$score1.", score2 =".$score2." WHERE idmatch = '".$_SESSION['tabMatchs'][$i]."';");
					
					 
					 
					 if($score1 > $score2)
					 {
						mysql_query("UPDATE equipe SET nbvictoire = nbvictoire +1 WHERE idequipe = '".$_SESSION['tabIdEquipes'][$i][0]."';");
						mysql_query("UPDATE equipe SET nbdefaite  = nbdefaite  +1 WHERE idequipe = '".$_SESSION['tabIdEquipes'][$i][1]."';");
					 }
					 if($score1 < $score2)
					 {
						mysql_query("UPDATE equipe SET nbvictoire = nbvictoire +1 WHERE idequipe = '".$_SESSION['tabIdEquipes'][$i][1]."';");
						mysql_query("UPDATE equipe SET nbdefaite  = nbdefaite  +1 WHERE idequipe = '".$_SESSION['tabIdEquipes'][$i][0]."';");
					 }
					 if($score1 == $score2) //Si matchs nul pas de défaite et une victoire en plus
					 {
						mysql_query("UPDATE equipe SET nbvictoire = nbvictoire +1 WHERE idequipe = '".$_SESSION['tabIdEquipes'][$i][0]."'
																			OR 	 idequipe = '".$_SESSION['tabIdEquipes'][$i][1]."' ;");
					
					 }
					 
					 //On récupère la ligne dans déroulementmatch associée au match courant
					 $resultat = mysql_query("select * from deroulementmatch where idtournoi = '".$idtournoi."' AND idmatch = '".$_SESSION['tabMatchs'][$i]."';") ;				 
					 
					 $ligne = mysql_fetch_array($resultat);
					 
					//Ensuite , on descend dans la table deroulementmatch l'ordre des matchs sur le terrain récuperé 
					$idTerrain = $ligne['idterrain'];	

					//ON update l'idterrainjoue dans le match en cours
					mysql_query("UPDATE matchs SET idterrainjoue = ".$idTerrain." WHERE idtournoi = '".$idtournoi."' AND idmatch = ".$_SESSION['tabMatchs'][$i].";");
					
					
					//On baisse l'ordre d'un rang dans le terrain dans lequel le match précedent est terminé
					mysql_query("UPDATE deroulementmatch SET ordre = ordre -1 WHERE idtournoi = '".$idtournoi."' AND idterrain ='".$idTerrain."';") OR DIE(mysql_error());				
					
					//Puis on supprime dans deroulementmatch la ligne du match courant
					mysql_query("DELETE from deroulementmatch WHERE idtournoi = '".$idtournoi."' AND idmatch = '".$_SESSION['tabMatchs'][$i]."';") OR DIE(mysql_error());
					  
					//On selectionne l'id du match suivant dans l'ordre
					$ligne = mysql_fetch_array(mysql_query("select * from deroulementmatch WHERE idtournoi = '".$idtournoi."' AND idterrain = '".$idTerrain."' AND ordre = 0 ;"));
					$idNewMatch = $ligne['idmatch'];
					
					//Pour enfin mettre son statut estEnCours à VRAI , et l'id du terrain dans lequel il a joué
					mysql_query("UPDATE matchs SET estfini = 0 ,estEnCours = 1 WHERE idtournoi = '".$idtournoi."' AND idmatch = '".$idNewMatch."';");
					
					
					
					
				}
				
				$requeteVide = mysql_query("select * from deroulementmatch WHERE idtournoi = '".$idtournoi."' ;");
				if( !($ligneVide = mysql_fetch_array($requeteVide)))
				{
					$requeteVide = mysql_query("select * from matchs WHERE  idtournoi = '".$idtournoi."' and idtypematch = 5");
					if( $ligne = mysql_fetch_array($requeteVide))
						header("Location: classement.php");
					else
						header("Location: generermatchs.php");
				}
				else
					header("Location: matchs.php?fini=1");
				
				
			}
		}else
		//Si on  a envoyé la demande de saisie des scores (2eme étape)
		if(isset($_POST['saisir']))
		{
			if( $_SESSION['lastAction'] == 3)
			{
				echo "Veuillez rafraichir la page.";
				echo"<form action='matchs.php?fini=".$_GET['fini']."' method='post'>";
				echo '<br/><br/><center><input type ="submit" value="Rafraichir"/><center>';
				echo'</form>';	
				
			}
			else
			{ 
				$_SESSION['lastAction'] = 2;		

				//On reinitialise les variables de session de traitement
				$_SESSION['nbMatchs'] = 0;				
				unset($_SESSION['tabMatchs']);
				unset($_SESSION['tabIdEquipes']);
				
				$i=0;
				$resultat  = mysql_query("select * from matchs where idtournoi = '".$idtournoi."' ;");
				while($ligne = mysql_fetch_array($resultat))
				{	
					$idmatch = $ligne['idmatch'];
					if(isset($_POST[$idmatch])) // On retient la/les cases cochées pour la saisir des scores
					{
						$_SESSION['tabMatchs'][$i] = $idmatch; //On l'id du match coché dans l'étape1 dans un tableau de session
						$_SESSION['tabIdEquipes'][$i][0] = $ligne['idequipe1'];//On stocke également les id des équipes
						$_SESSION['tabIdEquipes'][$i][1] = $ligne['idequipe2'];
						$_SESSION['nbMatchs']++;
						$i++;
					}
				}
				if($_SESSION['nbMatchs'] == 0)
					echo "Pas de matchs sélectionnés !";
				else
				{
					
					echo"<form action='matchs.php?fini=1' method='post' onSubmit='return estFormulaireValide(".$_SESSION['nbMatchs'].") '>";
					for($i=0;$i < $_SESSION['nbMatchs'];$i++)
					{
						$resultat2 = mysql_query("select * from equipe WHERE idtournoi = '".$idtournoi."' AND idequipe = '".$_SESSION['tabIdEquipes'][$i][0]."'");
						$resultat3 = mysql_query("select * from equipe WHERE idtournoi = '".$idtournoi."' AND idequipe = '".$_SESSION['tabIdEquipes'][$i][1]."'");
						
						$ligne2 = mysql_fetch_array($resultat2);
						$ligne3 = mysql_fetch_array($resultat3);
						
						
						$resultatDeroulementMatch = mysql_query("select * from deroulementmatch WHERE idtournoi = '".$idtournoi."' AND idmatch = '".$_SESSION['tabMatchs'][$i]."';");
						$ligneDeroulementMatch = mysql_fetch_array($resultatDeroulementMatch);
						$idTerrain = $ligneDeroulementMatch['idterrain'];
						//Puis on récupère le nom du terrain associé à son id
						$resultatTerrain = mysql_query("select * from terrains WHERE idterrain ='".$idTerrain."';");
						$ligneTerrain = mysql_fetch_array($resultatTerrain);
						
						
						echo "<label>".$ligneTerrain['nomterrain'].":<br/>Score Equipe ".$ligne2['nomequipe']."</label><br /><input type='text' id='sc1".$i."' name='p".$i."'/><br/>";
						echo "<label>Score Equipe ".$ligne3['nomequipe']."</label><br /><input type='text' id='sc2".$i."' name='q".$i."'/><br/>";
										
						echo "<br/>";
					}
					
					
					echo '<input type ="submit" value="Valider et terminer les matchs" name="valider" /><br/><br/>';
					echo'</form>';	
				}
			}
		}else//Affichage des matchs (Etape1)
		{
			$_SESSION['lastAction'] = 1;
			$_SESSION['nbMatchs'] = 0;
			unset($_SESSION['tabMatchs']);
			unset($_SESSION['tabIdEquipes']);
			
			$requete = "select * from matchs;"; //Au cas où
			
			$resultatTournoi = mysql_query("select * from tournoi WHERE idtournoi = '".$idtournoi."' ;");
			$ligneTournoi = mysql_fetch_array($resultatTournoi);
			echo '<fieldset>';
			
			//Matchs en cours
			if($_GET['fini'] == 1) 	
			{
				$requete = "select * from matchs m , deroulementmatch dm WHERE m.idtournoi = '".$idtournoi."'
							AND  m.estEnCours AND m.idmatch = dm.idmatch 
							AND dm.ordre = 0 ORDER BY dm.idterrain,dm.ordre;";	
							
				$resultat = mysql_query($requete);
				

				if(mysql_num_rows($resultat) == 0)
					echo "<br/><br/><br/><br/><center>Aucun matchs en cours trouvés pour le tournoi ".$ligneTournoi['nomtournoi']."</center>";
				else
				{
					echo'<form action="matchs.php?fini=1" method="post"><br /><br />';
					
					while($ligne = mysql_fetch_array($resultat))
					{
						$idmatch = $ligne['idmatch'];

						//On récupère le nom de l'équipe 1
						$res1 = mysql_query("select nomequipe as nomeq1 from equipe where idtournoi = '".$idtournoi."' AND idequipe = '$ligne[idequipe1]';");
						$nomEq1 = mysql_fetch_array($res1);
							
						//On récupère le nom de l'équipe 2
						$res2 = mysql_query("select nomequipe as nomeq2 from equipe where idtournoi = '".$idtournoi."' AND idequipe = '$ligne[idequipe2]';");
						$nomEq2 = mysql_fetch_array($res2);
											
						//On récupère
						$resultatDeroulementMatch = mysql_query("select * from deroulementmatch WHERE idtournoi = '".$idtournoi."' AND idmatch = '".$idmatch."';");
						$ligneDeroulementMatch = mysql_fetch_array($resultatDeroulementMatch);
						
						$idTerrain = $ligneDeroulementMatch['idterrain'];
						
						
						//Puis on récupère le nom du terrain associé à son id
						$resultatTerrain = mysql_query("select * from terrains WHERE idterrain ='".$idTerrain."';");
						$ligneTerrain = mysql_fetch_array($resultatTerrain);
						
						echo '<input type="checkbox" name ="'.$idmatch.'" id="'.$idmatch.'"/>';
						echo $ligneTerrain['nomterrain'].' : '.$nomEq1['nomeq1'].' vs '.$nomEq2['nomeq2']."<br />";
						
					}
					echo '<br/><input type ="submit" value="Saisir les scores" name="saisir" id/><br/><br/><br/>';				
					echo'</form>';	
				}
								
			}else
			{
				//Matchs à venir
				if($_GET['fini'] == 2) 		
					$requete = "select * from matchs m , deroulementmatch dm WHERE m.idtournoi = '".$idtournoi."' 
								AND !m.estEnCours AND !m.estfini AND m.idmatch = dm.idmatch 
								ORDER BY dm.idterrain,dm.ordre;";	
								
				//Matchs terminés
				if($_GET['fini'] == 3) 		
					$requete = "select * from matchs WHERE idtournoi = '".$idtournoi."'
								AND estfini AND !estEnCours ORDER BY idterrainjoue asc;";

				
				//On récupère le bon ensemble de matchs associé au  type (en cours , a venir, ..)
				$result = mysql_query($requete);
			
			
				$resultatTournoi = mysql_query("select * from tournoi WHERE idtournoi = '".$idtournoi."' ;");
				$ligneTournoi = mysql_fetch_array($resultatTournoi);
				
			
				
				if(  mysql_num_rows($result) == 0)
				{
							echo "<br/><br/><br/><br/><center>Aucun matchs ";
							if($_GET['fini'] == 2) 
								echo "à venir ";
							if($_GET['fini'] == 3) 
								echo "finis ";
							
							echo "trouvés pour le tournoi ".$ligneTournoi['nomtournoi']."</center>";
				}else
				{
					if($_GET['fini'] == 2) //Matchs à venir
						echo 'Par ordre de jeu : <br/><br/>';
						
					if($_GET['fini'] == 3) //Matchs finis
						echo 'Historique des matchs du tournoi '.$ligneTournoi['nomtournoi'].' : <br/><br/>';	
		
										
					$nbTerrains = $ligneTournoi['nbterrain'];
					
					$idLastTerrain = 1;
					for($i  = 1;$i<= $nbTerrains;$i++)
					{
						echo 'Terrain '.$i.'<br />';
						
						echo '<table id="liste_equipe">
							<tr>										 
								<th>Equipe</th>							  
								<th>vs</th>
								<th>Equipe</th>';
							
							if($_GET['fini'] == 3)
								echo'<th>score</th>';
							else
								echo' <th>ordre</th>';
								
							echo'</tr>';						
	
	
						$ligne = mysql_fetch_array($result);
						
						do
						{	
							
							$idmatch = $ligne['idmatch'];
														
							
							$res1 = mysql_query("select nomequipe as nomeq1 from equipe where idtournoi = '".$idtournoi."' AND idequipe = '$ligne[idequipe1]';");
							$nomEq1 = mysql_fetch_array($res1);
														
							$res2 = mysql_query("select nomequipe as nomeq2 from equipe where idtournoi = '".$idtournoi."' AND idequipe = '$ligne[idequipe2]';");
							$nomEq2 = mysql_fetch_array($res2);
								
						
							
							//On récupère l'id du terrain associé au match 
							if($_GET['fini'] == 2)
							{
								$resultatDeroulementMatch = mysql_query("select * from deroulementmatch WHERE idtournoi = '".$idtournoi."' 
																		AND idmatch = '".$idmatch."';");
								$ligneDeroulementMatch = mysql_fetch_array($resultatDeroulementMatch);
								$idTerrain = $ligneDeroulementMatch['idterrain'];
							}
							if($_GET['fini'] == 3)
							{							
								$resultatDeroulementMatch = mysql_query("select * from matchs WHERE idtournoi = '".$idtournoi."'
																		AND idmatch = '".$idmatch."';");
								$ligneDeroulementMatch = mysql_fetch_array($resultatDeroulementMatch);
								$idTerrain = $ligneDeroulementMatch['idterrainjoue'];
							}
							
						
							//Puis on récupère le nom du terrain associé à son id
							$resultatTerrain = mysql_query("select * from terrains WHERE idterrain ='".$idTerrain."';");
							$ligneTerrain = mysql_fetch_array($resultatTerrain);
							
							
							
								
								
							if($nomEq1['nomeq1'] != null && $nomEq2['nomeq2'] != null)	
							{
								echo '<tr>';
									echo '<td>';
										echo $nomEq1['nomeq1'];
									echo '</td>';
									
									echo '<td>';
										echo '-';
									echo '</td>';
									
									echo '<td>';
										echo $nomEq2['nomeq2'];
									echo '</td>';
			
								if($_GET['fini'] == 3) //On affiche les scores uniquement pour les matchs terminés
								{
									echo '<td>';
										echo $ligne['score1'].' - '.$ligne['score2'];
									echo '</td>';
								}else
								{
									echo '<td>';
										echo $ligneDeroulementMatch['ordre'];
									echo '</td>';
								}
								echo '</tr>';

							
							
							
							}
							
							$ligne = mysql_fetch_array($result);
							
							if($_GET['fini'] == 2)
								$idLastTerrain = $ligne['idterrain'];
							else
								$idLastTerrain = $ligne['idterrainjoue'];
								
							
							
							
						}while($idLastTerrain == $i);
					echo '</table>';
					echo '<br />';
					}	
					
				}
			}
			echo '</fieldset>';
		}

	}else
		echo '<br/><br/><br/><br/><center>Mauvais usage de la page</center>';
		
   	echo '</div>';
		include("pied_de_page.php"); ?>
	</body>
</html>
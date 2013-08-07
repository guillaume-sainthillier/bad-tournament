<?php
	session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Générer les matchs</title>
	   
	  
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	   <link rel="stylesheet" media="screen" type="text/css" title="design" href="style.css" />
	   
	   <?php
			include("connection.php");
		?>
		
   </head>
   <body>
   	
   		<?php include("banniere.php"); 
			  include("menu.php"); ?>

	<div id="contenu">
	<?php
			//Génère les matchs de 8eme/quart/demi/finale
			function genererMatchs2($pType,$pIdTournoi,$pNbPoules)
			{
				$resultat = mysql_query("select * from matchs WHERE idtournoi = '".$pIdTournoi."' AND idtypematch = '".($pType)."' ;");
							
				$i=0;
				while($ligne = mysql_fetch_array($resultat))
				{
					if($ligne['score1'] > $ligne['score2'])
						$tabFinal[$i]['idEquipe'] = $ligne['idequipe1'];
					else
						$tabFinal[$i]['idEquipe'] = $ligne['idequipe2'];
						
						
					$resultat2 = mysql_query("select * from equipe WHERE idtournoi = '".$pIdTournoi."' AND idequipe = '".$tabFinal[$i]['idEquipe']."' ;");
					$ligne2 = mysql_fetch_array($resultat2);
					
					$tabFinal[$i]['nomEquipe'] = $ligne2['nomequipe'];
					$tabFinal[$i]['poule'] = $ligne2['idpoule'];
					$tabFinal[$i]['nbVictoire'] = $ligne2['nbvictoire'];
					$i++;
				}
				$tabFinal['nbEq'] = $i;
				return $tabFinal;
				
			}
			//Fin genererMatchs
		//Début Fonctions 
			//totalPoints :  calcule le total des points marqués par une équipe
			function totalPoints($pIdEquipe,$pIdTournoi)
			{
				$score = 0;
				$resultat = mysql_query("select * from matchs WHERE idtournoi = '".$pIdTournoi."' AND
					(idequipe1 = '".$pIdEquipe."' OR idequipe2 = '".$pIdEquipe."') ;") OR DIE ("Erreur :<br />".mysql_error()."<br />");
										
				while($ligne = mysql_fetch_array($resultat))
				{
					if($ligne['idequipe1'] == $pIdEquipe)
						$score += $ligne['score1'];
					else
						$score += $ligne['score2'];							
				}
				return $score;
			}
			//Fin totalPoints
			
			
			//Génère les matchs de 8eme/quart/demi/finale
			function genererMatchs($pType,$pIdTournoi,$pNbPoules)
			{
					
				$val = (int)($pType/$pNbPoules); //Nombre d'équipes à retenir par poules
				for($i=0 ; $i < $pNbPoules;$i++)
				{
					$resultatPoule = mysql_query("select * from poule where idtournoi = '".$pIdTournoi."' 
					AND ( nompoule = 'Poule ".($i+1)."'
						OR nompoule = 'poule ".($i+1)."' ) ;") 
					OR DIE ("Erreur :<br />".mysql_error()."<br />");
								
					$lignePoule = mysql_fetch_array($resultatPoule);
					$idPoule = $lignePoule['idpoule'];
							
					$resultat = mysql_query("select * from equipe WHERE idtournoi = '".$pIdTournoi."' AND
											idpoule = '".$idPoule."' order by nbvictoire desc;")
											OR DIE ("Erreur :<br />".mysql_error()."<br />");
							

					$j = 0;
					while($ligne = mysql_fetch_array($resultat))
					{
						$tabH[$i][$j]['idequipe']   = $ligne['idequipe'];
						$tabH[$i][$j]['nomequipe']  = $ligne['nomequipe'];
						$tabH[$i][$j]['idpoule']    = $ligne['idpoule'];
						$tabH[$i][$j]['nbvictoire'] = $ligne['nbvictoire'];
						$j++;
					}	
					$tabH[$i]['nbEq'] = $j;
							
				}
				
		
				
				$l = 0;
				for($i = 0 ; $i < $pNbPoules;$i++)
				{
					
					for($j = 0;$j < $tabH[$i]['nbEq']-1;$j++)
					{	
						for($k = $j+1; $k < $tabH[$i]['nbEq'];$k++)
						{
							if($tabH[$i][$j]['nbvictoire'] == $tabH[$i][$k]['nbvictoire'])
							{
								if(totalPoints($tabH[$i][$j]['idequipe'],$pIdTournoi) < totalPoints($tabH[$i][$k]['idequipe'],$pIdTournoi))
								{
									$ech['idEquipe']    = $tabH[$i][$j]['idequipe'];
									$ech['nomEquipe']   = $tabH[$i][$j]['nomequipe'];
									$ech['poule']	    = $tabH[$i][$j]['idpoule'];
									$ech['nbVictoire']  = $tabH[$i][$j]['nbvictoire'];
									
									$tabH[$i][$j]['idequipe']    = $tabH[$i][$k]['idequipe'];
									$tabH[$i][$j]['nomequipe']	 = $tabH[$i][$k]['nomequipe'];
									$tabH[$i][$j]['idpoule']	 = $tabH[$i][$k]['idpoule'];
									$tabH[$i][$j]['nbvictoire']	 = $tabH[$i][$k]['nbvictoire'];
									
									$tabH[$i][$k]['idequipe']    = $ech['idEquipe'];
									$tabH[$i][$k]['nomequipe']	 = $ech['nomEquipe'];
									$tabH[$i][$k]['idpoule']	  = $ech['poule'];
									$tabH[$i][$k]['nbvictoire']	 = $ech['nbVictoire'];
								}
							}
						}		

						
					
						
						
					
					}
					for($m = 0 ; $m < $val;$m++)
						{
							$tabFinal[$l]['idEquipe']    = $tabH[$i][$m]['idequipe'];
							$tabFinal[$l]['nomEquipe']	 = $tabH[$i][$m]['nomequipe'];
							$tabFinal[$l]['poule']	 	 = $tabH[$i][$m]['idpoule'];
							$tabFinal[$l]['nbVictoire']	 = $tabH[$i][$m]['nbvictoire'];
							
							$l++;
						}
					
					
				}	
				
				$nbEq = $l;
					

				$i =0;
				$nbTentative = 0;
				while( $i < $nbEq-1 )
				{
					if( ($tabFinal[$i]['poule'] == $tabFinal[$i+1]['poule']) && $nbTentative <20)
					{
						shuffle($tabFinal);
						$i =0;
						$nbTentative++;
					}else
						$i = $i+2;
				}
					$tabFinal['nbEq'] = $nbEq;		
				$resultat = mysql_query("select * from equipe WHERE idtournoi = '".$pIdTournoi."' order by nbvictoire desc ;") OR DIE ("Erreur :<br />".mysql_error()."<br />");
						

				$nbRestant = $pType - ($val*$pNbPoules);
							
						
				$i = 0;
				while($i < $nbRestant && $ligne = mysql_fetch_array($resultat) )
				{			
					$dejaPris = false;
					for($j = 0; $j < $tabFinal['nbEq'] && !$dejaPris;$j++)
					{
						if($tabFinal[$j]['idEquipe'] == $ligne['idequipe'])						
							$dejaPris = true;
							
								
					}
					
					if(!$dejaPris)
					{
						$tab2H[$i]['idequipe'] = $ligne['idequipe'];
						$tab2H[$i]['poule'] = $ligne['idpoule'];
						$tab2H[$i]['nomequipe'] = $ligne['nomequipe'];
						$tab2H[$i]['nbvictoire'] = $ligne['nbvictoire'];
						$i++;
					}
					
					
				}
				$tab2H['nbeq'] = $i;
			
				for($i = 0;  $i < $tab2H['nbeq']; $i++)
				{
					$tabFinal[($tabFinal['nbEq']+$i)]['idEquipe']   = $tab2H[$i]['idequipe'];
					$tabFinal[($tabFinal['nbEq']+$i)]['poule']	    = $tab2H[$i]['poule'];
					$tabFinal[($tabFinal['nbEq']+$i)]['nomEquipe']  = $tab2H[$i]['nomequipe'];
					$tabFinal[($tabFinal['nbEq']+$i)]['nbVictoire'] = $tab2H[$i]['nbvictoire'];
				}
				$tabFinal['nbEq'] = $tabFinal['nbEq'] + $i;

				return $tabFinal;
			}
			//Fin genererMatchs
			
			
			//Affiche les matchs contenus dans $tabHuitiemes
			function afficherMatchs($pTableau,$pIdTournoi)
			{
					echo '<table id="liste_equipe">
										<tr>										   
										   <th>Poule</th>							  
											<th>Nom</th>
										   <th>Nb Victoires</th>
										   <th>Points marqués</th>
										   <th>-</th>
										   <th>Poule</th>									   
										   <th>Nom</th>
										   <th>Nb Victoires</th>
										   <th>Points marqués</th>
										   
										</tr>';
										
					
					for($i = 0; $i < $pTableau['nbEq'] -1;$i = $i +2)
					{
						echo '<tr>';
							
							echo '<td>'; //Poule N°1
								$resultat = mysql_query("select * from poule WHERE idpoule = '".$pTableau[$i]['poule']."' ;");
								$lignePoule = mysql_fetch_array($resultat);
								 echo ucfirst($lignePoule['nompoule']); //ucfirst met la première lettre en majuscule
							echo '</td>';
							
							echo '<td>'; //Nom N°1
								 echo $pTableau[$i]['nomEquipe'];
							echo '</td>';
							
							echo '<td>'; //nbVic N°1
								 echo $pTableau[$i]['nbVictoire'];
							echo '</td>';
							
							echo '<td>'; //Points marqué N°1
								 echo totalPoints($pTableau[$i]['idEquipe'],$pIdTournoi);
							echo '</td>';
						
							echo '<td>'; // - 
								echo '';
							echo '</td>';
							
							echo '<td>'; //Poule N°2
								 $resultat = mysql_query("select * from poule WHERE idpoule = '".$pTableau[$i+1]['poule']."' ;");
								$lignePoule = mysql_fetch_array($resultat);
								 echo ucfirst($lignePoule['nompoule']); //ucfirst met la première lettre en majuscule
							echo '</td>';
														
							echo '<td>'; //Nom N°2
								 echo $pTableau[$i+1]['nomEquipe'];
							echo '</td>';
							
							echo '<td>'; //nbVic N°2
								 echo $pTableau[$i+1]['nbVictoire'];
							echo '</td>';
							
							echo '<td>'; //Points marqué N°2
								  echo totalPoints($pTableau[$i+1]['idEquipe'],$pIdTournoi);
							echo '</td>';
							
						echo '</tr>';
					}
					echo '</table>';
					
				
				echo "<br />";
			}
			//Fin afficherMatchs
			
			
			//Inscrit dans la base de données les modifications nécéssaires
			function confirmer($pTableau,$pIdTournoi,$nbTerrains,$idTypeMatch)
			{
				$indiceEC = 0;
				$i =  0;
				$ordre = 0;
				while($i < $pTableau['nbEq']) // /!\ Pas de for ici
				{
					$estEnCours = 1;
					
					for($j = 1 ;$j <= $nbTerrains && $i < $pTableau['nbEq'];$j++)
					{
						if($indiceEC > 4)
							$estEnCours = 0;
						$idEquipe1 = $pTableau[$i]['idEquipe'];
						$idEquipe2 = $pTableau[($i+1)]['idEquipe'];
						
						$requete = mysql_query("INSERT INTO matchs(idequipe1,idequipe2,score1,score2,estfini,estEnCours,idtournoi,idtypematch)
											VALUES('$idEquipe1','$idEquipe2',0,0,0,$estEnCours,'$pIdTournoi',$idTypeMatch) ;")
											OR DIE("Erreur <br />".$requete."<br />".mysql_error());
																
						
											
											
						$resultat = mysql_query("select * from matchs WHERE (idequipe1 = '".$idEquipe1."' OR idequipe2 = '".$idEquipe1."' )
												AND	(idequipe1 = '".$idEquipe2."' OR idequipe2 = '".$idEquipe2."')
												AND	idtournoi = '".$pIdTournoi."' 
												AND idtypematch = '".$idTypeMatch."';")
												OR DIE("Erreur3 <br />".mysql_error());
						
						
						$ligne = mysql_fetch_array($resultat);
						$idMatch = $ligne['idmatch'];
						
						
						mysql_query("INSERT INTO deroulementmatch(idterrain,idmatch,ordre,idtournoi)
								 VALUES ('$j',".$idMatch.",'$ordre','$pIdTournoi');") 
								 OR DIE("Erreur4 <br />".mysql_error());
								 
						$indiceEC++;
						
						$i += 2;
					}
					$ordre++;
				}
			}
			//Fin confirmer
		//Fin fonctions 
			
		
		//Variables de session
		
		
		//On récupère l'idtournoi par la session si c'est possible , sinon par les cookies
		if(!isset($_SESSION['idtournoi']))
		{
			if(!isset($_COOKIE['idtournoi']))
			{
				echo 'Veuillez recharger le tournoi <br />';
			}else
				$idtournoi = $_COOKIE['idtournoi'];
		}else
			$idtournoi = $_SESSION['idtournoi'];
			
		
		
		//Variables de session servant à définir l'utilisation des boutons de génération des matchs avec des disabled ou non
		$_SESSION['poule'] 		 = false;
		$_SESSION['huitieme']	 = false;
		$_SESSION['quart']		 = false;
		$_SESSION['demi'] 		 = false;
		$_SESSION['finale']		 = false;
		$_SESSION['huitieme2']	 = false;
		$_SESSION['quart2'] 	 = false;
		$_SESSION['demi2'] 	 	 = false;
		$_SESSION['finale2'] 	 = false;
	
		
		
		
		
		//Partie constante
		$resultat = mysql_query("select count(*) as nbeq from equipe WHERE  idtournoi = '".$idtournoi."';") OR DIE ("Erreur :<br />".mysql_error()."<br />");
		$ligne	  = mysql_fetch_array($resultat);
		
		$nbEquipes = $ligne['nbeq']; //On récupère le nombre d'equipes pour un tournoi donné
		$nbMatchs  = ceil($nbEquipes/2);//On en déduit le nombre de matchs
		
		
		$resultat = mysql_query("select * from tournoi WHERE  idtournoi = '".$idtournoi."' ;") OR DIE ("Erreur :<br />".mysql_error()."<br />");
		$ligne	  = mysql_fetch_array($resultat);

		$nbTerrains = $ligne['nbterrain']; // On récupère le nombre de terrain du tournoi donné
		$nbPoules	= $ligne['nbpoule']; //On récupère aussi le nomre de poule pour le tournoi donné
		

		
		echo 'Il y a '.$nbPoules.' poules ,'.$nbEquipes.' équipes, et '.$nbTerrains.' terrains<br />';
				
			$ok = false;
			for($i = 0 ; $i < 5 && !$ok; $i++)
			{
				$resultat = mysql_query("select * from matchs where idtypematch = '".($i+1)."' 
										 AND idtournoi = '".$idtournoi."'; ")
										 OR DIE ("Erreur :<br />".mysql_error()."<br />");
										 
				
				if(!($ligne = mysql_fetch_array($resultat))) //On regarde si l'on trouve des matchs de poules , de huitiemes, de quarts , ...
				{				
					$ok = true;					
					
					$resultat = mysql_query("select * from matchs WHERE idtypematch = '".$i."' AND idtournoi = '".$idtournoi."'
											 AND estfini AND !estEnCours ;")
											 OR DIE ("Erreur :<br />".mysql_error()."<br />");
					//S'il n'y a pas de résultats sur la requête d'avant on regarde si tous les matchs du type de tournoi d'avant sont finis
					
					
					if($i == 0)				
						$_SESSION['poule'] = true; //On affiche rien sauf les poules
					
					if($i == 1)
					{
						if(!($ligne = mysql_fetch_array($resultat)))
							$_SESSION['huitieme2'] = true;
						
						$_SESSION['huitieme'] = true;	//On affiche rien sauf les huitièmes		
					
					}
					if($i == 2)
					{
						if(!($ligne = mysql_fetch_array($resultat)))
							$_SESSION['quart2'] = true;
						
						$_SESSION['quart'] = true;//On affiche rien sauf les quarts de finale
					}
					if($i == 3)
					{
						if(!($ligne = mysql_fetch_array($resultat)))
							$_SESSION['demi2'] = true;
						
						$_SESSION['demi'] = true;//On affiche rien sauf les demies
						
					}
					if($i == 4)
					{
						if(!($ligne = mysql_fetch_array($resultat)))
							$_SESSION['finale2'] = true;
						
						$_SESSION['finale'] = true;//On affiche rien sauf la finale
					
					}
				}
			}
			
			//Fin partie constante
			
			
			
			//Partie matchs de poules					
				
			
			if($_SESSION['poule']) //Affichage ou non du bouton generer matchs de poules
			{
				
				echo '<br /><form method="post" action="generermatchs.php">';
					echo 'Matchs de poules :';
					echo '<br/><input type ="submit" value="Generer" name="generer" /><br/>';				
				echo'</form>';	
			}
			
			if(isset($_POST['generer'])) // 1ère étape : Generation des matchs de poules
			{
				$_SESSION['tabMatchs'] = array();
				echo '<br /><form method="post" action="generermatchs.php">';
					echo 'Terminer :';
					echo '<br/><input type ="submit" value="Confirmer" name="confirmer" /><br/>';				
				echo'</form>';	
				
				//On récupère dans un tableau tabEquipe[idPoule][indiceEquipe] toutes les équipes des poules
				for($i = 1 ;$i <= $nbPoules ;$i++)
				{
					$resultatPoule = mysql_query("select * from poule where idtournoi = '".$idtournoi."' AND (
					 nompoule = 'Poule ".$i."'
					 OR nompoule = 'poule ".$i."' );") OR DIE ("Erreur :<br />".mysql_error()."<br />");
					 					 
					 $lignePoule = mysql_fetch_array($resultatPoule);
					 $idPoule = $lignePoule['idpoule'];
					$resultat = mysql_query("select * from equipe WHERE idtournoi = '".$idtournoi."' AND idpoule = '".$idPoule."';") OR DIE ("Erreur :<br />".mysql_error()."<br />");

					$j = 0;
					while(  $ligne = mysql_fetch_array($resultat)  )
					{
						
						$tabEquipes[$i][$j] = $ligne['idequipe'];
						$j++;
					}
					$tabEquipes[$i]['nbParticipants'] = $j; //On récupère nombre d'équipe dans la poule courante (pas toujours égal au nombreDeJoueurParPoule

					//dans la table tournoi
				}
				
				
				/*
					On shuffle les équipes dans les poules respectives
					Début shuffle
				*/
				for($i = 1;$i <= $nbPoules; $i++)
				{
					$nbParticipants = $tabEquipes[$i]['nbParticipants'];
					
					for($j = 0;$j < $nbParticipants ;$j++)
					{
							$val = rand(0,$nbParticipants-1); //La valeur aléatoire générée est l'indice du tableau des équipes
							
							$k = 0;
							while($k < $j) //On vérifie que l'indice généré n'est pas déjà pris auparavent
							//NB : On ne peut pas faire de for ici
							{
								/*Si pour un quelconque indice on a déjà inseré la valeur de tabEquipes au rang $val , on regenere une valeur et remet l'indice
								  de parcours k à 0 (un peu bizard)
								*/
								if($tabEquipesShuffle[$i][$k] == $tabEquipes[$i][$val]) 
								{
									$val = rand(0,$nbParticipants-1); 
									$k = 0;
								}else
									$k++;
								
							}
							//Une fois le bon indice généré , on met son contenu dans le tableau d'équipes shuffle
							$tabEquipesShuffle[$i][$j] = $tabEquipes[$i][$val];				
					}
					$tabEquipesShuffle[$i]['nbParticipants'] = $j;
					
					
				}		
				//Fin shuffle
						

				echo '<br />';
				echo '<br />';
				
				
				/*
					Ensuite , on réparti les équipes dans des matchs de poules , de façon ordonnée
					par un tri d'algo de S2 (Pour i de nbVal-1 FAIRE
												Pour j de i+1 à nbVal FAIRE 
													...traitement...).
					tout ça dans un tableau tabMatchs[idPoule][indiceMatch]['idequipe1']/['idequipe2']
					NB: indiceMatch sera l'ordre du déroulement des matchs plus tard
				*/
				for($i = 1; $i <= $nbPoules ;$i++)
				{
					$nbParticipants = $tabEquipes[$i]['nbParticipants'];
							
					$indiceMatch = 0;
					for($j = 0;$j < $nbParticipants-1;$j++)
					{						
						for($k = $j+1 ;$k < $nbParticipants;$k++)
						{
							$tabMatchs[$i][$indiceMatch]['idEquipe1'] = $tabEquipesShuffle[$i][$j];
							$tabMatchs[$i][$indiceMatch]['idEquipe2'] = $tabEquipesShuffle[$i][$k];
							$tabMatchs[$i][$indiceMatch]['poule'] = $i;
							$indiceMatch++;							
						}					
					}
							
					$tabMatchs[$i]['nbmatchs'] = $indiceMatch; //on stock le nombre de match pour une poule donnée
					
								
				}	
				
				
					$lastIndice = 1;
					for($i = $nbTerrains+1; $i <=$nbPoules;$i++)
					{
					
						$k = $lastIndice;
						for($j = 1 ;$j <= $tabMatchs[$i]['nbmatchs'] ; $j++)
						{
							$tabMatchs[$k][($tabMatchs[$k]['nbmatchs'])]['idEquipe1'] = $tabMatchs[$i][($j-1)]['idEquipe1'];
							$tabMatchs[$k][($tabMatchs[$k]['nbmatchs'])]['idEquipe2'] = $tabMatchs[$i][($j-1)]['idEquipe2'];
							$tabMatchs[$k][($tabMatchs[$k]['nbmatchs'])]['poule']     = $tabMatchs[$i][($j-1)]['poule'];
							
							$tabMatchs[$k]['nbmatchs']++;
							
							if($k == $nbTerrains)
								$k = 1;
							else
								$k++;
						
							$lastIndice = $k;
						}	
					}
					
					
					
						
						
				/*
					On shuffle les matchs dans les poules respectives
					Début shuffle
				*/
				for($i = 1;$i <= $nbPoules; $i++)
				{
					$nbMatchs = $tabMatchs[$i]['nbmatchs'];
					for($j = 0;$j < $nbMatchs;$j++)
					{					
							$estBon = 1; // Utilisé à la place de booléen. 1 vaut False et 2 vaut TRUE
							$nbTentative = 0; // Represente le nombre de tentative pour trouver un bon match
							while($estBon == 1) //Tant que l'on pas trouvé le bon rang du match
							{ 					
								$val = rand(0,$nbMatchs-1);
								$estBon = 1;
								$k =0;
								
								//On vérifie si l'indice généré n'a pas été généré avant (ne rentre pas dans la boucle au premier passage)
								while($k < $j)		 //Pas de for à la place , génère un bug				
								{
									if($tabVal[$k] == $val)
									{
										$val = rand(0,$nbMatchs-1);
										$dejaPris = 2;
										$k = 0;
									}else
										$k++;
										
								}					
								$tabVal[$j] = $val; //On stocke dans un tableau la liste des bons indices générés
								
								if($j > 0)
								{		
										//Si une des 2 équipes ne joue pas avant
										if( $_SESSION['tabMatchs'][$i][$j-1]['idEquipe1'] != $tabMatchs[$i][$val]['idEquipe1'] &&
											$_SESSION['tabMatchs'][$i][$j-1]['idEquipe2'] != $tabMatchs[$i][$val]['idEquipe1'] &&
											$_SESSION['tabMatchs'][$i][$j-1]['idEquipe1'] != $tabMatchs[$i][$val]['idEquipe2'] &&
											$_SESSION['tabMatchs'][$i][$j-1]['idEquipe2'] != $tabMatchs[$i][$val]['idEquipe2'] )
										{
											$estBon = 2;									
										}else
										{										
											$nbTentative++;
											//Au bout de 20 tentatives , c'est qu'on est casiment obligé de faire jouer 
											//une des 2 équipes deux fois de suite
											if($nbTentative > 20 )								
												$estBon = 2;										
										}							
								}else
								{
									$estBon = 2;
								}
							}					
							
							$_SESSION['tabMatchs'][$i][$j]['idEquipe1'] = $tabMatchs[$i][$val]['idEquipe1'];
							$_SESSION['tabMatchs'][$i][$j]['idEquipe2'] = $tabMatchs[$i][$val]['idEquipe2'];
							$_SESSION['tabMatchs'][$i][$j]['poule']     = $tabMatchs[$i][$val]['poule'];
							
					}
					$_SESSION['tabMatchs'][$i]['nbmatchs'] = $j;
				}
				
				
						
						
						
						//Affichages
						
						$_SESSION['mauvaisNombre'] = 0;
						for($i = 1; $i <= $nbTerrains && $i <= $nbPoules && !$_SESSION['mauvaisNombre'];$i++)
						{
							$k = $i;
							while($k <= $nbPoules && !$_SESSION['mauvaisNombre'])
							{
								$nbMatchs = $_SESSION['tabMatchs'][$k]['nbmatchs'];
								if($nbMatchs == 0)
									$_SESSION['mauvaisNombre'] = 1;
								$k = $k + $nbTerrains;
								
							}
						}
						 
						 
						for($i = 1; $i <= $nbTerrains && $i <= $nbPoules;$i++)
						{
							
							echo 'Terrain '.$i.' :<br />';		
							
							$k = $i;
							
							$ordre = 1;
							
							
								echo '<table id="liste_equipe">
										<tr>
										   <th>Ordre de jeu</th>
										   <th>Poule n°</th>
										   <th>Nom</th>	
										   <th> </th>
										   <th>Poule n°</th>										
										   <th>Nom</th>
										</tr>';
							
							
										
								$nbMatchs = $_SESSION['tabMatchs'][$i]['nbmatchs'];								
								
								for($j = 0;$j < $nbMatchs ;$j++)
								{	
									echo '<tr>';
										echo '<td>'; //Ordre
											echo $ordre;
										echo '</td>';
										
										echo '<td>'; //Poule
											echo $_SESSION['tabMatchs'][$i][$j]['poule'];
										echo '</td>';
										
																			
										$resultat = mysql_query("select * from equipe WHERE idtournoi = '".$idtournoi."' 
																AND idequipe = '".$_SESSION['tabMatchs'][$i][$j]['idEquipe1']."';");
										$ligne = mysql_fetch_array($resultat);
										echo '<td>'; //Nom de l'équipe 1
											echo $ligne['nomequipe'];
										echo '</td>';
										
										echo '<td> - </td>'; // ' ' 
										
										echo '<td>'; //Poule N°2
											echo $_SESSION['tabMatchs'][$k][$j]['poule'];
										echo '</td>';	
										
										$resultat = mysql_query("select * from equipe WHERE idtournoi = '".$idtournoi."'
																AND idequipe = '".$_SESSION['tabMatchs'][$i][$j]['idEquipe2']."';");
										$ligne = mysql_fetch_array($resultat);
										echo '<td>'; //Nom de l'équipe 2
											echo $ligne['nomequipe'];
										echo '</td>';
										
									echo '</tr>';
									$ordre++;
									// $_SESSION['nbMatchsShuffle'][$i]++;
								}								
								$k = $k + $nbTerrains;
														
								echo '</table>';
								echo '<br />';
						}
						
					
			}//Fin isset generer
			
			
			
			if(isset($_POST['confirmer']))//2ème étape : Partie sur la confirmation des générations de matchs de poule
			{
				
				mysql_query("delete from deroulementmatch WHERE idtournoi ='".$idtournoi."';") OR DIE ("Erreur :<br />".mysql_error()."<br />");
				mysql_query("delete from matchs WHERE idtournoi ='".$idtournoi."';") OR DIE ("Erreur :<br />".mysql_error()."<br />");
				
									
				for($i = 1 ; $i <= $nbPoules && $i <= $nbTerrains; $i++) // /!\ indice commence à 1
				{
					
					if(!isset($_SESSION['tabMatchs'][$i]['nbmatchs']) ) // Ne devrait pas arriver
					{
						echo 'Veuillez regenerer les matchs . <br />';
							echo '<br /><form method="post" action="generermatchs.php">';
							echo '<br/><input type ="submit" value="Generer" name="generer" /><br/>';				
						echo'</form>';
					}else
					{
						$nbMatchs = $_SESSION['tabMatchs'][$i]['nbmatchs'];
						for($j = 0 ; $j < $nbMatchs;$j++)
						{
														
								$idEquipe1 = $_SESSION['tabMatchs'][$i][$j]['idEquipe1'];
								$idEquipe2 = $_SESSION['tabMatchs'][$i][$j]['idEquipe2'];							
		
								if($j == 0)
									$estEnCours = 1;
								else
									$estEnCours = 0;
									
									
								
								mysql_query("INSERT INTO matchs(idequipe1,idequipe2,score1,score2,estfini,estEnCours,idtournoi,idtypematch)
											VALUES('$idEquipe1','$idEquipe2',0,0,0,$estEnCours,'$idtournoi',1); ") 
								
								OR DIE ("Erreur :<br />".mysql_error()."<br />");
																
								
								$resultat = mysql_query("select * from matchs WHERE (idequipe1 = '".$idEquipe1."' OR idequipe2 = '".$idEquipe1."' )
															AND
														(idequipe1 = '".$idEquipe2."' OR idequipe2 = '".$idEquipe2."')
															AND
														idtournoi = '".$idtournoi."' ;") OR DIE ("Erreur :<br />".mysql_error()."<br />");
								
								$ligne = mysql_fetch_array($resultat);
								$idMatch = $ligne['idmatch'];
								
								mysql_query("INSERT INTO deroulementmatch(idterrain,idmatch,ordre,idtournoi)
											 VALUES ('$i',".$idMatch.",'$j','$idtournoi') ;") OR DIE ("Erreur :<br />".mysql_error()."<br />");
								
								
						}
						echo '<br /><br />';
					}
				}
				header("Location: matchs.php?fini=1");
				
			}
			
			
			//Fin partie matchs de poules
			
			
					
			
			//Début matchs de huitièmes
			if($_SESSION['huitieme'])
			{
				$nom = "huitieme";
				echo '<br /><form method="post" action="generermatchs.php">';				
				$_SESSION['lastEndroit'] = 2;
				if($nbEquipes > 2 && $nbEquipes <=4 )
				{
					mysql_query("insert into matchs(score1,score2,estfini,estEnCours,idtournoi		,idtypematch) 
											 VALUES(-1,-1	 ,1	   ,0		  ,".$idtournoi."	,2) ;") 
										   	OR DIE("Erreur1".mysql_error());
					
					mysql_query("insert into matchs(score1,score2,estfini,estEnCours,idtournoi		,idtypematch) 
											 VALUES(-1   ,-1	 ,1	   ,0		  ,".$idtournoi."	,3) ;") 
									         OR DIE("Erreur2".mysql_error());
					
					mysql_query("insert into matchs(score1,score2,estfini,estEnCours,idtournoi		,idtypematch) 
											 VALUES(-1   ,-1	 ,1	   ,0		  ,".$idtournoi."	,4) ;") 
											 OR DIE("Erreur".mysql_error());
					
					
					echo 'Il n\'y a pas assez d\'équipes pour faire des huitièmes,des quarts ou des demies finales <br >
					On passe à la finale directement <br />';
					$nom = "finale";
				}
				if($nbEquipes > 4 && $nbEquipes <= 8)
				{
					echo 'Il n\'y a pas assez d\'équipes pour faire des huitièmes ou des quarts de finales <br >
						On passe aux demies finales directement <br />';	
					$nom = "demi";						
					
					mysql_query("insert into matchs(score1,score2,estfini,estEnCours,idtournoi		,idtypematch) 
										 VALUES(-1   ,-1	 ,		1	   ,0		  ,".$idtournoi."	,2) ;") 
										OR DIE("Erreur".mysql_error());
				
					mysql_query("insert into matchs(score1,score2,estfini,estEnCours,idtournoi		,idtypematch) 
											 VALUES(-1   ,-1	 ,1	   ,0		  ,".$idtournoi."	,3) ;") 
											OR DIE("Erreur".mysql_error());
				}
				if($nbEquipes > 8 && $nbEquipes <= 16)
				{
					echo '<span id="probleme" >Il n\'y a pas assez d\'équipes pour faire des huitièmes de finales <br >
					On passe aux quarts de finales directement <br /> </span>';
					$nom = "quart";
					
					
					mysql_query("insert into matchs(score1,score2,estfini,estEnCours,idtournoi	    ,idtypematch) 
											VALUES(-1   ,-1	 ,1	   ,0		  ,".$idtournoi."	,2) ;") 	
											OR DIE("Erreur".mysql_error());
				
				}					
							
				echo 'Matchs de '.$nom.' de finale:';
				echo '<br/><input type ="submit" value="Generer" name= "'.$nom.'" ';
				if($_SESSION['huitieme2'])
					 echo 'disabled';
					
				echo '/><br/>';				
				echo'</form>';	
			}
			
			
			if(isset($_POST['huitieme']))
			{
				echo '<br /><form method="post" action="generermatchs.php">';					
				echo '<br/><input type ="submit" value="Confirmer" name="confirmerHuitieme"/><br/><br />';				
				echo'</form>';
				$tabHuitiemes = genererMatchs(16,$idtournoi,$nbPoules);
				afficherMatchs($tabHuitiemes,$idtournoi);
				
			}
			
			if(isset($_POST['confirmerHuitieme']))
			{
				mysql_query("delete from deroulementmatch WHERE idtournoi ='".$idtournoi."';") OR DIE ("Erreur :<br />".mysql_error()."<br />");
				mysql_query("delete from matchs WHERE idtournoi ='".$idtournoi."' AND idtypematch = 2;") OR DIE ("Erreur :<br />".mysql_error()."<br />");
				$tabHuitiemes = genererMatchs(16,$idtournoi,$nbPoules);
	
				confirmer($tabHuitiemes,$idtournoi,$nbTerrains,2);			
				header("Location: matchs.php?fini=1");
			}
			//Fin matchs de huitièmes
			
			
			//Début matchs de quarts
			if($_SESSION['quart'])
			{
				echo '<br /><form method="post" action="generermatchs.php">';
					echo 'Matchs de quart de finale :';
					echo '<br/><input type ="submit" value="Generer" name="quart" ';
					if($_SESSION['quart2'])
						echo 'disabled';
					
					echo '/><br/>';					
				echo'</form>';	
			}
			
			
			if(isset($_POST['quart']))
			{
				if($nbEquipes <= 4)
				{
					echo 'Pas besoin de quart de final , on passe aux demies direct <br />';		
					
					mysql_query("insert into matchs(score1,score2,estfini,estEnCours,idtournoi,idtypematch) 
											 VALUES( 1	  ,0	  ,1	 ,0			,".$idtournoi.",3) ;") 
					OR DIE("Erreur".mysql_error());
					
				}else
				{
					echo '<br /><form method="post" action="generermatchs.php">';					
					echo 'Terminer<br/><input type ="submit" value="Confirmer" name="confirmerQuart"/><br/><br />';				
					echo'</form>';
				
					$resultat =  mysql_query("select * from matchs where idtournoi = '".$idtournoi."' AND score1 = -1 AND score2 = -1 ;");
					
					
					if( mysql_fetch_array($resultat) )					
						$tabHuitiemes = genererMatchs(8,$idtournoi,$nbPoules);					
					else
						$tabHuitiemes = genererMatchs2(2,$idtournoi,$nbPoules);
					
						
					afficherMatchs($tabHuitiemes,$idtournoi);
				}
			}
			
			if(isset($_POST['confirmerQuart']))
			{
				mysql_query("delete from deroulementmatch WHERE idtournoi ='".$idtournoi."';") OR DIE ("Erreur :<br />".mysql_error()."<br />");
				mysql_query("delete from matchs WHERE idtournoi ='".$idtournoi."' AND idtypematch = 3;") OR DIE ("Erreur :<br />".mysql_error()."<br />");
				
				
				$resultat =  mysql_query("select * from matchs where idtournoi = '".$idtournoi."' AND score1 = -1 AND score2 = -1 ;");
					
					
					if( mysql_fetch_array($resultat) )
						$tabHuitiemes = genererMatchs(8,$idtournoi,$nbPoules);
					else
						$tabHuitiemes = genererMatchs2(2,$idtournoi,$nbPoules);
				confirmer($tabHuitiemes,$idtournoi,$nbTerrains,3);
					
				header("Location: matchs.php?fini=1");
			}
			//Fin matchs de quarts de finale
			
			
			//Début match de demie finale
			if($_SESSION['demi'])
			{
				echo '<br /><form method="post" action="generermatchs.php">';
					echo 'Matchs de demie-finale :';
					echo '<br/><input type ="submit" value="Generer" name="demi" ';
					if($_SESSION['demi2'])
						echo 'disabled';
					
					echo '/><br/>';			
				echo'</form>';	
			}
			
			if(isset($_POST['demi']))
			{
				if($nbEquipes <= 4)
				{
					echo 'Pas besoin de huitièmes de final , on passe aux quarts direct <br />';		
					
					mysql_query("insert into matchs(estfini,estEnCours,idtournoi,idtypematch) VALUES(1,0,".$idtournoi.",4) ;") 
					OR DIE("Erreur".mysql_error());
					
				}else
				{
					echo '<br /><form method="post" action="generermatchs.php">';					
					echo 'Terminer<br/><input type ="submit" value="Confirmer" name="confirmerDemi"/><br/><br />';				
					echo'</form>';
					
					$tabHuitiemes = genererMatchs2(3,$idtournoi,$nbPoules);					
					afficherMatchs($tabHuitiemes,$idtournoi);
				}
			}
			
			
			if(isset($_POST['confirmerDemi']))
			{
				mysql_query("delete from deroulementmatch WHERE idtournoi ='".$idtournoi."';") OR DIE ("Erreur :<br />".mysql_error()."<br />");
				mysql_query("delete from matchs WHERE idtournoi ='".$idtournoi."' AND idtypematch = 4;") OR DIE ("Erreur :<br />".mysql_error()."<br />");
				$tabHuitiemes = genererMatchs2(3,$idtournoi,$nbPoules);
				confirmer($tabHuitiemes,$idtournoi,$nbTerrains,4);								
				header("Location: matchs.php?fini=1");
			}
			//Fin matchs de demie finale
			
			
			
			//Début finale
			if($_SESSION['finale'])
			{
				echo '<br /><form method="post" action="generermatchs.php">';
					echo 'Matchs de finale :';
					echo '<br/><input type ="submit" value="Generer" name="finale" ';
					if($_SESSION['finale2'])
						echo 'disabled';
					
					echo '/><br/>';					
				echo'</form>';	
			}
					
			if(isset($_POST['finale']))
			{
				if($nbEquipes <= 2)
				{
					echo 'Pas besoin de huitièmes de final , on passe aux quarts direct <br />';		
					
					mysql_query("insert into matchs(estfini,estEnCours,idtournoi,idtypematch) VALUES(1,0,".$idtournoi.",5) ;") 
					OR DIE("Erreur".mysql_error());
					
				}else
				{
					echo '<br /><form method="post" action="generermatchs.php">';					
					echo 'Terminer<br/><input type ="submit" value="Confirmer" name="confirmerFinale"/><br/><br />';				
					echo'</form>';
					$tabHuitiemes = genererMatchs2(4,$idtournoi,$nbPoules);
					afficherMatchs($tabHuitiemes,$idtournoi);

				}
			}
			
			if(isset($_POST['confirmerFinale']))
			{
				mysql_query("delete from deroulementmatch WHERE idtournoi ='".$idtournoi."';") OR DIE ("Erreur2 :<br />".mysql_error()."<br />");
				mysql_query("delete from matchs WHERE idtournoi ='".$idtournoi."'  AND idtypematch = 5;") OR DIE ("Erreur3 :<br />".mysql_error()."<br />");
				$tabHuitiemes = genererMatchs2(4,$idtournoi,$nbPoules);
				$_SESSION['tabClassement'][3] = $tabHuitiemes;
				confirmer($tabHuitiemes,$idtournoi,$nbTerrains,5);
								
				header("Location: matchs.php?fini=1");
			}
			//Fin finales
			
			
		
 	echo '</div>';
		include("pied_de_page.php"); ?>
	</body>
</html>
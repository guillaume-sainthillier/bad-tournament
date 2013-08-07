<?php
	session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Générer les matchs</title>
	   
	  
		<meta http-equiv="Content-Type" content="image/png; charset=UTF-8" />
		<link href="style.css" rel="stylesheet" type="text/css" />
	   
	   <?php
			include("connection.php");
		?>
		
   </head>
   <body id="class">
   	
   		<?php include("banniere2.php"); 
			  include("menu.php"); ?>

	<div id="contenu">
	
	
	<?php
	
	
		//totalPoints :  calcule le total des points marqués par une équipe
			function totalPoints($pIdEquipe,$pIdTournoi)
			{
				$score = 0;
				$resultat = mysql_query("select * from matchs WHERE idtournoi = '".$pIdTournoi."' AND
					(idequipe1 = '".$pIdEquipe."' OR idequipe2 = '".$pIdEquipe."') ;") OR DIE ("Erreur :<br />".mysql_error()."<br />");
										
				while( ($ligne = mysql_fetch_array($resultat)) )
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
			
			
			
		if(!isset($_SESSION['idtournoi']))
		{
			if(!isset($_COOKIE['idtournoi']))
			{
				echo 'Veuillez recharger le tournoi <br />';
			}else
				$idTournoi = $_COOKIE['idtournoi'];
		}else
			$idTournoi = $_SESSION['idtournoi'];
			

		
		
		//Partie constante
		$resultat = mysql_query("select count(*) as nbeq from equipe WHERE  idtournoi = '".$idTournoi."';") OR DIE ("Erreur :<br />".mysql_error()."<br />");
		$ligne	  = mysql_fetch_array($resultat);
		
		$nbEquipes = $ligne['nbeq']; //On récupère le nombre d'equipes pour un tournoi donné
		
		
		$resultat = mysql_query("select * from tournoi WHERE  idtournoi = '".$idTournoi."' ;") OR DIE ("Erreur :<br />".mysql_error()."<br />");
		$ligne	  = mysql_fetch_array($resultat);

		$nbPoules	= $ligne['nbpoule']; //On récupère aussi le nomre de poule pour le tournoi donné
		
		echo 'nombre de poules : '.$nbPoules.'<br />';
		echo 'nombre d\'équipes : '.$nbEquipes.'<br />';
		setcookie("nbpoules",$nbPoules,time()+3600*24*7);
		setcookie("nbequipes",$nbEquipes,time()+3600*24*7);
		
		
	
		$resultat = mysql_query("select * from matchs WHERE idtournoi = '".$idTournoi."' order by idtypematch desc;");
		if(!($ligne = mysql_fetch_array($resultat)))
			$val = 0;
		else
		{
			
			$val = $ligne['idtypematch'];
			$resultat2 = mysql_query("select * from matchs WHERE idtournoi = '".$idTournoi."' AND estfini and !estEnCours and idtypematch = '".$val."' ;");
			if( !($ligne2 = mysql_fetch_array($resultat2)) || $val == 1)
				$val--;
		}
			
		unset($_SESSION['tabClassement']);

		if($val > 0)
		{
			if($nbEquipes > 16)
			{
				$nbDebut = 0;		
			}
			if($nbEquipes >8 && $nbEquipes <= 16)
			{
				$nbDebut = 1;
			}
			if($nbEquipes >4 && $nbEquipes <=8)
			{
				$nbDebut = 2;
			}
			if($nbEquipes > 2 && $nbEquipes <=4)
			{
				$nbDebut = 3;
			}
			
			$_SESSION['debut'] = $nbDebut;
		
			$resultat = mysql_query("select * from matchs where idtournoi = '".$idTournoi."' AND idtypematch = ".($nbDebut+2).";");
			$i = 0;
			while($ligne = mysql_fetch_array($resultat))
			{
				$_SESSION['tabClassement'][$nbDebut][$i]['idEquipe'] = $ligne['idequipe1'];
				$resultat2 = mysql_query("select * from equipe WHERE idtournoi = '".$idTournoi."'
										AND idequipe = '".$ligne['idequipe1']."' ;");
				$ligne2 = mysql_fetch_array($resultat2);
				$_SESSION['tabClassement'][$nbDebut][$i]['nomEquipe'] = $ligne2['nomequipe'];
				$_SESSION['tabClassement'][$nbDebut][$i]['poule']	  = $ligne2['idpoule'];
				$_SESSION['tabClassement'][$nbDebut][$i]['nbVictoire'] = $ligne2['nbvictoire'];
				
				
				$_SESSION['tabClassement'][$nbDebut][$i+1]['idEquipe'] = $ligne['idequipe2'];
				$resultat2 = mysql_query("select * from equipe WHERE idtournoi = '".$idTournoi."'
										AND idequipe = '".$ligne['idequipe2']."' ;");
				$ligne2 = mysql_fetch_array($resultat2);
				$_SESSION['tabClassement'][$nbDebut][$i+1]['nomEquipe'] = $ligne2['nomequipe'];
				$_SESSION['tabClassement'][$nbDebut][$i+1]['poule'] = $ligne2['idpoule'];
				$_SESSION['tabClassement'][$nbDebut][$i+1]['nbVictoire'] = $ligne2['nbvictoire'];
				
				$i = $i +2;
			}
			$_SESSION['tabClassement'][$nbDebut]['nbEq'] = $i;
			
			for($i = $nbDebut+1 ;$i <$val;$i++)
			{			
				$_SESSION['tabClassement'][$i] = genererMatchs2(($i+1),$idTournoi,$nbPoules);
			}


			for($i = 0;$i < 5;$i++)
			{
				if(isset($_SESSION['tabClassement'][$i]))
				{
					for($j = 0;$j < $_SESSION['tabClassement'][$i]['nbEq'];$j++)
					{
						$resultat = mysql_query("select * from poule where idtournoi = '".$idTournoi."' AND idpoule = '".$_SESSION['tabClassement'][$i][$j]['poule']."'; ");
						$ligne = mysql_fetch_array($resultat);
						
						$idPoule = $ligne['nompoule']; //On sauvegarde le nom de la poule
						$numPoule = substr($idPoule,5,strlen($idPoule)); //Puis on extrait le nombre après le 'Poule '
						$_SESSION['tabClassement'][$i][$j]['indicePoule'] = $numPoule;
						
					}
				}
			}
		}
					
		unset($_SESSION['tabPodium']);
				
		$resultat = mysql_query("select * from matchs WHERE idtournoi = '".$idTournoi."' AND idtypematch = 5 AND estfini;");	
		
		if(mysql_num_rows($resultat) > 0)
		{
			if($ligne = mysql_fetch_array($resultat))
			{		
				if($ligne['score1'] > $ligne['score2'])
				{
					$tabPodium[0] = $ligne['idequipe1'];
					$tabPodium[1] = $ligne['idequipe2'];
				}else
				{
					$tabPodium[0] = $ligne['idequipe2'];
					$tabPodium[1] = $ligne['idequipe1'];
				}

					
					$resultat = mysql_query("select * from equipe WHERE idequipe = '".$tabPodium[0]."' AND idtournoi = '".$idTournoi."' ;");
					$ligne = mysql_fetch_array($resultat);
					$_SESSION['tabPodium'][0] = $ligne['nomequipe'];
					
					$resultat = mysql_query("select * from equipe WHERE idequipe = '".$tabPodium[1]."' AND idtournoi = '".$idTournoi."' ;");
					$ligne = mysql_fetch_array($resultat);
					$_SESSION['tabPodium'][1] = $ligne['nomequipe'];
			}
			
			
			$resultat = mysql_query("select * from matchs WHERE idtournoi = '".$idTournoi."' AND idtypematch = 4 AND estfini;");		
			$i = 0;
			
			
				while($ligne = mysql_fetch_array($resultat))
				{		
					
						if(	($ligne['idequipe1'] != $tabPodium[0] && $ligne['idequipe1'] != $tabPodium[1]))
						{
							$tabTroisiemes[$i] = $ligne['idequipe1'];
							$i++;
						}
						if(	($ligne['idequipe2'] != $tabPodium[0] && $ligne['idequipe2'] != $tabPodium[1]))
						{
							$tabTroisiemes[$i] = $ligne['idequipe2'];
							$i++;
						}
					
				}
				$nb = $i;
				
				//ici , il y a forcement 2 champs
				for($i = 0;$i <$nb; $i = $i+2)
				{
					$resultat = mysql_query("select * from equipe WHERE idequipe = '".$tabTroisiemes[$i]."' ;");
					$ligne = mysql_fetch_array($resultat);
					
					$resultat2 = mysql_query("select * from equipe WHERE idequipe = '".$tabTroisiemes[$i+1]."' ;");
					$ligne2 = mysql_fetch_array($resultat2);
					
					if($ligne['nbvictoire'] > $ligne2['nbvictoire'])			
						$_SESSION['tabPodium'][2] = $ligne['nomequipe'];
					if($ligne['nbvictoire'] < $ligne2['nbvictoire'])	
						$_SESSION['tabPodium'][2] = $ligne2['nomequipe'];
					if($ligne['nbvictoire'] == $ligne2['nbvictoire'])
					{
						if(totalPoints($ligne['idequipe'],$idTournoi) >= totalPoints($ligne2['idequipe'],$idTournoi))
							$_SESSION['tabPodium'][2] = $ligne['nomequipe'];
						else
							$_SESSION['tabPodium'][2] = $ligne2['nomequipe'];
							
					}
					
				}
				
						
				echo '<center><img SRC="arbre3.php" /></center>';
		}
		
		echo '<img SRC="podium.php" alt="OMG"/>';
	
 	echo '</div>';
		include("pied_de_page2.php"); ?>
	</body>
</html>
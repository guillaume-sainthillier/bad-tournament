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
	
	$idT=$_SESSION['idtournoi'];
	$_SESSION['generer']=false;
	$test=mysql_query("SELECT COUNT(*) as 'nb' FROM poule WHERE idtournoi=$idT");
	$count=mysql_fetch_array($test);
	
	if(isset($_POST['generer']) && $count['nb']==0){
		$requete=mysql_query("SELECT nbpoule,nbjoueurparpoule FROM tournoi WHERE idtournoi=$idT");
		$table=mysql_fetch_array($requete);
		
		$requete2=mysql_query("SELECT COUNT(*) AS 'nbj' FROM equipe WHERE idtournoi=$idT");
		$table2=mysql_fetch_array($requete2);
		$requete3=mysql_query("SELECT idequipe FROM equipe WHERE idtournoi=$idT");
		$i=0;
		$idE = array();
		while($table3=mysql_fetch_array($requete3)){
			$idE[$i]=$table3['idequipe'];
			$i++;
		}
		$nbp=$table['nbpoule'];
		$nbjp=$table['nbjoueurparpoule'];
		$nbj=$table2['nbj'];
	
			if($nbp==null || $nbp == 0){//*******************OK***********************
				shuffle($idE);
				if($nbjp != 0)
					$nbp=ceil($nbj/$nbjp);//nombre de poule
				else
					$nbjp = 0;
				if($nbp>=4){
				$val=$nbjp;//nombre de joueur des nbSup poules
				$val3=$nbjp-1;//nombre de joueur des nbInf poules
				$nbInf=$nbjp*$nbp-$nbj;//nombre de poule de $val joueurs
				$nbSup=$nbp-$nbInf;//nb de poule de $val3 joueurs
				
				if($nbSup<0){
					$var=$nbp*$nbjp-$nbj;
					$val3=$nbjp-$var;
					$val=$nbjp;
					if($val3<$val/2){
						$val3=$nbjp+$var;
						$val=$nbjp;
					
						$nbSup=$nbp-2;
						$nbInf=1;
					}else{
						
					$nbSup=$nbp-1;
					$nbInf=1;
					}
				}
				}else{							
						$var=$nbp*$nbjp-$nbj;
						$val3=$nbjp-$var;
						$val=$nbjp;
						if($val3<$val/2){
							$val3=$nbjp+$var;
							$val=$nbjp;
						
							$nbSup=$nbp-2;
							$nbInf=1;
						}else{
						
						$nbSup=$nbp-1;
						$nbInf=1;}
				}
				
				
			}else{
				if($nbjp==null  || $nbjp == 0){
					shuffle($idE);
					$nbjp=ceil($nbj/$nbp);//nombre de joueur par poule
					if($nbjp>=4){
						$val=$nbjp;//nombre de joueur des nbSup poules
						$val3=$nbjp-1;//nombre de joueur des nbInf poules
						$nbInf=$nbjp*$nbp-$nbj;//nombre de poule de $val joueurs
						$nbSup=$nbp-$nbInf;//nb de poule de $val3 joueurs
					}else{	
						$mod = 0;
						if($mod<=1){
							$val=$nbjp-1;//nombre de joueur par poule sauf la derniere
							$val2=($nbp-1)*$val;//nb de joueur total des premieres poules
							$val3=$nbj-$val2;//nombre de joueurs de la derniere poule
							
							if($mod==0){
							$nbInf=0;
							$nbSup=$nbp;
							}else{
							$nbInf=1;
							$nbSup=$nbp-1;
								
							}
						}else{
							$val2=$nbj-$mod;
							$val3=$nbj-$val2;
							$val=($nbj-$val3)/$nbp;
								
							$nbInf=1;
							$nbSup=$nbp;
						}
						
					}
						
									
				}
					
			}
			$k=0;
				for($i=0;$i<$nbSup;$i++){
						$nompoule='poule '.(string)($i+1);
						mysql_query("INSERT INTO poule VALUES ('','$nompoule','$val','0','$idT')");
						$requete4=mysql_query("SELECT idpoule FROM poule WHERE nompoule='$nompoule' and idtournoi='$idT'");
						$lastid=mysql_fetch_array($requete4);
						$lid=$lastid['idpoule'];
						for($j=0;$j<$val;$j++){
							$id=$idE[$k];
							mysql_query("UPDATE equipe SET idpoule=$lid WHERE  idequipe=$id");
							$k++;
						}
				}
				for($m=0;$m<$nbInf;$m++){	
					$nompoule='poule '.(string)($i+$m+1);
					mysql_query("INSERT INTO poule VALUES ('','$nompoule','$val3','0','$idT')");
					$requete4=mysql_query("SELECT idpoule FROM poule WHERE nompoule='$nompoule' and idtournoi='$idT'");
					$lastid=mysql_fetch_array($requete4);
					$lid=$lastid['idpoule'];
					for($l=0;$l<$val3;$l++){
						$id=$idE[$k];
						mysql_query("UPDATE equipe SET idpoule=$lid WHERE idequipe=$id");
						$k++;
									
					}
						
				}
				$_SESSION['generer']=true;
				$query=mysql_query("UPDATE tournoi SET estGenere='1' where idtournoi='".$_SESSION['idtournoi']."'");
	
	}
	$test=mysql_query("SELECT COUNT(*) as 'nb' FROM poule WHERE idtournoi=$idT");
	$count=mysql_fetch_array($test);
	if($count['nb']==0){
		
		?>
		
	
	<div id="contenu">
		<form method='post' action='genererpoules.php'>
			<input type='hidden' name='generer'/>
			<input type='submit' value='Générer'/>
			
		</form>
		
		<form method='post' action='genererpoules.php'>
			<input type='submit' name='raz' value='RAZ'/>
		</form>
	</div>
	<?php
	}else{
	
	echo '<div id="contenu">';
	if($_SESSION['generer']==true){
		echo "Poules générées";
	}
	echo	"<form method='post' action='genererpoules.php'>";
	echo	"<input type='submit' value='Générer' disabled />";
	echo	"</form><br/>";
	echo "<form method='post' action='genererpoules.php'>
			<input type='submit' name='raz' value='RAZ'/>
		</form>";
	if(!isset($_POST['generer'])){
		echo "Les poules ont déjà été générées";
	}
	echo "</div>";
	}
	
	if(isset($_POST['raz'])){
		mysql_query("UPDATE equipe SET idpoule=NULL");
		mysql_query("DELETE FROM poule");
		$_SESSION['generer']=false;
		
	}
	include("pied_de_page.php");
	?>
	</body>

</html>

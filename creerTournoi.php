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
		
   
				function estFormulaireValide(f)
				{
					var retour=false;
					var e2  = document.getElementById('radio1');
				    var e3  = document.getElementById('radio2');
					if(f.nomTournoi.value=="")
						alert("Le nom du tournoi n'est pas rempli");
					else
						if(isNaN(f.nbTerrain.value))	
								alert("Le nombre de terrain doit être composé uniquement de chiffre");
						else						
							if(f.nbTerrain.value=="")							
								alert("Le nombre de terrains n'est pas rempli");							
							else
								if(f.nbTerrain.value<=0)
									alert("Le nombre de terrains doit être strictement positif");
								else
									if(e2.checked && f.nbPoule2.value=="")
										alert("Le nombre de poules n'est pas rempli");
									else
										if(e2.checked && isNaN(f.nbPoule2.value))
												alert("Le nombre de poules n'est pas un nombre");
										else
											if(e2.checked && f.nbPoule2.value<=0)
												alert("Le nombre de poules doit être strictement positif");
										else
											if(e3.checked && f.nbMec2.value=="")
												alert("Le nombre de participants par poule n'est pas rempli");
											else
												if(e3.checked && isNaN(f.nbMec2.value))
													alert("Le nombre de participants par poule n'est pas un nombre");
												else
													if(e3.checked && f.nbMec2.value<=0)
														alert("Le nombre de participants par poule doit être strictement positif");
													else
														retour=true;	
					
					return retour;
				}
				
				function afficher()
				{
				   var e   = document.getElementById('blockCache');
				   var e1  = document.getElementById('blockCache2');
				   var e2  = document.getElementById('radio1');
				   var e3  = document.getElementById('radio2');

		
					if(e2.checked)
						e.style.display = 'block';
					else
						e.style.display = 'none';
					if(e3.checked)
						e1.style.display = 'block';
					else
						e1.style.display = 'none';
						
				}

			</script>

   </head>
   <body>
   	
   		<?php include("banniere.php"); 

			include("menu.php"); ?>

	<div id="contenu">
	<?php
		
		if(!(isset($_POST['nomtournoi']) || isset($_POST['creerTournoi'])))
		{
			echo "<form action='creerTournoi.php' id='formulaireTournoi' method='post' onSubmit='return estFormulaireValide(this)'>";
			
			
			echo "<label id='nomTournoi'>Nom du tournoi : <br/></label><input type='text' name='nomTournoi' id='nomTournoi' /><br/><br/>";
			
			echo "Type de catégorie :<br/>";
			$result= mysql_query("select * from categorie ;");
				
				
				echo '<select name="creerTournoi" size ="1">';
				
				while($ligne = mysql_fetch_array($result))
					echo "<option>".$ligne['nomcat'];
				

				echo"</select>";
				
				
			echo "<br/><br/>";
			echo "<label id='nbTerrain'>Nombre de terrains : <br/></label><input type='text' size='2' maxlength='2' name='nbTerrain' id='nbTerrain' /><br/>";
			
			
			echo "<br >";
			echo '<fieldset>';
				echo '<LEGEND align=top>Choix des modalités</LEGEND>';
				echo'<input name="choix" id="radio1" type="radio" onclick="afficher()" value="nbPoule" checked="checked" />Nombre de poules';
				
				echo'<div id="blockCache" >';
					echo "<input type='text' size='2' maxlength='2' name='nbPoule2'/>";
				echo "</div>";
				echo "<br/>";
				echo'<input name="choix" id="radio2" type="radio" onclick="afficher()" value="nbMec" />Nombre de participants par poule';
			
				echo'<div id="blockCache2" style="display: none" >';
					echo "<input type='text' size='2' maxlength='2'  name='nbMec2'/>";
				echo "</div>";
			echo '</fieldset>';
			
			echo'<br/><input type ="submit" value="Creer"/>';
			echo '</form>';
		
		}else
		{
		
			$requete = "select * from categorie WHERE nomcat = '$_POST[creerTournoi]' ;";

			//Select * from categorie where nomcat = "simple homme(exemple)";
			
			$cat = mysql_query($requete);
			$ligne = mysql_fetch_array($cat);
			
			
			if($_POST['choix'] == "nbPoule")
				$requete ="insert into tournoi(nomtournoi,nbterrain,idcat,nbpoule) values('$_POST[nomTournoi]','$_POST[nbTerrain]','$ligne[idcat]','$_POST[nbPoule2]');";
			if($_POST['choix'] == "nbMec")
				$requete ="insert into tournoi(nomtournoi,nbterrain,idcat,nbjoueurparpoule) values('$_POST[nomTournoi]','$_POST[nbTerrain]','$ligne[idcat]','$_POST[nbMec2]');";

			
			//insert into tournoi(nomtournoi,nbterrain,idcat) values('nomTournoi','nbTerrain','idcat');
													   
			if(!mysql_query($requete))
				 die('Erreur dans la création du tournoi.<br/>Erreur: ' . mysql_error());

			$nomT = $_POST['nomTournoi'];
			$requete2 = "select * from tournoi where nomtournoi = '$nomT' order by nomtournoi desc;";
			$var = mysql_query($requete2);
			$ligne2 = mysql_fetch_array($var);
			
			
			echo "Envoi de ".$ligne2['idtournoi']." à la session";
			$_SESSION['idtournoi'] = $ligne2['idtournoi'];
			
			
	
			
			header('Location: accueil.php');   

			
		}
			
			
			
		
	  ?>
	  </div>

	<?php include("pied_de_page.php"); ?>
	</body>
</html>
  
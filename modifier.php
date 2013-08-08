<?php
session_start();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Creer un tournoi</title>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	   <link rel="stylesheet" media="screen" type="text/css" title="design" href="style.css" />
		
	    <script langage="javascript">
		
   
				function estFormulaireValide(f)
				{
					var retour=false;
					var e2  = document.getElementById('radio1');
				    var e3  = document.getElementById('radio2');
					if(f.nomtournoi.value=="")
						alert("Le nom du tournoi n'est pas rempli");
					else
						if(isNaN(f.nbterrain.value))	
								alert("Le nombre de terrain doit être composé uniquement de chiffre");
						else						
							if(f.nbterrain.value=="")							
								alert("Le nombre de terrains n'est pas rempli");							
							else
								if(f.nbterrain.value<=0)
									alert("Le nombre de terrains doit être strictement positif");
								else
									if(e2.checked && f.nbpoule.value=="")
										alert("Le nombre de poules n'est pas rempli");
									else
										if(e2.checked && isNaN(f.nbpoule.value))
												alert("Le nombre de poules n'est pas un nombre");
										else
											if(e2.checked && f.nbpoule.value<=0)
												alert("Le nombre de poules doit être strictement positif");
										else
											if(e3.checked && f.nbmec.value=="")
												alert("Le nombre de participants par poule n'est pas rempli");
											else
												if(e3.checked && isNaN(f.nbmec.value))
													alert("Le nombre de participants par poule n'est pas un nombre");
												else
													if(e3.checked && f.nbmec.value<=0)
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
   	
		<?php
		include("connection.php");
		?>
   		<?php include("banniere.php"); 
		include("menu.php"); ?>

	<div id="contenu">
		<?php 
	$query = mysql_query("SELECT estGenere from tournoi where idtournoi='".$_SESSION['idtournoi']."'");
	$testG = mysql_fetch_array($query);
	
	if(isset($_SESSION['idtournoi']) && $testG['estGenere']==0)
	{
	?>
	<?php
	
	if((isset($_POST['nomtournoi']) && isset($_POST['nomcat']) && isset($_POST['nbterrain']) && isset($_POST['nbpoule'])) || (isset($_POST['nomtournoi']) && isset($_POST['nomcat']) && isset($_POST['nbterrain']) && isset($_POST['nbmec'])) )
	{
		$query1=mysql_query("SELECT idcat from categorie WHERE nomcat='".$_POST['nomcat']."'");
		$result=mysql_fetch_array($query1);
		
		if(($_POST['nbmec']>0 && $_POST['nbpoule']>0) || ($_POST['nbmec']==0 && $_POST['nbpoule']==0) || (empty($_POST['nbmec']) && empty($_POST['nbpoule'])) )
		{
			echo "<b><center>Erreur, vous devez entrer un nombre de poules OU un nombre de joueurs par poule !</b></center>";
		}
		else
		{
			$query=mysql_query("UPDATE tournoi SET nomtournoi='".$_POST['nomtournoi']."', idcat='".$result['idcat']."', nbterrain='".$_POST['nbterrain']."', nbjoueurparpoule='".$_POST['nbmec']."', nbpoule='".$_POST['nbpoule']."'  WHERE idtournoi='".$_SESSION['idtournoi']."'");
			
			echo "<b><center>Mise à jour effectuée !</b></center>";
		}
		
	}
		$query = mysql_query("select t.nomtournoi, t.nbpoule, t.nbjoueurparpoule, t.nbterrain, c.nomcat from tournoi t, categorie c WHERE t.idtournoi = '".$_SESSION['idtournoi']."' && c.idcat=t.idcat");
		$row = mysql_fetch_array($query);
		
		?>
		<form action='modifier.php' name='modif' id='modif' method='post' onSubmit='return estFormulaireValide(this)'>
			
		<label id='nomtournoi'>Nom du tournoi : <br/></label><input type='text' name='nomtournoi' id='nomtournoi' value='<?php  echo $row['nomtournoi'];?>'/><br/><br/>
			
		Type de catégorie :<br/>
		<?php
			$result=mysql_query("select * from categorie ;");
				
				
				echo "<select name='nomcat' id='nomcat' size ='1'>";
				
				while($ligne = mysql_fetch_array($result))
				{
					if($row['nomcat']==$ligne['nomcat'])
					{
						echo "<option selected='selected'>".$ligne['nomcat']."</option>";
					}
					else
					{
						echo "<option>".$ligne['nomcat']."</option>";
					}
				}

				echo"</select>";?>
				
				
			<br/><br/>
			<label id='nbterrain'>Nombre de terrains : <br/></label><input type='text' size='2' maxlength='2' name='nbterrain' id='nbterrain' value='<?php echo $row['nbterrain'];?>' /><br/>
			
			
			<br >
			<fieldset>
			<LEGEND align=top>Choix des modalités</LEGEND>
			<input name='choix' id='radio1' type='radio' onclick='afficher()' value='nbpoule' <?php if($row['nbpoule']>0)
			{
				echo " checked='checked' ";
			}
			?>/>Nombre de poules
				
			<div id="blockCache"  <?php if($row['nbpoule']>0)
			{
				echo " style='display: block' ";
			}
			else
			{
				echo " style='display: none' ";
			}?>>
			<input type='text' size='2' maxlength='2' name='nbpoule' value='<?php echo $row['nbpoule'];?>'/>
			</div>
			<br/>
			<input name='choix' id='radio2' type='radio' onclick='afficher()' value='nbMec' <?php if($row['nbjoueurparpoule']>0)
			{
				echo " checked='checked' ";
			}
			?>/>Nombre de participants par poule
			
			<div id='blockCache2' <?php if($row['nbjoueurparpoule']>0)
			{
				echo " style='display: block' ";
			}
			else
			{
				echo " style='display: none' ";
			}?>>
			<input type='text' size='2' maxlength='2'  name='nbmec' value='<?php echo $row['nbjoueurparpoule']; ?>'/>
			</div>
			</fieldset>
			
			<br/><input type ='submit' value='Modifier'>
			</form>
	<?php 
	//FIN BOUCLE vérif idtournoi & génération poule
	}
	else
	{
		if(isset($_SESSION['idtournoi']) && $testG['estGenere']==1)
		{
			echo "Vous ne pouvez plus modifier le tournoi car les poules ont été générés";
		}
		
		if(!isset($_SESSION['idtournoi']))
		{
			echo "Vous n'avez pas le droit d'accéder à cette page sans avoir chargé de tournoi";
		}
	}
	?>
	  </div>

	<?php include("pied_de_page.php"); ?>
	</body>
</html>
  
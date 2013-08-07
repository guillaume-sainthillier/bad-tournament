<?php
SESSION_START();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>BadTournament - IUT Blagnac 2011</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link rel="icon" href="favicon.ico"/>

<script language="javascript">
		
				function estFormulaireValide(f)
				{
					var retour=false;
					if(f.nomequipe.value=="")
						alert("Le nom de l'équipe n'est pas rempli");
					else						
						if(f.nomparticipant1.value=="")							
							alert("Le nom du participant 1 n'est pas rempli");							
							else
								if(f.prenomparticipant1.value=="")
									alert("Le prénom du participant 1 n'est pas rempli");
								else
									if(f.nomparticipant2.value=="")
									alert("Le nom du participant 2 n'est pas rempli");
								else
									if(f.prenomparticipant2.value=="")
									alert("Le prénom du participant 2 n'est pas rempli");
									else
									retour=true;
					return retour;
				}

			</script>

</head>
<body>

<!-- CONNEXION A LA BASE DE DONNEES -->
	<?php include("connection.php"); ?>
	
<!-- Bannière-->
	<?php include("banniere.php"); ?>

<!-- Menu -->
	<?php include("menu.php"); ?>
		
<!-- Contenu -->
	<div id="contenu">
	
	<?php 
	$query = mysql_query("SELECT estGenere from tournoi where idtournoi='".$_SESSION['idtournoi']."'");
	$testG = mysql_fetch_array($query);
	
	if(isset($_SESSION['idtournoi']) && $testG['estGenere']==0)
	{
	?>
		<!-- on récupère l'id du tournoi et sa catégorie -->
			<?php			

				//on récupère l'id du tournoi qui est dans notre variable de session
				$tournoi = $_SESSION['idtournoi'];
				//on récupère la catégorie du tournoi
				$query=mysql_query("select idcat from tournoi where idtournoi='".$tournoi."'") OR DIE (mysql_error());
				$result = mysql_fetch_array($query);
				$cat = $result['idcat'];
			?>
			

			<br/>
			<center>
			<h2>Inscription d'une équipe</h2>
			</center>
			
			<fieldset id="field_inscr_equipe">
			<?php
				
				$existeequipe=false;
				$existeparticipant=false;
					if($cat=='cat1' || $cat=='cat2')
					{
						if(isset($_POST['nomequipe']) && isset($_POST['nomparticipant1']) && isset($_POST['prenomparticipant1']))
						{
							if($_POST['nomequipe']!=null && $_POST['nomparticipant1']!=null && $_POST['prenomparticipant1']!=null)
							{
								//VERIFICATION de l'existence des participants et de l'équipe
								$existeequipe=false;
								$existeparticipant=false;
								
								$nomequipe=mysql_query("SELECT idequipe,nomequipe from equipe where idtournoi='".$_SESSION['idtournoi']."'") OR DIE (mysql_error());
								
								while(($row=mysql_fetch_array($nomequipe)) AND $existeequipe==false AND $existeparticipant==false)
								{
									if($row['nomequipe']==$_POST['nomequipe'])
									{
										$existeequipe=true;
									}
									else
									{
										$nom=mysql_query("SELECT nomparticipant, prenomparticipant from participant where idequipe='".$row['idequipe']."'") OR DIE (mysql_error());
										
										while(($row2=mysql_fetch_array($nom)) AND $existeparticipant==false AND $existeequipe==false)
										{
											if(($row2['nomparticipant']==$_POST['nomparticipant1']) && ($row2['prenomparticipant']==$_POST['prenomparticipant1']))
											{
												$existeparticipant=true;
											}
										}
									}
								}
								
								if($existeparticipant==false && $existeequipe==false)
								{
								
									//on insère l'équipe dans notre base de données
									$query=mysql_query("INSERT INTO equipe(nomequipe,nbdefaite,nbvictoire, idtournoi) VALUES ('".$_POST['nomequipe']."','0','0','".$tournoi."')") OR DIE (mysql_error());
									
									//on récupère l'id de l'équipe ajoutée
									$query1=mysql_query("SELECT idequipe from equipe where nomequipe='".$_POST['nomequipe']."'") OR DIE (mysql_error());
									$queryy = mysql_fetch_array($query1);
									$ideq = $queryy['idequipe'];
									
									//Si c'est un simple homme
									if($cat=='cat1')
									{
									$query2=mysql_query("INSERT INTO participant(nomparticipant,prenomparticipant,sexeparticipant,idequipe) VALUES ('".$_POST['nomparticipant1']."','".$_POST['prenomparticipant1']."','h','".$ideq."')") OR DIE (mysql_error());
									$_SESSION['cat']='simple';
									}
									else
									//Si c'est un simple femme
									{
									$query2=mysql_query("INSERT INTO participant(nomparticipant,prenomparticipant,sexeparticipant,idequipe) VALUES ('".$_POST['nomparticipant1']."','".$_POST['prenomparticipant1']."','f','".$ideq."')") OR DIE (mysql_error());
									$_SESSION['cat']='simple';
									}
									echo "L'équipe \"".$_POST['nomequipe']."\" <br/>a bien été ajoutée.<br/>";
									echo "<a href='inscrire_equipe.php'>Inscrire une autre équipe</a>";
								}
							}
						}
					}
					else
					{
						if($cat=='cat3' || $cat=='cat4' || $cat=='cat5')
						{
							if(isset($_POST['nomequipe']) && isset($_POST['nomparticipant1']) && isset($_POST['prenomparticipant1']) && isset($_POST['nomparticipant2']) && isset($_POST['prenomparticipant2']))
							{
								if($_POST['nomequipe']!=null && $_POST['nomparticipant1']!=null && $_POST['prenomparticipant1']!=null && $_POST['nomparticipant2']!=null && $_POST['prenomparticipant2']!=null)
								{
									//VERIFICATION de l'existence des participants et de l'équipe
									$existeequipe=false;
									$existeparticipant=false;
									
									$nomequipe=mysql_query("SELECT idequipe,nomequipe from equipe where idtournoi='".$_SESSION['idtournoi']."'") OR DIE (mysql_error());
									
									while(($row=mysql_fetch_array($nomequipe)) AND $existeequipe==false AND $existeparticipant==false)
									{
										if($row['nomequipe']==$_POST['nomequipe'])
										{
											$existeequipe=true;
										}
										else
										{
											$nom=mysql_query("SELECT nomparticipant, prenomparticipant from participant where idequipe='".$row['idequipe']."'") OR DIE (mysql_error());
											
											while(($row2=mysql_fetch_array($nom)) AND $existeparticipant==false AND $existeequipe==false)
											{
												if(($row2['nomparticipant']==$_POST['nomparticipant1']) && ($row2['prenomparticipant']==$_POST['prenomparticipant1']) || (($row2['nomparticipant']==$_POST['nomparticipant2']) && ($row2['prenomparticipant']==$_POST['prenomparticipant2'])))
												{
													$existeparticipant=true;
												}
											}
										}
									}
									
									if($existeparticipant==false && $existeequipe==false)
									{
										//on insère l'équipe dans notre base de données
										$query=mysql_query("INSERT INTO equipe(nomequipe,nbdefaite,nbvictoire, idtournoi) VALUES ('".$_POST['nomequipe']."','0','0','".$tournoi."')") OR DIE (mysql_error());
										
										//on récupère l'id de l'équipe ajoutée
										$query1=mysql_query("SELECT idequipe from equipe where nomequipe='".$_POST['nomequipe']."'") OR DIE (mysql_error());
										$queryy = mysql_fetch_array($query1);
										$ideq = $queryy['idequipe'];
										
										//Si c'est un double homme
										if($cat=='cat3')
										{
											$query2=mysql_query("INSERT INTO participant(nomparticipant,prenomparticipant,sexeparticipant,idequipe) VALUES ('".$_POST['nomparticipant1']."','".$_POST['prenomparticipant1']."','h','".$ideq."')") OR DIE (mysql_error());
											$query3=mysql_query("INSERT INTO participant(nomparticipant,prenomparticipant,sexeparticipant,idequipe) VALUES ('".$_POST['nomparticipant2']."','".$_POST['prenomparticipant2']."','h','".$ideq."')") OR DIE (mysql_error());
											$_SESSION['cat']='double';
										}
										else
										{
											//Si c'est un double femme
											if($cat=='cat4')
											{
												$query2=mysql_query("INSERT INTO participant(nomparticipant,prenomparticipant,sexeparticipant,idequipe) VALUES ('".$_POST['nomparticipant1']."','".$_POST['prenomparticipant1']."','f','".$ideq."')") OR DIE (mysql_error());
												$query3=mysql_query("INSERT INTO participant(nomparticipant,prenomparticipant,sexeparticipant,idequipe) VALUES ('".$_POST['nomparticipant2']."','".$_POST['prenomparticipant2']."','f','".$ideq."')") OR DIE (mysql_error());
												$_SESSION['cat']='double';
											}	
											else
											{
												if($cat=='cat5')
												{
													$query2=mysql_query("INSERT INTO participant(nomparticipant,prenomparticipant,sexeparticipant,idequipe) VALUES ('".$_POST['nomparticipant1']."','".$_POST['prenomparticipant1']."','f','".$ideq."')") OR DIE (mysql_error());
													$query3=mysql_query("INSERT INTO participant(nomparticipant,prenomparticipant,sexeparticipant,idequipe) VALUES ('".$_POST['nomparticipant2']."','".$_POST['prenomparticipant2']."','h','".$ideq."')") OR DIE (mysql_error());
													$_SESSION['cat']='double';
												}
											}
										}
										echo "L'équipe \"".$_POST['nomequipe']."\" <br/>a bien été ajoutée.<br/>";
										echo "<a href='inscrire_equipe.php'>Inscrire une autre équipe</a>";
									}
								}
							}
						}
					}
					if($existeparticipant==true)
					{
						echo "Ce participant existe déjà";
					}
					else
					{
						if($existeequipe==true)
						{
							echo "Cette équipe existe déjà";
						}
					}
				?>
			<?php
		
			if(isset($_SESSION['cat']))
			{
				if(($_SESSION['cat']=='simple' && (empty($_POST['nomequipe']) || empty($_POST['nomparticipant1']) || empty($_POST['prenomparticipant1']))) || ($_SESSION['cat']=='double' && (empty($_POST['nomequipe']) || empty($_POST['nomparticipant1']) || empty($_POST['prenomparticipant1']) && empty($_POST['prenomparticipant2']) || empty($_POST['nomparticipant2']))) || $existeparticipant==true || $existeequipe==true)
				{		
				?>
				
					<form action="inscrire_equipe.php" id="formuvisite" method="post" onSubmit='return estFormulaireValide(this)'>
				
					<!-- Nom de l'équipe -->
					<label id='nom'>Nom de l'équipe : <br/></label><input type='text' name='nomequipe' id='nomequipe' 
					<?php if (!empty($_POST['nomequipe'])){
						echo " value='".$_POST['nomequipe']."'";}?>/>
					<br/>
				
					<!-- Nom du participant 1 -->	
					<br/>		
					<?php if($cat=='cat5' || $cat=='cat4' || $cat=='cat2')
					{
						echo "<img style='margin-left:50px;' src='images/femme.png' alt='femme'/>";
					}
					else
					{
						echo "<img style='margin-left:50px;' src='images/homme.png' alt='homme'/>";
					}
					?><br/>
					
					<label id='nomparticipant1'>Nom du participant 1 :</label>
					<br/>
					<input type='text' name='nomparticipant1' id='nomparticipant1'
					<?php if (!empty($_POST['nomparticipant1'])){
						echo "value='".$_POST['nomparticipant1']."'";}
					?>/>
					
					<br/>
					
					<!-- prénom du participant 1 -->
					<label id='prenomparticipant1'>Prénom du participant 1 : </label>
					<br/>
					<input type='text' name='prenomparticipant1' id='prenomparticipant1'
					<?php if (!empty($_POST['prenomparticipant1'])){
						echo "value='".$_POST['prenomparticipant1']."'";}?>/>
					
					<br/>
					
					<!-- s'il s'agit d'une catégorie de double -->
					<?php 
					if($cat=='cat3' || $cat=='cat4' || $cat=='cat5')
					{?><br/>
					
					<?php if($cat=='cat5' || $cat=='cat3')
					{
						echo "<img style='margin-left:50px;' src='images/homme.png' alt='homme'/>";
					}
					else
					{
						if($cat=='cat4')
						{
							echo "<img style='margin-left:50px;' src='images/femme.png' alt='femme'/>";
						}
					}?>		
					<br/>
					<!-- Nom du participant 2 -->
					<label id='nomparticipant2'>Nom du participant 2 : </label>
					<br/>
					<input type='text' name='nomparticipant2' id='nomparticipant2'
					<?php if (!empty($_POST['nomparticipant2'])){
						echo "value='".$_POST['nomparticipant2']."'";} ?>/>
					<br/>
						
					<!-- prénom du participant 2 -->
					<label id='prenomparticipant2'>Prénom du participant 2 : </label>
					<br/>
					<input type='text' name='prenomparticipant2' id='prenomparticipant2'
					<?php if (!empty($_POST['prenomparticipant2'])){
						echo "value='".$_POST['prenomparticipant2']."'/>";}
					?>
					<br/>
					<?php
					}?>
					
					<br/>
					<!--BOUTON VALIDER-->
					<center><input type ="submit"></center>
					</form>
					<?php
				}
			}
			else
			{	 
			?>
				<form action="inscrire_equipe.php" id="formuvisite" method="post" onSubmit='return estFormulaireValide(this)'>
				
				<!-- Nom de l'équipe -->
					<label id='nom'>Nom de l'équipe : <br/></label><input type='text' name='nomequipe' id='nomequipe' 
					<?php if (!empty($_POST['nomequipe']))
					{
						echo "value='".$_POST['nomequipe']."'/>";
					}
					?>
					<br/>
				<!-- Nom du participant 1 -->	
					<br/>		
					<?php if($cat=='cat5' || $cat=='cat4' || $cat=='cat2')
					{
						echo "<img src='images/femme.png' alt='femme'/> - Femme";
					}
					else
					{
						echo "<img src='images/homme.png' alt='homme'/> - Homme";
					}
					?><br/>
					
					<label id='nomparticipant1'>Nom du participant 1 :</label>
					<br/>
					<input type='text' name='nomparticipant1' id='nomparticipant1'
					<?php if (!empty($_POST['nomparticipant1'])){
						echo "value='".$_POST['nomparticipant1']."'";}
					?>
					/>
					<br/>
					
				<!-- prénom du participant 1 -->
					<label id='prenomparticipant1'>Prénom du participant 1 : </label>
					<br/>
					<input type='text' name='prenomparticipant1' id='prenomparticipant1'
					<?php if (!empty($_POST['prenomparticipant1'])){
						echo "value='".$_POST['prenomparticipant1']."'";}?>
					/>
					<br/>
					
				<!-- s'il s'agit d'une catégorie de double -->
					<?php 
					if($cat=='cat3' || $cat=='cat4' || $cat=='cat5')
					{?><br/>
					
					<?php if($cat=='cat5' || $cat=='cat3')
					{
						echo "<img src='images/homme.png' alt='homme'/> - Homme";
					}
					else
					{
						if($cat=='cat4')
						{
							echo "<img src='images/femme.png' alt='femme'/> - Femme";
						}
					}?>		
					<br/>
					<!-- Nom du participant 2 -->
					<label id='nomparticipant2'>Nom du participant 2 : </label>
					<br/>
					<input type='text' name='nomparticipant2' id='nomparticipant2'
					<?php if (!empty($_POST['nomparticipant2'])){
						echo "value='".$_POST['nomparticipant2']."'/>";} ?>
						<br/>

						
					<!-- prénom du participant 2 -->
					<label id='prenomparticipant2'>Prénom du participant 2 : </label>
					<br/>
					<input type='text' name='prenomparticipant2' id='prenomparticipant2'
					<?php if (!empty($_POST['prenomparticipant2'])){
						echo "value='".$_POST['prenomparticipant2']."'";}
					?>
						/>
					<br/>
					<?php
					}?>
					
					<br/>
					<!--BOUTON VALIDER-->
					<center><input type ="submit"></center>
				</form>
			<?php
			}
			?>	
				
				<br/>
				</fieldset><br/><br/>
	<?php 
	//FIN BOUCLE vérif idtournoi & génération poule
	}
	else
	{
		if(isset($_SESSION['idtournoi']) && $testG['estGenere']==1)
		{
			echo "Vous ne pouvez plus écrire d'équipe car les poules ont été générées";
		}
		
		if(!isset($_SESSION['idtournoi']))
		{
			echo "Vous n'avez pas le droit d'accéder à cette page sans avoir chargé de tournoi";
		}
	}
	?>
	</div>

	<?php include("pied_de_page.php"); ?></body>

</html>

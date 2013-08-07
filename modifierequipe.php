<?php
session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BadTournament - IUT Blagnac 2011</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link rel="icon" href="favicon.ico"/>

<script langage="javascript">
		
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
									if(f.nomparticipant0.value=="")
									alert("Le nom du participant 2 n'est pas rempli");
								else
									if(f.prenomparticipant0.value=="")
									alert("Le prénom du participant 2 n'est pas rempli");
									else
									retour=true;
					return retour;
				}

			</script>

</head>
<body>
<?php include("banniere.php"); ?>

<?php include("menu.php"); ?>
<?php include("connection.php"); ?>
	<div id="contenu">
	
	<!-- on récupère l'id du tournoi et sa catégorie -->
		<center>
		<h2>Modification d'une equipe</h2>
		</center>
		
		<fieldset id="field_inscr_equipe">
		<?php
		
		if(!isset($_SESSION['cat']))
		{
		$_SESSION['cat']= 'simple';
		}

		
		$categorie =$_SESSION['cat'];
		if($categorie=='simple')
				{
					if(isset($_POST['nomequipe']) && isset($_POST['nomparticipant1']) && isset($_POST['prenomparticipant1']))
					{
						if($_POST['nomequipe']!=null && $_POST['nomparticipant1']!=null && $_POST['prenomparticipant1']!=null)
						{
							//on insère l'équipe dans notre base de données
							$deja=0;
							$test=mysql_query("SELECT idequipe,nomequipe from equipe where idtournoi='".$_SESSION['idtournoi']."'") OR DIE (mysql_error());
							while(($rowtest=mysql_fetch_array($test)) AND ($deja==0)) 
							{
								if($rowtest['nomequipe']==$_POST['nomequipe'])
								{
									$deja=1;
									echo "Le nom d'equipe est deja utilise!";
								}
								else
								{
									$test1=mysql_query("SELECT nomparticipant,prenomparticipant from participant where idequipe='".$rowtest['idequipe']."'") OR DIE (mysql_error());
									while($rowtest1 = mysql_fetch_array($test1) AND $deja==0) 
									{
										if($rowtest1['nomparticipant']==$_POST['nomparticipant1'] && $rowtest1['prenomparticipant']==$_POST['prenomparticipant1'])
										{
											$deja=2;
											echo "Le participant est déjà inscrit!";
										}
									}
									
								}
							
							}
							
							if($deja==0)
							{
							$query=mysql_query("UPDATE equipe SET nomequipe='".$_POST['nomequipe']."' where idequipe='".$_GET['id']."' ") OR DIE (mysql_error());
							
							//on récupère l'id de l'équipe ajoutée
							$query1=mysql_query("SELECT idequipe from equipe where nomequipe='".$_POST['nomequipe']."'") OR DIE (mysql_error());
							$queryy = mysql_fetch_array($query1);
							$ideq = $queryy['idequipe'];
							
							//Si c'est un simple homme
								if($categorie=='cat1')
								{
								$query2=mysql_query("Update participant set nomparticipant='".$_POST['nomparticipant1']."',prenomparticipant='".$_POST['prenomparticipant1']."' where idequipe='".$_GET['id']."' ") OR DIE (mysql_error());
								$_SESSION['cat']='simple';
								$_SESSION['deja']=1;
								}
								else
								//Si c'est un simple femme
								{
								$query2=mysql_query("Update participant set nomparticipant='".$_POST['nomparticipant1']."',prenomparticipant='".$_POST['prenomparticipant1']."' where idequipe='".$_GET['id']."' ") OR DIE (mysql_error());
								$_SESSION['cat']='simple';
								$_SESSION['deja']=1;
								}
							
							
							echo "L'equipe \"".$_POST['nomequipe']."\" <br/>a bien été modifiée.<br/>";
							}
						}
					}
				}
				else
				{
					if(isset($_POST['nomequipe']) && isset($_POST['nomparticipant0']) && isset($_POST['prenomparticipant0']) && isset($_POST['nomparticipant1']) && isset($_POST['prenomparticipant1']))
					{
						if($_POST['nomequipe']!=null && $_POST['nomparticipant0']!=null && $_POST['prenomparticipant0']!=null && $_POST['nomparticipant1']!=null && $_POST['prenomparticipant1']!=null)
						{
							//on insère l'équipe dans notre base de données
							
							$query=mysql_query("UPDATE equipe SET nomequipe='".$_POST['nomequipe']."' where idequipe='".$_GET['id']."' ") OR DIE (mysql_error());
							
							//on récupère l'id de l'équipe ajoutée
							$query1=mysql_query("SELECT idequipe from equipe where nomequipe='".$_POST['nomequipe']."'") OR DIE (mysql_error());
							$queryy = mysql_fetch_array($query1);
							$ideq = $queryy['idequipe'];
							
									
							$i=0;
							$resultfin=mysql_query("select p.idparticipant,p.nomparticipant,p.prenomparticipant,e.nomequipe from equipe e,participant p where e.idequipe='".$_GET['id']."' AND p.idequipe=e.idequipe") OR DIE (mysql_error());
							
							while($rowfin = mysql_fetch_array($resultfin)) 
							{
								if($i==0)
								{
									$query2=mysql_query("Update participant set nomparticipant='".$_POST['nomparticipant0']."',prenomparticipant='".$_POST['prenomparticipant0']."' where idparticipant='".$rowfin['idparticipant']."'  ") OR DIE (mysql_error());
									$_SESSION['cat']='double';
									
								}
								else
								{
									$query3=mysql_query("Update participant set nomparticipant='".$_POST['nomparticipant1']."',prenomparticipant='".$_POST['prenomparticipant1']."' where idparticipant='".$rowfin['idparticipant']."' ") OR DIE (mysql_error());
									$_SESSION['cat']='double';
									
								}
								if($i==0)
								{
									$i++;
								}
							}
							echo "</br>";
							
							echo "L'equipe \"".$_POST['nomequipe']."\" <br/>a bien été modifiée.<br/>";
						
						}
					
					}
				
				}					
			echo "<br/>";
				
					

			//on récupère l'id du tournoi qui est dans notre variable de session
			$tournoi = $_SESSION['idtournoi'];
			//on récupère la catégorie du tournoi
			$query=mysql_query("select idcat from tournoi where idtournoi='".$tournoi."'") OR DIE (mysql_error());
			$result = mysql_fetch_array($query);
			
			$result1=mysql_query("select p.nomparticipant,p.prenomparticipant,e.nomequipe from equipe e,participant p where e.idequipe='".$_GET['id']."' AND p.idequipe=e.idequipe");
			$result2=mysql_query("select p.nomparticipant,p.prenomparticipant,e.nomequipe from equipe e,participant p where e.idequipe='".$_GET['id']."' AND p.idequipe=e.idequipe");
			
			$row2 = mysql_fetch_array($result2);
			
			
		?>
		<br/>
		
		<?php
		if(!isset($_SESSION['deja']))
		{			
			
				if(($_SESSION['cat']=='simple' && (empty($_POST['nomequipe']) || empty($_POST['nomparticipant1']) || empty($_POST['prenomparticipant1']))) || ($_SESSION['cat']=='double' && (empty($_POST['nomequipe']) || empty($_POST['nomparticipant1']) || empty($_POST['prenomparticipant1']) && empty($_POST['prenomparticipant2']) || empty($_POST['nomparticipant2']))))
				{
						?>
						
						<form action="modifierequipe.php?id=<?php echo $_GET['id'];?>  " id="formuvisite" method="post" onSubmit='return estFormulaireValide(this)'>
						
						<!-- Nom de l'équipe -->
							<label id='nom'>Nom de l'equipe : <br/></label><input type='text' value='<?php echo $row2['nomequipe'];?>' name='nomequipe' id='nomequipe' 
							<?php if (!empty($_POST['nomequipe'])){
								echo "value='".$_POST['nomequipe']."'";}?>
							/>
							<input type='hidden' name='test' value='1'/>
							<br/>
							
							<?php	

						if(mysql_num_rows($result1)==1)
						{
							if($categorie=='cat2')
							{
								$row1 = mysql_fetch_array($result1);
								
								echo "<br/>";
								echo "<img src='images/femme.png' alt='femme'/>";
								echo "<br/>";
								?>
								<label id='nomparticipant1'>Nom du participant  :</label>
								<br/>
								<input type='text' name='nomparticipant1' value='<?php echo $row1['nomparticipant'];?>' id='nomparticipant1'
								<?php if (!empty($_POST['nomparticipant1'])){
									echo "value='".$_POST['nomparticipant1']."'";}
								?>/>
								<br/>
								
								<label id='prenomparticipant1'>Prenom du participant  : </label>
								<br/>
								<input type='text' name='prenomparticipant1' value='<?php echo $row1['prenomparticipant'];?>' id='prenomparticipant1'
								<?php if (!empty($_POST['prenomparticipant1'])){
									echo "value='".$_POST['prenomparticipant1']."'";}
								?>/><?php
									echo "<br/>";
							}
							else
							{
								$row1 = mysql_fetch_array($result1);
								echo "<br/>";
								echo "<img src='images/homme.png' alt='homme'/>";
								echo "<br/>";
								?>
								<label id='nomparticipant1'>Nom du participant :</label>
								<br/>
								<input type='text' name='nomparticipant1' value='<?php echo $row1['nomparticipant'];?>' id='nomparticipant1'
								<?php if (!empty($_POST['nomparticipant1'])){
									echo "value='".$_POST['nomparticipant1']."'";}
								
								?>/>
								<br/>
								
								<label id='prenomparticipant1'>Prenom du participant : </label>
								<br/>
								<input type='text' name='prenomparticipant1' value='<?php echo $row1['prenomparticipant'];?>' id='prenomparticipant1'
								<?php if (!empty($_POST['prenomparticipant1'])){
									echo "value='".$_POST['prenomparticipant1']."'";}
								?>/><?php
									echo "<br/>";
							}
							echo"<br/>";
						}
						else
						{
							if($categorie=='cat5')
							{
								
								echo "<br/>";
								echo "<img src='images/femme.png' alt='femme'/>";
								echo "<br/>";
								$i=0;
								while($row1 = mysql_fetch_array($result1)) 
								{
									if($i==0)
									{
										?>
										<label id='nomparticipant0'>Nom du participant :</label>
										<br/>
										<input type='text' name='nomparticipant0' value='<?php echo $row1['nomparticipant'];?>'id='nomparticipant0'
										<?php if (!empty($_POST['nomparticipant0'])){
											echo "value='".$_POST['nomparticipant0']."'";}
										
										?>/>
										
										<br/>
										
										<label id='prenomparticipant0'>Prenom du participant : </label>
										<br/>
										<input type='text' name='prenomparticipant0' value='<?php echo $row1['prenomparticipant'];?>'id='prenomparticipant0'
										<?php if (!empty($_POST['prenomparticipant0'])){
											echo "value='".$_POST['prenomparticipant0']."'";}?>
										/><?php
										echo "<br/>";
										echo "<br/>";
									}
									else
									{
										?>
										<label id='nomparticipant1'>Nom du participant :</label>
										<br/>
										<input type='text' name='nomparticipant1' value='<?php echo $row1['nomparticipant'];?> 'id='nomparticipant1'
										<?php if (!empty($_POST['nomparticipant1'])){
											echo "value='".$_POST['nomparticipant1']."'";}
										
										?>/>
										
										<br/>
										
										<label id='prenomparticipant1'>Prenom du participant : </label>
										<br/>
										<input type='text' name='prenomparticipant1' value='<?php echo $row1['prenomparticipant'];?>'id='prenomparticipant1'
										<?php if (!empty($_POST['prenomparticipant1'])){
											echo "value='".$_POST['prenomparticipant1']."'";}?>
										/><?php
										echo "<br/>";
										echo "<br/>";
									}
									if($i==0)
									{
										echo "<img src='images/homme.png' alt='homme'/> ";
										echo "<br/>";
										$i++;
									}
								}
								echo "</br>";
								
							}
							else
							{
								if($categorie=='cat4')
								{
									echo "<br/>";
									echo "<img src='images/femme.png' alt='femme'/>";
									echo "<br/>";
									$i=0;
									while($row1 = mysql_fetch_array($result1)) 
									{
										if($i==0)
										{
											?>
											<label id='nomparticipant0'>Nom du participant :</label>
											<br/>
											<input type='text' name='nomparticipant0' value='<?php echo $row1['nomparticipant'];?>'id='nomparticipant0'
											<?php if (!empty($_POST['nomparticipant0'])){
												echo "value='".$_POST['nomparticipant0']."'";}
											
											?>/>
											
											<br/>
											
											<label id='prenomparticipant0'>Prenom du participant : </label>
											<br/>
											<input type='text' name='prenomparticipant0' value='<?php echo $row1['prenomparticipant'];?>'id='prenomparticipant0'
											<?php if (!empty($_POST['prenomparticipant0'])){
												echo "value='".$_POST['prenomparticipant0']."'";}?>
											/><?php
											echo "<br/>";
											echo "<br/>";
										}
										else
										{
											?>
											<label id='nomparticipant1'>Nom du participant :</label>
											<br/>
											<input type='text' name='nomparticipant1' value='<?php echo $row1['nomparticipant'];?> 'id='nomparticipant1'
											<?php if (!empty($_POST['nomparticipant1'])){
												echo "value='".$_POST['nomparticipant1']."'";}
											
											?>/>
											
											<br/>
											
											<label id='prenomparticipant1'>Prenom du participant : </label>
											<br/>
											<input type='text' name='prenomparticipant1' value='<?php echo $row1['prenomparticipant'];?>'id='prenomparticipant1'
											<?php if (!empty($_POST['prenomparticipant1'])){
												echo "value='".$_POST['prenomparticipant1']."'";}?>
											/><?php
											echo "<br/>";
											echo "<br/>";
										}
										if($i==0)
										{
											echo "<img src='images/homme.png' alt='homme'/> ";
											echo "<br/>";
											$i++;
										}
									}
									echo "</br>";
									
									
									
								}
								else
								{
									echo "<br/>";
									echo "<img src='images/femme.png' alt='femme'/>";
									echo "<br/>";
									$i=0;
									while($row1 = mysql_fetch_array($result1)) 
									{
										if($i==0)
										{
											?>
											<label id='nomparticipant0'>Nom du participant :</label>
											<br/>
											<input type='text' name='nomparticipant0' value='<?php echo $row1['nomparticipant'];?>'id='nomparticipant0'
											<?php if (!empty($_POST['nomparticipant0'])){
												echo "value='".$_POST['nomparticipant0']."'";}
											
											?>/>
											
											<br/>
											
											<label id='prenomparticipant0'>Prenom du participant : </label>
											<br/>
											<input type='text' name='prenomparticipant0' value='<?php echo $row1['prenomparticipant'];?>'id='prenomparticipant0'
											<?php if (!empty($_POST['prenomparticipant0'])){
												echo "value='".$_POST['prenomparticipant0']."'";}?>
											/><?php
											echo "<br/>";
											echo "<br/>";
										}
										else
										{
											?>
											<label id='nomparticipant1'>Nom du participant :</label>
											<br/>
											<input type='text' name='nomparticipant1' value='<?php echo $row1['nomparticipant'];?> 'id='nomparticipant1'
											<?php if (!empty($_POST['nomparticipant1'])){
												echo "value='".$_POST['nomparticipant1']."'";}
											
											?>/>
											
											<br/>
											
											<label id='prenomparticipant1'>Prenom du participant : </label>
											<br/>
											<input type='text' name='prenomparticipant1' value='<?php echo $row1['prenomparticipant'];?>'id='prenomparticipant1'
											<?php if (!empty($_POST['prenomparticipant1'])){
												echo "value='".$_POST['prenomparticipant1']."'";}?>
											/><?php
											echo "<br/>";
											echo "<br/>";
										}
										if($i==0)
										{
											echo "<img src='images/homme.png' alt='homme'/> ";
											echo "<br/>";
											$i++;
										}
									}
									echo "</br>";
								}

							}
							echo"<br/>";
						}
					
						?>
							
							<br/>
							<!--BOUTON VALIDER-->
							<center><input type ="submit"></center>
							</form>
						<?php
				}
			
		}

		?>
		</fieldset><br/><br/>
	</div>

	<?php include("pied_de_page.php"); ?></body>

</html>

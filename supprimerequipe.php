<?php
SESSION_START();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Liste des équipes</title>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	   <link rel="stylesheet" media="screen" type="text/css" title="design" href="style.css" />
   </head>
   <body>
   
	<?php include("banniere.php"); ?>

	<?php include("menu.php"); ?>

	<div id="contenu">
	
		<?php
			$query=mysql_query("DELETE FROM participant WHERE idequipe ='".$_GET['id']."'") OR DIE (mysql_error());
			$query=mysql_query("DELETE FROM equipe WHERE idequipe ='".$_GET['id']."'") OR DIE (mysql_error());
					
			echo "Suppression effectuée";
		?>
			
		
		<FORM> <INPUT TYPE="button" VALUE="Retour" onClick="history.back()"> </FORM> 
	</div>

	<?php include("pied_de_page.php"); ?>		
		</body>
</html>

<?php
	if(!isset($_SESSION['idtournoi']))
		$_SESSION['idtournoi'] = 1;
?>
<div id="menu">
<table>
	<td><a class="b_off" href="accueil.php" id="index">Accueil</a>
		
	</td>	
	<td><span class="b_off" id="tournoi">Tournoi</span>
		<ul>
			<li><a href='index.php' >Nouveau</a></li>
			<li><a href='modifier.php'>Modifier</a></li>
			<li><a href='supprimer.php'>Supprimer</a></li>
		</ul>
	</td>	
	<td><a class="b_off" href="listeequipe.php?id=<?php echo $_SESSION['idtournoi'];?>" id="equipes">Equipes</a>
		<ul>
			<li><a href='inscrire_equipe.php' >Inscrire</a></li>
			<li><a href='listeequipe.php?id=<?php echo $_SESSION['idtournoi'];?>'>Afficher</a></li>
			
		</ul>
	</td>
	<td><a class="b_off" href="poule.php" id="poules">Poules</a>
	<ul>
			<li><a href='poule.php' >Afficher les poules</a></li>
			<li><a href='genererpoules.php' >Générer les poules</a></li>			
	</ul></td>
	<td><span class="b_off"  id="match">Match</span>
		<ul>
			<li><a href="matchs.php?fini=1">En cours</a></li>
			<li><a href="matchs.php?fini=2">A venir</a></li>
			<li><a href="matchs.php?fini=3">Finis</a></li>
			<li><a href="generermatchs.php">Générer</a></li>
			<li><a href="classement.php">Classement</a></li>
			
		</ul>
	</td>	
	
</table>
</div>

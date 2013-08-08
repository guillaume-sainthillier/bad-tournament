<?php
session_start();
header ("Content-type: image/png");


	
	$nbPoules = $_COOKIE['nbpoules'];
	$nbEquipes = $_COOKIE['nbequipes'];
	
	$largeur = 1000;
	$hauteur = 600;
	$image = imagecreate($largeur,$hauteur);

	$blanc = imagecolorallocate($image, 255, 255, 255);
	$noir = imagecolorallocate($image, 0, 0, 0);
	
	
	
	//On creer un tableau de couleurs differentes pour les poules
	$tabCouleurs[0] = imagecolorallocate($image, 255, 128, 0);//Orange
	$tabCouleurs[1] = imagecolorallocate($image, 0, 255, 64);//Vert
	$tabCouleurs[2] = imagecolorallocate($image, 255, 0, 0);//Rouge
	$tabCouleurs[3] = imagecolorallocate($image, 0, 0, 255);//Bleu
	$tabCouleurs[4] = imagecolorallocate($image, 255, 255, 0);//Jaune
	$tabCouleurs[5] = imagecolorallocate($image, 255, 0, 255);//Rose
	$tabCouleurs[6] = imagecolorallocate($image, 0, 255, 255);//Cyan
	$tabCouleurs[7] = imagecolorallocate($image, 128, 0, 128);//Euh
	$tabCouleurs[8] = imagecolorallocate($image, 255, 255, 255);//Blanc*/

	

	
	$debutx = (($largeur -($nbPoules*100))/2); //Position en X du premier trait rectangle sur le dessin (centré en fonction du nombre de poules)
	$finx = $debutx + 90; // Position en x du dernier trait du rectangle
	
	$lastHauteur = $hauteur; //Sauvegarde de l'avancée des figures dans le dessins en partant du bas  ((0;0) est en haut à gauche)
	
	$positionDansRectangleY = ( (   (50+imagefontheight(5))  /2) - imagefontheight(5)); //Position du texte en Y dans le rectangle en fonction de la taille de la police
	
	for($i = 0;$i < $nbPoules;$i++)
	{
		
		ImageRectangle($image, $debutx, $hauteur-60 ,$finx, $hauteur-10 , $tabCouleurs[$i]);//On rempli l'image d'un rectangle de 100px (90px de long + 10 d'espaces)
		ImageRectangle($image, $debutx+1, $hauteur-60 ,$finx+1, $hauteur-10 , $tabCouleurs[$i]);//Pour la mise en gras du rectangle(autre rectangle décalé d'1px)
		
		
		$texte = ("Poule ".($i+1));
		$positionDansRectangleX = (90 - (strlen($texte)*imagefontwidth(5)))/2;
		imagestring($image, 5, $debutx + $positionDansRectangleX , ($hauteur -60 )+ $positionDansRectangleY, $texte, $noir);//On y insère dedans le texte désiré
	
		$tabRectangle[$i]['debut'] = $debutx; //On sauvegarde la position en X du début de chaque réctangles de poules
		$tabRectangle[$i]['fin'] = $finx;
		$debutx += 100; //On redécale la position à venir de la taille de l'objet dessiné 
		$finx += 100; //Idem
	}
	
	$lastHauteur = $lastHauteur -60; // lastHauteur = hauteur - 60
	
	
	//On relie les rectangles de poules à un point ($largeur/2;$lastHauteur-65)
	for($i = 0; $i < $nbPoules;$i++)
	{
		ImageLine ($image, $largeur/2, $lastHauteur-65 ,($tabRectangle[$i]['debut']+$tabRectangle[$i]['fin'])/2,$lastHauteur, $tabCouleurs[$i]);
	}	
	$lastHauteur = $lastHauteur -65; //lastHauteur = hauteur -125
	

	
	$espace = 10; //Espace entre les rectangles
	$debut = 0;
	if($nbEquipes > 16)
	{
		$nbRectangle = 16;
		$espace = 5;
	}
	if($nbEquipes >8 && $nbEquipes <= 16)
	{
		$debut = 1;
		$nbRectangle = 8;
	}
	if($nbEquipes >4 && $nbEquipes <=8)
	{
		$debut = 2;
		$nbRectangle = 4;
	}
	if($nbEquipes > 2 && $nbEquipes <=4)
	{
		$debut = 3;
		$nbRectangle = 2;
	}
	
	
		
	$largeurRectangle = ((int)($largeur/$nbRectangle) - $espace); //On défini la largeur d'un rectangle variable suivant le nombre à afficher sur une ligne
	$debutx = (($largeur -($nbRectangle*($largeurRectangle+$espace)))/2);//Idem qu'avant on redéfini la position en x du premier rectangle de match
	$finx = $debutx + $largeurRectangle; // Position en x du dernier trait du premier rectangle
	// $lastHauteur = $hauteur;
	
	

	for($i = 0; $i < $nbRectangle;$i++)
	{
		$newX[$i] = ($debutx+$finx)/2; //La nouvelle position en X des rectangles du dessus sera égale à la moitié de la largeur des rectangles du dessous
		$debutx = $debutx +$largeurRectangle +$espace;
		$finx = $finx + $largeurRectangle +$espace;
		
	}

	if(isset($_SESSION['debut']))
		$nbDebut = $_SESSION['debut'];
	else
		$nbDebut = 0;
	for($i = 0;$i < $nbRectangle;$i++)
	{

		if(isset($_SESSION['tabClassement'][$nbDebut][$i]))
		{
			$numPoule = $_SESSION['tabClassement'][$nbDebut][$i]['indicePoule'];
			$couleur = $tabCouleurs[$numPoule-1]; // Les poules commencent à 1 , les indices de tableau à 
			// $couleur = $blanc; // Les poules commencent à 1 , les indices de tableau à 
		}else
		{
			$couleur = $noir;

		}

			ImageLine ($image, $largeur/2, $lastHauteur,$newX[$i],$lastHauteur-65, $couleur);
			ImageLine ($image, $largeur/2 +1, $lastHauteur,$newX[$i] +1,$lastHauteur-65, $couleur);
			// ImageLine ($image, $largeur/2 -1, $lastHauteur,$newX[$i] -1,$lastHauteur-65, $couleur);
	}
	$lastHauteur = $lastHauteur-65;
	
	

	for($k = $debut; $k < 5; $k++)
	{
			
			if($nbRectangle == 8)
				$largeurRectangle = 70;
			if($nbRectangle <= 4)
				$largeurRectangle = 90;
			
			
			
			$positionDansRectangleY = (50-imagefontheight($k))/2 ;
			for($i = 0; $i < $nbRectangle;$i++)
			{
				if(!(isset($_SESSION['tabClassement'][$k][$i])))
				{
					$texte = " ? ";
					$couleur = $noir;
				
				}else
				{
					$texte = $_SESSION['tabClassement'][$k][$i]['nomEquipe'];
					$numPoule = $_SESSION['tabClassement'][$k][$i]['indicePoule'];
					$couleur = $tabCouleurs[($numPoule-1)];
				}
				
					
					
				ImageRectangle($image, $newX[$i]-(int)(($largeurRectangle/2)),$lastHauteur,$newX[$i]+(int)(($largeurRectangle/2)),$lastHauteur-50, $couleur);
				ImageRectangle($image, ($newX[$i]-(int)(($largeurRectangle/2)))-1,$lastHauteur-1,($newX[$i]+(int)(($largeurRectangle/2)))-1,$lastHauteur-51, $couleur);
				// ImageRectangle($image, ($newX[$i]-(int)(($largeurRectangle/2)))+1,$lastHauteur+1,($newX[$i]+(int)(($largeurRectangle/2)))+1,$lastHauteur-49, $couleur);
				
				$positionDansRectangleX = ($largeurRectangle - (strlen($texte)*imagefontwidth($k)))/2;
				imagestring($image, $k, ($newX[$i]-($largeurRectangle/2))+$positionDansRectangleX, $lastHauteur-49+$positionDansRectangleY, $texte, $noir);//On y insère dedans le texte désiré
				
				if($k < 4)								
					ImageLine($image, $newX[$i], $lastHauteur-50 ,$newX[$i],$lastHauteur-60, $noir);
					
				if(!($i%2))
				{
					if($k < (4))
					{
						ImageLine($image, ($newX[$i]+$newX[$i+1])/2, $lastHauteur-60 ,($newX[$i]+$newX[$i+1])/2,$lastHauteur-70, $noir); // |---->|
						ImageLine($image, $newX[$i], $lastHauteur-60 ,$newX[$i+1],$lastHauteur-60, $noir); // |---->|
					}
					$newX[$i/2] = ($newX[$i] + $newX[$i+1])/2;
				}
		
			}
			$nbRectangle = $nbRectangle/2;	
			$lastHauteur = $lastHauteur - 70; //lastHauteur = hauteur -400
	}
	

imagecolortransparent($image, $blanc);// On rend le fond transparent
imagepng($image);
?>
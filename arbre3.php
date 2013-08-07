<?php
session_start();
header ("Content-type: image/png");



$image = imagecreatefrompng("images/podium.png");

$noir = imagecolorallocate($image, 0, 0, 0);



for($i = 0;$i <3;$i++)
{
	if(isset($_SESSION['tabPodium'][$i]))
		$tabPodium[$i] = $_SESSION['tabPodium'][$i];
	else
		$tabPodium[$i] = " Guy ";
}


$positionDansRectangleX0 = (179-49 - (strlen($tabPodium[0])*imagefontwidth(5)))/2;
$positionDansRectangleX1 = (331-179 - (strlen($tabPodium[1])*imagefontwidth(5)))/2;
$positionDansRectangleX2 = (451-331 - (strlen($tabPodium[2])*imagefontwidth(5)))/2;



imagestring($image, 5, 179+$positionDansRectangleX1, 145,$tabPodium[0], $noir);
imagestring($image, 5, 49+$positionDansRectangleX0, 175, $tabPodium[1], $noir);
imagestring($image, 5, 331+$positionDansRectangleX2, 185,$tabPodium[2], $noir);




imagepng($image);


?>
<?php 

// Measurements
// 1 inch = 1440 twips
// 1 cm = 567 twips
// 1 mm = 56.7 twips
$inch = 1440;
$cm = 567;
$mm = 56.7;

// Fonts
$fonts_array = array();
// Array structure - array(
//	"name"		=>	Name given to the font,
//	"family"	=>	[nil, roman, swiss, modern, script, decor, tech, bidi],
//	"charset"	=>	0
// );

$fonts_array[] = array(
	"name"		=>	"Arial",
	"family"	=>	"swiss",
	"charset"	=>	0
);

$fonts_array[] = array(
	"name"		=>	"Times New Roman",
	"family"	=>	"roman",
	"charset"	=>	0
);

$fonts_array[] = array(
	"name"		=>	"Verdana",
	"family"	=>	"swiss",
	"charset"	=>	0
);

$fonts_array[] = array(
	"name"		=>	"Symbol",
	"family"	=>	"roman",
	"charset"	=>	2
);

// Control Words
$control_array = array();
?>
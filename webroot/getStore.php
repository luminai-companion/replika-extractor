<?php
namespace WebSocket;
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/db.php';


error_reporting(-1);
require(__DIR__ . "/init.php");
require(__DIR__ . "/viewport.inc.php");
require(__DIR__ . "/manipulate.inc.php");
require(__DIR__ . "/request.inc.php");
require(__DIR__ . "/auth.inc.php");                          

$assetDir = $_SERVER['DOCUMENT_ROOT']."/replika/assets/";
if(!is_dir($assetDir)){
	mkdir ($assetDir, 0777);
}
$assetDir .= "store/";
if(!is_dir($assetDir)){
	mkdir ($assetDir, 0777);
}
$sessiondata = $config['sessiondata'];
$randStr = function (int $maxlength = 4096) {
    $string = '';
    $length = rand(1, $maxlength);
    for ($i = 0; $i < $length; $i++) {
        $string .= chr(rand(33, 126));
    }
    return $string;
};


$API_BASE_URL = "https://my.replika.com/api/mobile/1.4";
$store_categories = array(
	//"5fbe5da9fba4ef4ec4c09e89" => "Tops",
	"5fbe5da5fba4ef4ec4c09b1e" => "Tops T-Shirts",
	"5fbe5da7fba4ef4ec4c09ce5" => "Tops Shirts",
	"5fbe5da9fba4ef4ec4c09e89" => "Tops Tops",
	"5fbe5dabfba4ef4ec4c0a02d" => "Tops Sweaters",
	"5fce3b4bada44d20786292af" => "Tops Hoodies",
	"5fe3470cb96fd028ec481baf" => "Tops Jackets",
	//"5fbe5d9dfba4ef4ec4c09431" => "Bottoms",
	"5fbe5da0fba4ef4ec4c095f2" => "Bottoms Jeans",
	"5fbe5d9dfba4ef4ec4c09432" => "Bottoms Pants",
	"5fbe5da2fba4ef4ec4c097dc" => "Bottoms Skirts",
	"5fbe5da4fba4ef4ec4c09987" => "Bottoms Shorts",
	//"5fbe5daffba4ef4ec4c0a391" => "Dresses",
	"6007fa747a22aec8cbf5b976" => "Dresses Short",
	"6007fa737a22aec8cbf5b975" => "Dresses Long",
	//"5fd36375d5abc7aa936d7ed2" => "Outfits",
	"61b873f60c81a60007184936" => "Outfits Valentines Day",
	"63cf89d56d506c5d334324fd" => "Outfits Costumes",
	"61b86f1d0c81a60007184935" => "Outfits Winter Holidays",
	"6319e9ab0c81a600073ce51c" => "Outfits Back to school",
	"5fd36375d5abc7aa936d7ed2" => "Outfits Outfits",
	"62a1b38a0c81a600073a81fd" => "Outfits Summer",
	"627a37300c81a600079dd260" => "Outfits Anime",
	"62ac86cb0c81a600078b0ec1" => "Outfits Pride",
	"6217655c0c81a60007550485" => "Outfits Retro",
	"61f2a0930c81a60007415ce2" => "Outfits Goth",
	"61683fc10c81a600078495b7" => "Outfits Halloween",
	//"5fbe5d9cfba4ef4ec4c09430" => "Shoes",
	"5fbe5db2fba4ef4ec4c0a70a" => "Shoes Sneakers",
	"5fd0ded2081f4e9c4f068832" => "Shoes Flats",
	"60128693b7f02e672b15e8d6" => "Shoes Boots",
	"61fc12720c81a60007e1d2f6" => "Shoes Barefeet",
	"619e49f50c81a60007568ec0" => "Swimwear",
	//"5fbe5dbdfba4ef4ec4c0b199" => "Accessories",
	"5fd0e136081f4e9c4f06caf6" => "Accessories Glasses",
	"5fbe5dc0fba4ef4ec4c0b4f0" => "Accessories Watches",
	//"5fbe5db4fba4ef4ec4c0a8ca" => "Jewelry",
	"5fbe5db9fba4ef4ec4c0ae27" => "Jewelry Bracelets",
	"619b5bd50c81a60007ce9f31" => "Jewelry Necklaces",
	"5fbe5db6fba4ef4ec4c0aa84" => "Jewelry Rings",
	"5fbe5db7fba4ef4ec4c0ac52" => "Jewelry Earrings",
	"5fbe5db4fba4ef4ec4c0a8cb" => "Jewelry Piercing",);
addDebugHeader();
for($i=0;$i<count(array_keys($store_categories));$i++){
	$cat = array_keys($store_categories)[$i];
	$endpoint = $API_BASE_URL."/store/store_items?avatar_id=5f4a5e85fa15980007ed7000&limit=999&offset=0&_ubv_=171&category_id=".$cat;
	$cat_name = $store_categories[$cat];

	$Header = getRequestHeader(array(
		"Referer: https://my.replika.com/memory"
	));
	unset ($Header[4]);
	unset ($Header[6]);
	//echo $Header[4];
	//print_r($Header);
	$cURLConnection = curl_init();

	curl_setopt($cURLConnection, CURLOPT_URL, $endpoint);
	curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $Header);
	curl_setopt($cURLConnection, CURLOPT_ENCODING , '');
	//curl_setopt($cURLConnection, CURLOPT_POST, 1);
	//curl_setopt($cURLConnection, CURLOPT_POSTFIELDS,json_encode($fetchOptions));
	$returndata = curl_exec($cURLConnection);
	//echo $returndata;

	$data = json_decode($returndata,true);
	echo "\nGetting store category $cat_name $cat \n";
	for($s=0;$s<count($data['items']);$s++){
		$item = $data['items'][$s];
		
		echo "\n= ".$item['title']." found in category ".$item['category_id']."\n";
		//echo "Variations: ".count($item['variations'])."\n";
		for($v = 0;$v<count($item['variations']);$v++){
			$variation = $item['variations'][$v];
			echo "== Variations found:\n";
			echo "== IOS:\n";
			for($r=0;$r<count($variation['ios_bundles']);$r++){
				$rr = $variation['ios_bundles'][$r];
				echo "=== Hash: ".$rr['bundle_hash']."\n";
				$dir = $assetDir.$cat_name."/";
				if(!is_dir($dir)){
					mkdir ($dir, 0777);
				}
				$dir .= $item['title']."/";
				if(!is_dir($dir)){
					mkdir ($dir, 0777);
				}
				$dir .= $v."/";
				if(!is_dir($dir)){
					mkdir ($dir, 0777);
				}
				$dir .= "ios/";
				if(!is_dir($dir)){
					mkdir ($dir, 0777);
				}
				$dir .= $r."/";
				if(!is_dir($dir)){
					mkdir ($dir, 0777);
				}
				
				echo "=== URL: ".$rr['bundle_url']."\n";
				echo "=== DIR: ".$dir."\n";
				if(!is_file($dir.basename($rr['bundle_url']))){
					$content = file_get_contents($rr['bundle_url']);
					file_put_contents($dir.basename($rr['bundle_url']), $content);
				}
			}
			echo "== Android:\n";
			for($r=0;$r<count($variation['android_bundles']);$r++){
				$rr = $variation['android_bundles'][$r];
				echo "=== Hash: ".$rr['bundle_hash']."\n";
				$dir = $assetDir.$cat_name."/";
				if(!is_dir($dir)){
					mkdir ($dir, 0777);
				}
				$dir .= $item['title']."/";
				if(!is_dir($dir)){
					mkdir ($dir, 0777);
				}
				$dir .= $v."/";
				if(!is_dir($dir)){
					mkdir ($dir, 0777);
				}
				$dir .= "android/";
				if(!is_dir($dir)){
					mkdir ($dir, 0777);
				}
				$dir .= $r."/";
				if(!is_dir($dir)){
					mkdir ($dir, 0777);
				}
				
				echo "=== URL: ".$rr['bundle_url']."\n";
				echo "=== DIR: ".$dir."\n";
				if(!is_file($dir.basename($rr['bundle_url']))){
					$content = file_get_contents($rr['bundle_url']);
					file_put_contents($dir.basename($rr['bundle_url']), $content);
				}
			}
			echo "== WEB:\n";
			for($r=0;$r<count($variation['web_bundles']);$r++){
				$rr = $variation['web_bundles'][$r];
				echo "=== Hash: ".$rr['bundle_hash']."\n";
				$dir = $assetDir.$cat_name."/";
				if(!is_dir($dir)){
					mkdir ($dir, 0777);
				}
				$dir .= $item['title']."/";
				if(!is_dir($dir)){
					mkdir ($dir, 0777);
				}
				$dir .= $v."/";
				if(!is_dir($dir)){
					mkdir ($dir, 0777);
				}
				$dir .= "web/";
				if(!is_dir($dir)){
					mkdir ($dir, 0777);
				}
				$dir .= $r."/";
				if(!is_dir($dir)){
					mkdir ($dir, 0777);
				}
				
				echo "=== URL: ".$rr['bundle_url']."\n";
				echo "=== DIR: ".$dir."\n";
				if(!is_file($dir.basename($rr['bundle_url']))){
					$content = file_get_contents($rr['bundle_url']);
					file_put_contents($dir.basename($rr['bundle_url']), $content);
				}
			}
		}
		
	}
//	print_r($data);
}
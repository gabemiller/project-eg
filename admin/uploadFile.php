<?php

require_once 'includes/functions.php';

$uploader = new FileUploader();

// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
$uploader->allowedExtensions = array("jpeg","jpg","png","gif");

// Specify max file size in bytes.
$uploader->sizeLimit = 2 * 1024 * 1024 * 10;

// Specify the input name set in the javascript.
$uploader->inputName = 'file';

// If you want to use resume feature for uploader, specify the folder to save parts.
$uploader->chunksFolder = 'chunks';

// To save the upload with a specified name, set the second parameter.
//$name = md5(mt_rand()).'_'.$uploader->getName();

$name = Object::removeSpecialCharacters($uploader->getName(),true);

$nameNormal =  $_POST['fileNum']."_".$name;
$nameMini = $_POST['fileNum']."_mini_".$name;


$folderParent = '../img/'.$_POST['formName'];
$folder = $folderParent.'/'.$_POST['formId'];
$folderMini = $folder.'/mini';

if(!file_exists($folderParent)){
	mkdir($folderParent,0777);
}

if(!file_exists($folder)){
	mkdir($folder,0777);
}

if(!file_exists($folderMini)){
	mkdir($folderMini,0777);
}

$url = $folder.'/'.$nameNormal;
$urlMini = $folderMini.'/'.$nameMini;

$result = $uploader->handleUpload($folder, $nameNormal);


chmod ( $url , 0777);
$thumb = PhpThumbFactory::create($url);
$thumb->adaptiveResize(240, 240);
$thumb->save($urlMini);


// To return a name used for uploaded file you can use the following line.

$result['uploadName'] = $uploader->getUploadName();
$result['url'] = $url;
$result['urlMini'] = $urlMini;
$result['fileNum'] = $_POST['fileNum'];

header("Content-Type: application/json");
echo json_encode($result);

?>
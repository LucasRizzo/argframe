<?php

ini_set('max_execution_time', -1);
ini_set('memory_limit', '2096M');

$files = glob("*.csv");

//var_dump($files);

$result_file = "result.csv";

$wH = fopen($result_file, "w+");

$zip_file = "result.zip";

$file_i = 0;
foreach($files as $file) {
    $fh = fopen($file, "r");
    //var_dump($file);
    $line_i = 0;
    while(!feof($fh)) {
        // Don't write the header multiple time
        $line = fgets($fh);
        fwrite($wH, $line);
        //echo $line . "\n";
        $line_i = $line_i + 1;
    }
    //var_dump($line_i);
    fclose($fh);
    unset($fh);
    //fwrite($wH, "\n"); //usually last line doesn't have a newline
    $file_i = $file_i + 1;
}

$lines = file($result_file);
$lines = array_unique($lines);
file_put_contents($result_file, implode($lines));

$zip = new ZipArchive();
$zip->open($zip_file, ZIPARCHIVE::CREATE);
$zip->addFile($result_file);
$zip->close();

header("Content-type: application/zip"); 
header("Content-Disposition: attachment; filename=$zip_file");
header("Content-length: " . filesize($zip_file));
header("Pragma: no-cache"); 
header("Expires: 0"); 
readfile($zip_file);

//Get a list of all of the file names in the folder.
$files = glob('*.csv');
 
//Loop through the file list.
foreach($files as $file){
    //Make sure that this is a file and not a directory.
    if(is_file($file)){
        //Use the unlink function to delete the file.
        unlink($file);
    }
}

//Get a list of all of the file names in the folder.
$files = glob('*.zip');
 
//Loop through the file list.
foreach($files as $file){
    //Make sure that this is a file and not a directory.
    if(is_file($file)){
        //Use the unlink function to delete the file.
        unlink($file);
    }
}

exit;
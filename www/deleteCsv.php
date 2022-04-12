<?php

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


?>
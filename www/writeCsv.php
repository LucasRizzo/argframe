<?php
ini_set('max_post_size', '100M');

if(!empty($_POST['data'])){

    $parts=explode("*",$_POST['data']);

    $data = $parts[1];
    $fname = "data_" . $parts[0] . ".csv";
    // Clean file
    file_put_contents($fname, "");

    $file = fopen($fname, 'w');

    fwrite($file, $data);

    /*$requests = explode(";;",  $data);

    $file = 'error.txt';
    $current = file_get_contents($file);
    // Append a new person to the file
    $current .= var_dump(count($requests));
    // Write the contents back to the file
    file_put_contents($file, $current);*/

    fclose($file);
}
?>
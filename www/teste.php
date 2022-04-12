<?php
//echo shell_exec('./a');
//shell_exec('gcc -o a a.c');
//echo shell_exec('./a');
$dir = '/xxx';
if (!is_dir($dir)) {
    $mkDir = mkdir($dir, 0777);
    if (!$mkDir) {
        exit('Failed to create ' . $dir);
    }
}
?>

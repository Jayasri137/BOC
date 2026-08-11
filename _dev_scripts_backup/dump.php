<?php
$out = shell_exec('c:\xampp\php\php.exe index.php');
file_put_contents('out2.html', $out);

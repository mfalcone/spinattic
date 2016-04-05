<?php


//$salida = shell_exec('pwd && ./kmakemultires templates/vtour-multires.config frey-dic2012-01.jpg');

$salida = shell_exec('wine kmakemultires.exe front.jpg -config=templates/normal.config');
//$salida = shell_exec('ls');

echo "<pre>result: $salida</pre>";


//$salida = shell_exec('./kmakemultires /home/spinattic/dev.spinattic.com/material/frey-04.tif -config=./templates/vtour-multires.config');


?>
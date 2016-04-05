<?

$link=mysql_connect("mysql.spinattic.com","spindb_usu","ud3YRfmMWcFMaaSQ");

mysql_select_db("spin_bd");

$cdn_string = 'cdn.dev.spinattic.com';
$cdn = 'http://'.$cdn_string;
$bucket_config_file = '.s3cfg-spinattic-dev';
$environment = 'dev';

//Version
$ssqlp = "SELECT * FROM version";
$result = mysql_query($ssqlp);
$row = mysql_fetch_array($result);
$ver = $row["version"];
?>
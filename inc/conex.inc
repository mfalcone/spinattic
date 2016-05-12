<?

$link=mysql_connect("mysql.spinattic.com","spindb_usu","ud3YRfmMWcFMaaSQ");

mysql_select_db("spin_bd");


$http = 'http://';


$cdn_string = 'cdn.dev.spinattic.com';
$cdn = $http.$cdn_string;

$bucket_config_file = '.s3cfg-spinattic-dev';
$environment = 'dev';

//Version
$ssqlp = "SELECT * FROM version";
$result = mysql_query($ssqlp);
$row = mysql_fetch_array($result);
$ver = $row["version"];

//Payment
$seller_id = '901302756';
$public_key = 'E9DC86CA-D9FE-4473-93D4-1586F411E979';
$private_key = 'A13542DD-CE36-4B38-8A20-07BC8319A94A';
$sandbox = true;
$payment_env = 'sandbox';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Statistikk</title>
</head>

<body>
<?Php
date_default_timezone_set('GMT');

require 'vendor/autoload.php';
$laptimes=new laptimes($_GET['decoder']);
for($day=0; $day<=7; $day++)
{
	$timestamp_day=strtotime(sprintf('-%s days',$day));
	echo date('c',$timestamp_day)."\n";

	$where_today=$laptimes->query_today($timestamp_day);
	$q_todays_transponders=sprintf('SELECT distinct transponder FROM %s WHERE %s ORDER BY rtc_time DESC',$laptimes->table,$where_today); //Get todays drivers
	$todays_transponders=$laptimes->db->query($q_todays_transponders,'all_column');
	echo sprintf("%s unike transpondere\n",count($todays_transponders));

	$q_todays_passings=sprintf('SELECT count(passing_number) FROM %s WHERE %s',$laptimes->table,$where_today); //Get todays drivers
	$todays_passings=$laptimes->db->query($q_todays_passings,'column');
	echo sprintf("%s passeringer\n",$todays_passings);
	if($todays_passings==0)
	{
		echo "\n";
		continue;
	}
		
	$todays_last_passing=$laptimes->db->query(sprintf('SELECT * FROM %s WHERE %s ORDER BY rtc_time DESC LIMIT 1',$laptimes->table,$where_today),'assoc'); //Get todays drivers
	echo sprintf("Siste passering: %s\n",date('H:i',substr($todays_last_passing['rtc_time'],0,-6)));

	echo "\n";
}
	
?>
</body>
</html>
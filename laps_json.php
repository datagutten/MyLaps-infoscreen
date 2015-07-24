<?Php
require 'config.php';
$start=time();
//ini_set('display_errors',1);

$url="http://www.mylaps.com/api/practicelocation?evalScripts=true&id=$track_id&limit=$limit&page=1";
$data=json_decode(file_get_contents($url),true);
$datetime_now=new DateTime();
$cachedir='cache';
if(!file_exists($cachedir))
	mkdir($cachedir);
foreach($data['activities'] as $activity_key=>$activity)
{
	//print_r($activity);
	//break;
	/*Avatar
	Nick
	Transponder
	LapTime*/

	$userdata_url="http://www.mylaps.com/api/practiceactivity?activityID={$activity['id']}&chipID=&refreshInterval=0&view=table";

	if($activity['rawDate']!=date('Y-m-d')) //Activites from previous days can be cached
	{
		if(!file_exists($cachefile="$cachedir/{$activity['id']}.json"))
		{
			$userdata=file_get_contents($userdata_url); //Fetch data from MyLaps
			file_put_contents($cachefile,$userdata); //Write to cache
		}
		else
			$userdata=file_get_contents($cachefile);
	}
	else //Always fetch data for todays activities
		$userdata=file_get_contents($userdata_url); //Fetch data from MyLaps
	$userdata=json_decode($userdata,true);
	//print_r($activity);

	if(empty($userdata['chip']['sessions']))
	{
		//echo "Skip {$activity['id']}\n";
		continue;
	}
	foreach($userdata['chip']['sessions'] as $session_key=>$session) //Loop through the sessions to get the best times
	{
		foreach($session['items'] as $lap)
		{
			if($lap['class']=='best-time') //Days best time
				$other_info['best-time']=$lap['lapTime'];
			if($lap['class']=='best-session') //Best time in last session
				$other_info['best-session']=$lap['lapTime']; //The value will be overwritten until the last session
		}
	}
	
	$userdata2=$userdata;
	$last_session=array_pop($userdata['chip']['sessions']); //Get last session
	if(empty($last_session['items']))
		$last_session=array_pop($userdata['chip']['sessions']); //Get the next to last session

	$last_round=array_pop($last_session['items']); //Get last round of last session
	
	if(empty($activity['person']['avatar']))
		$activity['person']['avatar']=false;
	if(!isset($activity['person']['nickname']))
		$activity['person']['nickname']='';
	$personinfo=array('avatar'=>$activity['person']['avatar'],'nickname'=>$activity['person']['nickname'],'transponder'=>$activity['chiplabel']);
	//$lapinfo=array('laptime'=>$last_round['lapTime'],'difference'=>$last_round['difference'],'class'=>$last_round['class']
	

	//$timezone_mylaps=DateTime::createFromFormat('O', '+06:00')->getTimezone(); //MyLaps are presenting times in a weird timezone
	
	$datetime = new DateTime($last_round['startDate'].' '.$last_round['startTime']/*,$timezone_mylaps*/); //Create a DateTime object from the time
	if($datetime->format('H')>=22) //MyLaps changes the date at 22:00
		$datetime->sub(new dateInterval('P1D')); //Subtract one day
	//$datetime contains a correct time in the MyLaps timezone. Set the time zone and display the date
	
	$local_time = new DateTimeZone('Europe/Oslo'); //Set the local time zone
	//$datetime->setTimezone($local_time); //Convert the date and time
	
	$last_round['datetime']=$datetime->format('H:i:s d.m.Y');

	$data_out[$activity['id'].'_'.$last_round['lap']]=array_merge($personinfo,$last_round,$other_info);
}

$json=json_encode($data_out);
echo $json;
//print_r(json_decode($json,true));
?>
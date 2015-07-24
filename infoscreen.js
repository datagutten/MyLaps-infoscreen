"use strict";
//http://stackoverflow.com/questions/247483/http-get-request-in-javascript
function handler() {
  if(this.status !== 200) {
   document.getElementById('time').textContent='GET error: '+this.status;
  }
}

function httpGet(theUrl)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onload = handler;
    xmlHttp.open( "GET", theUrl, false );
    xmlHttp.send(null);
    return xmlHttp.responseText;
}

function laptimes()
{
    var div_laps=document.getElementById('laps');
    var laps_raw=httpGet('laps_json.php');

    var laps=JSON.parse(laps_raw); //Parse JSON

    div_laps.innerHTML=''; //Clear the page

    //Create table
    var table_laps=document.createElement('table');
    table_laps.setAttribute('border','1');
    table_laps.setAttribute('class','laps');
    div_laps.appendChild(table_laps);

    //Create header row
    var tr_header=document.createElement('tr');

    //User nick and avatar
    var th_user=document.createElement('th');
    th_user.textContent='Driver';
    tr_header.appendChild(th_user);

    //Transponder number or name
    var th_transponder=document.createElement('th');
    th_transponder.textContent='Transponder';
    tr_header.appendChild(th_transponder);

    //Last lap time
    var th_last_laptime=document.createElement('th');
    th_last_laptime.textContent='Last lap';
    tr_header.appendChild(th_last_laptime);

    //Best lap time in session
    var th_session_best_laptime=document.createElement('th');
    th_session_best_laptime.textContent='Session best';
    tr_header.appendChild(th_session_best_laptime);

    //Best lap time today
    var th_days_best_laptime=document.createElement('th');
    th_days_best_laptime.textContent='Days best';
    tr_header.appendChild(th_days_best_laptime);
    //Average time
    /*var th_average_laptime=document.createElement('th');
    th_average_laptime.textContent='Average';
    tr_header.appendChild(th_average_laptime);*/

    //Lap start time
    var th_starttime=document.createElement('th');
    th_starttime.textContent='Start time';
    tr_header.appendChild(th_starttime);

    table_laps.appendChild(tr_header);
	
	//Declare variables to be used in the loop
    var lap;
	var current_lap;
	var tr_lap;
	var td_avatarnick;
	var img_avatar;
	var span_nickname;
	var td_transponder;
	var td_laptime;
	var td_session_best_laptime;
	var td_days_best_laptime;
	var td_datetime;
	//var td_average_laptime;
    for (lap in laps)
    {
        tr_lap=document.createElement('tr');

        table_laps.appendChild(tr_lap);
        current_lap=laps[lap];
        tr_lap.setAttribute('class',current_lap.class);
        //keys=Object.keys(current_lap);

        //User nick and avatar
        td_avatarnick=document.createElement('td');
        td_avatarnick.setAttribute('class','avatarnick');

        if(current_lap.avatar.length>10) //User got avatar
        {
            img_avatar=document.createElement('img');
            img_avatar.setAttribute('src','data:image/jpg;base64, '+current_lap.avatar);
            img_avatar.setAttribute('class','avatar');
            td_avatarnick.appendChild(img_avatar);
        }

        if(current_lap.nickname.length>0) //Check if user got nickname
        {
            span_nickname=document.createElement('span');
            span_nickname.textContent=current_lap.nickname;
            td_avatarnick.appendChild(span_nickname);
        }

        tr_lap.appendChild(td_avatarnick);

        //Transponder number or name
        td_transponder=document.createElement('td');
        td_transponder.textContent=current_lap.transponder;
        tr_lap.appendChild(td_transponder);

        //Current lap time
        td_laptime=document.createElement('td');
        td_laptime.textContent=current_lap.lapTime;
        tr_lap.appendChild(td_laptime);

        //Best in session
        td_session_best_laptime=document.createElement('td');
        td_session_best_laptime.textContent=current_lap['best-session'];
        tr_lap.appendChild(td_session_best_laptime);

        //Best lap time
        td_days_best_laptime=document.createElement('td');
        td_days_best_laptime.textContent=current_lap['best-time'];
        tr_lap.appendChild(td_days_best_laptime);

        td_datetime=document.createElement('td');
        td_datetime.textContent=current_lap.datetime; //Date is formatted in PHP
        tr_lap.appendChild(td_datetime);

		//Average lap time
		/*td_average_laptime=document.createElement('td');
		td_average_laptime.textContent=current_lap.average;
		tr_lap.appendChild(td_average_laptime);*/

    }
    startTime();
}

function checkTime(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}
//Show when the page is updated
function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    // add a zero in front of numbers<10
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('time').innerHTML ="Updated: " + h + ":" + m + ":" + s;
    /*t = setTimeout(function () {
        startTime()
    }, 500);*/
}
function pageload()
{
    document.getElementById('laps').textContent="Loading laptimes. Please wait.";
    laptimes();
    var refresh_time=10000; //10s refresh time
    setInterval(function () {laptimes(); },refresh_time);
}
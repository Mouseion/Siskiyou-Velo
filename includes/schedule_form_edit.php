<form id="schedpage" name="schedpage" method="post" action="includes/schedule_edit_exec.php" onSubmit="return validate();">

<?php

include ("includes/db_connect.php");

$urlID = $_GET['id'];

$result = mysql_query("SELECT * FROM sv_schedule WHERE ID = $urlID ", $connection);
					  
if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {

$timestamp = $row["date"];
$date = $row["date"];
$month = date(m, $date);
$day = date(d, $date);
$year = date(Y, $date);
$time = $row["time"];
$start = $row["start"];
$start_city = $row["start_city"];
$start_link = $row["start_link"];
$ride_name = $row["ride_name"];
$ride_link = $row["ride_link"];
$pace = $row["pace"];
$distance = $row["distance"];
$ride_leader = $row["ride_leader"];
$phone = $row["phone"];
$email = $row["email"];
$map_link = $row['map_link'];
$terrain = $row['terrain'];
$notes = $row['notes'];
$ID = $row["ID"];

echo "
<input id='checkPace' type='hidden' value='".$pace."' />
<input id='checkLink' type='hidden' value='".$ride_link."' />
<input id='checkStart' type='hidden' value='".strip_tags($start_link)."' />
<input id='checkTerrain' type='hidden' value='".$terrain."' />

<h2>Time &amp; Date</h2>
<table class='schedule' width='654' border='0'>
    <tr>
    	<td width='25%'><input type='hidden' value='$ID' id='rideID' name='rideID' /><label for'month'>Month</label></td>
    	<td width='25%'><label for'day'>Day</label></td>
    	<td width='25%'><label for'year'>Year</label></td>
    	<td width='25%'><label for'time'>Start Time</label></td>
	</tr>
    <tr>        
        <td><input class='required' id='month' name='month' type='text' maxlength='2' value='$month' /></td>
        <td><input class='required' id='day' name='day' type='text' maxlength='2' value='$day' /></td>
        <td><input class='required' id='year' name='year' value='$year' maxlength='4' minlength='4' type='text' /></td>
        <td><input class='required' id='time' name='time' value='$time' minlength='3' maxlength='10' type='text' /></td>
    </tr>    
</table>

<h2>Ride Location</h2>
<table class='schedule' width='654' border='0'>
    <tr>
    	<td width='33%'><label for'start_link'>Start Location List</label></td>
    	<td width='33%'><label for'start'>Start Location<em style='font-size:9px; font-weight:normal; color:#FFF;'> (If not in list)</em></label></td>
    	<td width='33%'><label for'city'>Start City<em style='font-size:9px; font-weight:normal; color:#FFF;'> (If not in list)</em></label></td>
	</tr>
    <tr>      
        <td valign='top'>";

include('includes/startLocations.php');

echo "   </td>
        <td><input id='start' name='start' type='text' value='$start' /></td>
        <td><input id='city' name='city' type='text' value='$start_city' /></td>
	</tr>
</table>

<h2>Ride Details</h2>
<table class='schedule' width='654' border='0'>
    <tr>
    	<td  width='33%'><label for'ride_name'>Ride Name</label></td>
    	<td  width='33%'><label for'ride_link'>Ride Link (if applies)</label></td>
		<td  width='33%'><label for'map_link'>Map Link (if applies)</label></td>
	</tr>
    <tr>
        <td><input id='ride_name' name='ride_name' class='required' minlength='2' value='$ride_name' type='text' /></td>
        <td valign='top'>
			<select id='ride_link' name='ride_link' class='compare'>
	            <option value=''>Choose Ride for Link (if applies)</option>";
		
include ('includes/db_connect.php');

//DB Query
$result = mysql_query("SELECT * FROM sv_rides ORDER BY ridename", $connection);
if (!result) {
	die("Database query failed: " . mysql_error());
}

//Use returned data
while ($row = mysql_fetch_array($result)) {
$id = $row["ID"];
$ridename = $row["ridename"];

echo '<option value="ride_detail.php?id='.$id.'">'.$ridename.'</option>
';

}

echo "        </select></td>
			  <td><input id='map_link' class='url' name='map_link' type='text' value='".$map_link."' /></td>
	</tr>
    <tr>
    	<td><label for'pace'>Pace</label></td>
        <td><label for'pace'>Terrain</label></td>
    	<td><label for'distance'>Distance</label></td>
	</tr>
    <tr>        
        <td valign='top'><select id='pace' name='pace' type='text' class='compare'>
            <option value=''>Choose Pace</option>
            <option value='TBD'>TBD</option>
            <option value='Leisurely (7-9 mph)'>Leisurely (7-9 mph)</option>
            <option value='Mellow Pace (10-12mph)'>Mellow (10-12mph)</option>
            <option value='Moderate Pace (12-14mph)'>Moderate (12-14mph)</option>
            <option value='Brisk Pace (14-17mph)'>Brisk (14-18mph)</option>
            <option value='Brisk Plus Pace (&gt 17mph)'>Brisk Plus (&gt 17mph)</option>
            <option value='Race Pace (&gt 20mph)'>Race Pace (&gt 20mph)</option> 
        </select></td>
		
        <td valign='top'>
        <select id='terrain' name='terrain' type='text' class='compare'>
            <option value=''>Choose Terrain</option>
            <option value='TBD'>TBD</option>
            <option value='Flat'>Flat</option>
            <option value='Some Hills'>Some Hills</option>
            <option value='Hilly'>Hilly</option>
            <option value='Mountainous'>Mountainous</option>
        </select>
        </td>		
		
        <td><input id='distance' name='distance' type='text' value='$distance' /></td>
</tr>    
</table>

<h2>Ride Leader</h2>
<table class='schedule' width='654' border='0'>
    <tr>
    	<td><label for'ride_leader'>Ride Leader</label></td>
    	<td><label for'phone'>Phone Number</label></td>
    	<td><label for'email'>Email Address</label></td>
    </tr>
	<tr>
        <td><input id='ride_leader' name='ride_leader' value='$ride_leader' type='text' /></td>
        <td><input id='phone' name='phone' type='text' value='$phone' /></td>
        <td><input id='email' name='email' type='text' value='$email' /></td>
	</tr>
</table>

<h2>Notes</h2>
<textarea id='notes' name='notes' maxlength='330'>".$notes."</textarea>
";
}

?> 

<table width="654" border="0">
	<tr valign="middle">
        <td width="500" rowspan="2"><input type="submit" value="Update" class="submit" /></td>
		<td><label for="delete">Delete Ride</label>
            <input style="width:15px; margin:3px 0 0 7px;" id="delete" name="delete" type="checkbox" value="delete" /></td>
	</tr>
</table>

</form>

<p><a href="schedule.php">View Schedule</a></p>


<h1>Field Definitions</h1>
<ul class="mainlist">
    <li class="head"><strong>Month</strong>:</li>
    <li>1-or-2-digit month (11)</li>
    
    <li class="head"><strong>Day</strong>:</li>
    <li>1-or-2-digit day (25)</li>
    
    <li class="head"><strong>Year</strong>:</li>
    <li>4-digit year (2009)</li>
    
    <li class="head"><strong>Start Location List</strong>:</li>
    <li>Choose start location from list - this will autopopulate the city and link.</li>
    
    <li class="head"><strong>Start Location</strong>:</li>
    <li>Name of start location if not listed in pulldown list.</li>
    
    <li class="head"><strong>Start City</strong>:</li>
    <li>City where start is located (if location isn't in pulldown list</li>
    
    <li class="head"><strong>Ride Name</strong>:</li>
    <li>Name of Ride</li>
    
    <li class="head"><strong>Ride Link</strong>:</li>
    <li>If ride shows in list, select it to link to Ride Description &amp; Map page</li>
    
    <li class="head"><strong>Map Link</strong>:</li>
    <li>Paste URL to Google Maps (or similar) ride map. Unnecessary if Ride Link is selected.</li>
    
    <li class="head"><strong>Pace</strong>:</li>
    <li>Choose ride pace from list</li>
    
    <li class="head"><strong>Distance</strong>:</li>
    <li>Distance in miles or TBD (40)</li>
    
    <li class="head"><strong>Ride Leader</strong>:</li>
    <li>Enter rider's name or enter &ldquo;No Ride Leader&rdquo;</li>
    
    <li class="head"><strong>Phone Number</strong>:</li>
    <li>Ride Leader's 7-or-10 digit phone number</li>
    
    <li class="head"><strong>Email Address</strong>:</li>
    <li>Ride Leader's email address</li>
    
    <li class="head"><strong>Notes</strong>:</li>
    <li>Append short comment to ride. No HTML please.</li>
</ul>

<script type="text/javascript">
$(document).ready(function() {
	var pace = $('input#checkPace').val();
	var checkLink = $('input#checkLink').val();
	var checkStart = $('input#checkStart').val();
	var checkTerrain = $('input#checkTerrain').val();
	$('select.compare option').each(function() {
		if ($(this).val() == pace || $(this).val() == checkLink  || $(this).text() == checkStart || $(this).val() == checkTerrain) {
			$(this).attr({'selected' : 'selected'})
		}
	});
});
</script>
<?php
session_start();
$inactive = 3600; // Set timeout period in seconds
$start_time = microtime(true);
if (isset($_SESSION['timeout'])) {
    $session_life = time() - $_SESSION['timeout'];
    if ($session_life > $inactive) {
        session_destroy();
        header("Location: index.php?login=expired");
    }
}
if(isset($_SESSION['User_Id'])){}else
{
session_destroy();
header("Location: index.php?login=expired");
}
$_SESSION['timeout'] = time();
$Department=$_SESSION['Department'];
$Designation=$_SESSION['Designation'];
$UserName=$_SESSION['User_Name'];
$User_Id=$_SESSION['User_Id'];
$User_AdminStatus=$_SESSION['UserAdminStatus'];
$adminstatus=$_SESSION['UserAdminStatus'];
$supervisorstatus=$_SESSION['UserSupervisorStatus'];
$managerstatus=$_SESSION['UserManagerStatus'];


include("ajax.php");
include("connectivity.php");
?>
<html>
<head>
<title>HRM</title>
<link href="tstyle.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="jquerycal/jquery.calendars.picker.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" src="jquery.11.0.min.js"></script>

<script type="text/javascript" src="jquerycal/jquery.calendars.js"></script> 
<script type="text/javascript" src="jquerycal/jquery.calendars.plus.js"></script>
<script src="jquerycal/jquery.plugin.js"></script>
<!--<script src="jquery.calendars.all.js"></script><!-- Use instead of calendars, plus, and picker below -->

<script src="jquerycal/jquery.calendars.picker.js"></script>
<!--<script src="jquery.calendars.picker.ext.js"></script><!-- Include for ThemeRoller styling -->
<script src="jquerycal/jquery.calendars.islamic.js"></script>
<script type="text/javascript" src="jquerycal/jquery.plugin.js"></script> 
<script type="text/javascript" src="jquerycal/jquery.calendars.picker.js"></script>
<script type="text/javascript">

</script>
</head><style type="text/css">
    .box{
        display: block;
    }
    .en{  display: block; }
    .ar{   display: block;}
    .blue{ background: #0000ff; }
</style>
<script>
$(function() {
//	$.calendars.picker.setDefaults({renderer: $.calendars.picker.themeRollerRenderer}); // Requires jquery.calendars.picker.ext.js
	var calendar1 = $.calendars.instance('islamic');
	$('#arDatefrom').calendarsPicker({calendar: calendar1});
	var calendar2 = $.calendars.instance('islamic');
	$('#arDateto').calendarsPicker({calendar: calendar2});
	//$('#arDateto').calendarsPicker({calendar: calendar});
$('#startPicker,#endPicker').calendarsPicker({ 
    onSelect: customRange, showTrigger: '<img src="jquerycal/calendar.gif" alt="Popup" class="trigger">'}); 
     
function customRange(dates) { 
    if (this.id == 'startPicker') { 
        $('#endPicker').calendarsPicker('option',{dateFormat: mm/dd/yyyy},'minDate',dates[0] || null); 
    } 
    else { 
        $('#startPicker').calendarsPicker('option',{dateFormat: mm/dd/yyyy},'maxDate',dates[0] || null); 
    } 
	 
}
});
     
function showDate(date) {
	alert('The date chosen is ' + date);
}
</script>

<div id="google_translate_element"></div>
<style type="text/css">

  #modal_wrapper.overlay:before {
    content: " ";
    width: 100%;
    height: 100%;
    position: fixed;
    z-index: 100;
    top: 0;
    left: 0;
    background: #000;
    background: rgba(0,0,0,0.7);
  }

  #modal_window {
    display: none;
    z-index: 200;
    position: fixed;
    left: 50%;
    top: 50%;
    width: 360px;
    padding: 10px 20px;
    background: #fff;
    border: 5px solid #999;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.5);
  }

  #modal_wrapper.overlay #modal_window {
    display: block;
  }

</style>


<!-- 
	After you copied those 2 lines of code , make sure you take also the files into the same folder :-)
    Next step will be to set the appropriate statement to "start-up" the calendar on the needed HTML element.
    
    The first example of Javascript snippet is for the most basic use , as a popup calendar
    for a text field input.
-->

</head>
<body>
<table align="center" cellpadding="0" cellspacing="0" border="1" width="1200" height="600">
<!-- <tr><td colspan="3" align="center"></td></tr> -->

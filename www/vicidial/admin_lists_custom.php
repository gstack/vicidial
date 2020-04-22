<?php
# admin_lists_custom.php
# 
# Copyright (C) 2016  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# this screen manages the custom lists fields in ViciDial
#
# changes:
# 100506-1801 - First Build
# 100507-1027 - Added name position and options position, added extra space for name and help
# 100508-1855 - Added field_order to allow for multiple fields on the same line
# 100509-0922 - Added copy fields options
# 100510-1130 - Added DISPLAY field type option
# 100629-0200 - Added SCRIPT field type option
# 100722-1313 - Added field validation for label and name
# 100728-1724 - Added field validation for select lists and checkbox/radio buttons
# 100916-1754 - Do not show help in example form if help is empty
# 101228-2049 - Fixed missing PHP long tag
# 110629-1438 - Fixed change from DISPLAY or SCRIPT to other field type error, added HIDDEN and READONLY field types
# 110719-0910 - Added HIDEBLOB field type
# 110730-1106 - Added mysql reserved words check for add-field action
# 111025-1432 - Fixed case sensitivity on list fields
# 120122-1349 - Force vicidial_list custom field labels to be all lower case
# 120223-2315 - Removed logging of good login passwords if webroot writable is enabled
# 120713-2101 - Added extended_vl_fields option
# 120907-1209 - Raised extended fields up to 99
# 130508-1020 - Added default field and length check validation, made errors appear in bold red text
# 130606-0545 - Finalized changing of all ereg instances to preg
# 130621-1736 - Added filtering of input to prevent SQL injection attacks and new user auth
# 130902-0752 - Changed to mysqli PHP functions
# 140705-0811 - Added better error handling, hid field_required field since it is non-functional
# 140811-2110 - Fixes for issues with default fields
# 141006-0903 - Finalized adding QXZ translation to all admin files
# 141230-0018 - Added code for on-the-fly language translations display
# 150626-2120 - Modified mysqli_error() to mysqli_connect_error() where appropriate
# 151007-2001 - Fixed issue with field deletion
# 160325-1431 - Changes for sidebar update
# 160404-0938 - design changes
# 160414-1243 - Fixed translation issue with COPY form
# 160429-1125 - Added admin_row_click option
# 160508-0219 - Added screen colors feature
# 160510-2108 - Fixing issues with using only standard fields
#

$admin_version = '2.12-32';
$build = '160510-2108';

require("dbconnect_mysqli.php");
require("functions.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
if (isset($_GET["DB"]))							{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))				{$DB=$_POST["DB"];}
if (isset($_GET["action"]))						{$action=$_GET["action"];}
	elseif (isset($_POST["action"]))			{$action=$_POST["action"];}
if (isset($_GET["list_id"]))					{$list_id=$_GET["list_id"];}
	elseif (isset($_POST["list_id"]))			{$list_id=$_POST["list_id"];}
if (isset($_GET["field_id"]))					{$field_id=$_GET["field_id"];}
	elseif (isset($_POST["field_id"]))			{$field_id=$_POST["field_id"];}
if (isset($_GET["field_label"]))				{$field_label=$_GET["field_label"];}
	elseif (isset($_POST["field_label"]))		{$field_label=$_POST["field_label"];}
if (isset($_GET["field_name"]))					{$field_name=$_GET["field_name"];}
	elseif (isset($_POST["field_name"]))		{$field_name=$_POST["field_name"];}
if (isset($_GET["field_description"]))			{$field_description=$_GET["field_description"];}
	elseif (isset($_POST["field_description"]))	{$field_description=$_POST["field_description"];}
if (isset($_GET["field_rank"]))					{$field_rank=$_GET["field_rank"];}
	elseif (isset($_POST["field_rank"]))		{$field_rank=$_POST["field_rank"];}
if (isset($_GET["field_help"]))					{$field_help=$_GET["field_help"];}
	elseif (isset($_POST["field_help"]))		{$field_help=$_POST["field_help"];}
if (isset($_GET["field_type"]))					{$field_type=$_GET["field_type"];}
	elseif (isset($_POST["field_type"]))		{$field_type=$_POST["field_type"];}
if (isset($_GET["field_options"]))				{$field_options=$_GET["field_options"];}
	elseif (isset($_POST["field_options"]))		{$field_options=$_POST["field_options"];}
if (isset($_GET["field_size"]))					{$field_size=$_GET["field_size"];}
	elseif (isset($_POST["field_size"]))		{$field_size=$_POST["field_size"];}
if (isset($_GET["field_max"]))					{$field_max=$_GET["field_max"];}
	elseif (isset($_POST["field_max"]))			{$field_max=$_POST["field_max"];}
if (isset($_GET["field_default"]))				{$field_default=$_GET["field_default"];}
	elseif (isset($_POST["field_default"]))		{$field_default=$_POST["field_default"];}
if (isset($_GET["field_cost"]))					{$field_cost=$_GET["field_cost"];}
	elseif (isset($_POST["field_cost"]))		{$field_cost=$_POST["field_cost"];}
if (isset($_GET["field_required"]))				{$field_required=$_GET["field_required"];}
	elseif (isset($_POST["field_required"]))	{$field_required=$_POST["field_required"];}
if (isset($_GET["name_position"]))				{$name_position=$_GET["name_position"];}
	elseif (isset($_POST["name_position"]))		{$name_position=$_POST["name_position"];}
if (isset($_GET["multi_position"]))				{$multi_position=$_GET["multi_position"];}
	elseif (isset($_POST["multi_position"]))	{$multi_position=$_POST["multi_position"];}
if (isset($_GET["field_order"]))				{$field_order=$_GET["field_order"];}
	elseif (isset($_POST["field_order"]))		{$field_order=$_POST["field_order"];}
if (isset($_GET["source_list_id"]))				{$source_list_id=$_GET["source_list_id"];}
	elseif (isset($_POST["source_list_id"]))	{$source_list_id=$_POST["source_list_id"];}
if (isset($_GET["copy_option"]))				{$copy_option=$_GET["copy_option"];}
	elseif (isset($_POST["copy_option"]))		{$copy_option=$_POST["copy_option"];}
if (isset($_GET["ConFiRm"]))					{$ConFiRm=$_GET["ConFiRm"];}
	elseif (isset($_POST["ConFiRm"]))			{$ConFiRm=$_POST["ConFiRm"];}
if (isset($_GET["SUBMIT"]))						{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))			{$SUBMIT=$_POST["SUBMIT"];}

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
$stmt = "SELECT use_non_latin,webroot_writable,outbound_autodial_active,user_territories_active,custom_fields_enabled,enable_languages,language_method FROM system_settings;";
$rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {echo "$stmt\n";}
$qm_conf_ct = mysqli_num_rows($rslt);
if ($qm_conf_ct > 0)
	{
	$row=mysqli_fetch_row($rslt);
	$non_latin =					$row[0];
	$webroot_writable =				$row[1];
	$SSoutbound_autodial_active =	$row[2];
	$user_territories_active =		$row[3];
	$SScustom_fields_enabled =		$row[4];
	$SSenable_languages =			$row[5];
	$SSlanguage_method =			$row[6];
	}
##### END SETTINGS LOOKUP #####
###########################################

if ( (strlen($action) < 2) and ($list_id > 99) )
	{$action = 'MODIFY_CUSTOM_FIELDS';}
if (strlen($action) < 2)
	{$action = 'LIST';}
if (strlen($DB) < 1)
	{$DB=0;}
if ($field_size > 100)
	{$field_size = 100;}
if ( (strlen($field_size) < 1) or ($field_size < 1) )
	{$field_size = 1;}
if ( (strlen($field_max) < 1) or ($field_max < 1) )
	{$field_max = 1;}

if ($non_latin < 1)
	{
	$PHP_AUTH_USER = preg_replace('/[^-_0-9a-zA-Z]/','',$PHP_AUTH_USER);
	$PHP_AUTH_PW = preg_replace('/[^-_0-9a-zA-Z]/','',$PHP_AUTH_PW);

	$list_id = preg_replace('/[^0-9]/','',$list_id);
	$field_id = preg_replace('/[^0-9]/','',$field_id);
	$field_rank = preg_replace('/[^0-9]/','',$field_rank);
	$field_size = preg_replace('/[^0-9]/','',$field_size);
	$field_max = preg_replace('/[^0-9]/','',$field_max);
	$field_order = preg_replace('/[^0-9]/','',$field_order);
	$source_list_id = preg_replace('/[^0-9]/','',$source_list_id);

	$field_required = preg_replace('/[^NY]/','',$field_required);

	$field_type = preg_replace('/[^0-9a-zA-Z]/','',$field_type);
	$ConFiRm = preg_replace('/[^0-9a-zA-Z]/','',$ConFiRm);
	$name_position = preg_replace('/[^0-9a-zA-Z]/','',$name_position);
	$multi_position = preg_replace('/[^0-9a-zA-Z]/','',$multi_position);

	$field_label = preg_replace('/[^_0-9a-zA-Z]/','',$field_label);
	$copy_option = preg_replace('/[^_0-9a-zA-Z]/','',$copy_option);

	$field_name = preg_replace('/[^ \.\,-\_0-9a-zA-Z]/','',$field_name);
	$field_description = preg_replace('/[^ \.\,-\_0-9a-zA-Z]/','',$field_description);
	$field_options = preg_replace('/[^ \.\n\,-\_0-9a-zA-Z]/', '',$field_options);
	$field_default = preg_replace('/[^ \.\n\,-\_0-9a-zA-Z]/', '',$field_default);
	}	# end of non_latin
else
	{
	$PHP_AUTH_USER = preg_replace("/'|\"|\\\\|;/","",$PHP_AUTH_USER);
	$PHP_AUTH_PW = preg_replace("/'|\"|\\\\|;/","",$PHP_AUTH_PW);
	}

$STARTtime = date("U");
$TODAY = date("Y-m-d");
$NOW_TIME = date("Y-m-d H:i:s");
$date = date("r");
$ip = getenv("REMOTE_ADDR");
$browser = getenv("HTTP_USER_AGENT");
$user = $PHP_AUTH_USER;

if (file_exists('options.php'))
	{require('options.php');}

$vicidial_list_fields = '|lead_id|vendor_lead_code|source_id|list_id|gmt_offset_now|called_since_last_reset|phone_code|phone_number|title|first_name|middle_initial|last_name|address1|address2|address3|city|state|province|postal_code|country_code|gender|date_of_birth|alt_phone|email|security_phrase|comments|called_count|last_local_call_time|rank|owner|status|entry_date|entry_list_id|modify_date|user|';

if ($extended_vl_fields > 0)
	{
	$vicidial_list_fields = '|lead_id|vendor_lead_code|source_id|list_id|gmt_offset_now|called_since_last_reset|phone_code|phone_number|title|first_name|middle_initial|last_name|address1|address2|address3|city|state|province|postal_code|country_code|gender|date_of_birth|alt_phone|email|security_phrase|comments|called_count|last_local_call_time|rank|owner|status|entry_date|entry_list_id|modify_date|user|q01|q02|q03|q04|q05|q06|q07|q08|q09|q10|q11|q12|q13|q14|q15|q16|q17|q18|q19|q20|q21|q22|q23|q24|q25|q26|q27|q28|q29|q30|q31|q32|q33|q34|q35|q36|q37|q38|q39|q40|q41|q42|q43|q44|q45|q46|q47|q48|q49|q50|q51|q52|q53|q54|q55|q56|q57|q58|q59|q60|q61|q62|q63|q64|q65|q66|q67|q68|q69|q70|q71|q72|q73|q74|q75|q76|q77|q78|q79|q80|q81|q82|q83|q84|q85|q86|q87|q88|q89|q90|q91|q92|q93|q94|q95|q96|q97|q98|q99|';
	}

$mysql_reserved_words =
'|accessible|action|add|all|alter|analyze|and|as|asc|asensitive|before|between|bigint|binary|bit|blob|both|by|call|cascade|case|change|char|character|check|collate|column|condition|constraint|continue|convert|create|cross|current_date|current_time|current_timestamp|current_user|cursor|database|databases|date|day_hour|day_microsecond|day_minute|day_second|dec|decimal|declare|default|delayed|delete|desc|describe|deterministic|distinct|distinctrow|div|double|drop|dual|each|else|elseif|enclosed|enum|escaped|exists|exit|explain|false|fetch|float|float4|float8|for|force|foreign|from|fulltext|grant|group|having|high_priority|hour_microsecond|hour_minute|hour_second|if|ignore|in|index|infile|inner|inout|insensitive|insert|int|int1|int2|int3|int4|int8|integer|interval|into|is|iterate|join|key|keys|kill|leading|leave|left|like|limit|linear|lines|load|localtime|localtimestamp|lock|long|longblob|longtext|loop|low_priority|master_ssl_verify_server_cert|match|mediumblob|mediumint|mediumtext|middleint|minute_microsecond|minute_second|mod|modifies|mysql|natural|no|no_write_to_binlog|not|null|numeric|on|optimize|option|optionally|or|order|out|outer|outfile|precision|primary|procedure|purge|range|read|read_only|read_write|reads|real|references|regexp|release|remove|rename|repeat|replace|require|restrict|return|revoke|right|rlike|schema|schemas|second_microsecond|select|sensitive|separator|set|show|smallint|spatial|specific|sql|sql_big_result|sql_calc_found_rows|sql_small_result|sqlexception|sqlstate|sqlwarning|ssl|starting|straight_join|table|terminated|text|then|time|timestamp|tinyblob|tinyint|tinytext|to|trailing|trigger|true|undo|union|unique|unlock|unsigned|update|usage|use|using|utc_date|utc_time|utc_timestamp|values|varbinary|varchar|varcharacter|varying|when|where|while|with|write|xor|year_month|zerofill|lead_id|';

$stmt="SELECT selected_language from vicidial_users where user='$PHP_AUTH_USER';";
if ($DB) {echo "|$stmt|\n";}
$rslt=mysql_to_mysqli($stmt, $link);
$sl_ct = mysqli_num_rows($rslt);
if ($sl_ct > 0)
	{
	$row=mysqli_fetch_row($rslt);
	$VUselected_language =		$row[0];
	}

$auth=0;
$auth_message = user_authorization($PHP_AUTH_USER,$PHP_AUTH_PW,'',1);
if ($auth_message == 'GOOD')
	{$auth=1;}

if ($auth < 1)
	{
	$VDdisplayMESSAGE = _QXZ("Login incorrect, please try again");
	if ($auth_message == 'LOCK')
		{
		$VDdisplayMESSAGE = _QXZ("Too many login attempts, try again in 15 minutes");
		Header ("Content-type: text/html; charset=utf-8");
		echo "$VDdisplayMESSAGE: |$PHP_AUTH_USER|$auth_message|\n";
		exit;
		}
	Header("WWW-Authenticate: Basic realm=\"CONTACT-CENTER-ADMIN\"");
	Header("HTTP/1.0 401 Unauthorized");
	echo "$VDdisplayMESSAGE: |$PHP_AUTH_USER|$PHP_AUTH_PW|$auth_message|\n";
	exit;
	}

$rights_stmt = "SELECT modify_leads from vicidial_users where user='$PHP_AUTH_USER';";
if ($DB) {echo "|$stmt|\n";}
$rights_rslt=mysql_to_mysqli($rights_stmt, $link);
$rights_row=mysqli_fetch_row($rights_rslt);
$modify_leads =		$rights_row[0];

# check their permissions
if ( $modify_leads < 1 )
	{
	header ("Content-type: text/html; charset=utf-8");
	echo _QXZ("You do not have permissions to modify leads")."\n";
	exit;
	}

$stmt="SELECT full_name,modify_leads,custom_fields_modify,user_level from vicidial_users where user='$PHP_AUTH_USER';";
$rslt=mysql_to_mysqli($stmt, $link);
$row=mysqli_fetch_row($rslt);
$LOGfullname =				$row[0];
$LOGmodify_leads =			$row[1];
$LOGcustom_fields_modify =	$row[2];
$LOGuser_level =			$row[3];

?>
<html>
<head>

<?php
if ($action != "HELP")
	{
?>
<script language="JavaScript" src="calendar_db.js"></script>
<link rel="stylesheet" href="calendar.css">

<script language="Javascript">
function open_help(taskspan,taskhelp) 
	{
	document.getElementById("P_" + taskspan).innerHTML = " &nbsp; <a href=\"javascript:close_help('" + taskspan + "','" + taskhelp + "');\">help-</a><BR> &nbsp; ";
	document.getElementById(taskspan).innerHTML = "<B>" + taskhelp + "</B>";
	document.getElementById(taskspan).style.background = "#FFFF99";
	}
function close_help(taskspan,taskhelp) 
	{
	document.getElementById("P_" + taskspan).innerHTML = "";
	document.getElementById(taskspan).innerHTML = " &nbsp; <a href=\"javascript:open_help('" + taskspan + "','" + taskhelp + "');\">help+</a>";
	document.getElementById(taskspan).style.background = "white";
	}
</script>

<?php
	}
?>

<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<title><?php echo _QXZ("ADMINISTRATION: Lists Custom Fields"); ?>
<?php 

################################################################################
##### BEGIN help section
if ($action == "HELP")
	{
	?>
	</title>
	</head>
	<body bgcolor=white>
	<center>
	<TABLE WIDTH=98% BGCOLOR=#E6E6E6 cellpadding=2 cellspacing=4><TR><TD ALIGN=LEFT><FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2>
	<BR>
	<?php echo _QXZ("HELP HAS MOVED to"); ?> help.php
	</TD></TR></TABLE>
	</BODY>
	</HTML>
	<?php
	exit;
	}
### END help section





##### BEGIN Set variables to make header show properly #####
$ADD =					'100';
$hh =					'lists';
$sh =					'custom';
$LOGast_admin_access =	'1';
$SSoutbound_autodial_active = '1';
$ADMIN =				'admin.php';
$page_width='770';
$section_width='750';
$header_font_size='3';
$subheader_font_size='2';
$subcamp_font_size='2';
$header_selected_bold='<b>';
$header_nonselected_bold='';
$lists_color =		'#FFFF99';
$lists_font =		'BLACK';
$lists_color =		'#E6E6E6';
$subcamp_color =	'#C6C6C6';
##### END Set variables to make header show properly #####

require("admin_header.php");

if ( ($LOGcustom_fields_modify < 1) or ($LOGuser_level < 8) )
	{
	echo _QXZ("You are not authorized to view this section")."\n";
	exit;
	}

if ($SScustom_fields_enabled < 1)
	{
	echo "<B><font color=red>"._QXZ("ERROR: Custom Fields are not active on this system")."</B></font>\n";
	exit;
	}


$NWB = " &nbsp; <a href=\"javascript:openNewWindow('./help.php?action=HELP";
$NWE = "')\"><IMG SRC=\"help.gif\" WIDTH=20 HEIGHT=20 BORDER=0 ALT=\"HELP\" ALIGN=TOP></A>";


if ($DB > 0)
{
echo "$DB,$action,$ip,$user,$copy_option,$field_id,$list_id,$source_list_id,$field_label,$field_name,$field_description,$field_rank,$field_help,$field_type,$field_options,$field_size,$field_max,$field_default,$field_required,$field_cost,$multi_position,$name_position,$field_order";
}





################################################################################
##### BEGIN copy fields to a list form
if ($action == "COPY_FIELDS_FORM")
	{
	##### get lists listing for dynamic pulldown
	$stmt="SELECT list_id,list_name from vicidial_lists order by list_id";
	$rsltx=mysql_to_mysqli($stmt, $link);
	$lists_to_print = mysqli_num_rows($rsltx);
	$lists_list='';
	$o=0;
	while ($lists_to_print > $o)
		{
		$rowx=mysqli_fetch_row($rsltx);
		$lists_list .= "<option value=\"$rowx[0]\">$rowx[0] - $rowx[1]</option>\n";
		$o++;
		}

	echo "<TABLE><TR><TD>\n";
	echo "<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2>";
	echo "<br>"._QXZ("Copy Fields to Another List")."<form action=$PHP_SELF method=POST>\n";
	echo "<input type=hidden name=DB value=\"$DB\">\n";
	echo "<input type=hidden name=action value=COPY_FIELDS_SUBMIT>\n";
	echo "<center><TABLE width=$section_width cellspacing=3>\n";
	echo "<tr bgcolor=#$SSstd_row4_background><td align=right>"._QXZ("List ID to Copy Fields From").": </td><td align=left><select size=1 name=source_list_id>\n";
	echo "$lists_list";
	echo "</select></td></tr>\n";
	echo "<tr bgcolor=#$SSstd_row4_background><td align=right>"._QXZ("List ID to Copy Fields to").": </td><td align=left><select size=1 name=list_id>\n";
	echo "$lists_list";
	echo "</select></td></tr>\n";
	echo "<tr bgcolor=#$SSstd_row4_background><td align=right>"._QXZ("Copy Option").": </td><td align=left><select size=1 name=copy_option>\n";
	echo "<option value=\"APPEND\" selected>"._QXZ("APPEND")."</option>";
	echo "<option value=\"UPDATE\">"._QXZ("UPDATE")."</option>";
	echo "<option value=\"REPLACE\">"._QXZ("REPLACE")."</option>";
	echo "</select> $NWB#lists_fields-copy_option$NWE</td></tr>\n";
	echo "<tr bgcolor=#$SSstd_row4_background><td align=center colspan=2><input type=submit name=SUBMIT value='"._QXZ("SUBMIT")."'></td></tr>\n";
	echo "</TABLE></center>\n";
	echo "</TD></TR></TABLE>\n";
	}
### END copy fields to a list form




################################################################################
##### BEGIN copy list fields submit
if ( ($action == "COPY_FIELDS_SUBMIT") and ($list_id > 99) and ($source_list_id > 99) and (strlen($copy_option) > 2) )
	{
	if ($list_id=="$source_list_id")
		{echo "<B><font color=red>"._QXZ("ERROR: You cannot copy fields to the same list").": $list_id|$source_list_id</B></font>\n<BR>";}
	else
		{
		$table_exists=0;
		#$linkCUSTOM=mysql_connect("$VARDB_server:$VARDB_port", "$VARDB_custom_user","$VARDB_custom_pass");

		$linkCUSTOM=mysqli_connect("$VARDB_server", "$VARDB_custom_user", "$VARDB_custom_pass", "$VARDB_database", "$VARDB_port");
		if (!$linkCUSTOM) 
			{
			die('MySQL '._QXZ("connect ERROR").': '. mysqli_connect_error());
			}

		# if (!$linkCUSTOM) {die("Could not connect: $VARDB_server|$VARDB_port|$VARDB_database|$VARDB_custom_user|$VARDB_custom_pass" . mysqli_error());}
		#mysql_select_db("$VARDB_database", $linkCUSTOM);

		$stmt="SELECT count(*) from vicidial_lists_fields where list_id='$source_list_id';";
		if ($DB>0) {echo "$stmt";}
		$rslt=mysql_to_mysqli($stmt, $link);
		$fieldscount_to_print = mysqli_num_rows($rslt);
		if ($fieldscount_to_print > 0) 
			{
			$rowx=mysqli_fetch_row($rslt);
			$source_field_exists =	$rowx[0];
			}
		
		$stmt="SELECT count(*) from vicidial_lists_fields where list_id='$list_id';";
		if ($DB>0) {echo "$stmt";}
		$rslt=mysql_to_mysqli($stmt, $link);
		$fieldscount_to_print = mysqli_num_rows($rslt);
		if ($fieldscount_to_print > 0) 
			{
			$rowx=mysqli_fetch_row($rslt);
			$field_exists =	$rowx[0];
			}
		
		$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
		$rslt=mysql_to_mysqli($stmt, $link);
		$tablecount_to_print = mysqli_num_rows($rslt);
		if ($tablecount_to_print > 0) 
			{$table_exists =	1;}
		if ($DB>0) {echo "$stmt|$tablecount_to_print|$table_exists";}
		
		if ($source_field_exists < 1)
			{echo "<B><font color=red>"._QXZ("ERROR: Source list has no custom fields")."</B></font>\n<BR>";}
		else
			{
			##### REPLACE option #####
			if ($copy_option=='REPLACE')
				{
				if ($DB > 0) {echo _QXZ("Starting REPLACE copy")."\n<BR>";}
				if ($table_exists > 0)
					{
					$stmt="SELECT field_id,field_label from vicidial_lists_fields where list_id='$list_id' order by field_rank,field_order,field_label;";
					$rslt=mysql_to_mysqli($stmt, $link);
					$fields_to_print = mysqli_num_rows($rslt);
					$fields_list='';
					$o=0;
					while ($fields_to_print > $o) 
						{
						$rowx=mysqli_fetch_row($rslt);
						$A_field_id[$o] =			$rowx[0];
						$A_field_label[$o] =		$rowx[1];
						$o++;
						}

					$o=0;
					while ($fields_to_print > $o) 
						{
						### delete field function
						$SQLsuccess = delete_field_function($DB,$link,$linkCUSTOM,$ip,$user,$table_exists,$A_field_id[$o],$list_id,$A_field_label[$o],$A_field_name[$o],$A_field_description[$o],$A_field_rank[$o],$A_field_help[$o],$A_field_type[$o],$A_field_options[$o],$A_field_size[$o],$A_field_max[$o],$A_field_default[$o],$A_field_required[$o],$A_field_cost[$o],$A_multi_position[$o],$A_name_position[$o],$A_field_order[$o],$vicidial_list_fields);

						if ($SQLsuccess > 0)
							{echo _QXZ("SUCCESS: Custom Field Deleted")." - $list_id|$A_field_label[$o]\n<BR>";}
						$o++;
						}
					}
				$copy_option='APPEND';
				}
			##### APPEND option #####
			if ($copy_option=='APPEND')
				{
				if ($DB > 0) {echo _QXZ("Starting APPEND copy")."\n<BR>";}
				$stmt="SELECT field_id,field_label,field_name,field_description,field_rank,field_help,field_type,field_options,field_size,field_max,field_default,field_cost,field_required,multi_position,name_position,field_order from vicidial_lists_fields where list_id='$source_list_id' order by field_rank,field_order,field_label;";
				$rslt=mysql_to_mysqli($stmt, $link);
				$fields_to_print = mysqli_num_rows($rslt);
				$fields_list='';
				$o=0;
				while ($fields_to_print > $o) 
					{
					$rowx=mysqli_fetch_row($rslt);
					$A_field_id[$o] =			$rowx[0];
					$A_field_label[$o] =		$rowx[1];
					$A_field_name[$o] =			$rowx[2];
					$A_field_description[$o] =	$rowx[3];
					$A_field_rank[$o] =			$rowx[4];
					$A_field_help[$o] =			$rowx[5];
					$A_field_type[$o] =			$rowx[6];
					$A_field_options[$o] =		$rowx[7];
					$A_field_size[$o] =			$rowx[8];
					$A_field_max[$o] =			$rowx[9];
					$A_field_default[$o] =		$rowx[10];
					$A_field_cost[$o] =			$rowx[11];
					$A_field_required[$o] =		$rowx[12];
					$A_multi_position[$o] =		$rowx[13];
					$A_name_position[$o] =		$rowx[14];
					$A_field_order[$o] =		$rowx[15];

					$o++;
					$rank_select .= "<option>$o</option>";
					}

				$o=0;
				while ($fields_to_print > $o) 
					{
					$new_field_exists=0;
					$temp_field_label = $A_field_label[$o];
					if ( ($table_exists > 0) or (preg_match("/\|$temp_field_label\|/i",$vicidial_list_fields)) )
						{
						$stmt="SELECT count(*) from vicidial_lists_fields where list_id='$list_id' and field_label='$A_field_label[$o]';";
						if ($DB>0) {echo "$stmt";}
						$rslt=mysql_to_mysqli($stmt, $link);
						$fieldscount_to_print = mysqli_num_rows($rslt);
						if ($fieldscount_to_print > 0) 
							{
							$rowx=mysqli_fetch_row($rslt);
							$new_field_exists =	$rowx[0];
							}
						}
					if ($new_field_exists < 1)
						{
						if (preg_match("/\|$temp_field_label\|/i",$vicidial_list_fields))
							{$A_field_label[$o] = strtolower($A_field_label[$o]);}

						### add field function
						$SQLsuccess = add_field_function($DB,$link,$linkCUSTOM,$ip,$user,$table_exists,$A_field_id[$o],$list_id,$A_field_label[$o],$A_field_name[$o],$A_field_description[$o],$A_field_rank[$o],$A_field_help[$o],$A_field_type[$o],$A_field_options[$o],$A_field_size[$o],$A_field_max[$o],$A_field_default[$o],$A_field_required[$o],$A_field_cost[$o],$A_multi_position[$o],$A_name_position[$o],$A_field_order[$o],$vicidial_list_fields,$mysql_reserved_words);

						if ($SQLsuccess > 0)
							{echo _QXZ("SUCCESS: Custom Field Added")." - $list_id|$A_field_label[$o]\n<BR>";}

						if ($table_exists < 1) {$table_exists=1;}
						}
					else
						{
						if ($DB>0) {echo _QXZ("FIELD EXISTS").": |$list_id|$A_field_label[$o]|";}
						}
					$o++;
					}
				}
			##### UPDATE option #####
			if ($copy_option=='UPDATE')
				{
				if ($DB > 0) {echo _QXZ("Starting UPDATE copy")."\n<BR>";}
				if ( ($table_exists < 1) and (!preg_match("/\|$field_label\|/i",$vicidial_list_fields)) )
					{echo "<B><font color=red>"._QXZ("ERROR: Table does not exist")." custom_$list_id</B></font>\n<BR>";}
				else
					{
					$stmt="SELECT field_id,field_label,field_name,field_description,field_rank,field_help,field_type,field_options,field_size,field_max,field_default,field_cost,field_required,multi_position,name_position,field_order from vicidial_lists_fields where list_id='$source_list_id' order by field_rank,field_order,field_label;";
					$rslt=mysql_to_mysqli($stmt, $link);
					$fields_to_print = mysqli_num_rows($rslt);
					$fields_list='';
					$o=0;
					while ($fields_to_print > $o) 
						{
						$rowx=mysqli_fetch_row($rslt);
						$A_field_id[$o] =			$rowx[0];
						$A_field_label[$o] =		$rowx[1];
						$A_field_name[$o] =			$rowx[2];
						$A_field_description[$o] =	$rowx[3];
						$A_field_rank[$o] =			$rowx[4];
						$A_field_help[$o] =			$rowx[5];
						$A_field_type[$o] =			$rowx[6];
						$A_field_options[$o] =		$rowx[7];
						$A_field_size[$o] =			$rowx[8];
						$A_field_max[$o] =			$rowx[9];
						$A_field_default[$o] =		$rowx[10];
						$A_field_cost[$o] =			$rowx[11];
						$A_field_required[$o] =		$rowx[12];
						$A_multi_position[$o] =		$rowx[13];
						$A_name_position[$o] =		$rowx[14];
						$A_field_order[$o] =		$rowx[15];
						$o++;
						}

					$o=0;
					while ($fields_to_print > $o) 
						{
						$stmt="SELECT field_id from vicidial_lists_fields where list_id='$list_id' and field_label='$A_field_label[$o]';";
						if ($DB>0) {echo "$stmt";}
						$rslt=mysql_to_mysqli($stmt, $link);
						$fieldscount_to_print = mysqli_num_rows($rslt);
						if ($fieldscount_to_print > 0) 
							{
							$rowx=mysqli_fetch_row($rslt);
							$current_field_id =	$rowx[0];

							### modify field function
							$SQLsuccess = modify_field_function($DB,$link,$linkCUSTOM,$ip,$user,$table_exists,$current_field_id,$list_id,$A_field_label[$o],$A_field_name[$o],$A_field_description[$o],$A_field_rank[$o],$A_field_help[$o],$A_field_type[$o],$A_field_options[$o],$A_field_size[$o],$A_field_max[$o],$A_field_default[$o],$A_field_required[$o],$A_field_cost[$o],$A_multi_position[$o],$A_name_position[$o],$A_field_order[$o],$vicidial_list_fields);

							if ($SQLsuccess > 0)
								{echo _QXZ("SUCCESS: Custom Field Modified")." - $list_id|$A_field_label[$o]\n<BR>";}
							}
						$o++;
						}
					}
				}
			}

		$action = "MODIFY_CUSTOM_FIELDS";
		}
	}
### END copy list fields submit





################################################################################
##### BEGIN delete custom field confirmation
if ( ($action == "DELETE_CUSTOM_FIELD_CONFIRMATION") and ($list_id > 99) and ($field_id > 0) and (strlen($field_label) > 0) )
	{
	$stmt="SELECT count(*) from vicidial_lists_fields where list_id='$list_id' and field_label='$field_label';";
	if ($DB>0) {echo "$stmt";}
	$rslt=mysql_to_mysqli($stmt, $link);
	$fieldscount_to_print = mysqli_num_rows($rslt);
	if ($fieldscount_to_print > 0) 
		{
		$rowx=mysqli_fetch_row($rslt);
		$field_exists =	$rowx[0];
		}
	
	$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
	$rslt=mysql_to_mysqli($stmt, $link);
	$tablecount_to_print = mysqli_num_rows($rslt);
	if ($tablecount_to_print > 0) 
		{$table_exists =	1;}
	if ($DB>0) {echo "$stmt|$tablecount_to_print|$table_exists";}
	
	if ($field_exists < 1)
		{echo "<B><font color=red>"._QXZ("ERROR: Field does not exist")."</B></font>\n<BR>";}
	else
		{
		if ( ($table_exists < 1) and (!preg_match("/\|$field_label\|/i",$vicidial_list_fields)) )
			{echo "<B><font color=red>"._QXZ("ERROR: Table does not exist")." custom_$list_id</B></font>\n<BR>";}
		else
			{
			echo "<BR><BR><B><a href=\"$PHP_SELF?action=DELETE_CUSTOM_FIELD&list_id=$list_id&field_id=$field_id&field_label=$field_label&field_type=$field_type&ConFiRm=YES&DB=$DB\">"._QXZ("CLICK HERE TO CONFIRM DELETION OF THIS CUSTOM FIELD").": $field_label - $field_id - $list_id</a></B><BR><BR>";
			}
		}

	$action = "MODIFY_CUSTOM_FIELDS";
	}
### END delete custom field confirmation




################################################################################
##### BEGIN delete custom field
if ( ($action == "DELETE_CUSTOM_FIELD") and ($list_id > 99) and ($field_id > 0) and (strlen($field_label) > 0) and ($ConFiRm=='YES') )
	{
	$table_exists=0;
	#$linkCUSTOM=mysql_connect("$VARDB_server:$VARDB_port", "$VARDB_custom_user","$VARDB_custom_pass");
	#if (!$linkCUSTOM) {die("Could not connect: $VARDB_server|$VARDB_port|$VARDB_database|$VARDB_custom_user|$VARDB_custom_pass" . mysqli_error());}
	#mysql_select_db("$VARDB_database", $linkCUSTOM);
	$linkCUSTOM=mysqli_connect("$VARDB_server", "$VARDB_custom_user", "$VARDB_custom_pass", "$VARDB_database", "$VARDB_port");
	if (!$linkCUSTOM) 
		{
		die('MySQL '._QXZ("connect ERROR").': '. mysqli_connect_error());
		}

	$stmt="SELECT count(*) from vicidial_lists_fields where list_id='$list_id' and field_label='$field_label';";
	if ($DB>0) {echo "$stmt";}
	$rslt=mysql_to_mysqli($stmt, $link);
	$fieldscount_to_print = mysqli_num_rows($rslt);
	if ($fieldscount_to_print > 0) 
		{
		$rowx=mysqli_fetch_row($rslt);
		$field_exists =	$rowx[0];
		}
	
	$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
	$rslt=mysql_to_mysqli($stmt, $link);
	$tablecount_to_print = mysqli_num_rows($rslt);
	if ($tablecount_to_print > 0) 
		{$table_exists =	1;}
	if ($DB>0) {echo "$stmt|$tablecount_to_print|$table_exists";}
	
	if ($field_exists < 1)
		{echo "<B><font color=red>"._QXZ("ERROR: Field does not exist")."</B></font>\n<BR>";}
	else
		{
		if ( ($table_exists < 1) and (!preg_match("/\|$field_label\|/i",$vicidial_list_fields)) )
			{echo "<B><font color=red>"._QXZ("ERROR: Table does not exist")." custom_$list_id</B></font>\n<BR>";}
		else
			{
			### delete field function
			$SQLsuccess = delete_field_function($DB,$link,$linkCUSTOM,$ip,$user,$table_exists,$field_id,$list_id,$field_label,$field_name,$field_description,$field_rank,$field_help,$field_type,$field_options,$field_size,$field_max,$field_default,$field_required,$field_cost,$multi_position,$name_position,$field_order,$vicidial_list_fields);

			if ($SQLsuccess > 0)
				{echo _QXZ("SUCCESS: Custom Field Deleted")." - $list_id|$field_label\n<BR>";}
			}
		}

	$action = "MODIFY_CUSTOM_FIELDS";
	}
### END delete custom field




################################################################################
##### BEGIN add new custom field
if ( ($action == "ADD_CUSTOM_FIELD") and ($list_id > 99) )
	{
	$stmt="SELECT count(*) from vicidial_lists_fields where list_id='$list_id' and field_label='$field_label';";
	if ($DB>0) {echo "$stmt";}
	$rslt=mysql_to_mysqli($stmt, $link);
	$fieldscount_to_print = mysqli_num_rows($rslt);
	if ($fieldscount_to_print > 0) 
		{
		$rowx=mysqli_fetch_row($rslt);
		$field_exists =	$rowx[0];
		}
	
	if ( (strlen($field_label)<1) or (strlen($field_name)<2) or (strlen($field_size)<1) )
		{echo "<B><font color=red>"._QXZ("ERROR: You must enter a field label, field name and field size")." - $list_id|$field_label|$field_name|$field_size</B></font>\n<BR>";}
	else
		{
		if ( ( ($field_type=='TEXT') or ($field_type=='READONLY') or ($field_type=='HIDDEN') ) and ($field_max < strlen($field_default)) )
			{echo "<B><font color=red>"._QXZ("ERROR: Default value cannot be longer than maximum field length")." - $list_id|$field_label|$field_name|$field_max|$field_default|</B></font>\n<BR>";}
		else
			{
			if (preg_match("/\|$field_label\|/i",$mysql_reserved_words))
				{echo "<B><font color=red>"._QXZ("ERROR: You cannot use reserved words for field labels")." - $list_id|$field_label|$field_name|$field_size</B></font>\n<BR>";}
			else
				{
				$TEST_valid_options=0;
				if ( ($field_type=='SELECT') or ($field_type=='MULTI') or ($field_type=='RADIO') or ($field_type=='CHECKBOX') )
					{
					$TESTfield_options_array = explode("\n",$field_options);
					$TESTfield_options_count = count($TESTfield_options_array);
					$te=0;
					while ($te < $TESTfield_options_count)
						{
						if (preg_match("/,/",$TESTfield_options_array[$te]))
							{
							$TESTfield_options_value_array = explode(",",$TESTfield_options_array[$te]);
							if ( (strlen($TESTfield_options_value_array[0]) > 0) and (strlen($TESTfield_options_value_array[1]) > 0) )
								{$TEST_valid_options++;}
							}
						$te++;
						}
					$field_options_ENUM = preg_replace("/.$/",'',$field_options_ENUM);
					}

				if ( ( ($field_type=='SELECT') or ($field_type=='MULTI') or ($field_type=='RADIO') or ($field_type=='CHECKBOX') ) and ( (!preg_match("/,/",$field_options)) or (!preg_match("/\n/",$field_options)) or (strlen($field_options)<6) or ($TEST_valid_options < 1) ) )
					{echo "<B><font color=red>"._QXZ("ERROR: You must enter field options when adding a SELECT, MULTI, RADIO or CHECKBOX field type")."  - $list_id|$field_label|$field_type|$field_options</B></font>\n<BR>";}
				else
					{
					if ($field_exists > 0)
						{echo "<B><font color=red>"._QXZ("ERROR: Field already exists for this list")." - $list_id|$field_label</B></font>\n<BR>";}
					else
						{
						$table_exists=0;
						#$linkCUSTOM=mysql_connect("$VARDB_server:$VARDB_port", "$VARDB_custom_user","$VARDB_custom_pass");
						#if (!$linkCUSTOM) {die("Could not connect: $VARDB_server|$VARDB_port|$VARDB_database|$VARDB_custom_user|$VARDB_custom_pass" . mysqli_error());}
						#mysql_select_db("$VARDB_database", $linkCUSTOM);

						$linkCUSTOM=mysqli_connect("$VARDB_server", "$VARDB_custom_user", "$VARDB_custom_pass", "$VARDB_database", "$VARDB_port");
						if (!$linkCUSTOM) 
							{
							die('MySQL '._QXZ("connect ERROR").': '. mysqli_connect_error());
							}

						$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
						$rslt=mysql_to_mysqli($stmt, $link);
						$tablecount_to_print = mysqli_num_rows($rslt);
						if ($tablecount_to_print > 0) 
							{$table_exists =	1;}
						if ($DB>0) {echo "$stmt|$tablecount_to_print|$table_exists";}
					
						if (preg_match("/\|$field_label\|/i",$vicidial_list_fields))
							{$field_label = strtolower($field_label);}

						### add field function
						$SQLsuccess = add_field_function($DB,$link,$linkCUSTOM,$ip,$user,$table_exists,$field_id,$list_id,$field_label,$field_name,$field_description,$field_rank,$field_help,$field_type,$field_options,$field_size,$field_max,$field_default,$field_required,$field_cost,$multi_position,$name_position,$field_order,$vicidial_list_fields,$mysql_reserved_words);

						if ($SQLsuccess > 0)
							{echo _QXZ("SUCCESS: Custom Field Added")." - $list_id|$field_label\n<BR>";}
						}
					}
				}
			}
		}
	$action = "MODIFY_CUSTOM_FIELDS";
	}
### END add new custom field




################################################################################
##### BEGIN modify custom field submission
if ( ($action == "MODIFY_CUSTOM_FIELD_SUBMIT") and ($list_id > 99) and ($field_id > 0) )
	{
	### connect to your vtiger database
	#$linkCUSTOM=mysql_connect("$VARDB_server:$VARDB_port", "$VARDB_custom_user","$VARDB_custom_pass");
	#if (!$linkCUSTOM) {die("Could not connect: $VARDB_server|$VARDB_port|$VARDB_database|$VARDB_custom_user|$VARDB_custom_pass" . mysqli_error());}
	#mysql_select_db("$VARDB_database", $linkCUSTOM);

	$linkCUSTOM=mysqli_connect("$VARDB_server", "$VARDB_custom_user", "$VARDB_custom_pass", "$VARDB_database", "$VARDB_port");
	if (!$linkCUSTOM) 
		{
		die('MySQL '._QXZ("connect ERROR").': '. mysqli_connect_error());
		}


	$stmt="SELECT count(*) from vicidial_lists_fields where list_id='$list_id' and field_id='$field_id';";
	if ($DB>0) {echo "$stmt";}
	$rslt=mysql_to_mysqli($stmt, $link);
	$fieldscount_to_print = mysqli_num_rows($rslt);
	if ($fieldscount_to_print > 0) 
		{
		$rowx=mysqli_fetch_row($rslt);
		$field_exists =	$rowx[0];
		}
	
	$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
	$rslt=mysql_to_mysqli($stmt, $link);
	$tablecount_to_print = mysqli_num_rows($rslt);
	if ($tablecount_to_print > 0) 
		{$table_exists =	1;}
	if ($DB>0) {echo "$stmt|$tablecount_to_print|$table_exists";}

	if ($field_exists < 1)
		{echo "<B><font color=red>"._QXZ("ERROR: Field does not exist")."</B></font>\n<BR>";}
	else
		{
		if ( ($table_exists < 1) and (!preg_match("/\|$field_label\|/i",$vicidial_list_fields)) )
			{echo "<B><font color=red>"._QXZ("ERROR: Table does not exist")."</B></font>\n<BR>";}
		else
			{
			if ( ( ($field_type=='TEXT') or ($field_type=='READONLY') or ($field_type=='HIDDEN') ) and ($field_max < strlen($field_default)) )
				{echo "<B><font color=red>"._QXZ("ERROR: Default value cannot be longer than maximum field length")." - $list_id|$field_label|$field_name|$field_max|$field_default|</B></font>\n<BR>";}
			else
				{
				$TEST_valid_options=0;
				if ( ($field_type=='SELECT') or ($field_type=='MULTI') or ($field_type=='RADIO') or ($field_type=='CHECKBOX') )
					{
					$TESTfield_options_array = explode("\n",$field_options);
					$TESTfield_options_count = count($TESTfield_options_array);
					$te=0;
					while ($te < $TESTfield_options_count)
						{
						if (preg_match("/,/",$TESTfield_options_array[$te]))
							{
							$TESTfield_options_value_array = explode(",",$TESTfield_options_array[$te]);
							if ( (strlen($TESTfield_options_value_array[0]) > 0) and (strlen($TESTfield_options_value_array[1]) > 0) )
								{$TEST_valid_options++;}
							}
						$te++;
						}
					$field_options_ENUM = preg_replace("/.$/",'',$field_options_ENUM);
					}

				if ( ( ($field_type=='SELECT') or ($field_type=='MULTI') or ($field_type=='RADIO') or ($field_type=='CHECKBOX') ) and ( (!preg_match("/,/",$field_options)) or (!preg_match("/\n/",$field_options)) or (strlen($field_options)<6) or ($TEST_valid_options < 1) ) )
					{echo "<B><font color=red>"._QXZ("ERROR: You must enter field options when updating a SELECT, MULTI, RADIO or CHECKBOX field type")."  - $list_id|$field_label|$field_type|$field_options</B></font>\n<BR>";}
				else
					{
					### modify field function
					$SQLsuccess = modify_field_function($DB,$link,$linkCUSTOM,$ip,$user,$table_exists,$field_id,$list_id,$field_label,$field_name,$field_description,$field_rank,$field_help,$field_type,$field_options,$field_size,$field_max,$field_default,$field_required,$field_cost,$multi_position,$name_position,$field_order,$vicidial_list_fields);

					if ($SQLsuccess > 0)
						{echo _QXZ("SUCCESS: Custom Field Modified")." - $list_id|$field_label\n<BR>";}
					}
				}
			}
		}

	$action = "MODIFY_CUSTOM_FIELDS";
	}
### END modify custom field submission





################################################################################
##### BEGIN modify custom fields for list
if ( ($action == "MODIFY_CUSTOM_FIELDS") and ($list_id > 99) )
	{
	echo "</TITLE></HEAD><BODY BGCOLOR=white>\n";
	echo "<TABLE><TR><TD>\n";

	$custom_records_count=0;
	$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
	$rslt=mysql_to_mysqli($stmt, $link);
	$tablecount_to_print = mysqli_num_rows($rslt);
	if ($tablecount_to_print > 0) 
		{$table_exists =	1;}
	if ($DB>0) {echo "$stmt|$tablecount_to_print|$table_exists";}
	
	if ($table_exists > 0)
		{
		$stmt="SELECT count(*) from custom_$list_id;";
		if ($DB>0) {echo "$stmt";}
		$rslt=mysql_to_mysqli($stmt, $link);
		$fieldscount_to_print = mysqli_num_rows($rslt);
		if ($fieldscount_to_print > 0) 
			{
			$rowx=mysqli_fetch_row($rslt);
			$custom_records_count =	$rowx[0];
			}
		}

	echo "<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2>";
	echo "<br>"._QXZ("Modify Custom Fields: List ID")." $list_id   &nbsp; &nbsp; &nbsp; &nbsp; ";
	echo _QXZ("Records in this custom table").": $custom_records_count<br>\n";
	echo "<center><TABLE width=$section_width cellspacing=3>\n";

	$stmt="SELECT field_id,field_label,field_name,field_description,field_rank,field_help,field_type,field_options,field_size,field_max,field_default,field_cost,field_required,multi_position,name_position,field_order from vicidial_lists_fields where list_id='$list_id' order by field_rank,field_order,field_label;";
	$rslt=mysql_to_mysqli($stmt, $link);
	$fields_to_print = mysqli_num_rows($rslt);
	$fields_list='';
	$o=0;
	while ($fields_to_print > $o) 
		{
		$rowx=mysqli_fetch_row($rslt);
		$A_field_id[$o] =			$rowx[0];
		$A_field_label[$o] =		$rowx[1];
		$A_field_name[$o] =			$rowx[2];
		$A_field_description[$o] =	$rowx[3];
		$A_field_rank[$o] =			$rowx[4];
		$A_field_help[$o] =			$rowx[5];
		$A_field_type[$o] =			$rowx[6];
		$A_field_options[$o] =		$rowx[7];
		$A_field_size[$o] =			$rowx[8];
		$A_field_max[$o] =			$rowx[9];
		$A_field_default[$o] =		$rowx[10];
		$A_field_cost[$o] =			$rowx[11];
		$A_field_required[$o] =		$rowx[12];
		$A_multi_position[$o] =		$rowx[13];
		$A_name_position[$o] =		$rowx[14];
		$A_field_order[$o] =		$rowx[15];

		$o++;
		$rank_select .= "<option>$o</option>";
		}
	$o++;
	$rank_select .= "<option>$o</option>";
	$last_rank = $o;

	### SUMMARY OF FIELDS ###
	echo "<br>"._QXZ("SUMMARY OF FIELDS").":\n";
	echo "<center><TABLE cellspacing=0 cellpadding=1>\n";
	echo "<TR BGCOLOR=BLACK>";
	echo "<TD align=right><B><FONT FACE=\"Arial,Helvetica\" size=2 color=white>"._QXZ("RANK")." &nbsp; &nbsp; </B></TD>";
	echo "<TD align=right><B><FONT FACE=\"Arial,Helvetica\" size=2 color=white>"._QXZ("LABEL")." &nbsp; &nbsp; </B></TD>";
	echo "<TD align=right><B><FONT FACE=\"Arial,Helvetica\" size=2 color=white>"._QXZ("NAME")." &nbsp; &nbsp; </B></TD>";
	echo "<TD align=right><B><FONT FACE=\"Arial,Helvetica\" size=2 color=white>"._QXZ("TYPE")." &nbsp; &nbsp; </B></TD>";
	echo "<TD align=right><B><FONT FACE=\"Arial,Helvetica\" size=2 color=white>"._QXZ("COST")." &nbsp; &nbsp; </B></TD>\n";
	echo "</TR>\n";

	$o=0;
	while ($fields_to_print > $o) 
		{
		$LcolorB='';   $LcolorE='';
		$reserved_test = $A_field_label[$o];
		if (preg_match("/\|$reserved_test\|/i",$vicidial_list_fields))
			{
			$LcolorB='<font color=red>';
			$LcolorE='</font>';
			}
		if (preg_match('/1$|3$|5$|7$|9$/i', $o))
			{$bgcolor='class="records_list_x"';} 
		else
			{$bgcolor='class="records_list_y"';}
		echo "<tr $bgcolor align=right><td><font size=1>$A_field_rank[$o] - $A_field_order[$o] &nbsp; &nbsp; </td>";
		echo "<td align=right><font size=2> <a href=\"#ANCHOR_$A_field_label[$o]\">$LcolorB$A_field_label[$o]$LcolorE</a> &nbsp; &nbsp; </td>";
		echo "<td align=right><font size=2> $A_field_name[$o] &nbsp; &nbsp; </td>";
		echo "<td align=right><font size=2> $A_field_type[$o] &nbsp; &nbsp; </td>";
		echo "<td align=right><font size=2> $A_field_cost[$o] &nbsp; &nbsp; </td></tr>\n";

		$total_cost = ($total_cost + $A_field_cost[$o]);
		$o++;
		}

	if ($fields_to_print < 1) 
		{echo "<tr bgcolor=white align=center><td colspan=5><font size=1>"._QXZ("There are no custom fields for this list")."</td></tr>";}
	else
		{
		echo "<tr bgcolor=white align=right><td><font size=2>"._QXZ("TOTALS").": </td>";
		echo "<td align=right><font size=2> $o &nbsp; &nbsp; </td>";
		echo "<td align=right><font size=2> &nbsp; &nbsp; </td>";
		echo "<td align=right><font size=2> &nbsp; &nbsp; </td>";
		echo "<td align=right><font size=2> $total_cost &nbsp; &nbsp; </td></tr>\n";
		}
	echo "</table></center><BR><BR>\n";


	### EXAMPLE OF CUSTOM FORM ###
	echo "<form action=$PHP_SELF method=POST name=form_custom_$list_id id=form_custom_$list_id>\n";
	echo "<br>"._QXZ("EXAMPLE OF CUSTOM FORM").":\n";
	echo "<center><TABLE cellspacing=2 cellpadding=2>\n";
	if ($fields_to_print < 1) 
		{echo "<tr bgcolor=white align=center><td colspan=4><font size=1>"._QXZ("There are no custom fields for this list")."</td></tr>";}

	$o=0;
	$last_field_rank=0;
	while ($fields_to_print > $o) 
		{
		if ($last_field_rank=="$A_field_rank[$o]")
			{echo " &nbsp; &nbsp; &nbsp; &nbsp; ";}
		else
			{
			echo "</td></tr>\n";
			echo "<tr bgcolor=white><td align=";
			if ($A_name_position[$o]=='TOP') 
				{echo "left colspan=2";}
			else
				{echo "right";}
			echo "><font size=2>";
			}
		echo "<a href=\"#ANCHOR_$A_field_label[$o]\"><B>$A_field_name[$o]</B></a>";
		if ($A_name_position[$o]=='TOP') 
			{
			$helpHTML = "<a href=\"javascript:open_help('HELP_$A_field_label[$o]','$A_field_help[$o]');\">help+</a>";
			if (strlen($A_field_help[$o])<1)
				{$helpHTML = '';}
			echo " &nbsp; <span style=\"position:static;\" id=P_HELP_$A_field_label[$o]></span><span style=\"position:static;background:white;\" id=HELP_$A_field_label[$o]> &nbsp; $helpHTML</span><BR>";
			}
		else
			{
			if ($last_field_rank=="$A_field_rank[$o]")
				{echo " &nbsp;";}
			else
				{echo "</td><td align=left><font size=2>";}
			}
		$field_HTML='';

		if ($A_field_type[$o]=='SELECT')
			{
			$field_HTML .= "<select size=1 name=$A_field_label[$o] id=$A_field_label[$o]>\n";
			}
		if ($A_field_type[$o]=='MULTI')
			{
			$field_HTML .= "<select MULTIPLE size=$A_field_size[$o] name=$A_field_label[$o] id=$A_field_label[$o]>\n";
			}
		if ( ($A_field_type[$o]=='SELECT') or ($A_field_type[$o]=='MULTI') or ($A_field_type[$o]=='RADIO') or ($A_field_type[$o]=='CHECKBOX') )
			{
			$field_options_array = explode("\n",$A_field_options[$o]);
			$field_options_count = count($field_options_array);
			$te=0;
			while ($te < $field_options_count)
				{
				if (preg_match("/,/",$field_options_array[$te]))
					{
					$field_selected='';
					$field_options_value_array = explode(",",$field_options_array[$te]);
					if ( ($A_field_type[$o]=='SELECT') or ($A_field_type[$o]=='MULTI') )
						{
						if ($A_field_default[$o] == "$field_options_value_array[0]") {$field_selected = 'SELECTED';}
						$field_HTML .= "<option value=\"$field_options_value_array[0]\" $field_selected>$field_options_value_array[1]</option>\n";
						}
					if ( ($A_field_type[$o]=='RADIO') or ($A_field_type[$o]=='CHECKBOX') )
						{
						if ($A_multi_position[$o]=='VERTICAL') 
							{$field_HTML .= " &nbsp; ";}
						if ($A_field_default[$o] == "$field_options_value_array[0]") {$field_selected = 'CHECKED';}
						$field_HTML .= "<input type=$A_field_type[$o] name=$A_field_label[$o][] id=$A_field_label[$o][] value=\"$field_options_value_array[0]\" $field_selected> $field_options_value_array[1]\n";
						if ($A_multi_position[$o]=='VERTICAL') 
							{$field_HTML .= "<BR>\n";}
						}
					}
				$te++;
				}
			}
		if ( ($A_field_type[$o]=='SELECT') or ($A_field_type[$o]=='MULTI') )
			{
			$field_HTML .= "</select>\n";
			}
		if ($A_field_type[$o]=='TEXT') 
			{
			if ($A_field_default[$o]=='NULL') {$A_field_default[$o]='';}
			$field_HTML .= "<input type=text size=$A_field_size[$o] maxlength=$A_field_max[$o] name=$A_field_label[$o] id=$A_field_label[$o] value=\"$A_field_default[$o]\">\n";
			}
		if ($A_field_type[$o]=='AREA') 
			{
			$field_HTML .= "<textarea name=$A_field_label[$o] id=$A_field_label[$o] ROWS=$A_field_max[$o] COLS=$A_field_size[$o]></textarea>";
			}
		if ($A_field_type[$o]=='DISPLAY')
			{
			if ($A_field_default[$o]=='NULL') {$A_field_default[$o]='';}
			$field_HTML .= "\n";
			}
		if ($A_field_type[$o]=='READONLY')
			{
			if ($A_field_default[$o]=='NULL') {$A_field_default[$o]='';}
			$field_HTML .= "$A_field_default[$o]\n";
			}
		if ($A_field_type[$o]=='HIDDEN')
			{
			if ($A_field_default[$o]=='NULL') {$A_field_default[$o]='';}
			$field_HTML .= "-- HIDDEN --\n";
			}
		if ($A_field_type[$o]=='HIDEBLOB')
			{
			if ($A_field_default[$o]=='NULL') {$A_field_default[$o]='';}
			$field_HTML .= "-- HIDDEN --\n";
			}
		if ($A_field_type[$o]=='SCRIPT')
			{
			if ($A_field_default[$o]=='NULL') {$A_field_default[$o]='';}
			$field_HTML .= "$A_field_options[$o]\n";
			}
		if ($A_field_type[$o]=='DATE') 
			{
			if ( (strlen($A_field_default[$o])<1) or ($A_field_default[$o]=='NULL') ) {$A_field_default[$o]=0;}
			$day_diff = $A_field_default[$o];
			$default_date = date("Y-m-d", mktime(date("H"),date("i"),date("s"),date("m"),date("d")+$day_diff,date("Y")));

			$field_HTML .= "<input type=text size=11 maxlength=10 name=$A_field_label[$o] id=$A_field_label[$o] value=\"$default_date\">\n";
			$field_HTML .= "<script language=\"JavaScript\">\n";
			$field_HTML .= "var o_cal = new tcal ({\n";
			$field_HTML .= "	'formname': 'form_custom_$list_id',\n";
			$field_HTML .= "	'controlname': '$A_field_label[$o]'});\n";
			$field_HTML .= "o_cal.a_tpl.yearscroll = false;\n";
			$field_HTML .= "</script>\n";
			}
		if ($A_field_type[$o]=='TIME') 
			{
			$minute_diff = $A_field_default[$o];
			$default_time = date("H:i:s", mktime(date("H"),date("i")+$minute_diff,date("s"),date("m"),date("d"),date("Y")));
			$default_hour = date("H", mktime(date("H"),date("i")+$minute_diff,date("s"),date("m"),date("d"),date("Y")));
			$default_minute = date("i", mktime(date("H"),date("i")+$minute_diff,date("s"),date("m"),date("d"),date("Y")));
			$field_HTML .= "<input type=hidden name=$A_field_label[$o] id=$A_field_label[$o] value=\"$default_time\">";
			$field_HTML .= "<SELECT name=HOUR_$A_field_label[$o] id=HOUR_$A_field_label[$o]>";
			$field_HTML .= "<option>00</option>";
			$field_HTML .= "<option>01</option>";
			$field_HTML .= "<option>02</option>";
			$field_HTML .= "<option>03</option>";
			$field_HTML .= "<option>04</option>";
			$field_HTML .= "<option>05</option>";
			$field_HTML .= "<option>06</option>";
			$field_HTML .= "<option>07</option>";
			$field_HTML .= "<option>08</option>";
			$field_HTML .= "<option>09</option>";
			$field_HTML .= "<option>10</option>";
			$field_HTML .= "<option>11</option>";
			$field_HTML .= "<option>12</option>";
			$field_HTML .= "<option>13</option>";
			$field_HTML .= "<option>14</option>";
			$field_HTML .= "<option>15</option>";
			$field_HTML .= "<option>16</option>";
			$field_HTML .= "<option>17</option>";
			$field_HTML .= "<option>18</option>";
			$field_HTML .= "<option>19</option>";
			$field_HTML .= "<option>20</option>";
			$field_HTML .= "<option>21</option>";
			$field_HTML .= "<option>22</option>";
			$field_HTML .= "<option>23</option>";
			$field_HTML .= "<OPTION value=\"$default_hour\" selected>$default_hour</OPTION>";
			$field_HTML .= "</SELECT>";
			$field_HTML .= "<SELECT name=MINUTE_$A_field_label[$o] id=MINUTE_$A_field_label[$o]>";
			$field_HTML .= "<option>00</option>";
			$field_HTML .= "<option>05</option>";
			$field_HTML .= "<option>10</option>";
			$field_HTML .= "<option>15</option>";
			$field_HTML .= "<option>20</option>";
			$field_HTML .= "<option>25</option>";
			$field_HTML .= "<option>30</option>";
			$field_HTML .= "<option>35</option>";
			$field_HTML .= "<option>40</option>";
			$field_HTML .= "<option>45</option>";
			$field_HTML .= "<option>50</option>";
			$field_HTML .= "<option>55</option>";
			$field_HTML .= "<OPTION value=\"$default_minute\" selected>$default_minute</OPTION>";
			$field_HTML .= "</SELECT>";
			}

		if ($A_name_position[$o]=='LEFT') 
			{
			$helpHTML = "<a href=\"javascript:open_help('HELP_$A_field_label[$o]','$A_field_help[$o]');\">help+</a>";
			if (strlen($A_field_help[$o])<1)
				{$helpHTML = '';}
			echo " $field_HTML <span style=\"position:static;\" id=P_HELP_$A_field_label[$o]></span><span style=\"position:static;background:white;\" id=HELP_$A_field_label[$o]> &nbsp; $helpHTML</span>";
			}
		else
			{
			echo " $field_HTML\n";
			}

		$last_field_rank=$A_field_rank[$o];
		$o++;
		}
	echo "</td></tr></table></form></center><BR><BR>\n";


	### MODIFY FIELDS ###
	echo "<br>"._QXZ("MODIFY EXISTING FIELDS").":\n";
	$o=0;
	while ($fields_to_print > $o) 
		{
		$LcolorB='';   $LcolorE='';
		$reserved_test = $A_field_label[$o];
		if (preg_match("/\|$reserved_test\|/i",$vicidial_list_fields))
			{
			$LcolorB='<font color=red>';
			$LcolorE='</font>';
			}
		if (preg_match('/1$|3$|5$|7$|9$/i', $o))
			{$bgcolor='bgcolor="#'. $SSstd_row2_background .'"';} 
		else
			{$bgcolor='bgcolor="#'. $SSstd_row1_background .'"';}
		echo "<form action=$PHP_SELF method=POST>\n";
		echo "<input type=hidden name=action value=MODIFY_CUSTOM_FIELD_SUBMIT>\n";
		echo "<input type=hidden name=list_id value=$list_id>\n";
		echo "<input type=hidden name=DB value=$DB>\n";
		echo "<input type=hidden name=field_id value=\"$A_field_id[$o]\">\n";
		echo "<input type=hidden name=field_label value=\"$A_field_label[$o]\">\n";
		echo "<input type=hidden name=field_required value=\"$A_field_required[$o]\">\n";
		echo "<a name=\"ANCHOR_$A_field_label[$o]\">\n";
		echo "<center><TABLE width=$section_width cellspacing=3 cellpadding=1>\n";
		echo "<tr $bgcolor><td align=right>"._QXZ("Field Label")." $A_field_rank[$o]: </td><td align=left> $LcolorB<B>$A_field_label[$o]</B>$LcolorE $NWB#lists_fields-field_label$NWE </td></tr>\n";
		echo "<tr $bgcolor><td align=right>"._QXZ("Field Rank")." $A_field_rank[$o]: </td><td align=left><select size=1 name=field_rank>\n";
		echo "$rank_select\n";
		echo "<option selected>$A_field_rank[$o]</option>\n";
		echo "</select> &nbsp; $NWB#lists_fields-field_rank$NWE \n";
		echo " &nbsp; &nbsp; &nbsp; &nbsp; "._QXZ("Field Order").": <select size=1 name=field_order>\n";
		echo "<option>1</option>\n";
		echo "<option>2</option>\n";
		echo "<option>3</option>\n";
		echo "<option>4</option>\n";
		echo "<option>5</option>\n";
		echo "<option selected>$A_field_order[$o]</option>\n";
		echo "</select> &nbsp; $NWB#lists_fields-field_order$NWE </td></tr>\n";
		echo "<tr $bgcolor><td align=right>"._QXZ("Field Name")." $A_field_rank[$o]: </td><td align=left><textarea name=field_name rows=2 cols=60>$A_field_name[$o]</textarea> $NWB#lists_fields-field_name$NWE </td></tr>\n";
		echo "<tr $bgcolor><td align=right>"._QXZ("Field Name Position")." $A_field_rank[$o]: </td><td align=left><select size=1 name=name_position>\n";
		echo "<option value=\"LEFT\">"._QXZ("LEFT")."</option>\n";
		echo "<option value=\"TOP\">"._QXZ("TOP")."</option>\n";
		echo "<option selected>$A_name_position[$o]</option>\n";
		echo "</select>  $NWB#lists_fields-name_position$NWE </td></tr>\n";
		echo "<tr $bgcolor><td align=right>"._QXZ("Field Description")." $A_field_rank[$o]: </td><td align=left><input type=text name=field_description size=70 maxlength=100 value=\"$A_field_description[$o]\"> $NWB#lists_fields-field_description$NWE </td></tr>\n";
		echo "<tr $bgcolor><td align=right>"._QXZ("Field Help")." $A_field_rank[$o]: </td><td align=left><textarea name=field_help rows=2 cols=60>$A_field_help[$o]</textarea> $NWB#lists_fields-field_help$NWE </td></tr>\n";
		echo "<tr $bgcolor><td align=right>"._QXZ("Field Type")." $A_field_rank[$o]: </td><td align=left><select size=1 name=field_type>\n";
		echo "<option value='TEXT'>"._QXZ("TEXT")."</option>\n";
		echo "<option value='AREA'>"._QXZ("AREA")."</option>\n";
		echo "<option value='SELECT'>"._QXZ("SELECT")."</option>\n";
		echo "<option value='MULTI'>"._QXZ("MULTI")."</option>\n";
		echo "<option value='RADIO'>"._QXZ("RADIO")."</option>\n";
		echo "<option value='CHECKBOX'>"._QXZ("CHECKBOX")."</option>\n";
		echo "<option value='DATE'>"._QXZ("DATE")."</option>\n";
		echo "<option value='TIME'>"._QXZ("TIME")."</option>\n";
		echo "<option value='DISPLAY'>"._QXZ("DISPLAY")."</option>\n";
		echo "<option value='SCRIPT'>"._QXZ("SCRIPT")."</option>\n";
		echo "<option value='HIDDEN'>"._QXZ("HIDDEN")."</option>\n";
		echo "<option value='HIDEBLOB'>"._QXZ("HIDEBLOB")."</option>\n";
		echo "<option value='READONLY'>"._QXZ("READONLY")."</option>\n";
		echo "<option value='$A_field_type[$o]' selected>"._QXZ("$A_field_type[$o]")."</option>\n";
		echo "</select>  $NWB#lists_fields-field_type$NWE </td></tr>\n";
		echo "<tr $bgcolor><td align=right>"._QXZ("Field Options")." $A_field_rank[$o]: </td><td align=left><textarea name=field_options ROWS=5 COLS=60>$A_field_options[$o]</textarea>  $NWB#lists_fields-field_options$NWE </td></tr>\n";
		echo "<tr $bgcolor><td align=right>"._QXZ("Option Position")." $A_field_rank[$o]: </td><td align=left><select size=1 name=multi_position>\n";
		echo "<option value=\"HORIZONTAL\">"._QXZ("HORIZONTAL")."</option>\n";
		echo "<option value=\"VERTICAL\">"._QXZ("VERTICAL")."</option>\n";
		echo "<option value='$A_multi_position[$o]' selected>"._QXZ("$A_multi_position[$o]")."</option>\n";
		echo "</select>  $NWB#lists_fields-multi_position$NWE </td></tr>\n";
		echo "<tr $bgcolor><td align=right>"._QXZ("Field Size")." $A_field_rank[$o]: </td><td align=left><input type=text name=field_size size=5 maxlength=3 value=\"$A_field_size[$o]\">  $NWB#lists_fields-field_size$NWE </td></tr>\n";
		echo "<tr $bgcolor><td align=right>"._QXZ("Field Max")." $A_field_rank[$o]: </td><td align=left><input type=text name=field_max size=5 maxlength=3 value=\"$A_field_max[$o]\">  $NWB#lists_fields-field_max$NWE </td></tr>\n";
		echo "<tr $bgcolor><td align=right>"._QXZ("Field Default")." $A_field_rank[$o]: </td><td align=left><input type=text name=field_default size=50 maxlength=255 value=\"$A_field_default[$o]\">  $NWB#lists_fields-field_default$NWE </td></tr>\n";
	//	echo "<tr $bgcolor><td align=right>Field Required $A_field_rank[$o]: </td><td align=left><select size=1 name=field_required>\n";
	//	echo "<option value=\"Y\">YES</option>\n";
	//	echo "<option value=\"N\">NO</option>\n";
	//	echo "<option selected>$A_field_required[$o]</option>\n";
	//	echo "</select>  $NWB#lists_fields-field_required$NWE </td></tr>\n";
		echo "<tr $bgcolor><td align=center colspan=2><input type=submit name=submit value=\""._QXZ("SUBMIT")."\"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n";
		echo "<B><a href=\"$PHP_SELF?action=DELETE_CUSTOM_FIELD_CONFIRMATION&list_id=$list_id&field_id=$A_field_id[$o]&field_label=$A_field_label[$o]&field_type=$A_field_type[$o]&DB=$DB\">"._QXZ("DELETE THIS CUSTOM FIELD")."</a></B>";
		echo "</td></tr>\n";
		echo "</table></center></form><BR><BR>\n";

		$o++;
		}

	$bgcolor = ' bgcolor=#'.$SSalt_row1_background;

	echo "<form action=$PHP_SELF method=POST>\n";
	echo "<center><TABLE width=$section_width cellspacing=3 cellpadding=1>\n";
	echo "<tr bgcolor=white><td align=center colspan=2>"._QXZ("ADD A NEW CUSTOM FIELD FOR THIS LIST").":</td></tr>\n";
	echo "<tr $bgcolor>\n";
	echo "<input type=hidden name=action value=ADD_CUSTOM_FIELD>\n";
	echo "<input type=hidden name=list_id value=$list_id>\n";
	echo "<input type=hidden name=DB value=$DB>\n";
	echo "<input type=hidden name=field_required value=\"N\">\n";
	echo "<tr $bgcolor><td align=right>"._QXZ("New Field Rank").": </td><td align=left><select size=1 name=field_rank>\n";
	echo "$rank_select\n";
	echo "<option selected>$last_rank</option>\n";
	echo "</select> &nbsp; $NWB#lists_fields-field_rank$NWE \n";
	echo " &nbsp; &nbsp; &nbsp; &nbsp; "._QXZ("Field Order").": <select size=1 name=field_order>\n";
	echo "<option selected>1</option>\n";
	echo "<option>2</option>\n";
	echo "<option>3</option>\n";
	echo "<option>4</option>\n";
	echo "<option>5</option>\n";
	echo "</select> &nbsp; $NWB#lists_fields-field_order$NWE </td></tr>\n";
	echo "<tr $bgcolor><td align=right>"._QXZ("Field Label").": </td><td align=left><input type=text name=field_label size=20 maxlength=50> $NWB#lists_fields-field_label$NWE </td></tr>\n";
	echo "<tr $bgcolor><td align=right>"._QXZ("Field Name").": </td><td align=left><textarea name=field_name rows=2 cols=60></textarea> $NWB#lists_fields-field_name$NWE </td></tr>\n";
	echo "<tr $bgcolor><td align=right>"._QXZ("Field Name Position").": </td><td align=left><select size=1 name=name_position>\n";
	echo "<option value=\"LEFT\">"._QXZ("LEFT")."</option>\n";
	echo "<option value=\"TOP\">"._QXZ("TOP")."</option>\n";
	echo "</select>  $NWB#lists_fields-name_position$NWE </td></tr>\n";
	echo "<tr $bgcolor><td align=right>"._QXZ("Field Description").": </td><td align=left><input name=field_description type=text size=70 maxlength=100> $NWB#lists_fields-field_description$NWE </td></tr>\n";
	echo "<tr $bgcolor><td align=right>"._QXZ("Field Help").": </td><td align=left><textarea name=field_help rows=2 cols=60></textarea> $NWB#lists_fields-field_help$NWE </td></tr>\n";
	echo "<tr $bgcolor><td align=right>"._QXZ("Field Type").": </td><td align=left><select size=1 name=field_type>\n";
	echo "<option value='TEXT'>"._QXZ("TEXT")."</option>\n";
	echo "<option value='AREA'>"._QXZ("AREA")."</option>\n";
	echo "<option value='SELECT'>"._QXZ("SELECT")."</option>\n";
	echo "<option value='MULTI'>"._QXZ("MULTI")."</option>\n";
	echo "<option value='RADIO'>"._QXZ("RADIO")."</option>\n";
	echo "<option value='CHECKBOX'>"._QXZ("CHECKBOX")."</option>\n";
	echo "<option value='DATE'>"._QXZ("DATE")."</option>\n";
	echo "<option value='TIME'>"._QXZ("TIME")."</option>\n";
	echo "<option value='DISPLAY'>"._QXZ("DISPLAY")."</option>\n";
	echo "<option value='SCRIPT'>"._QXZ("SCRIPT")."</option>\n";
	echo "<option value='HIDDEN'>"._QXZ("HIDDEN")."</option>\n";
	echo "<option value='HIDEBLOB'>"._QXZ("HIDEBLOB")."</option>\n";
	echo "<option value='READONLY'>"._QXZ("READONLY")."</option>\n";
	echo "<option selected value='TEXT'>"._QXZ("TEXT")."</option>\n";
	echo "</select>  $NWB#lists_fields-field_type$NWE </td></tr>\n";
	echo "<tr $bgcolor><td align=right>"._QXZ("Field Options").": </td><td align=left><textarea name=field_options ROWS=5 COLS=60></textarea>  $NWB#lists_fields-field_options$NWE </td></tr>\n";
	echo "<tr $bgcolor><td align=right>"._QXZ("Option Position").": </td><td align=left><select size=1 name=multi_position>\n";
	echo "<option selected value=\"HORIZONTAL\">"._QXZ("HORIZONTAL")."</option>\n";
	echo "<option value=\"VERTICAL\">"._QXZ("VERTICAL")."</option>\n";
	echo "</select>  $NWB#lists_fields-multi_position$NWE </td></tr>\n";
	echo "<tr $bgcolor><td align=right>"._QXZ("Field Size").": </td><td align=left><input type=text name=field_size size=5 maxlength=3>  $NWB#lists_fields-field_size$NWE </td></tr>\n";
	echo "<tr $bgcolor><td align=right>"._QXZ("Field Max").": </td><td align=left><input type=text name=field_max size=5 maxlength=3>  $NWB#lists_fields-field_max$NWE </td></tr>\n";
	echo "<tr $bgcolor><td align=right>"._QXZ("Field Default").": </td><td align=left><input type=text name=field_default size=50 maxlength=255 value=\"NULL\">  $NWB#lists_fields-field_default$NWE </td></tr>\n";
//	echo "<tr $bgcolor><td align=right>Field Required: </td><td align=left><select size=1 name=field_required>\n";
//	echo "<option value=\"Y\">YES</option>\n";
//	echo "<option value=\"N\" SELECTED>NO</option>\n";
//	echo "</select>  $NWB#lists_fields-field_required$NWE </td></tr>\n";
	echo "<tr $bgcolor><td align=center colspan=2><input type=submit name=submit value=\""._QXZ("Submit")."\"></td></tr>\n";
	echo "</table></center></form><BR><BR>\n";
	echo "</table></center><BR><BR>\n";
	echo "</TABLE>\n";

	echo "&nbsp; <a href=\"./admin.php?ADD=311&list_id=$list_id\">"._QXZ("Go to the list modification page for this list")."</a><BR><BR>\n";

	echo "&nbsp; <a href=\"$PHP_SELF?action=ADMIN_LOG&list_id=$list_id\">"._QXZ("Click here to see Admin changes to this lists custom fields")."</a><BR><BR><BR> </center> &nbsp; \n";
	}
### END modify custom fields for list




################################################################################
##### BEGIN list lists as well as the number of custom fields in each list
if ($action == "LIST")
	{
	$stmt="SELECT list_id,list_name,active,campaign_id from vicidial_lists order by list_id;";
	$rslt=mysql_to_mysqli($stmt, $link);
	$lists_to_print = mysqli_num_rows($rslt);
	$o=0;
	while ($lists_to_print > $o) 
		{
		$rowx=mysqli_fetch_row($rslt);
		$A_list_id[$o] =		$rowx[0];
		$A_list_name[$o] =		$rowx[1];
		$A_active[$o] =			$rowx[2];
		$A_campaign_id[$o] =	$rowx[3];
		$o++;
		}

	echo "<br>"._QXZ("LIST LISTINGS WITH CUSTOM FIELDS COUNT").":\n";
	echo "<center><TABLE width=$section_width cellspacing=0 cellpadding=1>\n";
	echo "<TR BGCOLOR=BLACK>";
	echo "<TD align=right><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("LIST ID")."</B></TD>";
	echo "<TD align=right><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("LIST NAME")."</B></TD>";
	echo "<TD align=right><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("ACTIVE")."</B></TD>";
	echo "<TD align=right><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("CAMPAIGN")."</B></TD>\n";
	echo "<TD align=right><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("CUSTOM FIELDS")."</B></TD>\n";
	echo "<TD align=right><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("MODIFY")."</TD>\n";
	echo "</TR>\n";

	$o=0;
	while ($lists_to_print > $o) 
		{
		$A_list_fields_count[$o]=0;
		$stmt="SELECT count(*) from vicidial_lists_fields where list_id='$A_list_id[$o]';";
		if ($DB>0) {echo "$stmt";}
		$rslt=mysql_to_mysqli($stmt, $link);
		$fieldscount_to_print = mysqli_num_rows($rslt);
		if ($fieldscount_to_print > 0) 
			{
			$rowx=mysqli_fetch_row($rslt);
			$A_list_fields_count[$o] =	$rowx[0];
			}
		if (preg_match('/1$|3$|5$|7$|9$/i', $o))
			{$bgcolor='class="records_list_x"';} 
		else
			{$bgcolor='class="records_list_y"';}
		echo "<tr $bgcolor align=right"; if ($SSadmin_row_click > 0) {echo " onclick=\"window.document.location='$PHP_SELF?action=MODIFY_CUSTOM_FIELDS&list_id=$A_list_id[$o]'\"";} echo "><td><a href=\"admin.php?ADD=311&list_id=$A_list_id[$o]\"><font size=1 color=black>$A_list_id[$o]</a></td>";
		echo "<td align=right><font size=1> $A_list_name[$o]</td>";
		echo "<td align=right><font size=1> $A_active[$o]</td>";
		echo "<td align=right><font size=1> $A_campaign_id[$o]</td>";
		echo "<td align=right><font size=1> $A_list_fields_count[$o]</td>";
		echo "<td align=right><font size=1><a href=\"$PHP_SELF?action=MODIFY_CUSTOM_FIELDS&list_id=$A_list_id[$o]\">"._QXZ("MODIFY FIELDS")."</a></td></tr>\n";

		$o++;
		}

	echo "</TABLE></center>\n";
	}
### END list lists as well as the number of custom fields in each list





################################################################################
##### BEGIN admin log display
if ($action == "ADMIN_LOG")
	{
	if ($LOGuser_level >= 9)
		{
		echo "<TABLE><TR><TD>\n";
		echo "<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2>";

		$stmt="SELECT admin_log_id,event_date,user,ip_address,event_section,event_type,record_id,event_code from vicidial_admin_log where event_section='CUSTOM_FIELDS' and record_id='$list_id' order by event_date desc limit 10000;";
		$rslt=mysql_to_mysqli($stmt, $link);
		$logs_to_print = mysqli_num_rows($rslt);

		echo "<br>"._QXZ("ADMIN CHANGE LOG: Section Records")." - $category - $stage\n";
		echo "<center><TABLE width=$section_width cellspacing=0 cellpadding=1>\n";
		echo "<TR BGCOLOR=BLACK>";
		echo "<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("ID")."</B></TD>";
		echo "<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("DATE TIME")."</B></TD>";
		echo "<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("USER")."</B></TD>\n";
		echo "<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("IP")."</TD>\n";
		echo "<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("SECTION")."</TD>\n";
		echo "<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("TYPE")."</TD>\n";
		echo "<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("RECORD ID")."</TD>\n";
		echo "<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("DESCRIPTION")."</TD>\n";
		echo "<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("GOTO")."</TD>\n";
		echo "</TR>\n";

		$logs_printed = '';
		$o=0;
		while ($logs_to_print > $o)
			{
			$row=mysqli_fetch_row($rslt);

			if (preg_match('/USER|AGENT/i', $row[4])) {$record_link = "$PHP_SELF?ADD=3&user=$row[6]";}
			if (preg_match('/CAMPAIGN/i', $row[4])) {$record_link = "$PHP_SELF?ADD=31&campaign_id=$row[6]";}
			if (preg_match('/LIST/i', $row[4])) {$record_link = "$PHP_SELF?ADD=311&list_id=$row[6]";}
			if (preg_match('/CUSTOM_FIELDS/i', $row[4])) {$record_link = "./admin_lists_custom.php?action=MODIFY_CUSTOM_FIELDS&list_id=$row[6]";}
			if (preg_match('/SCRIPT/i', $row[4])) {$record_link = "$PHP_SELF?ADD=3111111&script_id=$row[6]";}
			if (preg_match('/FILTER/i', $row[4])) {$record_link = "$PHP_SELF?ADD=31111111&lead_filter_id=$row[6]";}
			if (preg_match('/INGROUP/i', $row[4])) {$record_link = "$PHP_SELF?ADD=3111&group_id=$row[6]";}
			if (preg_match('/DID/i', $row[4])) {$record_link = "$PHP_SELF?ADD=3311&did_id=$row[6]";}
			if (preg_match('/USERGROUP/i', $row[4])) {$record_link = "$PHP_SELF?ADD=311111&user_group=$row[6]";}
			if (preg_match('/REMOTEAGENT/i', $row[4])) {$record_link = "$PHP_SELF?ADD=31111&remote_agent_id=$row[6]";}
			if (preg_match('/PHONE/i', $row[4])) {$record_link = "$PHP_SELF?ADD=10000000000";}
			if (preg_match('/CALLTIME/i', $row[4])) {$record_link = "$PHP_SELF?ADD=311111111&call_time_id=$row[6]";}
			if (preg_match('/SHIFT/i', $row[4])) {$record_link = "$PHP_SELF?ADD=331111111&shift_id=$row[6]";}
			if (preg_match('/CONFTEMPLATE/i', $row[4])) {$record_link = "$PHP_SELF?ADD=331111111111&template_id=$row[6]";}
			if (preg_match('/CARRIER/i', $row[4])) {$record_link = "$PHP_SELF?ADD=341111111111&carrier_id=$row[6]";}
			if (preg_match('/SERVER/i', $row[4])) {$record_link = "$PHP_SELF?ADD=311111111111&server_id=$row[6]";}
			if (preg_match('/CONFERENCE/i', $row[4])) {$record_link = "$PHP_SELF?ADD=1000000000000";}
			if (preg_match('/SYSTEM/i', $row[4])) {$record_link = "$PHP_SELF?ADD=311111111111111";}
			if (preg_match('/CATEGOR/i', $row[4])) {$record_link = "$PHP_SELF?ADD=331111111111111";}
			if (preg_match('/GROUPALIAS/i', $row[4])) {$record_link = "$PHP_SELF?ADD=33111111111&group_alias_id=$row[6]";}

			if (preg_match('/1$|3$|5$|7$|9$/i', $o))
				{$bgcolor='class="records_list_x"';} 
			else
				{$bgcolor='class="records_list_y"';}
			echo "<tr $bgcolor"; if ($SSadmin_row_click > 0) {echo " onclick=\"window.document.location='admin.php?ADD=730000000000000&stage=$row[0]'\"";} echo "><td><a href=\"admin.php?ADD=730000000000000&stage=$row[0]\"><font size=1 color=black>$row[0]</a></td>";
			echo "<td><font size=1> $row[1]</td>";
			echo "<td><font size=1> <a href=\"admin.php?ADD=710000000000000&stage=$row[2]\">$row[2]</a></td>";
			echo "<td><font size=1> $row[3]</td>";
			echo "<td><font size=1> $row[4]</td>";
			echo "<td><font size=1> $row[5]</td>";
			echo "<td><font size=1> $row[6]</td>";
			echo "<td><font size=1> $row[7]</td>";
			echo "<td><font size=1> <a href=\"$record_link\">GOTO</a></td>";
			echo "</tr>\n";
			$logs_printed .= "'$row[0]',";
			$o++;
			}
		echo "</TABLE><BR><BR>\n";
		echo "\n";
		echo "</center>\n";
		}
	else
		{
		echo _QXZ("You do not have permission to view this page")."\n";
		exit;
		}
	}





$ENDtime = date("U");
$RUNtime = ($ENDtime - $STARTtime);
echo "\n\n\n<br><br><br>\n<font size=1> "._QXZ("runtime").": $RUNtime "._QXZ("seconds")." &nbsp; &nbsp; &nbsp; &nbsp; "._QXZ("Version").": $admin_version &nbsp; &nbsp; "._QXZ("Build").": $build</font>";

?>

</body>
</html>


<?php
################################################################################
################################################################################
##### Functions
################################################################################
################################################################################




################################################################################
##### BEGIN add field function
function add_field_function($DB,$link,$linkCUSTOM,$ip,$user,$table_exists,$field_id,$list_id,$field_label,$field_name,$field_description,$field_rank,$field_help,$field_type,$field_options,$field_size,$field_max,$field_default,$field_required,$field_cost,$multi_position,$name_position,$field_order,$vicidial_list_fields,$mysql_reserved_words)
	{
	$table_exists=0;
	$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
	$rslt=mysql_to_mysqli($stmt, $link);
	$tablecount_to_print = mysqli_num_rows($rslt);
	if ($tablecount_to_print > 0)
		{$table_exists =	1;}
	if ($DB>0) {echo "$stmt|$tablecount_to_print|$table_exists";}

	if ($table_exists < 1)
		{$field_sql = "CREATE TABLE custom_$list_id (lead_id INT(9) UNSIGNED PRIMARY KEY NOT NULL, $field_label ";}
	else
		{$field_sql = "ALTER TABLE custom_$list_id ADD $field_label ";}

	$field_options_ENUM='';
	$field_cost=1;
	if ( ($field_type=='SELECT') or ($field_type=='RADIO') )
		{
		$field_options_array = explode("\n",$field_options);
		$field_options_count = count($field_options_array);
		$te=0;
		while ($te < $field_options_count)
			{
			if (preg_match("/,/",$field_options_array[$te]))
				{
				$field_options_value_array = explode(",",$field_options_array[$te]);
				$field_options_ENUM .= "'$field_options_value_array[0]',";
				}
			$te++;
			}
		$field_options_ENUM = preg_replace("/.$/",'',$field_options_ENUM);
		$field_sql .= "ENUM($field_options_ENUM) ";
		$field_cost = strlen($field_options_ENUM);
		}
	if ( ($field_type=='MULTI') or ($field_type=='CHECKBOX') )
		{
		$field_options_array = explode("\n",$field_options);
		$field_options_count = count($field_options_array);
		$te=0;
		while ($te < $field_options_count)
			{
			if (preg_match("/,/",$field_options_array[$te]))
				{
				$field_options_value_array = explode(",",$field_options_array[$te]);
				$field_options_ENUM .= "'$field_options_value_array[0]',";
				}
			$te++;
			}
		$field_options_ENUM = preg_replace("/.$/",'',$field_options_ENUM);
		$field_cost = strlen($field_options_ENUM);
		if ($field_cost < 1) {$field_cost=1;};
		$field_sql .= "VARCHAR($field_cost) ";
		}
	if ( ($field_type=='TEXT') or ($field_type=='HIDDEN') or ($field_type=='READONLY') )
		{
		if ($field_max < 1) {$field_max=1;};
		$field_sql .= "VARCHAR($field_max) ";
		$field_cost = ($field_max + $field_cost);
		}
	if ($field_type=='HIDEBLOB')
		{
		$field_sql .= "BLOB ";
		$field_cost = 15;
		}
	if ($field_type=='AREA') 
		{
		$field_sql .= "TEXT ";
		$field_cost = 15;
		}
	if ($field_type=='DATE') 
		{
		$field_sql .= "DATE ";
		$field_cost = 10;
		}
	if ($field_type=='TIME') 
		{
		$field_sql .= "TIME ";
		$field_cost = 8;
		}
	$field_cost = ($field_cost * 3); # account for utf8 database

	if ( ($field_default != 'NULL') and ($field_type!='AREA') and ($field_type!='DATE') and ($field_type!='TIME') )
		{$field_sql .= "default '$field_default'";}

	if ($table_exists < 1)
		{$field_sql .= ");";}
	else
		{$field_sql .= ";";}

	$SQLexecuted=0;

	if ( ($field_type=='DISPLAY') or ($field_type=='SCRIPT') or (preg_match("/\|$field_label\|/i",$vicidial_list_fields)) )
		{
		if ($DB) {echo "Non-DB $field_type field type, $field_label\n";}

		if ($table_exists < 1)
			{$field_sql = "CREATE TABLE custom_$list_id (lead_id INT(9) UNSIGNED PRIMARY KEY NOT NULL);";}
		else
			{$SQLexecuted++;}
		}
	if ($SQLexecuted < 1)
		{
		$stmtCUSTOM="$field_sql";
		$rsltCUSTOM=mysql_to_mysqli($stmtCUSTOM, $linkCUSTOM);
		$table_update = mysqli_affected_rows($linkCUSTOM);
		if ($DB) {echo "$table_update|$stmtCUSTOM\n";}
		if (!$rsltCUSTOM) 
			{
			echo(_QXZ("Could not execute").': ' . mysqli_error()) . "|$stmtCUSTOM|<BR><B>"._QXZ("FIELD NOT ADDED, PLEASE GO BACK AND TRY AGAIN")."</b>";
			
			### LOG INSERTION Admin Log Table ###
			$SQL_log = "$stmt|$stmtCUSTOM";
			$SQL_log = preg_replace('/;/', '', $SQL_log);
			$SQL_log = addslashes($SQL_log);
			$stmt="INSERT INTO vicidial_admin_log set event_date=NOW(), user='$user', ip_address='$ip', event_section='CUSTOM_FIELDS', event_type='OTHER', record_id='$list_id', event_code='ADMIN ADD CUSTOM LIST FIELD ERROR', event_sql=\"$SQL_log\", event_notes='ADD ERROR:" . mysqli_error() . "';";
			if ($DB) {echo "|$stmt|\n";}
			$rslt=mysql_to_mysqli($stmt, $link);
			}
		else
			{$SQLexecuted++;}
		}

	if ($SQLexecuted > 0)
		{
		$stmt="INSERT INTO vicidial_lists_fields set field_label='$field_label',field_name='$field_name',field_description='$field_description',field_rank='$field_rank',field_help='$field_help',field_type='$field_type',field_options='$field_options',field_size='$field_size',field_max='$field_max',field_default='$field_default',field_required='$field_required',field_cost='$field_cost',list_id='$list_id',multi_position='$multi_position',name_position='$name_position',field_order='$field_order';";
		$rslt=mysql_to_mysqli($stmt, $link);
		$field_update = mysqli_affected_rows($link);
		if ($DB) {echo "$field_update|$stmt\n";}
		if (!$rslt) {echo(_QXZ("Could not execute").': ' . mysqli_error()) . "|$stmt|";}

		### LOG INSERTION Admin Log Table ###
		$SQL_log = "$stmt|$stmtCUSTOM";
		$SQL_log = preg_replace('/;/', '', $SQL_log);
		$SQL_log = addslashes($SQL_log);
		$stmt="INSERT INTO vicidial_admin_log set event_date=NOW(), user='$user', ip_address='$ip', event_section='CUSTOM_FIELDS', event_type='ADD', record_id='$list_id', event_code='ADMIN ADD CUSTOM LIST FIELD', event_sql=\"$SQL_log\", event_notes='';";
		if ($DB) {echo "|$stmt|\n";}
		$rslt=mysql_to_mysqli($stmt, $link);
		}
	
	return $SQLexecuted;
	}
##### END add field function





################################################################################
##### BEGIN modify field function
function modify_field_function($DB,$link,$linkCUSTOM,$ip,$user,$table_exists,$field_id,$list_id,$field_label,$field_name,$field_description,$field_rank,$field_help,$field_type,$field_options,$field_size,$field_max,$field_default,$field_required,$field_cost,$multi_position,$name_position,$field_order,$vicidial_list_fields)
	{
	$field_db_exists=0;
	if ( ($field_type=='DISPLAY') or ($field_type=='SCRIPT') or (preg_match("/\|$field_label\|/i",$vicidial_list_fields)) )
		{$field_db_exists=1;}
	else
		{
		$stmt="SHOW COLUMNS from custom_$list_id LIKE '$field_label';";
		if ($DB>0) {echo "$stmt";}
		$rslt=mysql_to_mysqli($stmt, $link);
		$field_db_exists = mysqli_num_rows($rslt);
		}
	if ($field_db_exists > 0)
		{$field_sql = "ALTER TABLE custom_$list_id MODIFY $field_label ";}
	else
		{$field_sql = "ALTER TABLE custom_$list_id ADD $field_label ";}

	$field_options_ENUM='';
	$field_cost=1;
	if ( ($field_type=='SELECT') or ($field_type=='RADIO') )
		{
		$field_options_array = explode("\n",$field_options);
		$field_options_count = count($field_options_array);
		$te=0;
		while ($te < $field_options_count)
			{
			if (preg_match("/,/",$field_options_array[$te]))
				{
				$field_options_value_array = explode(",",$field_options_array[$te]);
				$field_options_ENUM .= "'$field_options_value_array[0]',";
				}
			$te++;
			}
		$field_options_ENUM = preg_replace("/.$/",'',$field_options_ENUM);
		$field_sql .= "ENUM($field_options_ENUM) ";
		$field_cost = strlen($field_options_ENUM);
		}
	if ( ($field_type=='MULTI') or ($field_type=='CHECKBOX') )
		{
		$field_options_array = explode("\n",$field_options);
		$field_options_count = count($field_options_array);
		$te=0;
		while ($te < $field_options_count)
			{
			if (preg_match("/,/",$field_options_array[$te]))
				{
				$field_options_value_array = explode(",",$field_options_array[$te]);
				$field_options_ENUM .= "'$field_options_value_array[0]',";
				}
			$te++;
			}
		$field_options_ENUM = preg_replace("/.$/",'',$field_options_ENUM);
		$field_cost = strlen($field_options_ENUM);
		$field_sql .= "VARCHAR($field_cost) ";
		}
	if ( ($field_type=='TEXT') or ($field_type=='HIDDEN') or ($field_type=='READONLY') )
		{
		$field_sql .= "VARCHAR($field_max) ";
		$field_cost = ($field_max + $field_cost);
		}
	if ($field_type=='HIDEBLOB')
		{
		$field_sql .= "BLOB ";
		$field_cost = 15;
		}
	if ($field_type=='AREA') 
		{
		$field_sql .= "TEXT ";
		$field_cost = 15;
		}
	if ($field_type=='DATE') 
		{
		$field_sql .= "DATE ";
		$field_cost = 10;
		}
	if ($field_type=='TIME') 
		{
		$field_sql .= "TIME ";
		$field_cost = 8;
		}
	$field_cost = ($field_cost * 3); # account for utf8 database

	if ( ($field_default == 'NULL') or ($field_type=='AREA') or ($field_type=='DATE') or ($field_type=='TIME') )
		{$field_sql .= ";";}
	else
		{$field_sql .= "default '$field_default';";}

	$SQLexecuted=0;

	if ( ($field_type=='DISPLAY') or ($field_type=='SCRIPT') or (preg_match("/\|$field_label\|/i",$vicidial_list_fields)) )
		{
		if ($DB) {echo _QXZ("Non-DB")." $field_type "._QXZ("field type").", $field_label\n";}
		$SQLexecuted++;
		}
	else
		{
		$stmtCUSTOM="$field_sql";
		$rsltCUSTOM=mysql_to_mysqli($stmtCUSTOM, $linkCUSTOM);
		$field_update = mysqli_affected_rows($linkCUSTOM);
		if ($DB) {echo "$field_update|$stmtCUSTOM\n";}
		if (!$rsltCUSTOM) {echo(_QXZ("Could not execute").': ' . mysqli_error()) . "|$stmtCUSTOM|<BR><B>"._QXZ("FIELD NOT MODIFIED, PLEASE GO BACK AND TRY AGAIN")."</b>";
			### LOG INSERTION Admin Log Table ###
			$SQL_log = "$stmt|$stmtCUSTOM";
			$SQL_log = preg_replace('/;/', '', $SQL_log);
			$SQL_log = addslashes($SQL_log);
			$stmt="INSERT INTO vicidial_admin_log set event_date=NOW(), user='$user', ip_address='$ip', event_section='CUSTOM_FIELDS', event_type='OTHER', record_id='$list_id', event_code='ADMIN MODIFY CUSTOM LIST FIELD ERROR', event_sql=\"$SQL_log\", event_notes='MODIFY ERROR:" . mysqli_error() . "';";
			if ($DB) {echo "|$stmt|\n";}
			$rslt=mysql_to_mysqli($stmt, $link);
			}
		else
			{$SQLexecuted++;}
		}

	if ($SQLexecuted > 0)
		{
		$stmt="UPDATE vicidial_lists_fields set field_label='$field_label',field_name='$field_name',field_description='$field_description',field_rank='$field_rank',field_help='$field_help',field_type='$field_type',field_options='$field_options',field_size='$field_size',field_max='$field_max',field_default='$field_default',field_required='$field_required',field_cost='$field_cost',multi_position='$multi_position',name_position='$name_position',field_order='$field_order' where list_id='$list_id' and field_id='$field_id';";
		$rslt=mysql_to_mysqli($stmt, $link);
		$field_update = mysqli_affected_rows($link);
		if ($DB) {echo "$field_update|$stmt\n";}
		if (!$rslt) {echo('Could not execute: ' . mysqli_error()) . "|$stmt|";}

		### LOG INSERTION Admin Log Table ###
		$SQL_log = "$stmt|$stmtCUSTOM";
		$SQL_log = preg_replace('/;/', '', $SQL_log);
		$SQL_log = addslashes($SQL_log);
		$stmt="INSERT INTO vicidial_admin_log set event_date=NOW(), user='$user', ip_address='$ip', event_section='CUSTOM_FIELDS', event_type='MODIFY', record_id='$list_id', event_code='ADMIN MODIFY CUSTOM LIST FIELD', event_sql=\"$SQL_log\", event_notes='';";
		if ($DB) {echo "|$stmt|\n";}
		$rslt=mysql_to_mysqli($stmt, $link);
		}
	
	return $SQLexecuted;
	}
##### END modify field function





################################################################################
##### BEGIN delete field function
function delete_field_function($DB,$link,$linkCUSTOM,$ip,$user,$table_exists,$field_id,$list_id,$field_label,$field_name,$field_description,$field_rank,$field_help,$field_type,$field_options,$field_size,$field_max,$field_default,$field_required,$field_cost,$multi_position,$name_position,$field_order,$vicidial_list_fields)
	{
	$SQLexecuted=0;

	if ( ($field_type=='DISPLAY') or ($field_type=='SCRIPT') or (preg_match("/\|$field_label\|/i",$vicidial_list_fields)) )
		{
		if ($DB) {echo "Non-DB $field_type field type, $field_label\n";}
		$SQLexecuted++;
		}
	else
		{
		$stmtCUSTOM="ALTER TABLE custom_$list_id DROP $field_label;";
		$rsltCUSTOM=mysql_to_mysqli($stmtCUSTOM, $linkCUSTOM);
		$table_update = mysqli_affected_rows($linkCUSTOM);
		if ($DB) {echo "$table_update|$stmtCUSTOM\n";}
		if (!$rsltCUSTOM) {echo(_QXZ("Could not execute").': ' . mysqli_error()) . "|$stmtCUSTOM|<BR><B>"._QXZ("FIELD NOT DELETED, PLEASE GO BACK AND TRY AGAIN")."</b>";
			### LOG INSERTION Admin Log Table ###
			$SQL_log = "$stmt|$stmtCUSTOM";
			$SQL_log = preg_replace('/;/', '', $SQL_log);
			$SQL_log = addslashes($SQL_log);
			$stmt="INSERT INTO vicidial_admin_log set event_date=NOW(), user='$user', ip_address='$ip', event_section='CUSTOM_FIELDS', event_type='OTHER', record_id='$list_id', event_code='ADMIN DELETE CUSTOM LIST FIELD ERROR', event_sql=\"$SQL_log\", event_notes='DELETE ERROR:" . mysqli_error() . "';";
			if ($DB) {echo "|$stmt|\n";}
			$rslt=mysql_to_mysqli($stmt, $link);
			}
		else
			{$SQLexecuted++;}
		}

	if ($SQLexecuted > 0)
		{
		$stmt="DELETE FROM vicidial_lists_fields WHERE field_label='$field_label' and field_id='$field_id' and list_id='$list_id' LIMIT 1;";
		$rslt=mysql_to_mysqli($stmt, $link);
		$field_update = mysqli_affected_rows($link);
		if ($DB) {echo "$field_update|$stmt\n";}
		if (!$rslt) {echo(_QXZ("Could not execute").': ' . mysqli_error() . "|$stmt|");}

		### LOG INSERTION Admin Log Table ###
		$SQL_log = "$stmt|$stmtCUSTOM";
		$SQL_log = preg_replace('/;/', '', $SQL_log);
		$SQL_log = addslashes($SQL_log);
		$stmt="INSERT INTO vicidial_admin_log set event_date=NOW(), user='$user', ip_address='$ip', event_section='CUSTOM_FIELDS', event_type='DELETE', record_id='$list_id', event_code='ADMIN DELETE CUSTOM LIST FIELD', event_sql=\"$SQL_log\", event_notes='';";
		if ($DB) {echo "|$stmt|\n";}
		$rslt=mysql_to_mysqli($stmt, $link);
		}
	
	return $SQLexecuted;
	}
##### END delete field function

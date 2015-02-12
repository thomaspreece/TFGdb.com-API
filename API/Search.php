<?php	
# Dumps all the content of game with specified ID 
# Expects a GET variable of ContentType=JSON or ContentType=XML depending on what output you want. Also expects ID of game data you want to retrieve. ID's of games can be found using Search.php

$PageName = "API-Search.php";
# Include basic functions used on site and database
include("./../Includes/HeaderFunctions.php");

# Sets $Content variable:	0 - Error
#							1 - PHP
#							2 - JSON	
if(isset($_GET['ContentType'])){
	if ($_GET['ContentType']=="JSON"){
		$Content = 2;
	}elseif($_GET['ContentType']=="XML"){
		$Content = 1;
	}else{
		$Content = 0;
	}
}else{
	$Content = 0;
}


# Send relevant content headers for requested return content type
if($Content == 1){
	header('Content-type: text/xml');
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<Data>";	
}elseif($Content==2){
	header('Content-Type: application/json');
	echo "{
	\"games\": [
	";
}else{
	echo "You must specify content type!<br/>
	Please see <a href='http://tfgdb.com/API/'>http://tfgdb.com/API/</a>";
	die;
}
		
$SearchLine = "WHERE";

# Perform a clean up on user input to remove harmful content
$_CLEANREQUEST = array();
foreach($_GET as $key => $value){
	$_CLEANREQUEST[addslashes(htmlspecialchars($key,ENT_QUOTES))] = addslashes(htmlspecialchars($value,ENT_QUOTES));
}

#Generate Search Query
if (isset($_CLEANREQUEST["Mode"]) && $_CLEANREQUEST["Mode"] != ""){
	$SearchLine = $SearchLine." ModeBITS & ".intval($_CLEANREQUEST["Mode"])." AND";
}

if (isset($_CLEANREQUEST["Gen"]) && $_CLEANREQUEST["Gen"] != ""){
	$SearchLine = $SearchLine." GenreBITS & ".intval($_CLEANREQUEST["Gen"])." AND";
}

if (isset($_CLEANREQUEST["Plat"]) && $_CLEANREQUEST["Plat"] != ""){
	$SearchLine = $SearchLine." PlatformBITS & ".intval($_CLEANREQUEST["Plat"])." AND";
}

if (isset($_CLEANREQUEST["Release"]) && $_CLEANREQUEST["Release"] != ""){
	foreach($GAMERELEASE as $temp){
		if($temp['ID']==$_CLEANREQUEST["Release"]){
			$SearchLine = $SearchLine." `Release`='".$_CLEANREQUEST["Release"]."' AND";
		}
	}
}						

if (isset($_CLEANREQUEST["Letter"]) && $_CLEANREQUEST["Letter"] != ""){
	$_CLEANREQUEST['Search'] = "";
	$SearchLine = $SearchLine." Name LIKE '".$_CLEANREQUEST["Letter"]."%' AND";
}else{					
	if (isset($_CLEANREQUEST["Search"])  && $_CLEANREQUEST["Search"] != "" && strlen($_CLEANREQUEST["Search"])>1){
			$SearchLine = $SearchLine." Name LIKE '%".$_CLEANREQUEST["Search"]."%' AND";
	}else{

	}
}

if (isset($_CLEANREQUEST["Graphics"])  && $_CLEANREQUEST["Graphics"] != ""){
	
	foreach($GAMEGRAPHICS as $temp){
		if($temp['ID']==$_CLEANREQUEST["Graphics"]){
			$SearchLine = $SearchLine." Graphics='".$_CLEANREQUEST["Graphics"]."' AND";
		}
	}			
}

if (isset($_CLEANREQUEST["Type"])  && $_CLEANREQUEST["Type"] != ""){
	
	foreach($GAMETYPES as $temp){
		if($temp['ID']==$_CLEANREQUEST["Type"]){
			
			$SearchLine = $SearchLine." Type='".$_CLEANREQUEST["Type"]."' AND";
		}
	}				

}

if (isset($_CLEANREQUEST["Source"]) && $_CLEANREQUEST["Source"] != ""){
	
	foreach($GAMESOURCES as $temp){
		if($temp['ID']==$_CLEANREQUEST["Source"]){
			
			$SearchLine = $SearchLine." Source='".$_CLEANREQUEST["Source"]."' AND";
		}
	}			

}

if (isset($_CLEANREQUEST["Studio"]) && $_CLEANREQUEST["Studio"] != ""){
	
	foreach($GAMESTUDIOS as $temp){
		if($temp['ID']==$_CLEANREQUEST["Studio"]){
			$SearchLine = $SearchLine." Studio='".$_CLEANREQUEST["Studio"]."' AND";
		}
	}						
	
}	

$SearchLine = $SearchLine." `QuedTodaysGame`=0";						
	
if (isset($_CLEANREQUEST["Sort"])){
	if($_CLEANREQUEST["Sort"]==1){
		$SearchLine = $SearchLine." ORDER BY Rating, RateNum ";							
	}elseif($_CLEANREQUEST["Sort"]==2){
		$SearchLine = $SearchLine." ORDER BY Rating DESC, RateNum DESC ";
	}elseif($_CLEANREQUEST["Sort"]==3){
		$SearchLine = $SearchLine." ORDER BY ID DESC ";								
	}else{
		$SearchLine = $SearchLine." ORDER BY Name ";			
	}
}else{
	$SearchLine = $SearchLine." ORDER BY Name ";
}

# Perform Search 
$GameSearch = $Gamedb->prepare("SELECT * FROM freegames ".$SearchLine);
$GameSearch->execute();

# Output results
while($row = $GameSearch->fetch(PDO::FETCH_ASSOC)){

	$Desc = $row['About'];
	$Desc = preg_replace('/<br\/>/',"\n",$Desc);
	$Desc = preg_replace('/[^a-zA-Z0-9\., \n\'\"’\(\)&\?!\+-:;]/',"",$Desc);
	$Desc = preg_replace('/[\n]/',"<br/>",$Desc);
	$Desc = preg_replace('/[&][^#]/',"&amp; ",$Desc);	
	
	if(strlen($Desc) > 340){
		$Desc = substr($Desc,0,340)."... ";
	}	

	if($Content == 1){
		echo "
	<Game>
		<ID>".$row['ID']."</ID>
		<Name>".$row['Name']."</Name>
		<About>".$Desc."</About>	
	</Game>";
	}elseif($Content == 2){
		if($FirstDone==true){
			echo ",";
		}

		echo "
		{
			\"id\": \"".$row['ID']."\",
			\"name\": \"".$row['Name']."\",
			\"about\": \"".$Desc."\"
		}
		";

		$FirstDone = true;
	} 
}

# Finish content with relevant ending tags for content type
if($Content == 1){
	echo "
</Data>";
}elseif($Content == 2){
	echo "]
	}";
}	
				
?>

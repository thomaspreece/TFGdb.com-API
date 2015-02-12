<?php		
# Dumps all the content of game with specified ID 
# Expects a GET variable of ContentType=JSON or ContentType=XML depending on what output you want. Also expects ID of game data you want to retrieve. ID's of games can be found using Search.php

$PageName = "API-GetGame.php";
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

#Check ID is specified and is a number
if(isset($_GET['ID']) && intval($_GET['ID']) != 0){

}else{
	echo "You must specify an ID!<br/>
	Please see <a href='http://tfgdb.com/API/'>http://tfgdb.com/API/</a>";
	die;
}

# Send relevant content headers for requested return content type
if($Content == 1){
	header('Content-type: text/xml');
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<Data>";	
}elseif($Content==2){
	header('Content-Type: application/json');
	echo "{
	";
}else{
	echo "You must specify content type!<br/>
	Please see <a href='http://tfgdb.com/API/'>http://tfgdb.com/API/</a>";
	die;
}
		

# Perform a clean up on user input to remove harmful content
$_CLEANREQUEST = array();
foreach($_GET as $key => $value){
	$_CLEANREQUEST[addslashes(htmlspecialchars($key,ENT_QUOTES))] = addslashes(htmlspecialchars($value,ENT_QUOTES));
}

#Look in database for game with ID specified
$stmt = $Gamedb->prepare("SELECT * FROM freegames WHERE ID=:id");
$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
$stmt->execute();
$GameInfo = $stmt->fetch(PDO::FETCH_ASSOC);


if($GameInfo){
	#Get game data and output it
	#NOTE: Section is inefficient, could be much more efficient by using SQL Join instead of separate queries.
	$Genre = "";
	$Graphics = "";
	$Source = "";
	$Studio = "";	
	$Type = "";
	$Release = "";
	$Age = "";
	$Mode = "";
	
	foreach($GAMEMODESBITS as $temp){
		if($temp['ID'] & $GameInfo['ModeBITS']){
			$Mode = $Mode.$temp['Mode'].", ";
		}
	}
	$Mode = substr($Mode,0,-2);
	
	$Genre = "";
	foreach($GAMEGENRESBITS as $temp){
		if($temp['ID'] & $GameInfo['GenreBITS']){
			$Genre = $Genre.$temp['Genre'].", ";
		}
	}
	$Genre = substr($Genre,0,-2);
	
	$Platform = "";
	foreach($GAMEPLATFORMSBITS as $temp){
		if($temp['ID'] & $GameInfo['PlatformBITS']){
			$Platform = $Platform.$temp['Platform'].", ";
		}
	}
	$Platform = substr($Platform,0,-2);	

	foreach($GAMEAGES as $temp){
		if($temp['ID']==$GameInfo['Age']){
			$Age = $temp['Age'];
		}
	}
	
	foreach($GAMEGRAPHICS as $temp){
		if($temp['ID']==$GameInfo['Graphics']){
			$Graphics = $temp['Graphics'];
		}
	}
	
	foreach($GAMESOURCES as $temp){
		if($temp['ID']==$GameInfo['Source']){
			$Source = $temp['Source'];
		}	
	}	

	foreach($GAMESTUDIOS as $temp){
		if($temp['ID']==$GameInfo['Studio']){
			$Studio = $temp['Studio'];
		}	
	}			

	foreach($GAMETYPES as $temp){
		if($temp['ID']==$GameInfo['Type']){
			$Type = $temp['Type'];
		}	
	}		
	
	foreach($GAMERELEASE as $temp){
		if($temp['ID']==$GameInfo['Release']){
			$Release = $temp['Release'];
		}	
	}
	
	
	$Desc = $GameInfo['About'];
	$Desc = preg_replace('/<br\/>/',"\n",$Desc);
	$Desc = preg_replace('/[^a-zA-Z0-9\., \n\'\"’\(\)&\?!\+-:;]/',"",$Desc);
	$Desc = preg_replace('/[\n]/',"<br/>",$Desc);
	$Desc = preg_replace('/[&][^#]/',"&amp; ",$Desc);
	
	
	if($Content == 2){
		$Desc = preg_replace('/\"/',"\\\"",$Desc); #Replace " with \" for JSON
		echo "
	\"ID\":\"".$GameInfo['ID']."\",
	\"Name\":\"".$GameInfo['Name']."\",
	\"About\":\"$Desc\",
	\"Mode\":\"$Mode\",
	\"Genre\":\"$Genre\",
	\"Platform\":\"$Platform\",
	\"Age\":\"$Age\",
	\"Graphics\":\"$Graphics\",
	\"Source\":\"$Source\",
	\"Studio\":\"$Studio\",
	\"Type\":\"$Type\",
	\"Release\":\"$Release\",
	\"Trailer\":\"".$GameInfo['Trailer']."\",
	\"AverageRating\":\"".$GameInfo['Rating']."\",
	\"RatingNumber\":\"".$GameInfo['RateNum']."\",
	\"Websites\":[";
		$stmt = $Gamedb->prepare("SELECT * FROM websites WHERE ID=:id AND Pending=0 AND Flagged=0");
		$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
		$stmt->execute();
		$FirstRow = 0;
		while($Webrow = $stmt->fetch(PDO::FETCH_ASSOC)){
			if($FirstRow == 1){
				echo ",";
			}
			echo "
		{
			\"URL\":\"".$Webrow['Website']."\",
			\"Notes\":\"".$Webrow['Notes']."\"
		}";
			$FirstRow = 1;
		}
		echo "
	],
	\"Downloads\":[";
		$stmt = $Gamedb->prepare("SELECT * FROM downloadsbits WHERE ID=:id AND Pending=0 AND Flagged=0");
		$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
		$stmt->execute();	
		$FirstRow = 0;		
		while($Downloadrow = $stmt->fetch(PDO::FETCH_ASSOC)){
			$DownloadPlatform = "";
			foreach($GAMEPLATFORMSBITS as $temp){
				if($temp['ID'] & $Downloadrow['Platform']){
					$DownloadPlatform = $DownloadPlatform.$temp['Platform'].", ";
				}
			}
			$DownloadPlatform = substr($Platform,0,-2);	
			if($FirstRow == 1){
				echo ",";
			}			
			echo "
		{
			\"URL\":\"".$Downloadrow['Download']."\",
			\"Direct\":\"".$Downloadrow['Direct']."\",
			\"Platform\":\"$DownloadPlatform\",
			\"OS\":\"".$Downloadrow['OS']."\",
			\"Version\":\"".$Downloadrow['Version']."\",
			\"FileSize\":\"".$Downloadrow['FileSize']."\"
		}";
			$FirstRow = 1;			
		}
		echo "
	],
	\"Resources\":[";
		$stmt = $Gamedb->prepare("SELECT * FROM resources WHERE ID=:id AND Pending=0 AND Flagged=0");
		$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
		$stmt->execute();	
		$FirstRow = 0;			
		while($Resourcerow = $stmt->fetch(PDO::FETCH_ASSOC)){	
			switch ($Resourcerow['Type']) {
				case 1:
					$ResourceType = "Front";
					break;
				case 2:
					$ResourceType = "Back";
					break;
				case 3:
					$ResourceType = "FanArt";
					break;
				case 4:
					$ResourceType = "ScreenShot";
					break;
				case 5:
					$ResourceType = "Banner";
					break;					
				default:
					$ResourceType = "Unknown(Error)";
					break;
			}
			if($FirstRow == 1){
				echo ",";
			}			
			echo "
		{
			\"URL\":\"".$Resourcerow['ResourcePath']."\",
			\"URL-Thumb\":\"".$Resourcerow['ResourceThumb']."\",
			\"URL-Mid\":\"".$Resourcerow['ResourceMid']."\",
			\"Type\":\"$ResourceType\"
		}
				";
			$FirstRow = 1;					
		}		
		echo "
	],
	\"Reviews\":[";
		$stmt = $Gamedb->prepare("SELECT * FROM `reviews` WHERE `ID`=:id AND Pending=0 AND Flagged=0");
		$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
		$stmt->execute();
		
		
		$FirstRow = 0;			
		while($Rewviewrow = $stmt->fetch(PDO::FETCH_ASSOC)){	
			$Pros = preg_replace('/\"/',"\\\"",$Rewviewrow['Pros']); #Replace " with \" for JSON
			$Cons = preg_replace('/\"/',"\\\"",$Rewviewrow['Cons']); #Replace " with \" for JSON
			$FullReview = preg_replace('/\"/',"\\\"",$Rewviewrow['Review']); #Replace " with \" for JSON
			
			if($FirstRow == 1){
				echo ",";
			}				
			echo "
		{
			\"Pros\":\"".$Pros."\",
			\"Cons\":\"".$Cons."\",
			\"FullReview\":\"".$FullReview."\",
			\"Rating\":\"".$Rewviewrow['Rating']."\"
		}
				";
			$FirstRow = 1;					
		}			
		echo "
	]
";
	
	}elseif($Content == 1){
		echo "
	<game>
		<ID>".$GameInfo['ID']."</ID>
		<Name>".$GameInfo['Name']."</Name>
		<About>$Desc</About>
		<Mode>$Mode</Mode>
		<Genre>$Genre</Genre>
		<Platform>$Platform</Platform>
		<Age>$Age</Age>
		<Graphics>$Graphics</Graphics>
		<Source>$Source</Source>
		<Studio>$Studio</Studio>
		<Type>$Type</Type>
		<Release>$Release</Release>
		<Trailer>".$GameInfo['Trailer']."</Trailer>
		<AverageRating>".$GameInfo['Rating']."</AverageRating>
		<RatingNumber>".$GameInfo['RateNum']."</RatingNumber>
		<Websites>";
		$stmt = $Gamedb->prepare("SELECT * FROM websites WHERE ID=:id AND Pending=0 AND Flagged=0");
		$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
		$stmt->execute();
		while($Webrow = $stmt->fetch(PDO::FETCH_ASSOC)){
			echo "
			<Website>
				<URL>".$Webrow['Website']."</URL>
				<Notes>".$Webrow['Notes']."</Notes>
			</Website>";
		}
		echo "
		</Websites>
		<Downloads>";
		$stmt = $Gamedb->prepare("SELECT * FROM downloadsbits WHERE ID=:id AND Pending=0 AND Flagged=0");
		$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
		$stmt->execute();	
		while($Downloadrow = $stmt->fetch(PDO::FETCH_ASSOC)){
			$DownloadPlatform = "";
			foreach($GAMEPLATFORMSBITS as $temp){
				if($temp['ID'] & $Downloadrow['Platform']){
					$DownloadPlatform = $DownloadPlatform.$temp['Platform'].", ";
				}
			}
			$DownloadPlatform = substr($Platform,0,-2);	
			echo "
			<Download>
				<URL>".$Downloadrow['Download']."</URL>
				<Direct>".$Downloadrow['Direct']."</Direct>
				<Platform>$DownloadPlatform</Platform>
				<OS>".$Downloadrow['OS']."</OS>
				<Version>".$Downloadrow['Version']."</Version>
				<FileSize>".$Downloadrow['FileSize']."</FileSize>
			</Download>";
		}
		echo "
		</Downloads>
		<Resources>";
		$stmt = $Gamedb->prepare("SELECT * FROM resources WHERE ID=:id AND Pending=0 AND Flagged=0");
		$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
		$stmt->execute();	
		while($Resourcerow = $stmt->fetch(PDO::FETCH_ASSOC)){	
			switch ($Resourcerow['Type']) {
				case 1:
					$ResourceType = "Front";
					break;
				case 2:
					$ResourceType = "Back";
					break;
				case 3:
					$ResourceType = "FanArt";
					break;
				case 4:
					$ResourceType = "ScreenShot";
					break;
				case 5:
					$ResourceType = "Banner";
					break;					
				default:
					$ResourceType = "Unknown(Error)";
					break;
			}
			echo "
			<Resource>
				<URL>".$Resourcerow['ResourcePath']."</URL>
				<URL-Thumb>".$Resourcerow['ResourceThumb']."</URL-Thumb>
				<URL-Mid>".$Resourcerow['ResourceMid']."</URL-Mid>
				<Type>$ResourceType</Type>
			</Resource>
				";
		}		
		echo "
		</Resources>
		<Reviews>";
		$stmt = $Gamedb->prepare("SELECT * FROM `reviews` WHERE `ID`=:id AND Pending=0 AND Flagged=0");
		$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
		$stmt->execute();	
		while($Rewviewrow = $stmt->fetch(PDO::FETCH_ASSOC)){	
			echo "
			<Review>
				<Pros>".$Rewviewrow['Pros']."</Pros>
				<Cons>".$Rewviewrow['Cons']."</Cons>
				<FullReview>".$Rewviewrow['Review']."</FullReview>
				<Rating>".$Rewviewrow['Rating']."</Rating>
			</Review>
				";
		}			
		echo "
		</Reviews>
	</game>
";	
	}
}

# Finish content with relevant ending tags for content type
if($Content == 1){
	echo "</Data>";
}elseif($Content == 2){
	echo "
	}";
}	
				
?>

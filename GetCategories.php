<?php		
include("./../Includes/HeaderFunctions.php");

//$Content	0 - Error
//			1 - PHP
//			2 - JSON	

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

if($Content == 1){
	header('Content-type: text/xml');
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
	<categories>";	
}elseif($Content==2){
	header('Content-Type: application/json');
	echo "{
	";
}else{
	echo "You must specify content type!<br/>
	Please see <a href='http://tfgdb.com/API/'>http://tfgdb.com/API/</a>";
	die;
}

#----------------------------------Mode----------------------------------
if($Content == 1){
	echo "<modes>";
}elseif($Content == 2){
	echo "\"modes\": [
	";
}

$FirstDone=false;
$stmt = $Gamedb->prepare("SELECT * FROM modebits");
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	if($Content == 1){
		echo "
<item>
	<ID>".$row['ID']."</ID>
	<Name>".$row['Mode']."</Name>	
</item>";
	}elseif($Content == 2){
		if($FirstDone==true){
			echo ",";
		}

		echo "
		{
			\"id\": \"".$row['ID']."\",
			\"name\": \"".$row['Mode']."\"
		}
		";

		$FirstDone = true;
	}
}

if($Content == 1){
	echo "</modes>";
}elseif($Content == 2){
	echo "],
	";
}

#----------------------------------genre----------------------------------
if($Content == 1){
	echo "<genres>";
}elseif($Content == 2){
	echo "\"genres\": [
	";
}

$FirstDone=false;
$stmt = $Gamedb->prepare("SELECT * FROM genresbits");
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	if($Content == 1){
		echo "
<item>
	<ID>".$row['ID']."</ID>
	<Name>".$row['Genre']."</Name>	
</item>";
	}elseif($Content == 2){
		if($FirstDone==true){
			echo ",";
		}

		echo "
		{
			\"id\": \"".$row['ID']."\",
			\"name\": \"".$row['Genre']."\"
		}
		";

		$FirstDone = true;
	}
}

if($Content == 1){
	echo "</genres>";
}elseif($Content == 2){
	echo "],
	";
}

#----------------------------------Platform----------------------------------
if($Content == 1){
	echo "<platforms>";
}elseif($Content == 2){
	echo "\"platforms\": [
	";
}

$FirstDone=false;
$stmt = $Gamedb->prepare("SELECT * FROM platformbits");
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	if($Content == 1){
		echo "
<item>
	<ID>".$row['ID']."</ID>
	<Name>".$row['Platform']."</Name>	
</item>";
	}elseif($Content == 2){
		if($FirstDone==true){
			echo ",";
		}

		echo "
		{
			\"id\": \"".$row['ID']."\",
			\"name\": \"".$row['Platform']."\"
		}
		";

		$FirstDone = true;
	}
}

if($Content == 1){
	echo "</platforms>";
}elseif($Content == 2){
	echo "],
	";
}



#----------------------------------source----------------------------------
if($Content == 1){
	echo "<sources>";
}elseif($Content == 2){
	echo "\"sources\": [
	";
}

$FirstDone=false;
$stmt = $Gamedb->prepare("SELECT * FROM source");
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	if($Content == 1){
		echo "
<item>
	<ID>".$row['ID']."</ID>
	<Name>".$row['Source']."</Name>	
</item>";
	}elseif($Content == 2){
		if($FirstDone==true){
			echo ",";
		}

		echo "
		{
			\"id\": \"".$row['ID']."\",
			\"name\": \"".$row['Source']."\"
		}
		";

		$FirstDone = true;
	}
}

if($Content == 1){
	echo "</sources>";
}elseif($Content == 2){
	echo "],
	";
}



#----------------------------------graphics----------------------------------
if($Content == 1){
	echo "<graphics>";
}elseif($Content == 2){
	echo "\"graphics\": [
	";
}

$FirstDone=false;
$stmt = $Gamedb->prepare("SELECT * FROM graphics");
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	if($Content == 1){
		echo "
<item>
	<ID>".$row['ID']."</ID>
	<Name>".$row['Graphics']."</Name>	
</item>";
	}elseif($Content == 2){
		if($FirstDone==true){
			echo ",";
		}

		echo "
		{
			\"id\": \"".$row['ID']."\",
			\"name\": \"".$row['Graphics']."\"
		}
		";

		$FirstDone = true;
	}
}

if($Content == 1){
	echo "</graphics>";
}elseif($Content == 2){
	echo "],
	";
}


#----------------------------------studio----------------------------------
if($Content == 1){
	echo "<studios>";
}elseif($Content == 2){
	echo "\"studios\": [
	";
}

$FirstDone=false;
$stmt = $Gamedb->prepare("SELECT * FROM studio");
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	if($Content == 1){
		echo "
<item>
	<ID>".$row['ID']."</ID>
	<Name>".$row['Studio']."</Name>	
</item>";
	}elseif($Content == 2){
		if($FirstDone==true){
			echo ",";
		}

		echo "
		{
			\"id\": \"".$row['ID']."\",
			\"name\": \"".$row['Studio']."\"
		}
		";

		$FirstDone = true;
	}
}

if($Content == 1){
	echo "</studios>";
}elseif($Content == 2){
	echo "],
	";
}

#----------------------------------types----------------------------------
if($Content == 1){
	echo "<types>";
}elseif($Content == 2){
	echo "\"types\": [
	";
}

$FirstDone=false;
$stmt = $Gamedb->prepare("SELECT * FROM types");
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	if($Content == 1){
		echo "
<item>
	<ID>".$row['ID']."</ID>
	<Name>".$row['Type']."</Name>	
</item>";
	}elseif($Content == 2){
		if($FirstDone==true){
			echo ",";
		}

		echo "
		{
			\"id\": \"".$row['ID']."\",
			\"name\": \"".$row['Type']."\"
		}
		";

		$FirstDone = true;
	}
}

if($Content == 1){
	echo "</types>";
}elseif($Content == 2){
	echo "],
	";
}

#----------------------------------release----------------------------------
if($Content == 1){
	echo "<releases>";
}elseif($Content == 2){
	echo "\"releases\": [
	";
}

$FirstDone=false;
$stmt = $Gamedb->prepare("SELECT * FROM `release`");
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	if($Content == 1){
		echo "
<item>
	<ID>".$row['ID']."</ID>
	<Name>".$row['Release']."</Name>	
</item>";
	}elseif($Content == 2){
		if($FirstDone==true){
			echo ",
			";
		}

		echo "
		{
			\"id\": \"".$row['ID']."\",
			\"name\": \"".$row['Release']."\"
		}";

		$FirstDone = true;
	}
}

if($Content == 1){
	echo "</releases>";
}elseif($Content == 2){
	echo "]
	";
}


if($Content == 1){
	echo "
<sorts>
		<item>
			<ID>0</ID>
			<Name>Sort by Name</Name>
		</item>
		<item>
			<ID>1</ID>
			<Name>Sort by Rating(Worst First)</Name>
		</item>
		<item>
			<ID>2</ID>
			<Name>Sort by Rating(Best First)</Name>
		</item>
		<item>
			<ID>3</ID>
			<Name>Sort by Recently Added</Name>
		</item>		
</sorts>
	";
}elseif($Content == 2){

}



if($Content == 1){
	echo "</categories>";
}elseif($Content == 2){
	echo "}";
}	
				
?>

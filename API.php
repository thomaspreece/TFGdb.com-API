<?php
$MetaDescription = "TheFreeGamesDB's API allows direct access to our large collection of games for any application you so wish.";
$MetaKeywords = "API,Free,games,database,large,collection,applications";
$PageName = "API.php";

include("./Includes/HeaderFunctions.php");
$MetaTitle = "API - ".$DOMAINTITLE;
include("./Includes/Header.php");
?>
<div style='height:25px;'></div>
<div class='news'>
	<div style='margin:10px;'>
		<h1>Application programming interface (API)</h1>
		<p>
			This API allows you to directly return all the data about the games listed on this site as well as searching the games by all the categories available whilst using this site.<br/>
			The API currently only supports XML and JSON. And currently DOESN'T require you to get an API key, but this may be implemented in the future.<br/>
			You may use the API and data however you like in any application but we would appreciate a link back to the site if you do use our API and a post about your project in the forum would be nice too. We also give all developers administration to the site and expect them to help maintain the database.<br/>
			<br/>				
			The API consists of 3 different files: Search.php, GetGame.php and GetCategories.php. All them require you to specify the return data type (either 'XML' or 'JSON' using the ContentType GET variable.<br/>
			<br/>
			Also the API is open source and any contributes to it will be pushed to the live website. Find and contibute to the code here: <a href='https://github.com/thomaspreece10/TFGdb.com-API'>github.com/thomaspreece10/TFGdb.com-API</a>
			<br/><br/>
		</p>
		
		<APITitle>GetCategories.php</APITitle>
		<p class='API_p'>This file gets details about the numerical values for various categories. This is useful as the various GET variable filters for Search.php only take numbers and not the string values. If you are still a bit confused see the example below</p>
		<table class='API_Table' width='100%'>
			<colgroup width='150px'></colgroup>
			<colgroup width='*'></colgroup>
			<colgroup width='100px'></colgroup>
		<tr>
			<td class='API_Table_Top'>GET variable name</td>
			<td class='API_Table_Top'>Description</td>
			<td class='API_Table_Top'>Required?</td>
		</tr><tr>
			<td >ContentType</td>
			<td >Either 'XML' or 'JSON' depending on your requirements</td>
			<td >Yes</td>
		</tr>		
		</table>
		<p>
		<b>Example</b><br/>
		<a href='http://tfgdb.com/API/GetCategories.php?ContentType=JSON'>http://tfgdb.com/API/GetCategories.php?ContentType=JSON</a><br/>
		Returns JSON with all the numerical values for various categories including modes, genres, platforms, sources, graphics, studios, types and releases. So for example using this we could find out that the 'Freeware' type has id 1, we could then filter our search results in Search.php to only show games with type 'Freeware' by setting the GET variable 'Type' equal to 1. In other words our API request would be: 'Search.php?Type=1&ContentType=JSON'
		</p>	
		<br/>	
		<APITitle>Search.php</APITitle>
		<p class='API_p'>This file gets a list of games matching certain specified criteria.</p>
		<table class='API_Table' width='100%'>
			<colgroup width='150px'></colgroup>
			<colgroup width='*'></colgroup>
			<colgroup width='100px'></colgroup>
		<tr>
			<td class='API_Table_Top'>GET variable name</td>
			<td class='API_Table_Top'>Description</td>
			<td class='API_Table_Top'>Required?</td>
		</tr><tr>
			<td >ContentType</td>
			<td >Either 'XML' or 'JSON' depending on your requirements</td>
			<td >Yes</td>
		</tr><tr>
			<td >Search</td>
			<td >Can specify Search or Letter but not both! <br/>When specified only results with specified string in game 'Name' will be returned.</td>
			<td >No</td>
		</tr><tr>
			<td >Letter</td>
			<td >Can specify Search or Letter but not both!<br/>When specified only results with specified letter at start of game 'Name' will be returned.</td>
			<td >No</td>
		</tr><tr>
			<td >Mode</td>
			<td >Integer value. <br/>You can specify multiple values by adding together valid values. So for example 4+2=6 so setting a 'Mode' of 6 will filter games that are either 'Single Player' (2) or 'Multi Player' (4)<br/>To see valid values see GetCategories.php above. </td>
			<td >No</td>
		</tr><tr>
			<td >Gen</td>
			<td >Integer value. <br/>You can specify multiple values by adding together valid values. So for example 4+32=36 so setting a 'Gen' of 36 will filter games that are either 'Arcade' (4) or 'Card Games' (32)<br/>To see valid values see GetCategories.php above. </td>
			<td >No</td>
		</tr><tr>
			<td >Plat</td>
			<td >Integer value. <br/>You can specify multiple values by adding together valid values. So for example 16+1024+2048=3088 so setting a 'Plat' of 3088 will filter games that are either 'ScummVM' (16) or 'Desura-Mac' (1024) or 'Desura-Linux' (2048)<br/>To see valid values see GetCategories.php above. </td>
			<td >No</td>
		</tr><tr>
			<td >Release</td>
			<td >Integer value. <br/>To see valid values see GetCategories.php above.</td>
			<td >No</td>
		</tr><tr>
			<td >Graphics</td>
			<td >Integer value. <br/>To see valid values see GetCategories.php above.</td>
			<td >No</td>
		</tr><tr>
			<td >Type</td>
			<td >Integer value. <br/>To see valid values see GetCategories.php above.</td>
			<td >No</td>
		</tr><tr>
			<td >Source</td>
			<td >Integer value. <br/>To see valid values see GetCategories.php above.</td>
			<td >No</td>
		</tr><tr>
			<td >Studio</td>
			<td >Integer value. <br/>To see valid values see GetCategories.php above.</td>
			<td >No</td>
		</tr><tr>
			<td >Sort</td>
			<td >Integer value. <br/>To see valid values see GetCategories.php above.</td>
			<td >No</td>
		</tr>
		
		</table>
		<p>
		<b>Example</b><br/>
		<a href='http://tfgdb.com/API/Search.php?ContentType=XML&Search=battle&Gen=4&Studio=4'>http://tfgdb.com/API/Search.php?ContentType=XML&Search=battle&Gen=4&Studio=4</a><br/>
		This searches for games with genre 4 (Arcade) and studio 4 (indie) and who have project in their name.
		</p>	
		<br/>
		<APITitle>GetGame.php</APITitle>
		<p class='API_p'>This file gets all the details of a particular game. The requested game is identified by the unique ID it has in the database which you provide via the ID GET variable</p>
		<table class='API_Table' width='100%'>
			<colgroup width='150px'></colgroup>
			<colgroup width='*'></colgroup>
			<colgroup width='100px'></colgroup>
		<tr>
			<td class='API_Table_Top'>GET variable name</td>
			<td class='API_Table_Top'>Description</td>
			<td class='API_Table_Top'>Required?</td>
		</tr><tr>
			<td >ContentType</td>
			<td >Either 'XML' or 'JSON' depending on your requirements</td>
			<td >Yes</td>
		</tr><tr>
			<td >ID</td>
			<td >The unique ID for the game you want to get the data for. Search.php provides an ID when it returns games, use that here to get more information about each game.</td>
			<td >Yes</td>
		</tr>
		
		</table>
		<p>
		<b>Example</b><br/>
		<a href='http://tfgdb.com/API/GetGame.php?ContentType=XML&ID=632'>http://tfgdb.com/API/GetGame.php?ContentType=XML&ID=632</a><br/>
		Returns the game information for 0 A.D.
		</p>			
	</div>
</div>
					
<?php 
include("./Includes/Footer.php");
include("./Includes/FooterFunctions.php");
 ?>
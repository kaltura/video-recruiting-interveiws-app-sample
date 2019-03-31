<?php

require_once('../config.inc');
require_once('../adminKS.php');


/*
 * Calls createNewPlaylist if there isn't a playlist for this user yet
 * Add video entry to the playlist 
 * Return playlistId 
 */

function addToPlaylist($entryId, $email, $name, $ks) 
{
	$config = new KalturaConfiguration(PARTNER_ID);
  	$client = new KalturaClient($config);
  	$client->setKS($ks);

	$filter = new KalturaPlaylistFilter();
	$filter->creatorIdEqual = $email;
	$pager = new KalturaFilterPager();
	try {
		$result = $client->playlist->listAction($filter, $pager);
		if ($result->totalCount){
			$playlist = $result->objects[0]; 
		}else {
			$playlist = createNewPlaylist($name, $email, $client);
		}
		$id = $playlist->id; 
		$newPlaylist = new KalturaPlaylist();

		if (!isset($newPlaylist->playlistContent) || empty($newPlaylist->playlistContent)) {
			$newPlaylist->playlistContent = $entryId;
		}else {
			$newPlaylist->playlistContent = $playlist->playlistContent.','.$entryId; 
		}
		$newPlaylist->playlistContent = $playlist->playlistContent.','.$entryId; 
		$updateRes = $client->playlist->update($id, $newPlaylist, true);
  		print json_encode(array("playlistId"=>$updateRes->id)); 


	} catch (Exception $e) {
		echo $e->getMessage();
	}
}

function createNewPlaylist($name, $email, $client) 
{
	$playlist = new KalturaPlaylist(); 
	$playlist->name = $name; 
	$playlist->creatorId = $email; 
	$playlist->playlistType = KalturaPlaylistType::STATIC_LIST;

	try {
		$newPlaylist = $client->playlist->add($playlist); 
		addCategory($newPlaylist->id);

	} catch (Exception $e) {
		echo $e->getMessage(); 
	}

	return $newPlaylist;
}

/*
 * Get Admin KS in order to create a category entry 
 * For the category that has specific entitlements  
 */

function addCategory($playlistId) {
	try {
		$ks = getAdminKS(); 
		$config = new KalturaConfiguration(PARTNER_ID);
	  	$client = new KalturaClient($config);
	  	$client->setKS($ks);

		$categoryEntry = new KalturaCategoryEntry();
		$categoryEntry->categoryId = CATEGORY_ID;
		$categoryEntry->entryId = $playlistId;
		$result = $client->categoryEntry->add($categoryEntry);

	} catch (Exception $e) {
		echo $e->getMessage(); 
	}
}

$mandatory_params=array('entryId','name','email', 'ks');
foreach ($mandatory_params as $param){
	if (!isset($_GET[$param])){
		die("$param is mandatory");
	}
	$$param = strip_tags($_GET[$param]);
}

addToPlaylist($entryId, $email, $name, $ks);

?>
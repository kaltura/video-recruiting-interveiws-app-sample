<?php

require_once('../config.inc');
function search($term, $kals) 
{
    $config = new KalturaConfiguration();
    $client = new KalturaClient($config);
    $client->setKS($kals);

    /*
     * A few Entry Items are created: 
     * Partial match for the applicant name 
     * And exact match the applicant email 
     * They are given an OR condition 
     * Results are put in a list of entries
     */ 

    $entryTypeItem = new KalturaESearchEntryItem();
    $entryTypeItem->searchTerm = KalturaEntryType::PLAYLIST;
    $entryTypeItem->itemType = KalturaESearchItemType::EXACT_MATCH;
    $entryTypeItem->fieldName = KalturaESearchEntryFieldName::ENTRY_TYPE;

    $entryNameItem = new KalturaESearchEntryItem();
    $entryNameItem->searchTerm = $term;
    $entryNameItem->itemType = KalturaESearchItemType::PARTIAL;
    $entryNameItem->fieldName = KalturaESearchEntryFieldName::NAME;

    $creatorIdItem = new KalturaESearchEntryItem();
    $creatorIdItem->searchTerm = $term;
    $creatorIdItem->itemType = KalturaESearchItemType::EXACT_MATCH;
    $creatorIdItem->fieldName = KalturaESearchEntryFieldName::CREATOR_ID;

    $nameOrCreatorOperator = new KalturaESearchEntryOperator();
    $nameOrCreatorOperator->operator = KalturaESearchOperatorType::OR_OP;
    $nameOrCreatorOperator->searchItems = array($entryNameItem, $creatorIdItem);

    $categoryEntryItem = new KalturaESearchCategoryEntryItem();
    $categoryEntryItem->searchTerm = CATEGORY_ID;
    $categoryEntryItem->itemType = KalturaESearchItemType::EXACT_MATCH;
    $categoryEntryItem->fieldName = KalturaESearchCategoryEntryFieldName::ID;

    $searchOperator = new KalturaESearchEntryOperator();
    $searchOperator->operator = KalturaESearchOperatorType::AND_OP;
    $searchOperator->searchItems = array($entryTypeItem, $nameOrCreatorOperator, $categoryEntryItem);

    $searchParams = new KalturaESearchEntryParams();
    $searchParams->searchOperator = $searchOperator;

    $pager = new KalturaPager();
    $pager->pageSize = 20;
    $pager->pageIndex = 1;

    $elasticsearchPlugin = KalturaElasticSearchClientPlugin::get($client);
    $searchResults = $elasticsearchPlugin->eSearch->searchEntry($searchParams, $pager);
    $results = array();

    foreach ($searchResults->objects as $key=>$object) {
	$entry = $object->object;
	$results[$key] = array(); 
	$results[$key]["id"] = $entry->id; 
	$results[$key]["name"] = $entry->name; 
	$results[$key]["email"] = $entry->creatorId; 
	$results[$key]["thumbnail"] = $entry->thumbnailUrl; 

	$user = $client->user->get($entry->creatorId);
	$results[$key]["linkedin"] = $user->description; 
    }

    print json_encode($results);
}

search($_GET["term"], $_GET["ks"]);
?>

<?php
require_once 'config.php';
require_once 'lib.php';

$assetsCSVFile = __BASE_PATH . DS . "asset" . DS . "assets.csv";

echo $assetsCSVFile;

$assetsBuildDir = __BASE_PATH . DS . "asset" . DS . "build";

if(!file_exists($assetsBuildDir))
{
	mkdir($assetsBuildDir);
}

$availableBranchMapAr = array();
$allBranchDataMap = array();

$isHeader = true;
if (($handle = fopen($assetsCSVFile, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	array_walk($data,"trim");
	array_walk($data,"cleanArrayNode");
	//print_r($data);
	if($isHeader)
	{
		$isHeader = false;
		$csvKeys = $data;
	}
	else{
		$assetRowMap = array_combine($csvKeys, $data);
		//print_r($assetRowMap);
		$asset_index = $assetRowMap['INDEX'];	
		$asset_branch_id = $assetRowMap['BRANCH_ID'];
		$asset_branch_name = $assetRowMap['BRANCH_NAME'];

		if($asset_branch_id=="") $asset_branch_id = "UNKNOWN";
		$assetsByBranchFile = $assetsBuildDir . DS . "asset_by_branch_".$asset_branch_id.".json";
		//echo "$assetsByBranchFile\n";
		$assetByBranchData = array();
		if(file_exists($assetsByBranchFile))
		{
			$fileData = file_get_contents($assetsByBranchFile);
			$fileDataAr = json_decode($fileData,true);
			if(count($fileDataAr)>0) $assetByBranchData = $fileDataAr;
		}
		else{
			echo "\nCreating new branch file: $assetsByBranchFile";
		}

		$assetByBranchData[$asset_index] = $assetRowMap;
		$allBranchDataMap[$asset_branch_id][$asset_index] = $assetRowMap;

		file_put_contents($assetsByBranchFile, json_encode($assetByBranchData));

		$availableBranchMapAr[$asset_branch_id] = $asset_branch_name; 
		
	}
    }
    fclose($handle);
}

$availableBranchesMapFile = $assetsBuildDir . DS . "branches_available.json";
file_put_contents($availableBranchesMapFile, json_encode($availableBranchMapAr));

$allBranchesMapFile = $assetsBuildDir . DS . "all_branches_data.json";
file_put_contents($allBranchesMapFile, json_encode($allBranchDataMap));

echo "\nAsset data built\n";
echo "\n";

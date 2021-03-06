<?php

require_once('../v3-php-sdk-2.4.1/config.php');

require_once(PATH_SDK_ROOT . 'Core/ServiceContext.php');
require_once(PATH_SDK_ROOT . 'DataService/DataService.php');
require_once(PATH_SDK_ROOT . 'PlatformService/PlatformService.php');
require_once(PATH_SDK_ROOT . 'Utility/Configuration/ConfigurationManager.php');
require_once('helper/ItemHelper.php'); 

//Specify QBO or QBD
$serviceType = IntuitServicesType::QBO;

// Get App Config
$realmId = ConfigurationManager::AppSettings('RealmID');
if (!$realmId)
	exit("Please add realm to App.Config before running this sample.\n");

// Prep Service Context
$requestValidator = new OAuthRequestValidator(ConfigurationManager::AppSettings('AccessToken'),
                                              ConfigurationManager::AppSettings('AccessTokenSecret'),
                                              ConfigurationManager::AppSettings('ConsumerKey'),
                                              ConfigurationManager::AppSettings('ConsumerSecret'));
$serviceContext = new ServiceContext($realmId, $serviceType, $requestValidator);
if (!$serviceContext)
	exit("Problem while initializing ServiceContext.\n");

// Prep Data Services
$dataService = new DataService($serviceContext);
if (!$dataService)
	exit("Problem while initializing DataService.\n");

// Add a item
$addItem = $dataService->Add(ItemHelper::getItemFields($dataService));
echo "Item created :::  name ::: {$addItem->Name} \n";

//sparse update item
$addItem->Name = "New Name " . rand();
$addItem->sparse = 'true';
$savedItem = $dataService->Update($addItem);
echo "Item sparse updated :::  name ::: {$savedItem->Name} \n";


// update item with all fields
$updatedItem = ItemHelper::getItemFields($dataService);
$updatedItem->Id = $savedItem->Id;
$updatedItem->SyncToken = $savedItem->SyncToken;
$savedItem = $dataService->Update($updatedItem);
echo "Item updated with all fields :::  name ::: {$savedItem->Name} \n";

?>

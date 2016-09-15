<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('blank');
});
Route::get('login', function () {
    return view('welcome');
});

/**
 * Routes for User Management
 * /users
 */
//getting users
Route::get('users',array('uses'=>'UserController@index'));

//saving new user
Route::post('users',array('uses'=>'UserController@store'));

//Deleting user
Route::post('delete/users/{id}',array('uses'=>'UserController@destroy'));

//Updating  user
Route::post('users/{id}',array('uses'=>'UserController@update'));

//Updating  user
Route::post('password/{id}',array('uses'=>'UserController@updatePassword'));

//validating user during login
Route::post('login',array('as'=>'login', 'uses'=>'UserController@validateUser'));

//logging a user out
Route::get('logout',array('as'=>'logout', 'uses'=>'UserController@logout'));

//logging a user out
Route::get('userlogs/{id}',array('as'=>'logout', 'uses'=>'UserController@userlogs'));

//details of the logged in user
Route::get('loggenInuser',array('as'=>'logout', 'uses'=>'UserController@show'));

//getting user roles
Route::get('user_roles',array('uses'=>'UserRolesController@index'));

//saving new user roles
Route::post('user_roles',array('uses'=>'UserRolesController@store'));

//Deleting user roles
Route::post('delete/user_roles/{id}',array('uses'=>'UserRolesController@destroy'));

//Updating  user roles
Route::post('user_roles/{id}',array('uses'=>'UserRolesController@update'));


/**
 * Routes for Recipients
 * /recipient_levels
 */
//getting recipient levels
Route::get('recipient_levels',array('uses'=>'RecipientLevelController@index'));

//getting recipients for a specific level
Route::get('recipient_levels/recipients/{order}',array('uses'=>'RecipientLevelController@getRecipientsNumber'));

//getting recipients for a specific level
Route::get('recipient/{id}',array('uses'=>'RecipientController@show'));

//save a new recipient level
Route::post('recipient_levels',array('uses'=>'RecipientLevelController@store'));

//update a recipient level
Route::post('recipient_levels/{id}',array('uses'=>'RecipientLevelController@update'));

//delete a recipient level
Route::post('delete/recipient_levels/{id}',array('uses'=>'RecipientLevelController@destroy'));


//get the root node of recipient
Route::get('getFirstRecipients',array('uses'=>'RecipientController@getRoot'));

//get the recipients of a certain level
Route::get('recipients/{levelId}',array('uses'=>'RecipientController@getRecipientOnLevel'));

//getting recipients
Route::get('recipients',array('uses'=>'RecipientController@index'));

//getting recipients
Route::get('user/recipients',array('uses'=>'RecipientController@userRecipients'));

//getting recipients
Route::get('user/recipientLevel',array('uses'=>'RecipientController@userRecipientLevel'));

//saving a recipient
Route::post('recipients',array('uses'=>'RecipientController@store'));

//updating a recipient
Route::post('recipients/{id}',array('uses'=>'RecipientController@update'));

//saving a recipient
Route::post('recipients1',array('uses'=>'RecipientController@store1'));

//updating a recipient
Route::post('recipients1/{id}',array('uses'=>'RecipientController@update1'));

//delete a recipient
Route::post('delete/recipients/{id}',array('uses'=>'RecipientController@destroy'));

/**
 * Activities
 */

//getting Activities
Route::get('activities',array('uses'=>'ActivitiesController@index'));

//saving new Activities
Route::post('activities',array('uses'=>'ActivitiesController@store'));

//Deleting Activities
Route::post('delete/activities/{id}',array('uses'=>'ActivitiesController@destroy'));

//Updating  Activities
Route::post('activities/{id}',array('uses'=>'ActivitiesController@update'));

/**
 * Vaccines
 */

//getting Vaccines and Diluents
Route::get('vaccines',array('uses'=>'VaccineController@index'));

//getting Diluents alone
Route::get('diluents',array('uses'=>'VaccineController@getDiluents'));

//saving new Vaccines
Route::post('vaccines',array('uses'=>'VaccineController@store'));

//Deleting Vaccines
Route::post('delete/vaccines/{id}',array('uses'=>'VaccineController@destroy'));

//Updating  Vaccines
Route::post('vaccines/{id}',array('uses'=>'VaccineController@update'));

/**
 * packaging_information
 */

//getting packaging_information
Route::get('packaging_information',array('uses'=>'PackagingController@index'));

//saving new packaging_information
Route::post('packaging_information',array('uses'=>'PackagingController@store'));

//Deleting packaging_information
Route::post('delete/packaging_information/{id}',array('uses'=>'PackagingController@destroy'));

//Updating  packaging_information
Route::post('packaging_information/{id}',array('uses'=>'PackagingController@update'));

/**
 * items_min_max
 */

//getting items_min_max
Route::get('items_min_max',array('uses'=>'ItemMinMaxController@index'));

//saving new items_min_max
Route::post('items_min_max',array('uses'=>'ItemMinMaxController@store'));

//Deleting items_min_max
Route::post('delete/items_min_max/{id}',array('uses'=>'ItemMinMaxController@destroy'));

//Updating  items_min_max
Route::post('items_min_max/{id}',array('uses'=>'ItemMinMaxController@update'));

/**
 * sources
 */

//getting sources
Route::get('sources',array('uses'=>'SourceController@index'));

//saving new sources
Route::post('sources',array('uses'=>'SourceController@store'));

//Deleting sources
Route::post('delete/sources/{id}',array('uses'=>'SourceController@destroy'));

//Updating  sources
Route::post('sources/{id}',array('uses'=>'SourceController@update'));

/**
 * stores
 */

//getting stores
Route::get('stores',array('uses'=>'StoreController@index'));

//saving new stores
Route::post('stores',array('uses'=>'StoreController@store'));

//Deleting stores
Route::post('delete/stores/{id}',array('uses'=>'StoreController@destroy'));

//Updating  stores
Route::post('stores/{id}',array('uses'=>'StoreController@update'));

/**
 * transport_mode
 */

//getting transport_mode
Route::get('transport_mode',array('uses'=>'TransportModeController@index'));

//saving new transport_mode
Route::post('transport_mode',array('uses'=>'TransportModeController@store'));

//Deleting transport_mode
Route::post('delete/transport_mode/{id}',array('uses'=>'TransportModeController@destroy'));

//Updating  transport_mode
Route::post('transport_mode/{id}',array('uses'=>'TransportModeController@update'));

/**
 * adjustment_reasons
 */

//getting adjustment_reasons
Route::get('adjustment_reasons',array('uses'=>'AdjustmentReasonModeController@index'));

//saving new adjustment_reasons
Route::post('adjustment_reasons',array('uses'=>'AdjustmentReasonModeController@store'));

//Deleting adjustment_reasons
Route::post('delete/adjustment_reasons/{id}',array('uses'=>'AdjustmentReasonModeController@destroy'));

//Updating  adjustment_reasons
Route::post('adjustment_reasons/{id}',array('uses'=>'AdjustmentReasonModeController@update'));

/**
 * manufactures
 */

//getting manufactures
Route::get('manufactures',array('uses'=>'ManufactureModeController@index'));

//saving new manufactures
Route::post('manufactures',array('uses'=>'ManufactureModeController@store'));

//Deleting manufactures
Route::post('delete/manufactures/{id}',array('uses'=>'ManufactureModeController@destroy'));

//Updating  manufactures
Route::post('manufactures/{id}',array('uses'=>'ManufactureModeController@update'));

/**
 * annual_quota
 */

//getting annual_quota
Route::get('annual_quota',array('uses'=>'AnnualQuotaController@index'));

//getting annual_quota for this year
Route::get('annual_quota1',array('uses'=>'AnnualQuotaController@thisyearAnualQuota'));

//saving new annual_quota
Route::post('annual_quota',array('uses'=>'AnnualQuotaController@store'));

//Deleting annual_quota
Route::post('delete/annual_quota/{id}',array('uses'=>'AnnualQuotaController@destroy'));

//Updating  annual_quota
Route::post('annual_quota/{id}',array('uses'=>'AnnualQuotaController@update'));

/**
 * pre_shipments
 */

//getting pre_shipments
Route::get('pre_shipments',array('uses'=>'PreShipmentController@index'));

//getting pending pre_shipments
Route::get('pending_shipments',array('uses'=>'PreShipmentController@pending_shipments'));

//getting pre_shipments of certain package_id
Route::get('pre_shipments/{package_id}',array('uses'=>'PreShipmentController@getWithPackId'));

//saving new pre_shipments
Route::post('pre_shipments',array('uses'=>'PreShipmentController@store'));

//Deleting pre_shipments
Route::post('delete/pre_shipments/{id}',array('uses'=>'PreShipmentController@destroy'));

//Updating  pre_shipments
Route::post('pre_shipments/{id}',array('uses'=>'PreShipmentController@update'));

/**
 * system_settings
 */

//getting pre_shipments
Route::get('system_settings',array('uses'=>'UserController@getSettings'));

//saving new pre_shipments
Route::post('system_settings',array('uses'=>'UserController@saveSettings'));

/**
 * system_settings
 */

//getting stock_items
Route::get('stock_items',array('uses'=>'VaccineController@stock_items'));

//getting expired_stock_items
Route::get('expired_stock_items',array('uses'=>'VaccineController@expired_stock_items'));

//getting expired_stock_items
Route::get('expired_stock_items/{recipient}/child/{level}',array('uses'=>'VaccineController@expired_stock_items1'));

//getting near_expired_stock_items
Route::get('near_expired_stock_items',array('uses'=>'VaccineController@near_expired_stock_items'));

//getting last order number
Route::get('getNextPackageNumber',array('uses'=>'VaccineController@getNextPackageNumber'));

//getting arrivals
Route::get('arrivals',array('uses'=>'VaccineController@arrivals'));

//getting arrivalsforcancel
Route::get('arrivalsforcancel',array('uses'=>'VaccineController@arrivalsforcancel'));

//getting packages
Route::get('sent_packages',array('uses'=>'VaccineController@packages'));

//getting packages
Route::get('sent_packages/{recipient}/child/{level}',array('uses'=>'VaccineController@packages1'));

//getting expected_packages
Route::get('expected_items',array('uses'=>'VaccineController@expectedPackagesItems'));

//getting storeStock
Route::get('storeStocks/{id}',array('uses'=>'StoreController@storeStocks'));

//getting vaccine in stock
Route::get('vaccineStocks/{id}',array('uses'=>'StoreController@vaccineStocks'));

//getting expected_packages
Route::get('expected_packages',array('uses'=>'VaccineController@expectedPackages'));

//getting pending_expected_packages
Route::get('pending_expected_packages',array('uses'=>'VaccineController@pendingExpectedPackages'));

//getting dispatched_packages
Route::get('dispatched_packages',array('uses'=>'VaccineController@dispatchedPackages'));

//getting dispatched_packages
Route::get('packagesToCancel',array('uses'=>'VaccineController@packagesToCancel'));

//getting expected_packages
Route::get('store/stock/{id}',array('uses'=>'StoreController@storeStockItems'));

//getting all stores Items
Route::get('storeItems',array('uses'=>'StoreController@storeItems'));

//getting all stores Items in Details
Route::get('storeItemsDetails',array('uses'=>'StoreController@storeItemsDetails'));

//getting all dispatchedItems per vaccine
Route::get('disaptchedItems',array('uses'=>'StoreController@disaptchedItems'));

//getting all dispatchedItems per vaccine
Route::get('disaptchedItemsMonth',array('uses'=>'StoreController@disaptchedItemsMonth'));

//getting all arival Items per vaccine
Route::get('arrivalItemsMonth',array('uses'=>'StoreController@arrivalItemsMonth'));

//getting all arival Items
Route::get('arrivalItems',array('uses'=>'StoreController@arrivalItems'));

//getting all arival Items
Route::get('arrivalItems/{recipient}/child/{level}',array('uses'=>'StoreController@arrivalItems1'));

//getting all arival Items
Route::get('stock_items/{recipient}/child/{level}',array('uses'=>'StoreController@stock_items'));

//getting all adjustedItems
Route::get('adjustedItems',array('uses'=>'StoreController@adjustedItems'));

//getting all adjustedItems
Route::get('adjustedItems/{recipient}/child/{level}',array('uses'=>'StoreController@adjustedItems1'));

//getting all movedItems
Route::get('movedItems',array('uses'=>'StoreController@movedItems'));

//getting all movedItems
Route::get('movedItems/{recipient}/child/{level}',array('uses'=>'StoreController@movedItems1'));

//getting all canceledarrivalItems
Route::get('canceledarrivalItems',array('uses'=>'StoreController@canceledarrivalItems'));

//getting all canceledarrivalItems
Route::get('canceledarrivalItems/{recipient}/child/{level}',array('uses'=>'StoreController@canceledarrivalItems1'));

//getting all arival Items
Route::get('canceledDisItems',array('uses'=>'StoreController@canceledDisItems'));

//getting all arival Items
Route::get('canceledDisItems/{recipient}/child/{level}',array('uses'=>'StoreController@canceledDisItems1'));

//getting all dispatched Items
Route::get('disItems/{recipient}/child/{level}',array('uses'=>'StoreController@disItems'));

//getting all receivItems per vaccine
Route::get('receivItems',array('uses'=>'StoreController@receivItems'));

//saving new received items
Route::post('receive',array('uses'=>'VaccineController@receive'));

//saving new openning stock for items
Route::post('open',array('uses'=>'VaccineController@open'));

//saving new pre-advice items
Route::post('pre_receive',array('uses'=>'VaccineController@pre_receive'));

//saving new dispatch
Route::post('dispatch',array('uses'=>'VaccineController@dispatchVaccine'));

//saving adjust other details
Route::post('arrival_adjust',array('uses'=>'VaccineController@arrival_adjust'));

//saving  dispatch adjust other details
Route::post('dispatch_adjust',array('uses'=>'VaccineController@dispatch_adjust'));

//saving  stock adjust
Route::post('stock_adjust',array('uses'=>'VaccineController@stock_adjust'));

//saving  stock adjust
Route::post('move_item',array('uses'=>'VaccineController@move_item'));

//saving  stock adjust
Route::post('cancelDispatch/{id}',array('uses'=>'VaccineController@cancelDispatch'));

//updating the items
Route::get('updateItems',array('uses'=>'VaccineController@updateItems'));

//updating the items
Route::get('mark_as_received/{id}',array('uses'=>'VaccineController@markAsReceived'));

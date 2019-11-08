<?php

Route::get('/', function () {
  return view('auth/login');
});

Route::post('check-admin', 'AdminController@check');

// API's
Route::post('/api/login', 'UserController@login');
Route::post('/api/scanBarcode', 'UserController@scanBarcode');
Route::post('/api/checkSID', 'UserController@checkSID');
Route::get('/api/getOptions', 'UserController@getOptions');
Route::post('/api/updateOptions', 'UserController@updateOptions');
Route::post('/api/lastScanHistory', 'UserController@lastScanHistory');
Route::post('/api/todayTotalScanEntry', 'UserController@todayTotalScanEntry');
Route::get('/api/listOptions', 'UserController@listOptions');
Route::get('/api/appVersion', 'UserController@appVersion');
Route::post('/api/shippingList', 'UserController@shippingList');
Route::post('/api/shippingListCount', 'UserController@shippingListCount');
Route::post('/api/shippingByUser', 'UserController@shippingByUser');
Route::post('/api/programmerList', 'UserController@programmerList');
Route::post('/api/programmerByUser', 'UserController@programmerByUser');
Route::post('/api/updateInventory', 'UserController@updateInventory');
Route::post('/api/updateCore', 'UserController@updateCore');
Route::post('/api/checkpcmhw', 'UserController@checkpcmhw');
Route::post('/api/ChangeOnBoardTesting', 'UserController@ChangeOnBoardTesting');
Route::post('/api/savepcmhw', 'UserController@savepcmhw');
Route::get('/api/inventory/dataTableInventory', 'InventoryController@dataTableInventory');
Route::get('/api/core/dataTableCore', 'CoreController@dataTableCore');
Route::post('api/SecondaryManagers', 'AccountsController@SecondaryManagers');
Route::post('api/scannerHistory', 'SearchController@scannerHistory');
Route::get('api/programming-list', 'AccountsController@programmingList');
Route::get('api/program-user-list', 'AccountsController@programUserList');
Route::get('api/account-manager-list', 'AccountsController@accountManagerList');
Route::get('api/account-manager-list-download', 'AccountsController@accountManagerListDownload');
Route::get('/api/account-manager-points', 'AccountsController@accountManagerPoints');
Route::get('/api/account-manager-points-download', 'AccountsController@accountManagerPointsDownload');
Route::post('/api/CreateEasyPost', 'UserController@CreateEasyPost');
Route::post('/api/BuyEasyPost', 'UserController@BuyEasyPost');
Route::get('/api/testCreate', 'UserController@testCreate');
Route::post('/api/testRetrieve', 'UserController@testRetrieve');
Route::post('/api/CreateJunkyardToUs', 'UserController@CreateJunkyardToUs');
Route::post('/api/BuyEasyPostJunkyard', 'UserController@BuyEasyPostJunkyard');
Route::post('/api/customerVINMatches', 'AccountsController@customerVINMatches');
Route::post('/api/deleteOrderStatus', 'AccountsController@deleteOrderStatus');
// pingemployees
Route::get('/api/GetPingemployees', 'PingemployeesController@GetPingemployees');
Route::post('/api/PostPingemployees', 'PingemployeesController@PostPingemployees');
Route::get('/api/GetMyPing', 'PingemployeesController@GetMyPing');
Route::get('/api/ReadMyPing', 'PingemployeesController@ReadMyPing');

Route::post('/api/EbayServices', 'UserController@EbayServices');
Route::get('/api/StockMaster', 'UserController@StockMaster');
Route::get('/api/GetApiOrderInsert', 'UserController@GetApiOrderInsert');
Route::post('/api/UpdateApiOrderInsert', 'UserController@UpdateApiOrderInsert');
Route::post('/api/AdminEmails', 'UserController@AdminEmails');

//RMA PDF
Route::post('/api/RmaPdfDownload', 'UserController@RmaPdfDownload');

Route::get('privacy-policy', function () {
  return view('admin/privacy-policy');
});

Route::get('easypostwebhook', 'UserController@easypostwebhookPage');
Route::post('easypostwebhook', 'UserController@easypostwebhookPage');
Route::post('AutomaticRefund', 'UserController@AutomaticRefund');
Route::post('/api/GetRmaFormsData', 'UserController@GetRmaFormsData');
Route::post('/api/GetRmaFormsList', 'UserController@GetRmaFormsList');
Route::post('/api/CreateRmaForms', 'UserController@CreateRmaForms');
Route::post('/api/GetDocusignData', 'UserController@GetDocusignData');

Route::get('register', 'Auth\RegisterController@showRegistrationForm');

Route::get('/admin/user/settings', 'SettingsController@create');
Route::post('/admin/user/mysettings', 'SettingsController@update');
Route::get('/admin/user/changepassword', 'SettingsController@changepassword');
Route::post('/admin/user/updatePassword', 'SettingsController@updatePassword');
// Team routes
Route::get('/admin/user/teamsettings', 'SettingsController@teamsettings');
Route::get('/admin/user/getFreeUsers', 'SettingsController@getFreeUsers');
Route::get('/admin/user/getTeam', 'SettingsController@getTeam');
Route::post('/admin/user/ChangeTeamUserStatus', 'SettingsController@ChangeTeamUserStatus');

Auth::routes();

Route::get('/', 'AccountsController@index');
// refunded
Route::get('api/refundedTrackingStatuses', 'UserController@refundedTrackingStatuses');
Route::get('api/refundedVendors', 'UserController@refundedVendors');
Route::get('/quotes', 'AccountsController@quotes');

// print
Route::get('/print', 'AccountsController@print');
Route::post('/mass-print', 'AccountsController@massPrint');

Route::get('/accounts/usernameindex', 'AccountsController@usernameIndex');
Route::get('/admin/accounts/table', 'AccountsController@accountsindextable')->name('admin.accounts.table');
Route::get('/admin/accounts/quotes', 'AccountsController@accountsquotestable')->name('admin.quotes.table');
Route::resource('accounts', 'AccountsController');
Route::get('/accounts/docusign/view', 'AccountsController@docusignView');
Route::get('/accounts/docusign/create/{token}', 'UserController@docusignCreate');
Route::get('/accounts/rmaform/create/{token}', 'UserController@RMAFormCreate');
Route::get('/accounts/rmaform/create/{accid}/{formid}', 'AccountsController@RMAFormPage');

Route::get('api/getrelationdata', 'AccountsController@get_relation_data');
Route::get('api/getshopname', 'AccountsController@get_shop_name');
Route::get('api/getcustomername', 'AccountsController@get_customer_name');
Route::get('api/getShopNameVendor', 'AccountsController@get_shop_name_vendor');
Route::post('api/getMetadata', 'AccountsController@get_metadata');
Route::post('api/getMetadataVendor', 'AccountsController@get_metadata_vendor');
Route::get('api/admin/inventory/update', 'InventoryController@setpublish');
Route::get('api/admin/core/update', 'CoreController@setpublish');
Route::get('api/inventory-search', 'SearchController@inventory_search');
Route::get('api/core-search', 'SearchController@core_search');
Route::get('api/fetch-state', 'AccountsController@fetch_states');
Route::get('api/get-all-order-status', 'AccountsController@get_all_order_status');
Route::post('api/add-part-location', 'AccountsController@add_part_location');
Route::post('api/AddInstock', 'AccountsController@AddInstock');
Route::post('api/UpdateDocumentDetails', 'AccountsController@UpdateDocumentDetails');
Route::post('api/DeleteDocumentDetails', 'AccountsController@DeleteDocumentDetails');
Route::post('api/GetDocumentDetails', 'AccountsController@GetDocumentDetails');

// Docusign Forms
Route::post('api/CreateDocusignForm', 'UserController@CreateDocusignForm');
Route::get('getDocument/{id}', 'AccountsController@getDocument');
Route::post('api/SendDocusignFormEmail', 'UserController@SendDocusignFormEmail');
Route::post('api/GetDocusignDataByToken', 'UserController@GetDocusignDataByToken');

Route::post('/api/SecondaryManagerPoint', 'UserController@SecondaryManagerPoint');
Route::post('/api/SecondaryManagerPointHistory', 'UserController@SecondaryManagerPointHistory');
Route::post('/api/PointsBreakdown', 'UserController@PointsBreakdown');
Route::post('/api/PointsBreakdownDelete', 'UserController@PointsBreakdownDelete');

// RMA Form
Route::post('api/RMAForm', 'AccountsController@RMAForm');
Route::post('api/RMAFormDownload', 'AccountsController@RMAFormDownload');
Route::post('api/RMAFormSendFromEmail', 'AccountsController@RMAFormSendFromEmail');
Route::post('api/SendRmaFormEmail', 'UserController@SendRmaFormEmail');
Route::post('api/GetRMADataByToken', 'UserController@GetRMADataByToken');

Route::get('searches/customermetadatastable', 'SearchController@customermetadatastable')->name('searches.customermetadatastable');
Route::get('searches/accountstable', 'SearchController@accountstable')->name('searches.accountstable');
Route::get('searches/vendorstable', 'SearchController@vendorstable')->name('searches.vendorstable');
Route::get('searches/accountnotestable', 'SearchController@accountnotestable')->name('searches.accountnotestable');
Route::get('searches/creditcardstable', 'SearchController@creditcardstable')->name('searches.creditcardstable');
Route::get('/searches', 'SearchController@index');

Route::get('searches/programtable', 'SearchController@programtable')->name('searches.programtable');

Route::any('/admin/inventory/partmatches', 'InventoryController@partmatches');
Route::any('/admin/inventory/bulkcheckinventory', 'InventoryController@bulkcheckinventory')->name('admin.bulkCheckInventory');
Route::get('/admin/inventory/bulkcheckinventorycache', 'InventoryController@bulkcheckinventorycache')->name('admin.bulkCheckInventoryCache');
Route::any('/admin/inventory/bulkaddaccounts', 'InventoryController@bulkaddaccounts');
Route::any('/admin/inventory/ebayandwebsitebreakdown', 'InventoryController@ebayandwebsitebreakdown');
Route::any('/admin/inventory/addinventory', 'InventoryController@addinventory');
Route::any('/admin/inventory/addgeneric', 'InventoryController@addgeneric');
Route::post('/admin/inventory/bulkinsertinfo', 'InventoryController@bulkInsertInfo');

Route::get('/admin/inventory/create', 'InventoryController@create')->name('inventory.create');
Route::get('/admin/inventory/index', 'InventoryController@index')->name('inventory.index');
Route::get('/admin/inventory/printpreview', 'InventoryController@printPreview')->name('inventory.print');

Route::any('/admin/core/partmatches', 'CoreController@partmatches');
Route::any('/admin/core/bulkcheckcore', 'CoreController@bulkcheckcore');
Route::any('/admin/core/bulkaddaccounts', 'CoreController@bulkaddaccounts');
Route::any('/admin/core/addcore', 'CoreController@addcore');
Route::any('/admin/core/addgeneric', 'CoreController@addgeneric');
Route::get('admin/core', 'CoreController@create');
Route::get('/admin/core/index', 'CoreController@index')->name('core.index');

// Route::get('/admin/scanner', function () {return view('admin/scanner');});
Route::get('/admin/scanner', 'SearchController@scannerSearch');
Route::any('/admin/scannermatches', 'SearchController@inventoryCoreSearch');
Route::get('/admin/shipping', 'SearchController@shippingSearch');
Route::post('/api/ShippingSearchResult', 'SearchController@ShippingSearchResult');

Route::get('/admin/points/accountmanager', function () {
  return view('admin.points.accountmanager');
})->middleware('auth');
Route::get('/admin/points/account-manager-sel', 'AccountsController@accountManager');
Route::get('/admin/points/accountmanager/download', 'AccountManagerController@download')->name('accountmanager.download');
Route::get('/admin/points/programmer', function () {
  return view('admin/points/program');
})->middleware('auth');
Route::get('/admin/points/program-user', 'AccountsController@programUser');
Route::get('/admin/points/shipping', function () {
  return view('admin/points/shipping');
})->middleware('auth');
Route::get('/admin/points/shipping/{id}', 'AccountManagerController@shippingUser');

Route::get('/admin/chat/index', 'ChatController@index');

Route::any('adminer', '\Miroc\LaravelAdminer\AdminerController@index');
Route::any('adminer', '\Miroc\LaravelAdminer\AdminerAutologinController@index');

Route::get('/admin/patchnotes', 'SettingsController@patchNotes');
Route::post('api/CreatePatchNotes', 'SettingsController@CreatePatchNotes');
Route::get('api/GetPatchNotes', 'SettingsController@GetPatchNotes');

// API For Misc Points
Route::post('/api/PaypalPoints', 'UserController@PaypalPoints');
Route::post('/api/CaseClosedPoints', 'UserController@CaseClosedPoints');
Route::post('/api/NegativeFeedbackRemovedPoints', 'UserController@NegativeFeedbackRemovedPoints');
Route::post('/api/BBBPoints', 'UserController@BBBPoints');
Route::post('/api/LateShipmentRemoval', 'UserController@LateShipmentRemoval');

Route::get('api/fetch-order-info', 'InventoryController@fetchOrderInfo');
Route::post('api/check-magento-orders', 'InventoryController@checkMagentoOrders');
Route::post('api/check-ebay-orders', 'InventoryController@checkEbayOrders');
Route::post('api/add-magento-orders', 'InventoryController@addMagentoOrders');
Route::post('api/add-ebay-orders', 'InventoryController@addEbayOrders');
Route::get('api/fetch-ebay-order-info', 'InventoryController@FetchEbayOrderInfo');
Route::post('api/add-ebay-order-live', 'InventoryController@addEbayOrdersLive');

// API For Vendor
Route::post('api/update-or-create-vendor/{account}', 'AccountsController@updateOrCreateVendor');
// API For Customer Meta Data
Route::post('api/update-customer/{account}', 'AccountsController@updateCustomer');
// API For Tracking Status
Route::post('api/update-or-create-tracking/{account}', 'AccountsController@updateOrCreateTracking');
// API For Programming Entries
Route::post('api/update-or-create-entry/{account}', 'AccountsController@updateOrCreateEntry');

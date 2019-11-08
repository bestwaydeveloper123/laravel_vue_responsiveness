<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'api/StockMaster',
        'api/EbayServices',
        'api/BuyEasyPost',
        'api/CreateEasyPost',
        'api/BuyEasyPostJunkyard',
        'AutomaticRefund',
        'easypostwebhook', 
        'adminer',
        'check-admin',
        'api/login',
        'api/updateOptions',
        'api/lastScanHistory',
        'api/todayTotalScanEntry',
        'api/scanBarcode',
        'api/listOptions',
        'api/checkSID',
        'api/appOptions',
        'api/shippingList',
        'api/shippingByUser',
        'api/testCreate',
        'api/testRetrieve',
        'api/editInventory',
        'api/updateInventory',
        'api/checkpcmhw',
        'api/ChangeOnBoardTesting',
        'api/savepcmhw',
        'api/RMAForm',
        'api/RMAFormSendFromEmail',
    ];
}

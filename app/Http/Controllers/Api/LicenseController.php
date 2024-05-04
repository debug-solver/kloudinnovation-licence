<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Service\LicenseService;
use App\Models\Item;
use App\Models\License;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LicenseController extends Controller
{
    protected string $apiKey = 'B77MsI9905rTCtdoWy8v06WkeMgrsiXDpZH3WDpO';
    public function __construct(protected LicenseService $licenseService)
    {

    }

    public function activate(Request $request)
    {
        try {
            if ($this->apiKey != $request->header('Api-key')) {
                return response()->json([
                    'error' => 'Invalid Api Key',
                ]);
            }

            $items = Item::pluck('item_number')->toArray();
            if (!in_array($request->header('Item-Id'), $items)) {
                return response()->json([
                    'error' => 'Invalid Item Id',
                ], Response::HTTP_BAD_REQUEST);
            }

            $item = Item::where('item_number',$request->header('Item-Id'))->first();
            $license = License::where('purchase_code',$request->input('purchase_code'))->first();

            if ($license){
                return response()->json([
                    'error' => 'You are already verified',
                ],  Response::HTTP_BAD_REQUEST);
            }

            $response = $this->licenseService->getPurchaseCode($request->input('purchase_code'));
            $license = $this->licenseService->store($response,$item,$request);

            return response()->json([
                'success' => 'Your item has been verified thank you join with us',
                'license_key' => $license->license_key
            ],  Response::HTTP_CREATED);

        }catch (Exception $exception){
            return response()->json([
                'error' => $exception->getMessage(),
            ],  Response::HTTP_BAD_REQUEST);
        }
    }
}

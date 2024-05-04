<?php

namespace App\Http\Service;

use App\Models\Item;
use App\Models\License;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LicenseService
{
    protected string $personalToken = "Xx8mZ243lfYZgW0Pg7Q5dGiVl0voA1kJ";

    /**
     * @throws Exception
     */
    public function getPurchaseCode(string $code)
    {
        $code = trim($code);
        if (!preg_match("/^([a-f0-9]{8})-(([a-f0-9]{4})-){3}([a-f0-9]{12})$/i", $code)) {
            throw new Exception("Invalid purchase code");
        }

        $response = Http::withHeaders([
            "Authorization" => "Bearer {$this->personalToken}",
            "User-Agent" => "Purchase code verification script"
        ])->timeout(20)->get("https://api.envato.com/v3/market/author/sale", [
            "code" => $code
        ]);

        if ($response->failed()) {
            $statusCode = $response->status();
            throw match ($statusCode) {
                404 => new Exception("Invalid purchase code"),
                403 => new Exception("The personal token is missing the required permission for this script"),
                401 => new Exception("The personal token is invalid or has been deleted"),
                default => new Exception("Got status {$statusCode}, try again shortly"),
            };
        }

        $body = $response->json();

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error parsing response, try again");
        }

        return $body;
    }


    public function store(array $params, Item $item, Request $request): License
    {
        return License::create([
            'item_id' => $item->id,
            'license_key' => Str::random(),
            'purchase_code' => $request->input('purchase_code'),
            'amount' => Arr::get($params, 'amount'),
            'supported_until' => Carbon::parse(Arr::get($params, 'supported_until')),
            'support_amount' => Arr::get($params, 'support_amount'),
            'buyer' => Arr::get($params, 'buyer'),
            'purchase_count' => Arr::get($params, 'purchase_count'),
            'domain' => null,
            'url' => request()->header('url'),
            'ip' => request()->header('ip'),
            'root_path' => request()->header('root-path'),
            'is_verified' => true,
        ]);
    }
}

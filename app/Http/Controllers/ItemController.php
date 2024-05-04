<?php

namespace App\Http\Controllers;

use App\Http\Service\LicenseService;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemController extends Controller
{

    public function __construct(protected LicenseService $licenseService)
    {

    }

    public function index(): View
    {
        $items = Item::get();
        return view('dashboard', compact('items'));
    }
}

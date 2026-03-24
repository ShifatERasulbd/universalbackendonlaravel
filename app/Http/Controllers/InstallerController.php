<?php

namespace App\Http\Controllers;
use App\Models\AvailableWebsite;
use Illuminate\Http\Request;

class InstallerController extends Controller
{
    //
    public function getBusinessCategories(){
        return response()->json(
            AvailableWebsite::where('is_active', 1)
                ->select('id','name')
                ->get()
        );
    }
}

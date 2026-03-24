<?php

namespace App\Http\Controllers;

use App\Models\AvailableWebsite;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InstallerController extends Controller
{
    public function showStepOne()
    {
        return view('home');
    }

    public function showStepTwo()
    {
        if (! session()->has('installer.step_one')) {
            return redirect('/installer');
        }

        return view('home');
    }

    public function getBusinessCategories(){
        return response()->json(
            AvailableWebsite::where('is_active', 1)
                ->select('id','name')
                ->get()
        );
    }

    public function storeStepOne(Request $request){
        $validated = $request->validate([
            'name'          => 'required',
            'email'         => 'required|email',
            'phone'         => 'required|numeric',
            'business_name' => 'required',
            'business_url'  => 'required',
            'business_category' => 'required',
        ]);

        session([
            'installer.step_one' => $validated,
        ]);

        session()->forget('installer.step_two');

        return response()->json([
            'status'  => true,
            'message' => 'Step 1 saved successfully',
        ]);
    }

    public function debugStepOne()
    {
        return response()->json([
            'session_data' => session('installer.step_one'),
        ]);
    }

    public function getThemes()
    {
        $stepOne = session('installer.step_one');
           
       
        if (! $stepOne) {
            return response()->json([
                'status' => false,
                'message' => 'Step one data not found in session.',
            ], 422);
        }

        $themes = Template::query()
            ->where('is_active', true)
            ->where('available_website_id', $stepOne['business_category'])
            ->orderBy('order')
            ->select('id', 'name', 'slug', 'preview_image', 'available_website_id')
            ->get();

        return response()->json([
            'status' => true,
            'themes' => $themes,
            'business_category' => $stepOne['business_category'],
        ]);
    }

    public function storeStepTwo(Request $request)
    {
        $stepOne = session('installer.step_one');

        if (! $stepOne) {
            return response()->json([
                'status' => false,
                'message' => 'Step one data not found in session.',
            ], 422);
        }

        $validated = $request->validate([
            'theme_id' => [
                'required',
                Rule::exists('templates', 'id')->where(function ($query) use ($stepOne) {
                    $query->where('available_website_id', $stepOne['business_category'])
                        ->where('is_active', true);
                }),
            ],
        ]);

        session([
            'installer.step_two' => [
                'theme_id' => (int) $validated['theme_id'],
            ],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Theme selected successfully.',
            'data' => session('installer.step_two'),
        ]);
    }
}

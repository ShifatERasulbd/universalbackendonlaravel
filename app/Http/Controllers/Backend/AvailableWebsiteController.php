<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AvailableWebsite;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AvailableWebsiteController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:available-websites.view', only: ['index']),
            new Middleware('permission:available-websites.create', only: ['create', 'store']),
            new Middleware('permission:available-websites.edit', only: ['edit', 'update', 'reorder']),
            new Middleware('permission:available-websites.delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $websites = AvailableWebsite::orderBy('order')->get();
        return view('backend.available-websites.index', compact('websites'));
    }

    public function create()
    {
        return view('backend.available-websites.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:available_websites,name'],
            'url' => ['required', 'url', 'max:255', 'unique:available_websites,url'],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', 'max:100'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['order'] = $validated['order'] ?? 0;
        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['created_by'] = auth()->id();

        AvailableWebsite::create($validated);

        return redirect()->route('available-websites.index')
            ->with('success', "Website \"{$validated['name']}\" created successfully.");
    }

    public function edit(AvailableWebsite $availableWebsite)
    {
        return view('backend.available-websites.edit', ['website' => $availableWebsite]);
    }

    public function update(Request $request, AvailableWebsite $availableWebsite)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:available_websites,name,' . $availableWebsite->id],
            'url' => ['required', 'url', 'max:255', 'unique:available_websites,url,' . $availableWebsite->id],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', 'max:100'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['updated_by'] = auth()->id();

        $availableWebsite->update($validated);

        return redirect()->route('available-websites.index')
            ->with('success', "Website \"{$availableWebsite->name}\" updated successfully.");
    }

    public function destroy(AvailableWebsite $availableWebsite)
    {
        $name = $availableWebsite->name;
        $availableWebsite->delete();

        return redirect()->route('available-websites.index')
            ->with('success', "Website \"{$name}\" deleted successfully.");
    }

    public function reorder(Request $request)
    {
        $websites = $request->validate([
            'websites' => 'required|array',
            'websites.*.id' => 'required|exists:available_websites,id',
            'websites.*.order' => 'required|integer',
        ])['websites'];

        foreach ($websites as $websiteData) {
            AvailableWebsite::find($websiteData['id'])->update(['order' => $websiteData['order']]);
        }

        return response()->json(['success' => true]);
    }
}

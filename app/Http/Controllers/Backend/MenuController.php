<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MenuController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:menus.view', only: ['index']),
            new Middleware('permission:menus.create', only: ['create', 'store']),
            new Middleware('permission:menus.edit', only: ['edit', 'update']),
            new Middleware('permission:menus.delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('backend.menus.index', compact('menus'));
    }

    public function create()
    {
        $parentMenus = Menu::where('parent_id', null)->orderBy('order')->pluck('title', 'id');
        return view('backend.menus.create', compact('parentMenus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:menus,id'],
            'order' => ['nullable', 'integer'],
            'icon' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $validated['is_active'] ?? true;

        Menu::create($validated);

        return redirect()->route('menus.index')
            ->with('success', "Menu \"{$validated['title']}\" created successfully.");
    }

    public function edit(Menu $menu)
    {
        $parentMenus = Menu::where('parent_id', null)->where('id', '!=', $menu->id)->orderBy('order')->pluck('title', 'id');
        return view('backend.menus.edit', compact('menu', 'parentMenus'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:menus,id'],
            'order' => ['nullable', 'integer'],
            'icon' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $validated['is_active'] ?? true;

        if ($validated['parent_id'] === (string) $menu->id) {
            return back()->withErrors(['parent_id' => 'A menu cannot be its own parent.'])->withInput();
        }

        $menu->update($validated);

        return redirect()->route('menus.index')
            ->with('success', "Menu \"{$menu->title}\" updated successfully.");
    }

    public function destroy(Menu $menu)
    {
        $title = $menu->title;
        $menu->delete();

        return redirect()->route('menus.index')
            ->with('success', "Menu \"{$title}\" deleted successfully.");
    }

    public function reorder(Request $request)
    {
        $menus = $request->validate([
            'menus' => 'required|array',
            'menus.*.id' => 'required|exists:menus,id',
            'menus.*.order' => 'required|integer',
        ])['menus'];

        foreach ($menus as $menuData) {
            Menu::find($menuData['id'])->update(['order' => $menuData['order']]);
        }

        return response()->json(['success' => true]);
    }
}
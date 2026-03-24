<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class PageController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:pages.view', only: ['index']),
            new Middleware('permission:pages.create', only: ['create', 'store']),
            new Middleware('permission:pages.edit', only: ['edit', 'update', 'editContent', 'updateContent']),
            new Middleware('permission:pages.delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $pages = Page::orderBy('order')->get();
        return view('backend.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('backend.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255', 'unique:pages'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pages'],
            'content' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published,scheduled'],
            'published_at' => ['nullable', 'date_format:Y-m-d H:i'],
            'scheduled_at' => ['nullable', 'date_format:Y-m-d H:i'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['created_by'] = auth()->id();
        $validated['order'] = $validated['order'] ?? 0;

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        Page::create($validated);

        return redirect()->route('pages.index')
            ->with('success', "Page \"{$validated['title']}\" created successfully.");
    }

    public function edit(Page $page)
    {
        return view('backend.pages.edit', compact('page'));
    }

    public function editContent(Page $page)
    {
        return view('backend.pages.builder', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255', 'unique:pages,title,' . $page->id],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pages,slug,' . $page->id],
            'content' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published,scheduled'],
            'published_at' => ['nullable', 'date_format:Y-m-d H:i'],
            'scheduled_at' => ['nullable', 'date_format:Y-m-d H:i'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['updated_by'] = auth()->id();

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $page->update($validated);

        return redirect()->route('pages.index')
            ->with('success', "Page \"{$page->title}\" updated successfully.");
    }

    public function destroy(Page $page)
    {
        $title = $page->title;
        $page->delete();

        return redirect()->route('pages.index')
            ->with('success', "Page \"{$title}\" deleted successfully.");
    }

    public function updateContent(Request $request, Page $page)
    {
        $validated = $request->validate([
            'content' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['updated_by'] = auth()->id();

        $page->update($validated);

        return redirect()->route('pages.index')
            ->with('success', "Page content for \"{$page->title}\" updated successfully.");
    }

    public function reorder(Request $request)
    {
        $pages = $request->validate([
            'pages' => 'required|array',
            'pages.*.id' => 'required|exists:pages,id',
            'pages.*.order' => 'required|integer',
        ])['pages'];

        foreach ($pages as $pageData) {
            Page::find($pageData['id'])->update(['order' => $pageData['order']]);
        }

        return response()->json(['success' => true]);
    }
}

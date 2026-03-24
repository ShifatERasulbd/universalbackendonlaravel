<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AvailableWebsite;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:templates.view', only: ['index']),
            new Middleware('permission:templates.create', only: ['create', 'store']),
            new Middleware('permission:templates.edit', only: ['edit', 'update', 'reorder']),
            new Middleware('permission:templates.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $selectedCategory = $request->integer('available_website_id');

        $templates = Template::with('availableWebsite')
            ->when($selectedCategory, fn ($query) => $query->where('available_website_id', $selectedCategory))
            ->orderBy('order')
            ->get();

        $categories = AvailableWebsite::orderBy('name')->pluck('name', 'id');

        return view('backend.templates.index', compact('templates', 'categories', 'selectedCategory'));
    }

    public function create(Request $request)
    {
        $categories = AvailableWebsite::where('is_active', true)->orderBy('name')->pluck('name', 'id');
        $selectedCategory = $request->integer('available_website_id');

        return view('backend.templates.create', compact('categories', 'selectedCategory'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'available_website_id' => ['required', 'exists:available_websites,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:templates,slug'],
            'description' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'preview_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('preview_image')) {
            $validated['preview_image'] = $request->file('preview_image')->store('templates', 'public');
        }

        $validated['slug'] = Str::slug($validated['slug'] ?: $validated['name']);
        $validated['order'] = $validated['order'] ?? 0;
        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['created_by'] = auth()->id();

        Template::create($validated);

        return redirect()->route('templates.index', ['available_website_id' => $validated['available_website_id']])
            ->with('success', "Template \"{$validated['name']}\" created successfully.");
    }

    public function edit(Template $template)
    {
        $categories = AvailableWebsite::where('is_active', true)->orderBy('name')->pluck('name', 'id');

        return view('backend.templates.edit', compact('template', 'categories'));
    }

    public function update(Request $request, Template $template)
    {
        $validated = $request->validate([
            'available_website_id' => ['required', 'exists:available_websites,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:templates,slug,' . $template->id],
            'description' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'preview_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('preview_image')) {
            if (!empty($template->preview_image) && Storage::disk('public')->exists($template->preview_image)) {
                Storage::disk('public')->delete($template->preview_image);
            }

            $validated['preview_image'] = $request->file('preview_image')->store('templates', 'public');
        }

        $validated['slug'] = Str::slug($validated['slug'] ?: $validated['name']);
        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['updated_by'] = auth()->id();

        $template->update($validated);

        return redirect()->route('templates.index', ['available_website_id' => $validated['available_website_id']])
            ->with('success', "Template \"{$template->name}\" updated successfully.");
    }

    public function destroy(Template $template)
    {
        $name = $template->name;
        $categoryId = $template->available_website_id;

        if (!empty($template->preview_image) && Storage::disk('public')->exists($template->preview_image)) {
            Storage::disk('public')->delete($template->preview_image);
        }

        $template->delete();

        return redirect()->route('templates.index', ['available_website_id' => $categoryId])
            ->with('success', "Template \"{$name}\" deleted successfully.");
    }

    public function reorder(Request $request)
    {
        $templates = $request->validate([
            'templates' => 'required|array',
            'templates.*.id' => 'required|exists:templates,id',
            'templates.*.order' => 'required|integer',
        ])['templates'];

        foreach ($templates as $templateData) {
            Template::find($templateData['id'])->update(['order' => $templateData['order']]);
        }

        return response()->json(['success' => true]);
    }
}

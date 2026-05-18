<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{

public function index($collection)
{
    return response()->json(Item::where('collection_id', $collection)->with('tags')->get());
}

    public function store(Request $request)
    {
        $request->validate([
            'collection_id' => 'required|exists:collections,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50'
        ]);

        $collection = Collection::findOrFail($request->collection_id);

        if ($collection->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $item = new Item();
        $item->collection_id = $request->collection_id;
        $item->name = $request->name;
        $item->description = $request->description;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
            $item->image = $path;
        }

        $item->save();

        if ($request->tags) {
            $tagIds = [];
            foreach ($request->tags as $tagName) {
                $tag = \App\Models\Tag::firstOrCreate(['name' => $tagName]);
                $tagIds[] = $tag->id;
            }
            $item->tags()->sync($tagIds);
        }

        return response()->json($item->load('tags'));
    }

    public function update(Request $request,$id)
    {
        $item = Item::findOrFail($id);

        $collection = $item->collection;
        if ($collection->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50'
        ]);

        $item->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        if ($request->has('tags')) {
            $tagIds = [];
            foreach ($request->tags as $tagName) {
                $tag = \App\Models\Tag::firstOrCreate(['name' => $tagName]);
                $tagIds[] = $tag->id;
            }
            $item->tags()->sync($tagIds);
        }

        return response()->json($item->load('tags'));
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        $collection = $item->collection;
        if ($collection->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $item->delete();

        return response()->json(['success' => true]);
    }

}

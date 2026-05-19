<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{

public function index(Request $request)
{
    $query = Collection::with(['items', 'user'])->withCount('items');

    if ($request->search) {
        $query->where('title', 'LIKE', '%'.$request->search.'%');
    }

    return response()->json($query->get());
}

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $collection = Collection::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description
        ]);

        return response()->json($collection->load(['items', 'user'])->loadCount('items'));
    }

    public function update(Request $request,$id)
    {
        $collection = Collection::findOrFail($id);

        if ($collection->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $collection->update([
            'title' => $request->title,
            'description' => $request->description
        ]);

        return response()->json($collection->load(['items', 'user'])->loadCount('items'));
    }

    public function destroy($id)
    {
        $collection = Collection::findOrFail($id);

        if ($collection->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $collection->delete();

        return response()->json(['success' => true]);
    }

}

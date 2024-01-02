<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TodoItem;

class TodoItemController extends Controller
{
    public function index()
    {
        // Retrieve all TodoItems and pass them to the view
        $todoItems = TodoItem::all();
        return view('welcome', compact('todoItems'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // Create a new TodoItem
        $todoItem = TodoItem::create([
            'title' => $request->input('title'),
            'completed' => false,
        ]);

        // Return the newly created TodoItem as JSON
        return response()->json($todoItem, 201);
    }

    public function update(Request $request, $id)
    {
        // Find the TodoItem by ID
        $todoItem = TodoItem::findOrFail($id);

        // Validate the incoming request
        $request->validate([
            'completed' => 'boolean',
        ]);

        // Update the TodoItem with the request data
        $todoItem->update($request->all());

        // Return the updated TodoItem as JSON
        return response()->json($todoItem);
    }

    public function destroy($id)
    {
        // Find the TodoItem by ID
        $todoItem = TodoItem::findOrFail($id);

        // Delete the TodoItem
        $todoItem->delete();

        // Return a success message as JSON
        return response()->json(['message' => 'Todo item deleted successfully']);
    }
}

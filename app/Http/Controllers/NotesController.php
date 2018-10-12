<?php

namespace App\Http\Controllers;

use App\Note;

class NotesController extends Controller
{
    public function store()
    {
        request()->validate([
            'title' => ['present'],
            'body' => ['present'],
            'color' => ['in:white,grey,red,orange,yellow,green,teal,blue,indigo,purple,pink']
        ]);

        $note = Note::make([
            'title' => request('title') ?? '',
            'body' => request('body') ?? '',
            'color' => request('color') ?? 'white'
        ]);
        $note->user()->associate(request()->user());

        $note->save();

        return response()->json($note, 201);
    }

    public function update(Note $note)
    {
        $this->authorize('view', $note);

        request()->validate([
            'title' => ['present'],
            'body' => ['present'],
            'color' => ['in:white,grey,red,orange,yellow,green,teal,blue,indigo,purple,pink']
        ]);

        $note->fill(request()->only(['title', 'body', 'color']));

        if ($note->isDirty()) {
            $note->save();
        }

        return response()->json($note, 200);
    }

    public function destroy(Note $note)
    {
        $this->authorize('view', $note);

        $note->delete();

        return response()->json(null, 204);
    }
}

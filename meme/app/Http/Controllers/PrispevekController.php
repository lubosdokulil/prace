<?php

namespace App\Http\Controllers;
use App\Models\Prispevek;
use Illuminate\Http\Request;

class PrispevekController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fotka' => 'required|string|max:255',
        ]);

        Prispevek::create([
            'fotka' => $validated['fotka'],
            'id_uzivatel' => auth()->id(),
        ]);

        return redirect()->back()->with('message', 'Příspěvek byl úspěšně vytvořen');
    }
}

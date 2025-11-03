<?php

namespace App\Http\Controllers;
use App\Models\Prispevek;
use App\Models\Like;
use Illuminate\Http\Request;

class PrispevekController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fotka' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $path = $request->file('fotka')->store('prispevky', 'public');

        Prispevek::create([
            'fotka' => $path,
            'id_uzivatel' => auth()->id(),
            'lajky' => 0
        ]);

        return redirect()->back()->with('message', 'Příspěvek byl úspěšně vytvořen');
    }

    public function index()
    {
        // načíst příspěvky s autorem a komentáři (včetně autorů komentářů)
        $prispevky = Prispevek::with(['user', 'comments.user'])->get();
        return view('welcome', compact('prispevky'));
    }


    public function like($id)
    {   
    $prispevek = Prispevek::findOrFail($id);
    
    // Kontrola, zda uživatel již lajknul
    if ($prispevek->isLikedByUser(auth()->id())) {
        return redirect()->back()->with('error', 'Již jste tento příspěvek lajknuli');
    }

    // Vytvoření lajku
    $prispevek->likes()->create([
        'id_uzivatel' => auth()->id()
    ]);
    
    // Increment lajků
    $prispevek->increment('lajky');

    return redirect()->back()->with('success', 'Příspěvek byl úspěšně olajkován');
    }
    
    public function commentStore(Request $request, $id)
    {
        $request->validate([
            'text' => 'required|string|max:1000'
        ]);

        $prispevek = Prispevek::findOrFail($id);

        // musí být přihlášený uživatel
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $prispevek->comments()->create([
            'text' => $request->input('text'),
            'id_uzivatel' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Komentář byl přidán');
    }
}

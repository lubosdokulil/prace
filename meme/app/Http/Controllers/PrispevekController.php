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

        //nacteni prispevku s uzivatelem a komentari

        $prispevky = Prispevek::with(['user', 'comments.user'])->get();

        return view('welcome', compact('prispevky'));

    }

    public function like($id)
    {   
        $prispevek = Prispevek::findOrFail($id);
        $userId = auth()->id();

        //kontrola jestli to uzivatel lajknul
        $existingLike = $prispevek->likes()->where('id_uzivatel', $userId)->first();
    
        if ($existingLike) {
            //odebrani lajku
            $existingLike->delete();            //smaze zaznam v tabulce
            $prispevek->decrement('lajky');     //snizi cislo
            
            $message = 'Lajk byl odebrán.';
        } else {
            //lajknuti prispevku
            $prispevek->likes()->create([
                'id_uzivatel' => $userId
            ]);
            $prispevek->increment('lajky');
            
            $message = 'Příspěvek byl úspěšně olajkován.';
        }

        return redirect()->back()->with('success', $message);
    }
    
    //komentare
    public function commentStore(Request $request, $id)
    {
        $request->validate([
            'text' => 'required|string|max:1000'
        ]);

        $prispevek = Prispevek::findOrFail($id);

        //kontrola prihlaseni
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

<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
        @auth
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <span class="inline-flex rounded-md">
                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-600 bg-white hover:text-gray-900 focus:outline-none transition">
                            {{ Auth::user()->name }}
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </span>
                </x-slot>

                <!--dropdown-->
                <x-slot name="content">
                    <form method="GET" action="{{ route('profile.show') }}">
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            {{ __('Profile') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </x-slot>
            </x-dropdown>
            <!--tlacitka login register-->
        @else
            <div class="flex flex-row items-center justify-start gap-3">
                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow focus:outline-none">
                    {{ __('Log in') }}
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-700 bg-white hover:bg-blue-50 text-sm font-medium rounded-md shadow-sm focus:outline-none">
                        {{ __('Register') }}
                    </a>
                @endif
            </div>
        @endauth
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!--novej prispevek-->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-t-4 border-indigo-500">
                <h3 class="font-bold text-lg mb-4">Nahrát nový příspěvek</h3>
                @auth
                    <form method="POST" action="{{ route('prispevky.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-6">
                            <label for="fotka" class="block text-sm font-medium text-gray-700 mb-2">Vyberte fotku:</label>
                            <input type="file" name="fotka" id="fotka" accept="image/*" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded shadow-md transition transform hover:-translate-y-0.5">🚀 Odeslat příspěvek</button>
                    </form>
                @else
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded text-yellow-800">
                        Pro nahrání příspěvku se prosím <a href="{{ route('login') }}" class="text-blue-600 underline font-bold">přihlaste</a>.
                    </div>
                @endauth
            </div>
            <!--vypis prispevku-->
            <div class="grid gap-6">
                @foreach($prispevky->sortByDesc('created_at') as $prispevek)
                    <div class="bg-white border rounded-lg shadow-sm p-6">
                        <div class="flex justify-between items-center mb-2">
                            <p class="font-bold text-lg">{{ $prispevek->user->name ?? 'Smazaný uživatel' }}</p>
                            <span class="text-xs text-gray-400">{{ $prispevek->created_at?->diffForHumans() ?? 'Neznámé datum' }}</span>
                        </div>
                        
                        <img src="{{ asset('storage/' . $prispevek->fotka) }}" alt="Příspěvek" class="w-full h-64 object-cover rounded-md mb-4 bg-gray-100">

                        <!--lajky-->
                        <div class="flex items-center gap-3 mb-4 border-b pb-4">
                            @auth
                                @if(!$prispevek->isLikedByUser(auth()->id()))
                                    <form method="POST" action="{{ route('prispevky.like', $prispevek->id) }}">
                                        @csrf
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                                            👍 Like
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('prispevky.like', $prispevek->id) }}">
                                        @csrf
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition">
                                            👎 Odebrat lajk
                                        </button>
                                    </form>
                                @endif
                            @endauth
                            <span class="text-gray-600 font-medium">{{ $prispevek->lajky }} lajků</span>
                        </div>
                        <!--komentare-->
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-3">Komentáře</h4>

                            <div class="space-y-3 mb-4 max-h-60 overflow-y-auto">
                                @forelse($prispevek->comments as $komentar)
                                    <div class="border rounded p-3 bg-gray-50">
                                        <div class="flex justify-between items-baseline">
                                            <strong class="text-sm">{{ $komentar->user->name ?? 'Smazaný uživatel' }}</strong>
                                            <span class="text-xs text-gray-500">{{ $komentar->created_at?->diffForHumans() }}</span>
                                        </div>
                                        <p class="mt-1 text-gray-800">{{ $komentar->text }}</p>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 italic">Zatím žádné komentáře.</p>
                                @endforelse
                            </div>
                            <!--postovani komentu-->
                            @auth
                                <form method="POST" action="{{ route('prispevky.comment', $prispevek->id) }}">
                                    @csrf
                                    <div class="flex gap-2">
                                        <textarea name="text" rows="1" class="flex-1 border rounded p-2 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Napište komentář..." required></textarea>
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition whitespace-nowrap">Odeslat</button>
                                    </div>
                                    @error('text') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </form>
                            @else
                                <p class="text-sm text-gray-600">Pro přidání komentáře se <a href="{{ route('login') }}" class="underline text-blue-600">přihlaste</a>.</p>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-guest-layout>
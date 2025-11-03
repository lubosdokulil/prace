<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
        @auth
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <span class="inline-flex rounded-md">
                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-600 bg-white hover:text-gray-900 focus:outline-none">
                            {{ Auth::user()->name }}
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </span>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link href="{{ route('profile.show') }}">
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link href="{{ route('logout') }}"
                                         onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        @else
            <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900">Log in</a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900">Register</a>
            @endif
        @endauth
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <h3>Nahrát nový příspěvek</h3>
               @auth
                  <form method="POST" action="{{ route('prispevky.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="fotka" class="block text-gray-700">Nahrát fotku:</label>
                            <input type="file"
                                  name="fotka"
                                  id="fotka"
                               accept="image/*"
                                  required
                                  class="mt-1 block w-full">
                       </div>
                       <div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Odeslat</button>
                        </div>
                   </form>
              @else
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded">
                       Pro nahrání příspěvku se prosím <a href="{{ route('login') }}" class="text-blue-600 underline">přihlaste</a>.
                       @if(Route::has('register'))
                            Nebo se <a href="{{ route('register') }}" class="text-blue-600 underline">zaregistrujte</a>.
                        @endif
                    </div>
               @endauth
            </div>
            <div class="grid gap-4">
                        @foreach($prispevky as $prispevek)
                            <div class="border rounded p-4">
                                <p><strong> {{ $prispevek->user->name ?? 'Smazaný uživatel' }}</strong></p>
                                <img src="{{ asset('storage/' . $prispevek->fotka) }}" 
                                    alt="Nahraný obrázek" 
                                    class="w-full h-48 object-cover mb-2">
                                <div class="flex items-center gap-2">
    @auth
        @if(!$prispevek->isLikedByUser(auth()->id()))
            <form method="POST" action="{{ route('prispevky.like', $prispevek->id) }}" class="inline">
                @csrf
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    👍 Like
                </button>
            </form>
        @else
            <span class="bg-gray-300 text-gray-600 py-2 px-4 rounded">
                👍 Již lajknuto
            </span>
        @endif
    @endauth
    <span class="text-gray-600">{{ $prispevek->lajky }} lajků</span>
</div>
                                <p><strong>ID komentáře:</strong> {{ $prispevek->id_komentar ?? 'Žádný komentář' }}</p>
                            </div>
                        @endforeach
                    </div>
        </div>
    </div>
</x-guest-layout>
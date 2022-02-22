<x-front-layout>
    <main class="max-w-6xl mx-auto mt-6 min-h-screen">
        <section class="bg-grey-200 dark:bg-gray-900 dark:text-white mt-4 p-2 rounded">
            <div class="m-2 p-2 text-2xl font-bold text-indigo-600 dark:text-indigo-300">
                <h1>Movies</h1>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 rounded">
                @foreach ($movies as $movie)
                <x-movie-card>
                    <x-slot name="image">
                        <a href="">
                            <div class="aspect-w-2 aspect-h-3">
                                <img class="object-cover" src="https://www.themoviedb.org/t/p/w220_and_h330_face/{{ $movie->poster_path }}">
                                <div class="absolute left-0 top-0 h-8 w-12 bg-gray-800 text-yellow-500 text-center font-bold">New</div>
                            </div>
                            <div class="absolute inset-0 z-10 bg-gradient-to-t from-black to-transparent"></div>
                            <div class="absolute z-10 bottom-2 left-2 text-white font-bold">
                                @foreach ($movie->genres as $genre)

                                @endforeach
                            </div>
                        </a>
                    </x-slot>
                    <a href="/">
                        <div class="dark:text-white font-bold group-hover:text-blue-400">{{ $movie->title }}</div>
                    </a>
                </x-movie-card>
                @endforeach
            </div>
        </section>
        <section class="bg-grey-200 dark:bg-gray-900 dark:text-white mt-4 p-2 rounded">
            <div class="m-2 p-2 text-2xl font-bold text-indigo-600 dark:text-indigo-300">
                <h1>Episodes</h1>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 rounded">
                @foreach ($episodes as $episode)
                <x-movie-card>
                    <x-slot name="image">
                        <a href="">
                            <div class="aspect-w-2 aspect-h-3">
                                <img class="object-cover" src="https://www.themoviedb.org/t/p/w220_and_h330_face/{{ $episode->season->poster_path }}">
                                <div class="absolute left-0 top-0 h-8 w-12 bg-gray-800 text-yellow-500 text-center font-bold">New</div>
                            </div>
                        </a>
                    </x-slot>
                    <a href="/">
                        <div class="dark:text-white font-bold group-hover:text-blue-400">{{ $episode->name }}</div>
                    </a>
                </x-movie-card>
                @endforeach
            </div>
        </section>
        <section class="bg-grey-200 dark:bg-gray-900 dark:text-white mt-4 p-2 rounded">
            <div class="m-2 p-2 text-2xl font-bold text-indigo-600 dark:text-indigo-300">
                <h1>Series</h1>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 rounded">
                @foreach ($series as $serie)
                <x-movie-card>
                    <x-slot name="image">
                        <a href="">
                            <div class="aspect-w-2 aspect-h-3">
                                <img class="object-cover" src="https://www.themoviedb.org/t/p/w220_and_h330_face/{{ $serie->poster_path }}">
                                <div class="absolute left-0 top-0 h-8 w-12 bg-gray-800 text-yellow-500 text-center font-bold">New</div>
                            </div>
                        </a>
                    </x-slot>
                    <a href="/">
                        <div class="dark:text-white font-bold group-hover:text-blue-400">{{ $serie->name }}</div>
                    </a>
                </x-movie-card>
                @endforeach
            </div>
        </section>
    </main>
</x-front-layout>

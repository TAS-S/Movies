<?php

namespace App\Http\Livewire;

use App\Models\Genre;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class GenreIndex extends Component
{
    use WithPagination;

    public $tmdbId;
    public $title;
    public $genreId;

    public $search = '';
    public $sort = 'asc';
    public $perPage = 5;

    public $showGenreModal = false;

    protected $rules = [
        'title' => 'required',
    ];

    // public function generateGenre()
    // {
    //     $newGenre = Http::get('https://api.themoviedb.org/3/genre/' . $this->tmdbId . '?api_key=9510154dda5827dc6ce167f4d0027379&language=en-US')->json();

    //     // dd($newGenre);

    //     $genre = Genre::where('tmdb_id', $newGenre['id'])->first();
    //     dd($genre);

    //     if (!$genre) {
    //         Genre::create([
    //             'tmdb_id' => $newGenre['id'],
    //             'title'    => $newGenre['name'],
    //             'slug'    => Str::slug($newGenre['name']),
    //         ]);
    //         $this->reset();
    //         $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Genre created']);
    //     } else {
    //         $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Genre exisit']);
    //     }
    // }

    public function generateGenre()
    {
        $newGenre = Http::get('https://api.themoviedb.org/3/genre/movie/list?api_key=9510154dda5827dc6ce167f4d0027379&language=en-US')->json();

        $genre = $newGenre['genres'];

        foreach ($genre as $gen) {
            Genre::create([
                'tmdb_id' => $gen['id'],
                'title'    => $gen['name'],
                'slug'    => Str::slug($gen['name'])
            ]);
        }

        $this->reset();
        $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Genre created']);
    }

    public function showEditModal($id)
    {
        $this->genreId = $id;
        $this->loadGenre();
        $this->showGenreModal = true;
    }

    public function loadGenre()
    {
        $genre = Genre::findOrFail($this->genreId);
        $this->title = $genre->title;
    }

    public function updateGenre()
    {
        $this->validate();
        $genre = Genre::findOrFail($this->genreId);
        $genre->update([
            'title' => $this->title,
        ]);
        $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Genre updated']);
        $this->reset();
    }

    public function closeGenreModal()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function deleteGenre($id)
    {
        Genre::findOrFail($id)->delete();
        $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Genre deleted']);
        $this->reset();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'sort', 'perPage']);
    }
    public function render()
    {
        return view('livewire.genre-index', [
            'genres' => Genre::search('title', $this->search)->orderBy('title', $this->sort)->paginate($this->perPage)
        ]);
    }
}

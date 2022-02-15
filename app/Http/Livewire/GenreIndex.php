<?php

namespace App\Http\Livewire;

use App\Models\Genre;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Http;

class GenreIndex extends Component
{
    use WithPagination;

    protected $key = '9510154dda5827dc6ce167f4d0027379';

    public $tmdbId;
    public $title;
    public $genreId;

    public $showGenreModal = false;

    protected $rules = [
        'title' => 'required'
    ];

    // public function generateGenre()
    // {
    //     // $newGenre = Http::get('https://api.themoviedb.org/3/genre/' . $this->tmdbId . '?api_key=9510154dda5827dc6ce167f4d0027379&language=en-US')->json();
    //     $newGenre = Http::get('https://api.themoviedb.org/3/genre/movie/list?api_key=9510154dda5827dc6ce167f4d0027379&language=en-US')->json();

    //     $genre = Genre::where('tmdb_id', $newGenre['id'])->first();

    //     if(!$genre){
    //         Genre::create([
    //         'tmdb_id' => $newGenre['id'],
    //         'title' => $newGenre['name'],
    //         'slug' => Str::slug($newGenre['name'])
    //     ]);
    //     } else {
    //         $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Genre exists!']);
    //     }
    // }

    public function generateGenre()
    {
        // $newGenre = Http::get('https://api.themoviedb.org/3/genre/' . $this->tmdbId . '?api_key=9510154dda5827dc6ce167f4d0027379&language=en-US')->json();
        $newGenre = Http::get('https://api.themoviedb.org/3/genre/movie/list?api_key=9510154dda5827dc6ce167f4d0027379&language=en-US')->json();

        // $genre = Genre::all();

        Genre::create([
            'tmdb_id' => $newGenre['id'],
            'title' => $newGenre['name'],
            'slug' => Str::slug($newGenre['name'])
        ]);

        $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Genre exists!']);

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
            'name' => $this->title

        ]);
        $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Genre updated successfully']);
        $this->reset();
    }

    public function deleteGenre($id)
    {
        Genre::findOrFail($id)->delete();
        $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Genre deleted successfully']);
    }

    public function closeGenreModal()
    {
        // $this->reset();
        $this->showGenreModal = false;
        $this->resetValidation();
    }
    public function render()
    {
        return view('livewire.genre-index', [
            'genres' => Genre::paginate(5)
        ]);
    }
}

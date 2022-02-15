<?php

namespace App\Http\Livewire;

use App\Models\Serie;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Livewire\WithPagination;

class SerieIndex extends Component
{
    use WithPagination;

    public $name;
    public $tmdbId;
    public $serieId;
    public $createdYear;
    public $posterPath;

    public $search = '';
    public $sort = 'asc';
    public $perPage = 5;

    public $showSerieModal = false;

    protected $rules = [
        'name' => 'required',
        'posterPath' => 'required',
        'createdYear' => 'required'
    ];

    public function generateSerie()
    {
        $newSerie = Http::get('https://api.themoviedb.org/3/tv/' . $this->tmdbId . '?api_key=9510154dda5827dc6ce167f4d0027379&language=en-US')->json();

        $serie = Serie::where('tmdb_id', $newSerie['id'])->first();

        if(!$serie){

            Serie::create([
                'tmdb_id'=> $newSerie['id'],
                'name' => $newSerie['name'],
                'slug' => Str::slug($newSerie['name']),
                'created_year' => $newSerie['first_air_date'],
                'poster_path' => $newSerie['poster_path']
                ]);
                $this->reset();
                $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Serie created!']);
                } else {
                    $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Serie exists!']);
                    $this->reset();
                }
        // dd($newSerie);
    }
    public function showEditModal($id)
    {
        $this->serieId = $id;
        $this->loadSerie();
        $this->showSerieModal = true;

    }

    public function loadSerie()
    {
        $serie = Serie::findOrfail($this->serieId);
        $this->name = $serie->name;
        $this->posterPath = $serie->poster_path;
        $this->createdYear = $serie->created_year;
    }

    public function closeSerieModal()
    {
        $this->showSerieModal = false;
    }

    public function updateSerie()
    {
        $this->validate();
        $serie = Serie::findOrfail($this->serieId);
        $serie->update([
            'name' => $this->name,
            'created_year' => $this->createdYear,
            'poster_path' => $this->posterPath
        ]);
        $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Serie updated successfully']);
        $this->reset();
    }

    public function deleteSerie($id)
    {
        Serie::findOrFail($id)->delete();
        $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Serie deleted successfully']);
        $this->reset();
    }

    public function resetFilters()
    {
        $this->reset(['search','sort','perPage']);
    }

    public function render()
    {
        return view('livewire.serie-index', [
            'series' => Serie::search('name', $this->search)->orderBy('name', $this->sort)->paginate($this->perPage)
        ]);
    }
}

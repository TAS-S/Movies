<?php

namespace App\Http\Livewire;

use App\Models\Serie;
use App\Models\Season;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Http;

class SeasonIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $sort = 'asc';
    public $perPage = 5;

    public $seasonNumber;
    public $name;
    public $seasonId;
    public $posterPath;

    public $showSeasonModal = false;

    protected $rules = [
        'name' => 'required',
        'posterPath' => 'required',
        'seasonNumber' => 'required'
    ];

    public Serie $serie;

    //generate season

    public function generateSeason()
    {
        $season = Season::where('season_number', $this->seasonNumber)->exists();
        if($season){
            $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Season exists']);
            return;
        }
        $apiSeason = Http::get('https://api.themoviedb.org/3/tv/' . $this->serie->tmdb_id . '/season/' . $this->seasonNumber . '?api_key=9510154dda5827dc6ce167f4d0027379&language=en-US
                    ');
        if ($apiSeason->ok()) {
                $newSeason = $apiSeason->json();
                Season::create([
                    'serie_id' => $this->serie->id,
                    'tmdb_id' => $newSeason['id'],
                    'name'    => $newSeason['name'],
                    'slug'    => Str::slug($newSeason['name']),
                    'season_number' => $newSeason['season_number'],
                    'poster_path'  => $newSeason['poster_path'] ? $newSeason['poster_path'] : $this->serie->poster_path
                ]);
                // $this->reset('seasonNumber');
                $this->reset(['seasonId', 'seasonNumber', 'name', 'posterPath', 'showSeasonModal']);
                $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Season created']);

        } else {
            $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Api not exists']);
            $this->reset('seasonNumber');
        }

    }

    public function showEditModal($id)
    {
        $this->seasonId = $id;
        $this->loadSeason();
        $this->showSeasonModal = true;
    }

    public function loadSeason()
    {
        $season = Season::findOrfail($this->seasonId);
        $this->name = $season->name;
        $this->posterPath = $season->poster_path;
        $this->seasonNumber = $season->season_number;
    }

    public function closeSeasonModal()
    {
        $this->showSeasonModal = false;
    }

    public function updateSeason()
    {
        $this->validate();
        $season = Season::findOrfail($this->seasonId);

        $season->update([
            'name' => $this->name,
            'season_number' => $this->seasonNumber,
            'poster_path' => $this->posterPath
        ]);
        $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Season updated successfully']);
        $this->reset(['seasonId', 'seasonNumber', 'name', 'posterPath', 'showSeasonModal']);
    }

    public function deleteSeason($id)
    {
        $season = Season::findOrFail($id);
        $season->delete();
        $this->reset(['seasonNumber', 'name', 'posterPath', 'seasonId', 'showSeasonModal']);
        $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Season deleted']);
    }

    public function resetFilters()
    {
        $this->reset(['search', 'sort', 'perPage']);
    }

    public function render()
    {
        return view('livewire.season-index', [
            'seasons' => Season::where('serie_id', $this->serie->id)
                ->search('name', $this->search)
                ->orderBy('name', $this->sort)
                ->paginate($this->perPage)
        ]);
    }
}

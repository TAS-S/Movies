<?php

namespace App\Http\Livewire;

use App\Models\Serie;
use App\Models\Season;
use App\Models\Episode;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Http;


class EpisodeIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $sort = 'asc';
    public $perPage = 5;

    public $episodeNumber;
    public $name;
    public $episodeId;
    public $overview;

    public $showEpisodeModal = false;

    protected $rules = [
        'name' => 'required',
        'overview' => 'required',
        'episodeNumber' => 'required'
    ];

    public Serie $serie;
    public Season $season;

    public function generateEpisode()
    {
        $newEpisode = Http::get('https://api.themoviedb.org/3/tv/' . $this->serie->tmdb_id . '/season/' . $this->season->season_number . '/episode/' . $this->episodeNumber . '?api_key=9510154dda5827dc6ce167f4d0027379&language=en-US');

        if($newEpisode->ok())
        {
            $episode = Episode::where('tmdb_id', $newEpisode['id'])->first();

            if(!$episode){

                Episode::create([
                    'season_id' => $this->season->id,
                    'tmdb_id' => $newEpisode['id'],
                    'name' => $newEpisode['name'],
                    'slug' => Str::slug($newEpisode['name']),
                    'episode_number' => $newEpisode['episode_number'],
                    'overview' => $newEpisode['overview'],
                    'is_public' => false,
                    'visits' => 1
                ]);
                $this->reset('episodeNumber');
                $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Episode created!']);
            } else {
                $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Episode exists!']);
                $this->reset();
            }
        } else {
            $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => "Api doesn't exists!"]);
            $this->reset('episodeNumber');
        }

    }

    public function showEditModal($id)
    {
        $this->episodeId = $id;
        $this->loadEpisode();
        $this->showEpisodeModal = true;

    }

    public function loadEpisode()
    {
        $episode = Episode::findOrfail($this->episodeId);
        $this->name = $episode->name;
        $this->overview = $episode->overview;
        $this->episodeNumber = $episode->episode_number;
    }

    public function closeEpisodeModal()
    {
        $this->showEpisodeModal = false;
    }

    public function updateEpisode()
    {
        $this->validate();
        $episode = Episode::findOrfail($this->episodeId);

        $episode->update([
            'name' => $this->name,
            'season_number' => $this->episodeNumber,
            'overview' => $this->overview
        ]);
        $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Episode updated successfully']);
        $this->reset(['episodeId','episodeNumber', 'name', 'overview', 'showEpisodeModal']);
    }

    public function deleteEpisode($id)
    {
        Episode::findOrFail($id)->delete();
        $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Episode deleted successfully']);
        $this->reset(['episodeId','episodeNumber', 'name', 'overview', 'showEpisodeModal']);
    }

    public function resetFilters()
    {
        $this->reset(['search','sort','perPage']);
    }


    public function render()
    {
        return view('livewire.episode-index',[
            'episodes' => Episode::where('season_id', $this->season->id)
            ->search('name', $this->search)
            ->orderBy('name', $this->sort)
            ->paginate($this->perPage)
        ]);
    }
}

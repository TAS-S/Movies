<?php

namespace App\Http\Livewire;

use App\Models\Cast;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Http;

class CastIndex extends Component
{
    use WithPagination;

    protected $key = '9510154dda5827dc6ce167f4d0027379';

    public $castTMDBId;
    public $castName;
    public $castPosterPath;
    public $castId;

    public $search = '';
    public $sort = 'asc';
    public $perPage = 5;

    public $showCastModal = false;

    protected $rules = [
        'castName' => 'required',
        'castPosterPath' => 'required'
    ];

    public function generateCast()
    {
        $newCast = Http::get('https://api.themoviedb.org/3/person/' . $this->castTMDBId . '?api_key=9510154dda5827dc6ce167f4d0027379&language=en-US')->json();

        $cast = Cast::where('tmdb_id', $newCast['id'])->first();

        if(!$cast){
            Cast::create([
            'tmdb_id' => $newCast['id'],
            'name' => $newCast['name'],
            'slug' => Str::slug($newCast['name']),
            'poster_path' => $newCast['profile_path']
        ]);
        } else {
            $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Cast exists!']);
        }
    }

    public function showEditModal($id)
    {
        $this->castId = $id;
        $this->loadCast();
        $this->showCastModal = true;
    }

    public function loadCast()
    {
        $cast = Cast::findOrFail($this->castId);
        $this->castName = $cast->name;
        $this->castPosterPath = $cast->poster_path;
    }

    public function updateCast()
    {
        $this->validate();
        $cast = Cast::findOrFail($this->castId);
        $cast->update([
            'name' => $this->castName,
            'poster_path' => $this->castPosterPath
        ]);
        $this->dispatchBrowserEvent('banner-message', ['style' => 'success', 'message' => 'Cast updated successfully']);
        $this->reset();
    }

    public function deleteCast($id)
    {
        Cast::findOrFail($id)->delete();
        $this->dispatchBrowserEvent('banner-message', ['style' => 'danger', 'message' => 'Cast deleted successfully']);
    }

    public function closeCastModal()
    {
        // $this->reset();
        $this->showCastModal = false;
        $this->resetValidation();
    }

    public function resetFilters()
    {
        $this->reset(['search','sort','perPage']);
    }

    public function render()
    {

        return view('livewire.cast-index', [
            'casts' => Cast::search('name', $this->search)->orderBy('name', $this->sort)->paginate($this->perPage)
        ]);
    }
}

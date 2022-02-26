<?php

namespace App\Models;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Serie extends Model implements Searchable
{
    use HasFactory;

    public function getSearchResult(): SearchResult
    {
       $url = route('series.show', $this->slug);

        return new \Spatie\Searchable\SearchResult(
           $this,
           $this->name,
           $url
        );
    }

    protected $fillable = ['tmdb_id', 'name', 'slug', 'created_year', 'poster_path'];

    public function seasons()
    {
        return $this->hasMany(Season::class);
    }
}

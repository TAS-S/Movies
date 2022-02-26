<?php

namespace App\Models;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cast extends Model implements Searchable
{
    use HasFactory;

    protected $fillable = ['tmdb_id', 'name', 'slug', 'poster_path'];

    public function getSearchResult(): SearchResult
    {
       $url = route('casts.show', $this->slug);

        return new \Spatie\Searchable\SearchResult(
           $this,
           $this->name,
           $url
        );
    }

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'cast_movie')->latest();
    }
}

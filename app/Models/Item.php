<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'name',
        'description',
        'image'
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}

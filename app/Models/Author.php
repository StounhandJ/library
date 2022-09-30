<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Author extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $fillable = ["fio"];

    protected $hidden = ["created_at", "updated_at", "deleted_at"];

    public function toSearchableArray()
    {
        return [
            "fio" => $this->fio
        ];
    }
}

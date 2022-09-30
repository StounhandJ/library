<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class Book extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $fillable = ["name", "description", "date_publication", "path_cover"];

    protected $hidden = ["path_cover"];

    protected $appends = ["cover_url", "genres"];

    public function getCoverUrlAttribute(): string
    {
        return Request::root() . $this->getCoverSrc();
    }

    public function getGenresAttribute(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->genres()->get();
    }

    public function toSearchableArray()
    {
        return [
            "name" => $this->name
        ];
    }

    public function getCoverSrc()
    {
        return Storage::disk("cover")->url($this->path_cover);
    }

    public function getImgPath()
    {
        $src_cover = Storage::disk("cover")->readStream($this->path_cover);
        if ($src_cover == null) {
            return $src_cover;
        }
        return stream_get_meta_data($src_cover)["uri"];
    }

    /**
     * Sets the new image src.
     *
     * @param UploadedFile|string|null $img
     */
    public function setImgSrcIfNotEmpty(UploadedFile|string|null $img)
    {
        if (!is_null($img) and ((is_string($img) and $img != "") or !is_string($img))) {
            $this->path_cover = Book::saveImg($img);
        }
    }

    /**
     * The photo is saved on the disk. Return src.
     *
     * @param UploadedFile|string $img
     * @return string
     */
    public static function saveImg(UploadedFile|string $img): string
    {
        return Storage::disk("cover")->putFile("/", $img);
    }

    public function genres(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'genre_book')->distinct();
    }
}

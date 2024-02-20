<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['title', 'body', 'user_id'];


    public function toSearchableArray()
    {
        return [
            'title' => $this->tile,
            'body' => $this->body
        ];
    }

    public function userData()
    {

        return $this->belongsTo(User::class, 'user_id');
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use App\Traits\QueryScopes;

class Post extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'post_catalogue_id',
        'image',
        'album',
        'publish',
        'order',
        'user_id',
        'follow'
        // 'description'
    ];

    protected $table = 'posts';

    const DELETED_AT = 'delete_at';

    public function languages(){
        return $this->belongsToMany(Language::class, 'post_language','post_id' ,'language_id' )
        ->withPivot(
            'name',
            'canonical',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'description',
            'content')->withTimestamps();
    }

    public function post_catalogues(){
        return $this->belongsTomany(PostCatalogue::class, 'post_catalogue_post', 'post_id', 'post_catalogue_id');
    }


}

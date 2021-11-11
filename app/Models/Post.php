<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Controllers\PostController;
use Illuminate\Database\Eloquent\Model;

  
class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'access',
        'text',
        'file',
        'user_id ',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at',
    ];
    /**
     * The for database table.
     *
     * @var array
     */
    protected $table = 'posts';

}

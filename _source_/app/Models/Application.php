<?php


namespace App\Models;


use Jenssegers\Mongodb\Eloquent\Model;

class Application extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

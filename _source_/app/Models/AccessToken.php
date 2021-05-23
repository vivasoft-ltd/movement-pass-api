<?php
namespace App\Models;


use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model;

class AccessToken extends Model
{
    protected $collection = 'access_tokens';
    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(Admin::class);
    }
}

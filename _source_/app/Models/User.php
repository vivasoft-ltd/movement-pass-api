<?php

namespace App\Models;

use App\Models\MongoUserModel as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    protected $hidden = [
        'password', 'updated_at', 'created_at'
    ];

    protected $appends = [
        'image_url'
    ];

    public function can($abilities, $arguments = [])
    {
        // TODO: Implement can() method.
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getImageUrlAttribute(): string
    {
        return Storage::url($this->image);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}

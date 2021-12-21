<?php


namespace App\Models;


use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model;

class Application extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approve()
    {
        $this->approved = true;
        $this->approved_at = Carbon::now();
        $this->approved_by = auth('admin')->user()->id;
        $this->save();
    }

    public function reject()
    {
        $this->approved = false;
        $this->rejected_at = Carbon::now();
        $this->rejected_by = auth('admin')->user()->id;
        $this->save();
    }
}

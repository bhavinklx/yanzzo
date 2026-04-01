<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = "state";
    protected $primaryKey = "state_id";
    public $timestamps = false;

    protected $fillable = [
        "state_id",
        "country_id",
        "state_name",
        "state_status"
    ];
    public function product()
    {
        return $this->hasMany(Product::class, 'state_id', 'state_id');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'state_id', 'state_id');
    }
}

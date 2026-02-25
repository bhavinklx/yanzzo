<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $table = "subscription";
    protected $primaryKey = "subscription_id";
    public $timestamps = false;

    protected $fillable = [
        "subscription_id",
        "subscription_plan_id",
        "business_id",
        "subscription_start",
        "subscription_end",
        "amount",
        "is_active",
        "created_at",
        "created_user_id"
    ];
}

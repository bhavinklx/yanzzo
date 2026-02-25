<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;
    protected $table = "membership";
    protected $primaryKey = "membership_id";
    public $timestamps = false;

    protected $fillable = [
        "membership_id",
        "membership_title",
        "membership_slug",
        "membership_price",
        "membership_offer_price",
        "membership_duration",
        "membership_discount",
        "membership_desc",
        "membership_recommended",
        "membership_order",
        "membership_status",
        "created_at"
    ];
}

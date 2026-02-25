<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = "faq";
    protected $primaryKey = "faq_id";
    public $timestamps = false;

    protected $fillable = [
        "faq_id",
        "city_id",
        "faq_title",
        "faq_desc",
        "faq_order",
        "faq_status",
        "faq_hstatus",
        "created_at"
    ];
}
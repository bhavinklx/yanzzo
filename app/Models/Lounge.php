<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lounge extends Model
{
    use HasFactory;
    protected $table = "lounge";
    protected $primaryKey = "lounge_id";
    public $timestamps = false;

    protected $fillable = [
        "lounge_id",
        "lounge_code",
        "lounge_name",
        "lounge_slug",
        "lounge_email",
        "lounge_mobile",
        "lounge_image",
        "lounge_short_desc",
        "lounge_max_person",
        "lounge_includes",
        "lounge_amenities",
        "lounge_address",
        "lounge_area",
        "cities_id",
        "lounge_google_map",
        "lounge_unit",
        "lounge_ownership",
        "lounge_agreement_start_date",
        "lounge_agreement_end_date",
        "lounge_gst_invoice",
        "lounge_franchise_fee",
        "lounge_desc",
        "lounge_rules",
        "lounge_meta_title",
        "lounge_meta_keyword",
        "lounge_meta_desc",
        "lounge_canonical",
        "lounge_order",
        "lounge_status",
        "lounge_book_status",
        "created_at"
    ];
}

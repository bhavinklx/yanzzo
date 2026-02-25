<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $table = "testimonial";
    protected $primaryKey = "testimonial_id";
    public $timestamps = false;

    protected $fillable = [
        "testimonial_id",
        "testimonial_title",
        "testimonial_designation",
        "testimonial_image",
        "testimonial_desc",
        "testimonial_order",
        "testimonial_status",
        "created_at"
    ];
}

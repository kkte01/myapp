<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ripple extends Model
{
    use HasFactory;
    protected $table = "ripples";
    protected $fillable = ['content'];
}

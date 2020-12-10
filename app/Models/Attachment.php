<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;
    protected $fillable = ['filename', 'bytes', 'mime'];

    public function article(){
        return $this->belongsTo(Article::class);
    }

    public function getBytesAttribute($value){
        return format_filesize($value);
    }

    public function getUrlAttribute(){
        return url('test_image'.$this->filename);
    }
}

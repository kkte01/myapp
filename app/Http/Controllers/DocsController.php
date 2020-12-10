<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocsController extends Controller
{
    //
    protected $docs;
    
    public function __construct(\App\Models\Documentation $docs)
    {
        $this->docs = $docs;
    }
    
    public function show($file = null)
    {
        // $index = markdown($this->docs->get());
        // $content = markdown($this->docs->get($file ?: 'installation.md'));
        //p.182 
        $index = \Cache::remember('docs.index', 120, function(){
            //dd('reached');
            return markdown($this->docs->get());
        });

        $content = \Cache::remember('docs.{$file}', 120, function() use($file){
            //dd('reached');
            return markdown($this->docs->get($file ? $file : 'installation.md'));
        });

        return view('docs.show', compact('index','content'));
    }
    
    public function image($file)
    {
        $image = $this->docs->image($file);

        return response($image->encode('jpeg'), 200, ['Content-Type' => 'image/jpeg']);
    }
}

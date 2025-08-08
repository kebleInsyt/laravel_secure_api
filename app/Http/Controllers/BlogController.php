<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        $blogs = Blog::where('user_id', $request->user()->id)->orderBy('id', 'desc')->paginate(4);

        return view("blog.index", compact("blogs"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view("blog.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "title" => 'required|string',
            "description" => 'required|string',
            "banner_image" => 'required|image'
        ]);

        $data["user_id"]  = $request->user()->id;

        //check if file was uploaded
        if($request->hasFile('banner_image')) {
            $data["banner_image"] = $request->file('banner_image')->store('blog', 'public');
        }

        Blog::create($data);
        
        return to_route('blog.index')->with("success", "Blog post created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        return view("blog.show", compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        return view("blog.edit", compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $data = $request->validate([
            "title" => 'required|string',
            "description" => 'required|string',
        ]);

        $data["user_id"]  = $request->user()->id;

        //check if file was uploaded
        if ($request->hasFile('banner_image')) {
            //if an image path already exists in database, remove from storage 
            if($blog->banner_image) {
                Storage::disk("public")->delete($blog->banner_image);
            }

            $data["banner_image"] = $request->file('banner_image')->store('blog', 'public');
        }

        $blog->update($data);

        return to_route('blog.index', $blog)->with("success", "Blog post updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog  $blog)
    {
        //remove actual image from server if path exists in database
        if($blog->banner_image) {
            Storage::disk("public")->delete($blog->banner_image);
        }

        //delete from database
        $blog->delete();

        return to_route('blog.index')->with("success", "Blog post deleted successfully");
    }
}

// P H J N Q J V G
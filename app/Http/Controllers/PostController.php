<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Coach;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
// use pagination;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $post = Post::all();
    }

    // show Post Record on Website

    public function showPost()
    {
        $post = Post::with('coach')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('posts')
                    ->groupBy('coach_id');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(4); // Correctly chain the paginate() method here
    
        return response()->json([
            "success" => true,
            "message" => "Record Get Successfully",
            "post" => $post
        ]);
    }
    


    // show Post Record on Website

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'post_title' => 'required|string|max:255',
            'post_name' => 'required|string|max:255',
            'post_description' => 'required|nullable|string',
            'post_time' => 'nullable|date',
            'post_status' => 'required',
            'post_location' => 'nullable|string|max:255',
            'coach_id' => 'nullable|exists:coaches,id', // assuming there's a 'coaches' table
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => 'Record Not Saved',
                $validator->errors(),
            ],422);
        }

        $post_image = $request->file('post_image');
        $ext = $post_image->getClientOriginalExtension();
        $post_image_name = time() . '.' . $ext; 
        $post_image->move(public_path('uploads/coach_posts'),$post_image_name);

        $post = new Post();
        $post->post_title = $request->post_title;
        $post->post_name = $request->post_name;
        $post->post_description = $request->post_description;
        $post->post_time = Carbon::now(); 
        $post->post_status = $request->post_status;
        $post->post_location = $request->post_location;
        $post->coach_id = $request->coach_id;
        $post->post_image = $post_image_name;
        $post->save();

        return response()->json([
            "success" => true,
            "message" => "Record Saved Successfully",
            "post" => $post,
            "path" => asset('uploads/coach_posts'.$post_image_name)
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    public function showBlogPost($id){

        $post = Post::with('coach')->where('coach_id', $id)->orderBy('id', 'desc')->get();
    
        if ($post->isEmpty()) {
            return response()->json([
                "success" => false,
                "message" => "No posts found for this coach."
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Records fetched successfully",
            "post" => $post
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

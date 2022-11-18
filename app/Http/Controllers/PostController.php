<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['categories'] = Category::orderBy('id', 'desc')->get();
        $post_query = Post::withCount('comments')->where('user_id', auth()->id());

        if ($request->category) {
            $post_query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        if ($request->keyword) {
            $post_query->where('title', 'LIKE', '%' . $request->keyword . '%');
        }

        $data['posts'] = $post_query->orderBy('id', 'DESC')->paginate(2);

        return view('posts.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['categories'] = Category::orderBy('id', 'desc')->get();
        $data['tags'] = Tag::orderBy('id', 'desc')->get();
        return view('posts.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg',
            'category' => 'required',
            'tags' => 'required|array'
        ], [
            'category.required' => 'Please select a Category',
            'tags.required' => 'Please Select atleast one Tag'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->extension();
            $image->move(public_path('post_images'), $image_name);
        }

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $image_name,
            'user_id' => auth()->id(),
            'category_id' => $request->category
        ]);

        $post->tags()->sync($request->tags);

        return redirect()->route('posts.index')->with('success', 'Post successfully created');


        //J'ai vu ! C'est vraiment bien de faire les recherches hein, on découvre de nouveaux trucs and it improves our skills
        // $validator = Validator::make($request->all(), [
        //     'title' => 'required|max:255',
        //     'description' => 'required',
        //     'image' => 'required|mimes:png,jpg,jpeg',
        //     'category' => 'required',
        //     'tags' => 'required|array'
        // ], [
        //     'category.required' => 'Please select a Category',
        //     'tags.required' => 'Please Select atleast one Tag'
        // ]);

        // if ($validator->fails()) {
        //     return $validator->errors();
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
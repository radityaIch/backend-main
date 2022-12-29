<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleGallery;
use App\Models\ArticleLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index()
    {
        $article = Article::with('client', 'gallery', 'created_by')->latest()->get();

        return response()->json([
            'data' => $article
        ], 200);
    }

    public function store(Request $request)
    {
        $article = new Article();

        $rules = [
            'title' => 'required',
            'category' => 'required',
            'user_id' => 'required',
            'description' => 'required',
            'image' => 'image|file|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $file = $request->file('image');

        if ($file != null) {
            $string = rand(22, 5033);
            $name = $string . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filename = $file->storeAs('images', $name);
            $article->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        if ($request->client_id != null) {
            $article->client_id = $request->client_id;
        }

        $article->title = $request->title;
        $article->category = $request->category;
        $article->user_id = $request->user_id;
        $article->description = $request->description;
        $article->save();

        if ($article->client_id != null) {
            $link = $request->getSchemeAndHttpHost() . '/api/article/' . $article->id;
            ArticleLink::create([
                'link' => $link,
                'article_id' => $article->id,
                'client_id' => $article->client_id
            ]);
        }

        $article = Article::where('id', $article->id)->with('client', 'gallery', 'created_by')->first();

        return response()->json([
            'message' => 'Article created successfully',
            'data' => $article,
        ], 201);
    }

    public function show($id)
    {
        $article = Article::where('id', $id)->with('client', 'gallery', 'created_by')->first();

        if (!$article) {
            return response()->json([
                'message' => 'Article not found'
            ], 400);
        }

        return response()->json([
            'data' => $article
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $article = Article::where('id', $id)->with('client', 'gallery', 'created_by')->first();

        if (!$article) {
            return response()->json([
                'message' => 'Article not found'
            ], 400);
        }

        $rules = [
            'title' => 'required',
            'category' => 'required',
            'user_id' => 'required',
            'description' => 'required',
            'image' => 'image|file|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $file = $request->file('image');

        if ($file != null) {
            if ($article->image != '' && $article->image != null) {
                $oldImage = $article->image;
                $nameSplit = explode('/', $oldImage);
                $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
                Storage::delete($fileName);
            }
            // $filename = $string . preg_replace('/\s+/', '', $file->getClientOriginalName());
            $string = rand(22, 5033);
            $name = $string . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filename = $file->storeAs('images', $name);
            $article->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        if ($request->client_id != null) {
            $article->client_id = $request->client_id;
            $link = $request->getSchemeAndHttpHost() . '/api/article/' . $article->id;

            ArticleLink::updateOrCreate(
                ['article_id' => $article->id],
                ['client_id' => $request->client_id, 'link' => $link]
            );
        } else {
            if ($article->client_id != null) {
                $articleLink = ArticleLink::where('article_id', $article->id)
                    ->where('client_id', $article->client_id)->first();
                $articleLink->delete();
            }
        }

        $article->title = $request->title;
        $article->category = $request->category;
        $article->user_id = $request->user_id;
        $article->description = $request->description;
        $article->save();

        $article = Article::where('id', $id)->with('client', 'gallery', 'created_by')->first();

        return response()->json([
            'message' => 'article updated successfully',
            'data' => $article
        ], 201);
    }

    public function destroy($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json([
                'message' => 'Article not found'
            ], 400);
        }

        if ($article->image != '' && $article->image != null) {
            $oldImage = $article->image;
            $nameSplit = explode('/', $oldImage);
            $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
            Storage::delete($fileName);
        }

        if ($article->client_id != null) {
            $articleLink =  ArticleLink::where('article_id', $article->id)->first();
            $articleLink->delete();
        }

        $galleries = ArticleGallery::where('article_id', $article->id)->get();

        if ($galleries != null) {
            foreach ($galleries as $gallery) {
                $oldImage = $gallery->image;
                $nameSplit = explode('/', $oldImage);
                $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
                Storage::delete($fileName);
            }
        }

        $article->delete();

        return response()->json([
            'message' => 'Article deleted successfully'
        ], 200);
    }
}

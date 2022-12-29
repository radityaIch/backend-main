<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArticleGalleryController extends Controller
{
    public function index()
    {
        $gallery = ArticleGallery::latest()->get();

        return response()->json([
            'data' => $gallery
        ], 200);
    }

    public function store(Request $request)
    {
        $article = Article::latest()->first();
        $article_id = $article->id;

        $rules = [
            'images.*' => 'required|image|file|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        if ($request->file('images') != null) {
            foreach ($request->file('images') as $image) {
                $articelGallery = new ArticleGallery();

                $string = rand(22, 5033);
                $name = $string . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());
                $filename = $image->storeAs('images', $name);
                $articelGallery->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
                $articelGallery->article_id = $article_id;
                $articelGallery->save();
            }
        }

        $gallery = ArticleGallery::where('article_id', $article_id)->get();

        return response()->json([
            'message' => 'Article gallery created successfully',
            'data' => $gallery,
        ], 201);
    }

    public function show($id)
    {
        $articleGallery = ArticleGallery::find($id);

        if (!$articleGallery) {
            return response()->json([
                'message' => 'Article gallery not found'
            ], 400);
        }

        return response()->json([
            'data' => $articleGallery
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $gallery = ArticleGallery::find($id);

        if (!$gallery) {
            return response()->json([
                'message' => 'Article gallery not found'
            ], 400);
        }

        $rules = [
            'image' => 'image|file|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $file = $request->file('image');

        if ($file != null) {
            if ($gallery->image != '' && $gallery->image != null) {
                $oldImage = $gallery->image;
                $nameSplit = explode('/', $oldImage);
                $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
                Storage::delete($fileName);
            }

            $string = rand(22, 5033);
            $name = $string . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filename = $file->storeAs('images', $name);
            $gallery->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $gallery->save();

        return response()->json([
            'message' => 'Article gallery updated successfully',
            'data' => $gallery
        ], 201);
    }

    public function updateByArticleId(Request $request, $articleId)
    {
        $galleries = ArticleGallery::where('article_id', $articleId)->get();

        if (!$galleries) {
            return response()->json([
                'message' => 'Article gallery not found'
            ], 400);
        }

        $rules = [
            'images.*' => 'image|file|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        if ($request->file('images') != null) {
            if ($galleries != null) {
                foreach ($galleries as $oldGallery) {
                    $oldImage = $oldGallery->image;
                    $nameSplit = explode('/', $oldImage);
                    $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
                    Storage::delete($fileName);
                    $oldGallery->delete();
                }
            }

            foreach ($request->file('images') as $image) {
                $articelGallery = new ArticleGallery();

                $string = rand(22, 5033);
                $name = $string . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());
                $filename = $image->storeAs('images', $name);
                $articelGallery->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
                $articelGallery->article_id = $articleId;
                $articelGallery->save();
            }
        }

        $galleries = ArticleGallery::where('article_id', $articleId)->get();

        return response()->json([
            'message' => 'Article gallery updated successfully',
            'data' => $galleries
        ], 201);
    }

    public function destroy($id)
    {
        $articelGallery = ArticleGallery::find($id);

        if (!$articelGallery) {
            return response()->json([
                'message' => 'Article gallery not found'
            ], 400);
        }

        if ($articelGallery->image != '' && $articelGallery->image != null) {
            $oldImage = $articelGallery->image;
            $nameSplit = explode('/', $oldImage);
            $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
            Storage::delete($fileName);
        }

        $articelGallery->delete();

        return response()->json([
            'message' => 'Article gallery deleted successfully'
        ], 200);
    }
}

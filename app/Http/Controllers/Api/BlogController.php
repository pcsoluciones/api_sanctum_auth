<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function createBlog(Request $request){
        $request->validate([
            "title" => "required",
            "content" => "required"
        ]);

        $user_id = auth()->user()->id;

        $blog = new Blog();
        $blog->user_id = $user_id;
        $blog->title = $request->title;
        $blog->content = $request->content;

        $blog->save();

        //Respuesta API
        return response([
            "status" => 1,
            "msg" => "¡Blog creado exitosamente!"
        ]);
    }

    public function listBlog(){
        $user_id = auth()->user()->id;
        $blogs = Blog::where("user_id", $user_id)->get();

        return response([
            "status" => 1,
            "msg" => "Listado de blogs",
            "data" => $blogs
        ]);
    }

    public function showBlog(){

    }

    public function updateBlog(Request $request, $id){
        $user_id = auth()->user()->id;
        if ( Blog::where(["user_id"=>$user_id, "id"=>$id])->exists() ){
            // Si existe lo actualizamos
            $blog = Blog::find($id);
            
            $blog->title =  isset($request->title) ? $request->title : $blog->title;
            $blog->content = isset($request->content) ? $request->content : $blog->content;
            $blog->save();

            // respuesta de la API
            return response([
                "status" => 1,
                "msg" => "¡Blog actualizado correctamente!"
            ]);

        }else {
            return response([
                "status" => 0,
                "msg" => "No se encontró el Blog"
            ], 404);
        }


    }

    public function deleteBlog($id){
        $user_id = auth()->user()->id;
        if ( Blog::where(["id"=>$id, "user_id"=>$user_id])->exists() ){
            $blog = Blog::where(["id"=>$id, "user_id"=>$user_id])->first();
            $blog->delete();

            // respuesta de la API
            return response([
                "status" => 1,
                "msg" => "Blog eliminado correctamente"
            ]);

        }else {
            return response([
                "status" => 0,
                "msg" => "No se encontró el Blog"
            ], 404);
        }
    }
}

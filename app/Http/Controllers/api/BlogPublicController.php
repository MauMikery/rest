<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;

class BlogPublicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Del modelo descargamos todos los datos a mostrar en la entrada
        $blogs = Blog::select('id', 'title', 'content')->get();  


        //Si todavía no hay blogs
        if($blogs->isEmpty()){
            $data = [
                "message" =>  "Todavía no hay blogs para mostrar de manera pública", 
                "status" =>  404 
            ];

            return response()->json($data, 404); 
        }

        $data = [
            "blogs" =>  $blogs, 
            "status" =>  200 
        ]; 
        
        //Respondemos con un código 200 y enviamos los blogs del modelo
        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //Buscamos el blog
        $blog = Blog::find($id); 

        //Si no se encontró el blog 
        if(!$blog){
            $data = [
                "message" =>  "Blog no encontrado", 
                "status" =>  404 
            ];

            return response()->json($data, 404); 
        }

        //Del modelo descargamos los comentarios para el blog especificado
        $comments = Comment::where("blog_id", $id)->get(); 

        //Enviamos los detalles del elemento a mostrar
        $data = [
            "blog" => $blog, 
            "comments" => $comments, 
            "image_url" => "storage/".$blog->image,
            "status" => 200 
        ];

        return response()->json($data, 200); 
    }
}

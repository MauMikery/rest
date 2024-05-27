<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(String $id)
    {
        //Del modelo descargamos los comentarios para el blog especificado
        $comments = Comment::where("blog_id", $id)->get(); 

        //Si no hay comentarios 
        if($comments->isEmpty()) {
            $data = [
                'message' => 'Todavía no hay comentarios',
                'status' => 404
            ]; 

            return response()->json($data, 404);
        }

        $data = [
            "comments" => $comments, 
            "status" => 200
        ]; 

        return response()->json($data, 200); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, String $id)
    {  
        //Validamos que no nos hayan mandado datos erroneos
        $validator = Validator::make($request->all(), [
            'content' => 'required'
        ]);

        if($validator->fails()){
            $data = [ 
                "message" => "Error en la validación de los datos", 
                "errors" => $validator->errors(),
                "status" => 400
            ];

            return response()->json($data, 400); 
        }

        //Obtenemos el usuario
        $user = Auth::user(); 

        //Creamos el nuevo comentario
        $comment = Comment::make([
            "blog_id" => $id, 
            "author" => $user->email,
            "content" => $request->content
        ]);

        //Si no se pudo crear el usuario
        if(!$comment){
            $data = [
                'message' => 'Error al crear comentario', 
                'status' => 500
            ]; 
            return response()->json($data, 500); 
        }

        $comment->save(); 

        //Si se pudo crear el comentario 
        $data = [
            "message" => "Comentario guardado correctamente", 
            'comment' => $comment, 
            'status' => 201
        ]; 

        return response()->json($data, 201); 
    }
}

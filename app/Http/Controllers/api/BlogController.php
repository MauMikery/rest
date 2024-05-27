<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator; 

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Del modelo descargamos todos los datos
        $blogs = Blog::all();  

        $data = [
            "blogs" =>  $blogs, 
            "status" =>  200 
        ]; 
        
        //Respondemos con un código 200 y enviamos los blogs del modelo
        return response()->json($data, 200); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Tenemos que validar que nos hayan mandado datos
        $validator = Validator::make($request->all(), [
            'title'=> 'required|max:255', 
            'content' => 'required',
            'image' => 'image|dimensions:min_width=200,min_height=200'
        ]); 

        if($validator->fails()){
            $data = [
                'message' => 'Error en la validación de los datos', 
                'errors' => $validator->errors(),
                'status' => 400
            ]; 
            return response()->json($data, 400);
        }

        //Creamos un blog con el modelo
        $blog = new Blog($request->all());

        //Si se decidieron subir imagenes se crea la ruta de la imagen y se sube al servidor
        if($request->hasFile("image")){
            //Subir imagenes 
            $path = $request->image->store('images', 'public');
            $blog->image = $path; 
        }

        //Guardamos el blog en la base de datos
        $blog->save(); 

        //Si no se pudo crear el blog
        if(!$blog){
            $data = [
                'message' => 'Error al crear blog', 
                'status' => 500
            ]; 
            return response()->json($data, 500); 
        }

        $data = [
            "message" => "Post guardado correctamente", 
            'blog' => $blog, 
            'status' => 201
        ]; 

        return response()->json($data, 201); 
    }
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //Buscamos un blog
        $blog = Blog::find($id); 

        if(!$blog){
            $data = [
                'message' => 'Blog no encontrado',
                'status' => 404
            ]; 
            return response()->json($data, 404); 
        }

        $data = [
            'blog' => $blog, 
            'status' => 200
        ]; 

        return response()->json($data, 200);
    }

    /**
    * Display the public details of blog.
    */
    public function showPublicBlog(){
        //Del modelo descargamos todos los datos a mostrar en la entrada
        $blogs = Blog::select('id', 'title', 'content')->get();  

        $data = [
            "blogs" =>  $blogs, 
            "status" =>  200 
        ]; 
        
        //Respondemos con un código 200 y enviamos los blogs del modelo
        return response()->json($data, 200);
    }


    /**
    * Display the image resource.
    */
    public function showImage(Blog $blog)
    {
        return response()->download(public_path(Storage::url($blog->image)), $blog->title);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //Buscamos el blog especificado
        $blog = Blog::find($id); 

        //Si el blog no existe
        if(!$blog){
            $data = [
                "message" => "El blog no existe", 
                "status" => 404
            ]; 

            return response()->json($data, 404); 
        }

        //Validamos que no haya errores a la hora de enviar la peticion del usuario
        $validator = Validator::make($request->all(), [
            'title'=> 'required|max:255', 
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

        //Se actualizan los datos en el modelo 
        $blog->title = $request->input("title");
    
        $blog->content = $request->input("content");
    

        // Guardamos los cambios en la base de datos
        $blog->save();

        $data = [
            "message" => "Blog actualizado",
            "blog" => $blog,
            "status" => 200
        ]; 

        return response()->json($data, 200); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //Buscamos al blog
        $blog = Blog::find($id); 
        
        if(!$blog){
            $data = [
                "message" => "No se encontró el blog por eliminar", 
                "status" => 404 
            ]; 
            return response()->json($data, 404); 
        }
        
        //Borramos el blog
        $blog->delete(); 

        $data = [
            "message" => "El blog se ha eliminado correctamente", 
            "status" => 200,
        ]; 

        return response()->json($data, 200); 
    }
}

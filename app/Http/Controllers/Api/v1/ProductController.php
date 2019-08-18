<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\StoreUpdateProductFormRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private $product;
    private $totalPage = 10;
    private $path = 'products';

    public function __construct(Product $product){
        $this->product = $product;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = $this->product->getResults($request->all(),$this->totalPage);

        return response()->json($products);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateProductFormRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('image') && $request->file('image')->isValid()){

            $name = kebab_case($request->name);
            $extension = $request->image->extension();

            $nameFile = "{$name}.{$extension}";
            $data['image'] = $nameFile;

            $upload = $request->image->storeAs($this->path,$nameFile);

            if(!$upload)
                return response()->json(['error' => 'Fail_Upload'], 500);
        }

        $product = $this->product->create($data);

        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!$product = $this->product->with('category')->find($id))
           return response()->json(['error' => 'Not Found'], 404);

        return response()->json($product);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateProductFormRequest $request, $id)
    {
        if (!$product = $this->product->find($id))
            return response()->json(['error' => 'Not Found'], 404);

        $data = $request->all();
         /* 
         *Atualiza imagem
         */   
        if ($request->hasFile('image') && $request->file('image')->isValid()){
            if($product->image){
                if (Storage::exists("{$this->path}/{$product->image}"))
                    Storage::delete("{$this->path}/{$product->image}");
            }

            $name = kebab_case($request->name);
            $extension = $request->image->extension();

            $nameFile = "{$name}.{$extension}";
            $data['image'] = $nameFile;

            $upload = $request->image->storeAs($this->path,$nameFile);

            if(!$upload)
                return response()->json(['error' => 'Fail_Upload'], 500);
        }


        $product->update($data);

        return response()->json($product);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$product = $this->product->find($id))
            return response()->json(['error' => 'Not Found'], 404);

            if($product->image){
                if (Storage::exists("{$this->path}/{$product->image}"))
                        Storage::delete("{$this->path}/{$product->image}");
            }
            
        
        $product->delete();

        return response()->json(['success' => true],204);
    }

}

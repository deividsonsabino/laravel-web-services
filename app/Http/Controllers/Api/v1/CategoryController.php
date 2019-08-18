<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StoreUptadeCategoryFormRequest;

class CategoryController extends Controller
{
    private $totalPage = 5;
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /* 
    *Lista um ou mais registros 
    */

    public function index(Request $request)
    {
        $categories = $this->category->getResults($request->name);

        return response()->json($categories,200);
    }

    /* 
    * Mostra um unico registro
    */

    public function show($id)
    {
        if (!$category = $this->category->find($id))
            return response()->json(['error' => 'Not found'], 404);

        return response()->json($category->name);
    }


    /* 
    *Insere um registro
    */

    public function store(StoreUptadeCategoryFormRequest $request)
    {
        $category = $this->category->create($request->all());

        return response()->json($category, 201);
    }

    /* 
    *Atualiza um registro
    */

    public function update(StoreUptadeCategoryFormRequest $request, $id)
    {
        if (!$category = $this->category->find($id))
            return response()->json(['error' => 'Not found'], 404);

        $category->update($request->all());

        return response()->json($category);
    }
    /* 
    *Deleta um registro
    */

    public function destroy(Request $request, $id)
    {
        if (!$category = $this->category->find($id))
        return response()->json(['error' => 'Not found'], 404);

        $destroy = $category->delete($id);
        if ($destroy)
        return response()->json(['success' => "Categoria {$category->id} Excluída Com Sucesso "],402);
    }

    public function products($id)
    {
        if (!$category = $this->category->find($id))
        return response()->json(['error' => 'Not found'], 404);
       
        $products = $category->products()->paginate($this->totalPage);

        return response()->json([
            'category' => $category,
            'products' => $products
        ]);
    }
}

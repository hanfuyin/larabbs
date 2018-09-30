<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Transformers\CategoryTransformer;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::paginate(10);
        return $this->response->paginator($category, new CategoryTransformer());
    }
}

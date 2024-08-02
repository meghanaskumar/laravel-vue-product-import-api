<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $products = Product::where('user_id', $userId)->get();

        return response()->json($products, 200);
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $file = $request->file('file');
        $fileContents = file($file->getPathname());
        array_shift($fileContents);
        foreach ($fileContents as $line) {
            $data = str_getcsv($line);
            Product::create([
                'user_id' => Auth::id(),
                'name' => $data[0],
                'price' => $data[1],
                'sku' => $data[2],
                'description' => $data[3],
            ]);
        }

        return response()->json(['message' => 'Products imported successfully'], 200);
    }
}

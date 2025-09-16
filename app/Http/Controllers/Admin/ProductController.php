<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\ProductsDataTable;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

use App\Models\Product;

use function Pest\Laravel\call;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductsDataTable $dataTable)
    {
        return $dataTable->render('admin.products.index');
    }

    public function import(Request $request)
    {

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            $excelData = Excel::toArray(new ProductsImport, $request->file('excel_file'));
            $excelProductIds = collect($excelData[0])->pluck(0)->filter()->toArray();

            Excel::import(new ProductsImport, $request->file('excel_file'));

            Product::whereNotIn('id', $excelProductIds)->update(['stock' => 0]);

            return response()->json(['message' => 'Productos sincronizados exitosamente.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al importar: ' . $e->getMessage()], 422);
        }
    }

    public function showUploadImages()
    {
        return view('admin.products.upload-images');
    }

    public function uploadImages(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $uploadedImages = [];

        if ($request->hasFile('images')) {
            $manager = new ImageManager(new Driver());

            foreach ($request->file('images') as $image) {
                $filename = $image->getClientOriginalName();

                $processedImage = $manager->read($image)
                    ->scaleDown(800, height: 600);

                $extension = strtolower($image->getClientOriginalExtension());
                switch ($extension) {
                    case 'jpg':
                    case 'jpeg':
                        $processedImage = $processedImage->toJpeg(80);
                        break;
                    case 'png':
                        $processedImage = $processedImage->toPng();
                        break;
                    case 'gif':
                        $processedImage = $processedImage->toGif();
                        break;
                    default:
                        $processedImage = $processedImage->toJpeg(80);
                }

                $path = 'products/' . $filename;
                Storage::disk('public')->put($path, $processedImage);

                $uploadedImages[] = [
                    'filename' => $filename,
                    'path' => $path,
                    'url' => Storage::url($path)
                ];
            }
        }

        return response()->json([
            'message' => 'Imágenes cargadas exitosamente',
            'images' => $uploadedImages
        ]);
    }
}

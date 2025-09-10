<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Carousel;

use App\DataTables\CarouselsDataTable;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
    public function index(CarouselsDataTable $dataTable)
    {
        return $dataTable->render('admin.carousels.index');
    }

    public function show($id)
    {
        $carousel = Carousel::findOrFail($id);
        return response()->json($carousel);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        if ($request->hasFile('image')) {
            $manager = new ImageManager(new Driver());
            $image = $request->file('image');
            $filename = $image->getClientOriginalName();

            $processedImage = $manager->read($image)
                ->resize(1280, 350);

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

            $path = 'carousels/' . $filename;
            Storage::disk('public')->put($path, $processedImage);

            $carousel = Carousel::create(['img' => $filename]);

            return response()->json([
                'message' => 'Imagen del carousel agregada exitosamente',
                'carousel' => $carousel
            ]);
        }

        return response()->json(['error' => 'No se pudo procesar la imagen'], 422);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $carousel = Carousel::findOrFail($id);

        if ($request->hasFile('image')) {
            $manager = new ImageManager(new Driver());
            $image = $request->file('image');
            $filename = $image->getClientOriginalName();

            $processedImage = $manager->read($image)
                ->resize(1280, 350);
                
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

            if ($carousel->img && Storage::disk('public')->exists('carousels/' . $carousel->img)) {
                Storage::disk('public')->delete('carousels/' . $carousel->img);
            }

            $path = 'carousels/' . $filename;
            Storage::disk('public')->put($path, $processedImage);

            $carousel->update(['img' => $filename]);
        }

        return response()->json([
            'message' => 'Imagen del carousel actualizada exitosamente',
            'carousel' => $carousel
        ]);
    }

    public function destroy($id)
    {
        $carousel = Carousel::findOrFail($id);

        if ($carousel->img && Storage::disk('public')->exists('carousels/' . $carousel->img)) {
            Storage::disk('public')->delete('carousels/' . $carousel->img);
        }

        $carousel->delete();

        return response()->json(['message' => 'Imagen del carousel eliminada exitosamente']);
    }
}

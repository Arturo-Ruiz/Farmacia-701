<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ad;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    public function index()
    {
        $ads = Ad::orderBy('created_at', 'asc')->get();
        return view('admin.ads.index', compact('ads'));
    }

    public function show($id)
    {
        $ad = Ad::findOrFail($id);
        return response()->json($ad);
    }

    public function uploadImages(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $uploadedImages = [];

        if ($request->hasFile('images')) {
            $manager = new ImageManager(new Driver());

            foreach ($request->file('images') as $image) {
                $filename = $image->getClientOriginalName();

                $processedImage = $manager->read($image)
                    ->scaleDown(500, 500);

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

                $path = 'ads/' . $filename;

                Storage::disk('public')->put($path, $processedImage);

                $ad = Ad::create(['img' => $filename]);

                $uploadedImages[] = [
                    'id' => $ad->id,
                    'filename' => $filename,
                    'path' => $path,
                    'url' => Storage::url($path)
                ];
            }
        }

        return response()->json([
            'message' => 'Anuncios cargados exitosamente',
            'images' => $uploadedImages
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $ad = Ad::findOrFail($id);

        if ($request->hasFile('image')) {
            $manager = new ImageManager(new Driver());
            $image = $request->file('image');
            $filename = $image->getClientOriginalName();

            $processedImage = $manager->read($image)
                ->scaleDown(500, 500);

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

            if ($ad->img && Storage::disk('public')->exists('ads/' . $ad->img)) {
                Storage::disk('public')->delete('ads/' . $ad->img);
            }

            $path = 'ads/' . $filename;
            Storage::disk('public')->put($path, $processedImage);

            $ad->update(['img' => $filename]);
        }

        return response()->json([
            'message' => 'Anuncio actualizado exitosamente',
            'ad' => $ad
        ]);
    }

    public function destroy($id)
    {
        $ad = Ad::findOrFail($id);

        // Corregir la ruta del archivo  
        if ($ad->img && Storage::disk('public')->exists('ads/' . $ad->img)) {
            Storage::disk('public')->delete('ads/' . $ad->img);
        }

        $ad->delete();

        return response()->json(['message' => 'Publicidad eliminada exitosamente']);
    }
}

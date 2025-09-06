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
        $ads = Ad::latest()->get();
        return view('admin.ads.index', compact('ads'));
    }

    public function showUploadImages()
    {
        return view('admin.ads.upload-images');
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

                $ad = Ad::create(['img' => $path]);

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

    public function destroy($id)
    {
        $ad = Ad::findOrFail($id);

        if ($ad->img && Storage::disk('public')->exists($ad->img)) {
            Storage::disk('public')->delete($ad->img);
        }

        $ad->delete();

        return response()->json(['message' => 'Publicidad eliminada exitosamente']);
    }
}

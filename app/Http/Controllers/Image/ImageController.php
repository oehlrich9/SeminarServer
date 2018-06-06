<?php

namespace App\Http\Controllers\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Models\Image;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Device\DeviceController;

use App\Jobs\AnalyseImage;

use DateTime, DateTimeZone;


class ImageController extends Controller
{
    public function uploadImage(Request $request){
        if(!$request->has('dateTimeTaken')){
            return response()->json(["error" => "dateTimeTaken not set"], 400);
        }
        if(!$request->has('dateTimeFormat')){
            return response()->json(["error" => "dateTimeFormat not set, requires format in the form of http://php.net/manual/de/function.date.php"], 400);
        }
        if(!$request->has('dateTimeTimezone')){
            return response()->json(["error" => "dateTimeTimezone not set, requires to be like  UTC, GMT, Atlantic/Azores or +0200 or +02:00 or EST, MDT, etc."], 400);
        }
        if(!$request->hasFile('image')){
            return response()->json(["error" => "image not given, is not a file or upload failed"], 400);
        }
        $now = new DateTime();
        $path = $request->image->store('images');
        $complete_Path = __DIR__.'/../../../../storage/app/'.$path;
        $image = new Image();
        $image->analized = false;
        $image->emotion = null;
        $image->path = $path;
        if(!is_null(DeviceController::device())){
            $image->device()->associate(DeviceController::device());
        }
        $image->dateTimeTaken = DateTime::createFromFormat($request->get('dateTimeFormat'), $request->get('dateTimeTaken'), new DateTimeZone($request->get('dateTimeTimezone')));
        Auth::user()->images()->save($image);
        $this->analyseImage($image);
        return response()->json(["success" => $image], 201);
    }

    public function getImages(){
        $images = Auth::user()->images()->with('device')->get();
        return response()->json(["images" => $images], 200);
    }

    public function getImageDetail(Request $request, $id){
        if(!is_numeric($id)){
            return response()->json(['error' => 'ID must be numerical'], 400);
        }
        $image = Auth::user()->images()->with('emotion')->findOrFail($id);
        return response()->json(["image" => $image], 200);
    }

    public function getImage(Request $request, $id){
        if(!is_numeric($id)){
            return response()->json(['error' => 'ID must be numerical'], 400);
        }
        $image = Auth::user()->images()->findOrFail($id);
        return Storage::download($image->path);
    }

    public function deleteImage(Request $request, $id){
        if(!is_numeric($id)){
            return response()->json(['error' => 'ID must be numerical'], 400);
        }
        $image = Auth::user()->images()->findOrFail($id);
        Storage::delete($image->path);
        $image->delete();
        return response()->json(["success" => "Image removed"], 200);
    }

    private function analyseImage($image){
        $this->dispatch(new AnalyseImage($image));
    }
}

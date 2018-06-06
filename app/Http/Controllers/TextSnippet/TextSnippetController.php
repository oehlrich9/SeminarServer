<?php

namespace App\Http\Controllers\TextSnippet;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


use App\Http\Controllers\Device\DeviceController;
use App\Models\TextSnippet;

use App\Jobs\AnalyseTextSnippet;


class TextSnippetController extends Controller
{
    public function insertTextSnippet(Request $request){
        $errors = Validator::make($request->all(), [
            'snippet' => 'required|string'
        ])->errors();
        if(count($errors)){
            return response()->json(['error' => $errors], 400);
        }
        $snippet = new textSnippet();
        $snippet->snippet = $request->get('snippet');
        if(!is_null(DeviceController::device())){
            $snippet->device()->associate(DeviceController::device());
        }
        Auth::user()->textSnippets()->save($snippet);
        $this->analyseTextSnippet($snippet);
        return response()->json(['textSnippet' => $snippet], 200);
    }

    public function getTextSnippets(){
        $snippets = Auth::user()->textSnippets()->with('device')->get();
        return response()->json(["textSnippets" => $snippets], 200);
    }

    public function getTextSnippet(Request $request, $id){
        if(!is_numeric($id)){
            return response()->json(['error' => 'ID must be numerical'], 400);
        }
        $snippet = Auth::user()->textSnippets()->findOrFail($id);
        return response()->json(["textSnippet" => $snippet], 200);
    }

    public function deleteTextSnippet(Request $request, $id){
        if(!is_numeric($id)){
            return response()->json(['error' => 'ID must be numerical'], 400);
        }
        Auth::user()->textSnippets()->findOrFail($id)->delete();
        return response()->json(["success" => "textSnipped removed"], 200);
    }

    private function analyseTextSnippet(TextSnippet $textSnippet){
        $this->dispatch(new AnalyseTextSnippet($textSnippet));
    }
}

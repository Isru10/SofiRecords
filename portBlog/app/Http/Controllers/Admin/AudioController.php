<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Audio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AudioController extends Controller
{
    public function index(){
        $data=Audio::all();
        return response()->json(["audios"=>$data]);


    }
    public function store(Request $request){
        $data = new Audio();
        $file=$request->file;
        $filename=time().'.'. $file->getClientOriginalExtension();
        $request->file->move('AUDIO',$filename);
        $data->file = $filename;
        $data->name=$request->name;
        $data->description=$request->description;
        $data->save();
        return response()->json(["audio"=>$data]);
    }
    public function download(Request $request,$file){
            return response()->download(public_path('AUDIO/'.$file));
    }
    public function view($id){
        $data = Audio::find($id);
        return response()->json(["audio"=>$data]);
}


        public function destroy($id){
            $audio = Audio::findOrFail($id);
        $destination = public_path('storage\\' . $audio->file);

        if (File::exists($destination)) {
            File::delete($destination);
        }

        $result = $audio->delete();
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => "Audio Delete Successfully",
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Some Problem",
            ]);
        }
        }



}

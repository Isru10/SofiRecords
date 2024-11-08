<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index(){

        try {
            $projects = Project::orderBy('id', 'desc')->get();
            if ($projects) {
                return response()->json([
                    'success' => true,
                    'projects' => $projects
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }

    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'experience' => 'required|string',
        ]);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('project', 'public');
            } else {
                $imagePath = null;
            }

            $project = Project::create([
                'title' => $request->title,
                'experience' => $request->experience,
                'image' => $imagePath,
                'description'=>$request->description,
                   ]);
    
                return response()->json($project, 201);
    }


    public function show($id)
    {
        $project = Project::findorfail($id);
        return $project;
    }


    public function edit($id)
    {
        $project=Project::find($id);
        if($project){
            return response()->json(["projects"=>$project,"status"=>200],200);
        }
        else{
            return response()->json(["message"=>"no such project!","status"=>404],404);
        }
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image' => 'nullable',
            'experience' => 'required|string',
 ]);
        $project = Project::find($id);
        if ($request->hasFile('image')) {
            // Delete the old image
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $imagePath = $request->file('image')->store('project', 'public');
        }
        
            else {
            $imagePath = $project->image;
        }

        $project->update([
            'title' => $request->title,
            'experience' => $request->experience,
            'image' => $imagePath,
            'description'=>$request->description,
    ]);

        return response()->json($project);

    }

    public function destroy($id)
    {
        $project = Project::find($id);
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
        $project->delete();

        return response()->json("deleted project", 204);
    }
    public function numproj(){
        $project=Project::count();
        return response()->json([
            'success' => true,
            'project' =>$project,
        ]);
}

}

<?php

namespace Modules\Projects\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Projects\Models\ProjectTemplate;
use DateTime;


class ProjectTemplateController extends Controller
{
    public function index()
    {
        $templates = ProjectTemplate::all();
        return response()->json($templates);
    }

    public function store(Request $request)
    {

        if ($request->hasFile('file')) {
            $user_id = \Auth::id();
            // --
            // Image file
            $size = $request->file('file')->getClientSize();
            $fileName = $request->file('file')->getClientOriginalName();
            $fileExt = $request->file('file')->getClientOriginalExtension();
            $fileBaseName = basename(
                $request->file('file')->getClientOriginalName(),
                '.'.$request->file('file')->getClientOriginalExtension()
            );
            if ($fileName == '-1') {
                 $uniqueFileName = '-1';
            } else {
                 $uniqueFileName = $request->input('templateName').'_'.uniqid().'.'.$fileExt;
            }
            $folder = $request->input('folder');
            $request->file('file')->move(public_path("/uploads/project_templates"), $uniqueFileName);
            

            // Check if template with the given name exists
            $existingTemplate = ProjectTemplate::where('template_name', $request->input('templateName'))->first();

             if ($existingTemplate) {
                $creationTime = new DateTime($existingTemplate->created_at);
                $currentTime = new DateTime();
                $interval = $currentTime->diff($creationTime);
                $intervalFlag = $interval->s <= 5 && $interval->i == 0 && $interval->h == 0 && $interval->d == 0;
 

                if($intervalFlag) {
                    $tasks = json_decode($existingTemplate->tasks, true);
                    $tasks[$request->input('taskName')] = $uniqueFileName; // Add new task
                    $existingTemplate->tasks = json_encode($tasks);
                    $existingTemplate->save();
                }
                else{
                    return response()->json(['error' => 'Template with the same name already exists.'], 400);
                }

            } else {
                // If template does not exist, create a new one
                $template = ProjectTemplate::create([
                    'template_name' => $request->input('templateName'),
                    'tasks' => json_encode([$request->input('taskName') => $uniqueFileName]),
                ]);

                return response()->json($template, 201);
            }
             
        }
    }

    public function show($id)
    {
        $template = ProjectTemplate::findOrFail($id);
        $tasks = json_decode($template->tasks, true);
    
        // Loop through each task and attach the corresponding image name
        foreach ($tasks as $taskName => &$imageFileName) {
            // Get the image path from /uploads/project_templates
            $imagePath = '/uploads/project_templates/' . $imageFileName;
    
            // Check if the image file exists
            if (file_exists(public_path($imagePath))) {
                // Attach the image path to the task
                $tasks[$taskName] = [
                    'task' => $taskName,
                    'image' => $imagePath
                ];
            } else {
                // If image file doesn't exist, set image path to null
                $tasks[$taskName] = [
                    'task' => $taskName,
                    'image' => null
                ];
            }
        }
    
        // Return the modified template with tasks and associated images
        return response()->json([
            'template_name' => $template->template_name,
            'tasks' => $tasks
        ]);
    
    }

    public function update(Request $request, $id)
    {
        $template = ProjectTemplate::findOrFail($id);
        $template->update($request->all());
        return response()->json($template, 200);
    }

    public function destroy($id)
    {
         $template = ProjectTemplate::find($id);

         if ($template) {
            $tasks = json_decode($template->tasks, true);
            $imageNames = array_values($tasks);
            
            foreach ($imageNames as $imageName) {
                if(\File::exists(public_path('/uploads/project_templates/'.$imageName))){
                    \File::delete(public_path('/uploads/project_templates/'.$imageName));
                }
            }
    
            // Delete the template
            $template->delete();
            return response()->json(['message' => 'Template deleted successfully.'], 200);
        } 
        else {
            return response()->json(['message' => 'Template not found.'], 404);
        }
    }

    //get template by id
    public function getTemplateById($id)
    {
        $template = ProjectTemplate::findOrFail($id);
        return response()->json($template);
    }
}

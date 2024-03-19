<?php

namespace Modules\Projects\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Projects\Models\ProjectTemplate;


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
                 $uniqueFileName = uniqid().'.'.$fileExt;
            }
            $folder = $request->input('folder');
            $request->file('file')->move(public_path("/uploads/project_templates"), $uniqueFileName);
            
            if (!(\File::exists(public_path('/uploads/filebrowser/'.'index.php')))) {
                \File::put(public_path('/uploads/filebrowser/'.'index.php'), "");
            }


            // Check if template with the given name exists
            $existingTemplate = ProjectTemplate::where('template_name', $request->input('templateName'))->first();

            if ($existingTemplate) {
                // If template exists, update its tasks with the new task information
                $tasks = json_decode($existingTemplate->tasks, true);
                $tasks[$request->input('taskName')] = $uniqueFileName; // Add new task
                $existingTemplate->tasks = json_encode($tasks);
                $existingTemplate->save();
            } else {
                // If template does not exist, create a new one
                $template = ProjectTemplate::create([
                    'template_name' => $request->input('templateName'),
                    'tasks' => json_encode([$request->input('taskName') => $uniqueFileName]),
                ]);
            }
             
        }
    }

    public function show($id)
    {
        $template = ProjectTemplate::findOrFail($id);
        return response()->json($template);
    }

    public function update(Request $request, $id)
    {
        $template = ProjectTemplate::findOrFail($id);
        $template->update($request->all());
        return response()->json($template, 200);
    }

    public function destroy($id)
    {
        $template = ProjectTemplate::findOrFail($id);
        $template->delete();
        return response()->json(null, 204);
    }

    //get template by id
    public function getTemplateById($id)
    {
        $template = ProjectTemplate::findOrFail($id);
        return response()->json($template);
    }
}

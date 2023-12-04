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
        $template = ProjectTemplate::create($request->all());
        return response()->json($template, 201);
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

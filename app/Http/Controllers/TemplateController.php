<?php
// app/Http/Controllers/TemplateController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    // Get all templates
    public function index()
    {
        $templates = DB::table('medical_templates')->get();

        return view('adminDashboard.template-builder', compact('templates'));
    }

    public function create()
    {
        return view('templates.create');
    }

    public function storeTemp(Request $request)
    {


        $request->validate([
            'name' => 'required|string|max:255',
            'html_content' => 'required|string',
        ]);

        DB::table('medical_templates')->insert([
            'name' => $request->name,
            'html_content' => $request->html_content,
            'created_by' => 1,
            'is_active' => true,
        ]);

        return redirect()->route('templates.index')
            ->with('success', 'Template created successfully!');
    }

    public function viewtemp($id)
    {

        $template = DB::table('medical_templates')->where('id', $id)->first();
        return view('adminDashboard.viewTemp', compact('template'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Lead;
use App\Models\Product;
use App\Models\Approval;

class ProjectController
{
    public function index()
    {
        $projects = Project::with('lead','product')->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $leads = Lead::all();
        $products = Product::all();
        return view('projects.create', compact('leads','products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(["lead_id"=>"required","product_id"=>"nullable","estimated_fee"=>"nullable"]);
        $project = Project::create($data + ['status'=>'pending','manager_approval'=>false]);
        return redirect()->route('projects.index');
    }

    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    public function approve(Request $request, Project $project)
    {
        $userId = session('user_id');
        $approved = $request->input('approved') == '1';
        $project->manager_approval = $approved;
        $project->status = $approved ? 'approved' : 'rejected';
        $project->manager_id = $userId;
        $project->approval_notes = $request->input('notes');
        $project->save();

        Approval::create(['project_id'=>$project->id,'approved_by'=>$userId,'approved'=>$approved,'notes'=>$request->input('notes'),'created_at'=>now()]);
        return redirect()->route('projects.index');
    }
}

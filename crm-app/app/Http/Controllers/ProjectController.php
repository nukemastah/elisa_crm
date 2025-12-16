<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Lead;
use App\Models\Product;
use App\Models\Approval;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Carbon;

class ProjectController
{
    public function index()
    {
        $projects = Project::with('lead','product')->get();
        return View::make('projects.index', compact('projects'));
    }

    public function create()
    {
        $leads = Lead::all();
        $products = Product::all();
        return View::make('projects.create', compact('leads','products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(["lead_id"=>"required","product_id"=>"nullable","estimated_fee"=>"nullable"]);
        $project = Project::create($data + ['status'=>'pending','manager_approval'=>false]);
        return Redirect::route('projects.index');
    }

    public function show(Project $project)
    {
        return View::make('projects.show', compact('project'));
    }

    public function approve(Request $request, Project $project)
    {
        $userId = $request->session()->get('user_id');
        $approved = $request->input('approved') == '1';
        $project->manager_approval = $approved;
        $project->status = $approved ? 'approved' : 'rejected';
        $project->manager_id = $userId;
        $project->approval_notes = $request->input('notes');
        $project->save();

        Approval::create(['project_id'=>$project->id,'approved_by'=>$userId,'approved'=>$approved,'notes'=>$request->input('notes'),'created_at'=>Carbon::now()]);
        return Redirect::route('projects.index');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return Redirect::route('projects.index');
    }
}

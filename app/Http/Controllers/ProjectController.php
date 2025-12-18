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
        $q = request('q');
        $sort = request('sort','id');
        $dir = request('dir','desc');
        $allowedSorts = ['id','estimated_fee','status'];
        if (!in_array($sort, $allowedSorts)) { $sort = 'id'; }
        $dir = strtolower($dir) === 'asc' ? 'asc' : 'desc';

        $query = Project::with('lead','product');
        if ($q) {
            $query->where(function($builder) use ($q) {
                $builder->where('status','ILIKE',"%$q%")
                        ->orWhereHas('lead', function($q2) use ($q) { $q2->where('name','ILIKE',"%$q%"); })
                        ->orWhereHas('product', function($q3) use ($q) { $q3->where('name','ILIKE',"%$q%"); });
            });
        }

        $projects = $query->orderBy($sort,$dir)->paginate(10)->appends(request()->query());
        return View::make('projects.index', compact('projects','q','sort','dir'));
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
        return Redirect::route('projects.index')->with('success','Project created');
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
        return Redirect::route('projects.index')->with('success', $approved ? 'Project approved' : 'Project rejected');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return Redirect::route('projects.index')->with('success','Project deleted');
    }
}

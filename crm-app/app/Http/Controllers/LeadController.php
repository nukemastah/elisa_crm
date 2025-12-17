<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class LeadController
{
    public function index()
    {
        $q = request('q');
        $sort = request('sort', 'id');
        $dir = request('dir', 'desc');

        $allowedSorts = ['id','name','phone','email','source','status'];
        if (!in_array($sort, $allowedSorts)) { $sort = 'id'; }
        $dir = strtolower($dir) === 'asc' ? 'asc' : 'desc';

        $query = Lead::query();
        if ($q) {
            $query->where(function($builder) use ($q) {
                $builder->where('name','ILIKE',"%$q%")
                        ->orWhere('phone','ILIKE',"%$q%")
                        ->orWhere('email','ILIKE',"%$q%")
                        ->orWhere('source','ILIKE',"%$q%")
                        ->orWhere('status','ILIKE',"%$q%");
            });
        }

        $leads = $query->orderBy($sort, $dir)->paginate(10)->appends(request()->query());
        return View::make('leads.index', compact('leads','q','sort','dir'));
    }

    public function create()
    {
        return View::make('leads.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(["name"=>"required","phone"=>"nullable","email"=>"nullable","address"=>"nullable","source"=>"nullable"]);
        Lead::create($data + ['status'=>'new']);
        return Redirect::route('leads.index')->with('success','Lead created');
    }

    public function show(Lead $lead)
    {
        return View::make('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        return View::make('leads.edit', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        $lead->update($request->only(['name','phone','email','address','source','status']));
        return Redirect::route('leads.index')->with('success','Lead updated');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return Redirect::route('leads.index')->with('success','Lead deleted');
    }
}

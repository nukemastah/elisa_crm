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
        $leads = Lead::all();
        return View::make('leads.index', compact('leads'));
    }

    public function create()
    {
        return View::make('leads.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(["name"=>"required","phone"=>"nullable","email"=>"nullable","address"=>"nullable","source"=>"nullable"]);
        Lead::create($data + ['status'=>'new']);
        return Redirect::route('leads.index');
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
        return Redirect::route('leads.index');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return Redirect::route('leads.index');
    }
}

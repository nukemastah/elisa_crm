<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;

class LeadController
{
    public function index()
    {
        $leads = Lead::all();
        return view('leads.index', compact('leads'));
    }

    public function create()
    {
        return view('leads.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(["name"=>"required","phone"=>"nullable","email"=>"nullable","address"=>"nullable","source"=>"nullable"]);
        Lead::create($data + ['status'=>'new']);
        return redirect()->route('leads.index');
    }

    public function show(Lead $lead)
    {
        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        return view('leads.edit', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        $lead->update($request->only(['name','phone','email','address','source','status']));
        return redirect()->route('leads.index');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('leads.index');
    }
}

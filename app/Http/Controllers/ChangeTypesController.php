<?php

namespace App\Http\Controllers;

use App\ChangeType;
use App\Http\Requests\ChangeTypes\CreateChangeTypeRequest;
use App\Http\Requests\ChangeTypes\UpdateChangeTypeRequest;
use Illuminate\Http\Request;

class ChangeTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("change-types.index")->with("changeTypes", ChangeType::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("change-types.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateChangeTypeRequest $request)
    {
        ChangeType::create([
            "name" => $request->name
        ]);

        session()->flash("success", "Change Type created successfully");

        return redirect(route("change-types.index"));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ChangeType $changeType)
    {
        return view("change-types.create")->with("changeType", $changeType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateChangeTypeRequest $request, ChangeType $changeType)
    {
        $data = $request->only("name");
        $changeType->update($data);

        session()->flash("success", "Change Type updated successfully");

        return redirect(route("change-types.index"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChangeType $changeType)
    {
        // $changeType->delete();

        // session()->flash("success", "Change Type deleted successfully");

        // return redirect(route("change-types.index"));
    }
}

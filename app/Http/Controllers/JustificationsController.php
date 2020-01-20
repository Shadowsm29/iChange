<?php

namespace App\Http\Controllers;

use App\Http\Requests\Justifications\CreateJustificationRequest;
use App\Http\Requests\Justifications\UpdateJustificationRequest;
use App\Justification;
use Illuminate\Http\Request;

class JustificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("justifications.index")->with("justifications", Justification::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("justifications.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateJustificationRequest $request)
    {
        Justification::create([
            "name" => $request->name
        ]);

        session()->flash("success", "Justification created successfully");

        return redirect(route("justifications.index"));
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
    public function edit(Justification $justification)
    {
        return view("justifications.create")->with("justification", $justification);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateJustificationRequest $request, Justification $justification)
    {
        $data = $request->only("name");
        $justification->update($data);

        session()->flash("success", "Justification updated successfully");

        return redirect(route("justifications.index"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Justification $justification)
    {
        $justification->delete();

        session()->flash("success", "Justification deleted successfully");

        return redirect(route("justifications.index"));
    }
}

<?php

namespace App\Http\Controllers;

use App\Circle;
use App\Http\Requests\Supercircles\CreateSupercircleRequest;
use App\Http\Requests\Supercircles\UpdateSupercircleRequest;
use App\Supercircle;
use Illuminate\Http\Request;

class SupercirclesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("supercircles.index")->with("supercircles", Supercircle::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("supercircles.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSupercircleRequest $request)
    {
        Supercircle::create([
            "name" => $request->name
        ]);

        session()->flash("success", "Supercircle created successfully");

        return redirect(route("supercircles.index"));
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
    public function edit(Supercircle $supercircle)
    {
        return view("supercircles.create")
            ->with("supercircle", $supercircle)
            ->with("circles", Circle::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSupercircleRequest $request, Supercircle $supercircle)
    {
        $data = $request->only("name");
        $supercircle->update($data);

        session()->flash("success", "Supercircle updated successfully");

        return redirect(route("supercircles.index"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supercircle $supercircle)
    {
        if($supercircle->circles->count() > 0) {
            session()->flash("error", "Unable to delete, supercircle is still associated with circles.");
            return redirect()->back();
        }

        $supercircle->delete();

        session()->flash("success", "Supercircle deleted successfully");

        return redirect(route("supercircles.index"));
    }
}

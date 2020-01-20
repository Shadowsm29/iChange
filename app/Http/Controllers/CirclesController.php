<?php

namespace App\Http\Controllers;

use App\Circle;
use App\Http\Requests\Circles\CreateCircleRequest;
use App\Http\Requests\Circles\UpdateCircleRequest;
use App\Supercircle;
use Illuminate\Http\Request;

class CirclesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("circles.index")->with("circles", Circle::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("circles.create")->with("supercircles", Supercircle::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCircleRequest $request)
    {

        // dd($request->supercircle);
        Circle::create([
            "name" => $request->name,
            "supercircle_id" => $request->supercircle,
        ]);

        session()->flash("success", "Circle added successfully");

        return redirect(route("circles.index"));
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
    public function edit(Circle $circle)
    {
        return view("circles.create")
        ->with("circle", $circle)
        ->with("supercircles", Supercircle::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCircleRequest $request, Circle $circle)
    {
        $circle->update([
            "name" => $request->name,
            "supercircle_id" => $request->supercircle
        ]);

        session()->flash("success", "Circle updated successfully");

        return redirect(route("circles.index"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Circle $circle)
    {
        $circle->delete();

        session()->flash("success", "Circle deleted successfully");

        return redirect(route("circles.index"));
    }
}

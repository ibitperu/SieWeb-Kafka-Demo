<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LibretaNota;
use App\Models\User;
use Illuminate\Http\Request;

class LibretaNotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LibretaNota  $libretaNota
     * @return \Illuminate\Http\Response
     */
    public function show(LibretaNota $libretaNota)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LibretaNota  $libretaNota
     * @return \Illuminate\Http\Response
     */
    public function edit(LibretaNota $libretaNota)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LibretaNota  $libretaNota
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LibretaNota $libretaNota)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LibretaNota  $libretaNota
     * @return \Illuminate\Http\Response
     */
    public function destroy(LibretaNota $libretaNota)
    {
        //
    }
}

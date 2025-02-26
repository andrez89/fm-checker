<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDatabaseRequest;
use App\Http\Requests\UpdateDatabaseRequest;
use App\Models\Database;

class DatabaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDatabaseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Database $database)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Database $database)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDatabaseRequest $request, Database $database)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Database $database)
    {
        //
    }
}

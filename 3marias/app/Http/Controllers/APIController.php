<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

interface APIController {
    /**
     * Gets all resources.
     */
    public function index();

    /**
     * Destroy a resource.
     */
    public function destroy($id);

    /**
     * Gets a single resource.
     */
    public function show($id);

    /**
     * Creates a new resource.
     */
    public function create(Request $request);

    /**
     * Creates a new resource.
     */
    public function store(Request $request);

    /**
     * Updates a resource.
     */
    public function update(Request $request, $id);
}
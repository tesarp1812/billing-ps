<?php

namespace App\Http\Controllers;

use App\Models\NamaCustomer;
use Illuminate\Http\Request;

class NamaCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            NamaCustomer::select('nama', 'gender', 'customer')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(NamaCustomer $namaCustomer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NamaCustomer $namaCustomer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NamaCustomer $namaCustomer)
    {
        //
    }
}

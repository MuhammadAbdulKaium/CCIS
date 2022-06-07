<?php

namespace Modules\Canteen\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CanteenController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }


    public function index()
    {
        return view('canteen::index');
    }



    public function create()
    {
        return view('canteen::create');
    }



    public function store(Request $request)
    {
        //
    }



    public function show($id)
    {
        return view('canteen::show');
    }



    public function edit($id)
    {
        return view('canteen::edit');
    }



    public function update(Request $request, $id)
    {
        //
    }



    public function destroy($id)
    {
        //
    }
}

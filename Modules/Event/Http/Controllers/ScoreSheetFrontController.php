<?php

namespace Modules\Event\Http\Controllers;
use App;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ScoreSheetFrontController extends Controller
{
    public function swimmingForm(){
        return view('event::score-sheet.swimming-form');
    }
    public function swimmingFormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.swimming-form-pdf')->setPaper('a4', 'landscape');
        return $pdf->stream();

    }
    public function swimmingFinal(){
        return view('event::score-sheet.swimming-final');
    }
    public function swimmingFinalPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.swimming-final-pdf')->setPaper('a4', 'landscape');
        return $pdf->stream();

    }

    public function cricket(){
        return view('event::score-sheet.cricket-score-sheet');
    }
    public function cricketFormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.cricket-form-pdf')->setPaper('a4', 'landscape');
        return $pdf->stream();

    }
    //cricket final
    public function cricketFinal(){
        return view('event::score-sheet.cricket-final');
    }
    public function cricketFinalPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.cricket-final-pdf')->setPaper('a4', 'portrait');
        return $pdf->stream();

    }



    public function basketballForm(){
        return view('event::score-sheet.basketball-form');
    }
    public function basketballFormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.basketball-form-pdf')->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    public function footballForm(){
        return view('event::score-sheet.football-form');
    }
    public function footballFormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.football-form-pdf')->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    //volleyball
    public function volleyballForm(){
        return view('event::score-sheet.volleyball-form');
    }
    public function volleyballFormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.volleyball-form-pdf')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

    //football final Score sheet


    public function footballballFinalForm(){
        return view('event::score-sheet.football-final');
    }
    public function footballballFinalFormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.football-final-pdf')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

    public function crossCountryForm(){
        return view('event::score-sheet.cross-country-form');
    }
    public function crossCountryFormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.cross-country-form-pdf')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
    public function crossCountryFinal(){
        return view('event::score-sheet.cross-country-final');
    }
    public function crossCountryFinalPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.cross-country-final-pdf')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
    //drill form

    public function drillForm(){
        return view('event::score-sheet.drill-form');
    }
    public function drillFormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.drill-form-pdf')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
    public function drillFinal(){
        return view('event::score-sheet.drill-final');
    }
    public function drillFinalPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.drill-final-pdf')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
    //obstacle Form
    public function obstacleForm(){
        return view('event::score-sheet.obstacle-form');
    }
    public function obstacleFormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.obstacle-form-pdf')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
    public function obstacleFinal(){
        return view('event::score-sheet.obstacle-final');
    }
    public function obstacleFinalPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.obstacle-final-pdf')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
//Quiz Form Function
    public function quizForm(){
        return view('event::score-sheet.quiz-form');
    }
    public function quizFormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.quiz-form-pdf')->setPaper('a4', 'landscape');
        return $pdf->stream();
    }
//Wall Magazine
    public function wallMagazineForm(){
        return view('event::score-sheet.wall-magazine');
    }
    public function wallMagazineFormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.wall-magazine-pdf')->setPaper('a4', 'landscape');
        return $pdf->stream();
    }
//Math Olympiad
    public function mathOlympiadForm(){
        return view('event::score-sheet.math-olympiad-form');
    }
    public function mathOlympiadFormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.math-olympiad-form-pdf')->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    public function tableTennisForm(){
        return view('event::score-sheet.table-tennis');
    }
    public function tableTennisFormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.table-tennis-pdf')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
    public function chessForm(){
        return view('event::score-sheet.chess-form');
    }
    public function chessFormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.chess-form-pdf')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
    public function carromForm(){
        return view('event::score-sheet.carrom-score-sheet');
    }
    public function carromFormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.carrom-score-sheet-pdf')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
    public function allOtherCSCForm(){
        return view('event::score-sheet.all-other-csc');
    }
    public function allOtherCSCFormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.all-other-csc-pdf')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
    public function athleticType1Form(){
        return view('event::score-sheet.athletic-type-1');
    }
    public function athleticType1FormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.athletic-type-1-pdf')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
    public function athleticType2Form(){
        return view('event::score-sheet.athletic-type-2');
    }
    public function athleticType2FormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.athletic-type-2-pdf')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
    public function athleticType3Form(){
        return view('event::score-sheet.athletic-type-3');
    }
    public function athleticType3FormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.athletic-type-3-pdf')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

    public function athleticType4Form(){
        return view('event::score-sheet.athletic-type-4');
    }
    public function athleticType4FormPdf(){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('event::score-sheet.athletic-type-4-pdf')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('event::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('event::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('event::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('event::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}

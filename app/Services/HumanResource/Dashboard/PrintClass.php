<?php

namespace App\Services\HumanResource\Dashboard;

class PrintClass
{
    protected $top;

    public function __construct(TopClass $top)
    {
        $this->top = $top;
    }

    public function tardiness($request)
    {
        $year  = $request->year ?? date('Y');
        $month = $request->month ?? date('F');

        $pdf = \PDF::loadView('prints.tardiness', [
            'groups' => $this->top->tardinessReport($request)['groups'],
            'month' => $month,
            'year' => $year,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Tardiness-'.$month.'-'.$year.'.pdf');
    }

    public function absences($request)
    {
        $year  = $request->year ?? date('Y');
        $month = $request->month ?? date('F');

        $pdf = \PDF::loadView('prints.absences', [
            'groups' => $this->top->absencesReport($request),
            'month' => $month,
            'year' => $year,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Absences-'.$month.'-'.$year.'.pdf');
    }
}

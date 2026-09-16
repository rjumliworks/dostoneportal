<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Services\Procurement\ProcurementClass;
use Illuminate\Http\Request;

class ProcurementDashboardController extends Controller
{
    public function __construct(protected ProcurementClass $procurement)
    {
    }

    public function index(Request $request)
    {
        return inertia('Modules/Procurement/Dashboard', $this->procurement->dashboardPageProps());
    }
}

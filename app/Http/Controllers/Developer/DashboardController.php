<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $dbStatus = 'Tidak terhubung';
        $dbError = null;

        try {
            DB::connection()->getPdo();
            $dbStatus = 'Terhubung ('.config('database.default').' / '.config('database.connections.mysql.database').')';
        } catch (\Throwable $e) {
            $dbError = $e->getMessage();
        }

        return view('developer.dashboard', [
            'appVersion' => config('app.version', '1.0.0'),
            'phpVersion' => PHP_VERSION,
            'laravelVersion' => app()->version(),
            'dbStatus' => $dbStatus,
            'dbError' => $dbError,
            'environment' => config('app.env'),
            'debugMode' => config('app.debug') ? 'Aktif' : 'Nonaktif',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BugReport;
use App\Models\SystemVersion;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            $dbStatus = 'Terhubung (' . config('database.default') . ' / ' . config('database.connections.mysql.database') . ')';
        } catch (\Throwable $e) {
            $dbError = $e->getMessage();
        }

        return view('developer.dashboard', [
            'appVersion' => SystemVersion::orderByDesc('id_version')->first()?->versi ?? '1.0.0',
            'phpVersion' => PHP_VERSION,
            'laravelVersion' => app()->version(),
            'dbStatus' => $dbStatus,
            'dbError' => $dbError,
            'environment' => config('app.env'),
            'debugMode' => config('app.debug') ? 'Aktif' : 'Nonaktif',
            'versionCount' => SystemVersion::count(),
            'openBugs' => BugReport::where('status', 'open')->count(),
            'resolvedBugs' => BugReport::where('status', 'resolved')->count(),
            'logCount' => ActivityLog::count(),
            'recentLogs' => ActivityLog::with('user')->orderByDesc('tanggal_log')->limit(5)->get(),
        ]);
    }

    public function monitoring(): View
    {
        $dbStatus = 'Tidak terhubung';
        try {
            DB::connection()->getPdo();
            $dbStatus = 'OK - Active';
        } catch (\Throwable $e) {
            $dbStatus = 'Error: ' . $e->getMessage();
        }

        $monitoringData = [
            'os' => PHP_OS_FAMILY . ' (' . php_uname('r') . ')',
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'PHP Development Server',
            'memory_limit' => ini_get('memory_limit'),
            'memory_usage' => number_format(memory_get_usage() / 1024 / 1024, 2) . ' MB',
            'memory_peak' => number_format(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB',
            'post_max_size' => ini_get('post_max_size'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'max_execution_time' => ini_get('max_execution_time') . ' detik',
            'timezone' => date_default_timezone_get(),
            'loaded_extensions' => count(get_loaded_extensions()),
            'db_connection' => config('database.default'),
            'db_host' => config('database.connections.mysql.host'),
            'db_port' => config('database.connections.mysql.port'),
            'db_database' => config('database.connections.mysql.database'),
            'db_status' => $dbStatus,
        ];

        return view('developer.monitoring', compact('monitoringData'));
    }

    public function versiIndex(): View
    {
        $versions = SystemVersion::orderByDesc('id_version')->paginate(10);
        return view('developer.versi', compact('versions'));
    }

    public function versiStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'versi' => ['required', 'string', 'max:50'],
            'deskripsi' => ['required', 'string'],
            'tanggal_rilis' => ['required', 'date'],
        ], [
            'versi.required' => 'Nomor versi wajib diisi.',
            'deskripsi.required' => 'Deskripsi versi wajib diisi.',
            'tanggal_rilis.required' => 'Tanggal rilis wajib diisi.',
        ]);

        $versi = SystemVersion::create($validated);

        ActivityLog::record("Menambahkan versi sistem baru: {$versi->versi}");

        return redirect()
            ->route('developer.versi.index')
            ->with('success', 'Versi sistem berhasil ditambahkan.');
    }

    public function versiDestroy(int $id): RedirectResponse
    {
        $versi = SystemVersion::findOrFail($id);
        $nomorVersi = $versi->versi;
        $versi->delete();

        ActivityLog::record("Menghapus versi sistem: {$nomorVersi}");

        return redirect()
            ->route('developer.versi.index')
            ->with('success', 'Versi sistem berhasil dihapus.');
    }

    public function bugIndex(): View
    {
        $bugs = BugReport::orderByDesc('id_bug')->paginate(10);
        return view('developer.bug', compact('bugs'));
    }

    public function bugStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string'],
        ], [
            'judul.required' => 'Judul laporan bug wajib diisi.',
            'deskripsi.required' => 'Rincian deskripsi bug wajib diisi.',
        ]);

        $validated['status'] = 'open';
        $validated['tanggal_dilaporkan'] = now();

        $bug = BugReport::create($validated);

        ActivityLog::record("Melaporkan bug sistem: {$bug->judul}");

        return redirect()
            ->route('developer.bug.index')
            ->with('success', 'Laporan bug berhasil dikirim.');
    }

    public function bugResolve(int $id): RedirectResponse
    {
        $bug = BugReport::findOrFail($id);
        $bug->update(['status' => 'resolved']);

        ActivityLog::record("Menyelesaikan laporan bug: {$bug->judul}");

        return redirect()
            ->route('developer.bug.index')
            ->with('success', 'Status laporan bug berhasil diubah menjadi Resolved.');
    }

    public function bugDestroy(int $id): RedirectResponse
    {
        $bug = BugReport::findOrFail($id);
        $judulBug = $bug->judul;
        $bug->delete();

        ActivityLog::record("Menghapus laporan bug: {$judulBug}");

        return redirect()
            ->route('developer.bug.index')
            ->with('success', 'Laporan bug berhasil dihapus.');
    }

    public function logIndex(): View
    {
        $logs = ActivityLog::with('user')->orderByDesc('tanggal_log')->paginate(15);
        return view('developer.log', compact('logs'));
    }

    public function usersIndex(): View
    {
        $users = User::orderBy('name')->paginate(10);
        return view('developer.users', compact('users'));
    }

    public function usersUpdateRole(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:admin,developer,user'],
        ], [
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
        ]);

        $user = User::findOrFail($id);
        $oldRole = $user->role;
        
        $user->update(['role' => $validated['role']]);

        ActivityLog::record("Mengubah role pengguna {$user->name} (Username: {$user->username}) dari [{$oldRole}] menjadi [{$validated['role']}]");

        return redirect()
            ->route('developer.users.index')
            ->with('success', "Role pengguna {$user->name} berhasil diubah dari [{$oldRole}] menjadi [{$validated['role']}].");
    }
}

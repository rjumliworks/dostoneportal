<?php

namespace App\Http\Controllers\Executive;

use App\Http\Controllers\Controller;
use App\Services\Executive\Maintenance\ActionClass;
use App\Services\Executive\Maintenance\S3Class;
use App\Services\Executive\Maintenance\ViewClass;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MaintenanceController extends Controller
{
    protected ViewClass $view;
    protected ActionClass $action;
    protected S3Class $s3;

    public function __construct(ViewClass $view, ActionClass $action, S3Class $s3)
    {
        $this->view = $view;
        $this->action = $action;
        $this->s3 = $s3;
    }

    public function index(Request $request)
    {
        switch ($request->option) {
            case 'system-info':
                return response()->json($this->view->systemInfo());
            case 'storage-info':
                return response()->json($this->view->storageInfo());
            case 'backups':
                return response()->json($this->view->backups());
            default:
                return inertia('Executive/Maintenance/Index', [
                    'systemInfo' => $this->view->systemInfo(),
                    'storageInfo' => $this->view->storageInfo(),
                    'backups' => $this->view->backups(),
                    'scheduledTasks' => $this->view->scheduledTasks(),
                ]);
        }
    }

    public function runBackup()
    {
        return response()->json($this->action->runBackup());
    }

    public function deleteBackup(Request $request)
    {
        $request->validate(['filename' => 'required|string']);

        return response()->json($this->action->deleteBackup($request->filename));
    }

    public function downloadBackup(string $filename): BinaryFileResponse
    {
        return $this->action->downloadBackup($filename);
    }

    public function clearCache()
    {
        return response()->json($this->action->clearCache());
    }

    public function toggleMode(Request $request)
    {
        $request->validate(['enable' => 'required|boolean']);

        return response()->json($this->action->toggleMaintenanceMode($request->boolean('enable')));
    }

    public function s3Browse(Request $request)
    {
        return response()->json($this->s3->browse($request->query('prefix', ''), $request->query('token')));
    }

    public function s3DownloadFile(Request $request)
    {
        $request->validate(['key' => 'required|string']);

        return redirect()->away($this->s3->downloadUrl($request->query('key')));
    }

    public function s3DownloadFolder(Request $request)
    {
        $request->validate(['prefix' => 'required|string']);

        return $this->s3->downloadFolder($request->query('prefix'));
    }
}

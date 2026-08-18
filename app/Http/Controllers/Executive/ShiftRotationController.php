<?php

namespace App\Http\Controllers\Executive;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use App\Services\Executive\ShiftRotation\ViewClass;
use App\Services\Executive\ShiftRotation\SaveClass;
use App\Http\Requests\Executive\ShiftRotationRequest;

class ShiftRotationController extends Controller
{
    use HandlesTransaction;

    protected $view, $save;

    public function __construct(ViewClass $view, SaveClass $save)
    {
        $this->view = $view;
        $this->save = $save;
    }

    public function index()
    {
        return inertia('Executive/ShiftRotations/Index', [
            'lists' => $this->view->lists(),
            'guards' => $this->view->guards(),
        ]);
    }

    public function store(ShiftRotationRequest $request)
    {
        $result = $this->handleTransaction(function () use ($request) {
            return $this->save->save($request);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function update(ShiftRotationRequest $request)
    {
        $result = $this->handleTransaction(function () use ($request) {
            return $this->save->update($request);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function destroy($id)
    {
        $result = $this->handleTransaction(function () use ($id) {
            return $this->save->delete($id);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }
}

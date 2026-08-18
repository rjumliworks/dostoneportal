<?php

namespace App\Services\Executive\ShiftRotation;

use App\Models\ShiftRotation;

class SaveClass
{
    public function save($request)
    {
        $data = ShiftRotation::create([
            'user_id' => $request->user_id,
            'order' => $request->order,
            'is_active' => $request->is_active,
        ]);

        return [
            'data' => $data,
            'message' => 'Guard added to the rotation successfully.',
            'info' => 'This guard will now be included in the weekly shift rotation.',
        ];
    }

    public function update($request)
    {
        $data = ShiftRotation::findOrFail($request->id);
        $data->update([
            'user_id' => $request->user_id,
            'order' => $request->order,
            'is_active' => $request->is_active,
        ]);

        return [
            'data' => $data,
            'message' => 'Rotation entry updated successfully.',
            'info' => 'The guard rotation details have been refreshed.',
        ];
    }

    public function delete($id)
    {
        $data = ShiftRotation::findOrFail($id);
        $data->delete();

        return [
            'data' => [],
            'message' => 'Guard removed from the rotation successfully.',
            'info' => 'This guard will no longer be included in the weekly shift rotation.',
        ];
    }
}

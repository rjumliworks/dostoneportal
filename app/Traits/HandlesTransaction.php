<?php

namespace App\Traits;

use Illuminate\Database\QueryException;
use Illuminate\Database\DeadlockException;

trait HandlesTransaction
{
    public static function handleTransaction($callback){
        $data = '';
        $info = null;
        $status = false;

        try {
            $result = \DB::transaction($callback);
            // $status = ($result['status'] === 'error') ? false : true;
            $data = $result['data'];
            $info = $result['info'];
            $message = $result['message'];
            // $status = $status;
            $status = isset($result['status']) ? $result['status'] : true;
        } catch (QueryException $e) {
            \Log::error('Transaction failed (query exception): ' . $e->getMessage());
            $info = 'Transaction failed: ' . $e->getMessage();
            $message = 'Error occured';
        } catch (DeadlockException $e) {
            \Log::error('Transaction failed (deadlock): ' . $e->getMessage());
            $info = 'Transaction failed due to deadlock: ' . $e->getMessage();
            $message = 'Error occured';
        } catch (\Exception $e) {
            \Log::error('Transaction failed (unexpected): ' . $e->getMessage());
            $info = 'An unexpected error occurred: ' . $e->getMessage();
            $message = 'Error occured';
        }

        return [
            'data' => ($data) ? $data : 'Nothing found.',
            'message' => $message,
            'info' => $info,
            'status' => $status,
        ];
    }
}

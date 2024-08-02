<?php

namespace App\Http\Controllers;

use App\Jobs\ExportUsersJob;
use Illuminate\Support\Facades\Bus;

class UserController extends Controller
{
    public function export()
    {
        $totalRecords = 20000000; // 20 million records
        $recordsPerChunk = 1200000; // 12 lakh records per file
        $totalChunks = $totalRecords / $recordsPerChunk; // This will be exactly 10

        $batch = Bus::batch([])
            ->then(function () {
                \Log::info('Export of 20 million records completed successfully!');
            })
            ->catch(function () {
                \Log::error('An error occurred during the export process.');
            })
            ->finally(function () {
                \Log::info('Export process finished.');
            })
            ->dispatch();

        for ($i = 1; $i <= $totalChunks; $i++) {
            $batch->add(new ExportUsersJob($i, $recordsPerChunk));
        }

        // ExportUsersJob::dispatch();
        return response()->json([
            'message' => 'Export of 1 million records started. You will be notified when it\'s complete.',
            'batch_id' => $batch->id
        ]);
    }
}
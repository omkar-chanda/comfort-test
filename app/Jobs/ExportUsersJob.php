<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Log;

class ExportUsersJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chunkNumber;
    protected $recordsPerChunk;

    public function __construct($chunkNumber, $recordsPerChunk)
    {
        $this->chunkNumber = $chunkNumber;
        $this->recordsPerChunk = $recordsPerChunk;
    }

    public function handle()
    {
        $offset = ($this->chunkNumber - 1) * $this->recordsPerChunk;
        $users = User::skip($offset)->take($this->recordsPerChunk)->get();

        if ($users->count() > 0) {
            $fileName = 'users_chunk_' . $this->chunkNumber . '.csv';
            Excel::store(new UsersExport($users), $fileName, 'public', \Maatwebsite\Excel\Excel::CSV, []);
        }

        if ($this->chunkNumber * $this->recordsPerChunk >= 1000000) {
            Log::info('Export of 1 million records completed successfully!');
            $this->batch()->cancel();
        }
    }
}
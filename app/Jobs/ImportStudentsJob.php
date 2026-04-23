<?php

namespace App\Jobs;

use App\Models\Student;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\Attributes\Tries;
use Illuminate\Support\Facades\Log;

#[Tries(3)]
#[Timeout(5)]
class ImportStudentsJob implements ShouldQueue {
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public array $rows) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        sleep(10);
        foreach ($this->rows as $row) {
            Student::updateOrCreate(
                ['email' => $row[1]],
                ['name' => $row[0]]
            );
        }
    }
    public function failed(\Throwable $exception) {
        Log::error('ImportStudentsJob failed', [
            'error' => $exception->getMessage(),
            'rows_count' => count($this->rows)
        ]);
    }
}

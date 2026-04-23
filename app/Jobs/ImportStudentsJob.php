<?php

namespace App\Jobs;

use App\Models\Student;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ImportStudentsJob implements ShouldQueue {
    // This trait bundles the essential methods needed to push a job onto the queue and manage its execution, no need for any additional traits
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
        foreach ($this->rows as $row) {
            Student::create([
                'name' => $row[0],
                'email' => $row[1]
            ]);
        }
    }
}

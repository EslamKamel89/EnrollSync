<?php

use App\Jobs\ImportStudentsJob;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Livewire;

new class extends Component {
    use WithFileUploads;
    public  $file;

    public function import() {
        $this->validate([
            'file' => ['required', 'file', 'mimes:csv']
        ]);
        /** @var TemporaryUploadedFile $file */
        $file = $this->file;
        $path = $file->getRealPath();
        $rows = file($path);
        $rows = array_map('str_getcsv', $rows);
        array_shift($rows);
        ImportStudentsJob::dispatch($rows);
        // foreach ($rows as $row) {
        //     Student::create([
        //         'name' => $row[0],
        //         'email' => $row[1]
        //     ]);
        // }
        session()->flash('success', 'Students imported successfully');
        $this->js("alert('Data imported successfully!')");
    }
};
?>

<div class="min-h-screen flex items-center justify-center  px-6">
    <div class="w-full max-w-xl bg-white dark:bg-gray-900 shadow-xl rounded-2xl border border-gray-200 dark:border-gray-800 p-8">

        <h1 class="text-2xl md:text-3xl font-bold mb-6 text-center text-gray-900 dark:text-gray-100">
            Upload Student CSV
        </h1>
        @if (session()->has('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif
        <form wire:submit.prevent="import" class="space-y-5">

            <div class="flex flex-col gap-2">
                <input
                    type="file"
                    wire:model="file"
                    class="w-full text-sm text-gray-700 dark:text-gray-300
                           file:mr-4 file:py-2 file:px-4
                           file:rounded-lg file:border-0
                           file:text-sm file:font-semibold
                           file:bg-gray-900 file:text-white
                           hover:file:bg-gray-800
                           dark:file:bg-gray-100 dark:file:text-gray-900 dark:hover:file:bg-gray-200
                           border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800
                           focus:outline-none focus:ring-2 focus:ring-gray-400 transition" />

                @error("file")
                <p class="text-red-600 dark:text-red-400 text-sm">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full inline-flex items-center justify-center px-5 py-2.5 rounded-xl
                       bg-gray-900 text-white text-sm font-semibold shadow-sm
                       hover:bg-gray-800 hover:shadow-md active:scale-[0.98]
                       transition-all duration-200 ease-out
                       focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2
                       dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200">
                Import Students
            </button>

        </form>
    </div>
</div>
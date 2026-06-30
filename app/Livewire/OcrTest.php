<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\KtpScannerService;

class OcrTest extends Component
{
    use WithFileUploads;

    public $image;
    public $rawText = '';
    public $parsed = [];
    public $isScanning = false;
    public $hasError = false;
    public $errorMessage = '';

    public function mount()
    {
        abort_if(! in_array(auth()->user()->role, ['rt_admin', 'security']), 403);
    }

    public function scan()
    {
        $this->validate([
            'image' => 'required|image|max:2048',
        ]);

        $this->isScanning = true;
        $this->hasError = false;
        $this->errorMessage = '';
        $this->rawText = '';
        $this->parsed = [];

        try {
            $scanner = app(KtpScannerService::class);
            $this->parsed = $scanner->scan($this->image);
            $this->rawText = $this->parsed['_raw'] ?? '';
        } catch (\Throwable $e) {
            $this->hasError = true;
            $this->errorMessage = $e->getMessage();
        } finally {
            $this->isScanning = false;
        }
    }

    public function resetTest()
    {
        $this->reset(['image', 'rawText', 'parsed', 'isScanning', 'hasError', 'errorMessage']);
    }

    public function render()
    {
        return view('livewire.ocr-test')
            ->layout('layouts.app');
    }
}

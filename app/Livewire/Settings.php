<?php

namespace App\Livewire;

use Livewire\Component;

class Settings extends Component
{
    public $gemini_api_key;
    public $is_saved = false;

    public function mount()
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized action. Superadmin only.');
        }

        $setting = \App\Models\Setting::where('key', 'gemini_api_key')->first();
        $this->gemini_api_key = $setting ? $setting->value : '';
    }

    public function save()
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized action. Superadmin only.');
        }

        $this->validate([
            'gemini_api_key' => 'required|string',
        ]);

        \App\Models\Setting::updateOrCreate(
            ['key' => 'gemini_api_key'],
            ['value' => $this->gemini_api_key, 'description' => 'Google Gemini API Key (Vision OCR)']
        );

        $this->is_saved = true;
    }

    public function render()
    {
        return view('livewire.settings')->layout('layouts.app');
    }
}

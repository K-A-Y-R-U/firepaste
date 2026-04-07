<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\GeneralSetting;

class Header extends Component
{
    public $siteName;
    public $moreConfigs;

    public function mount()
    {
        $settings = GeneralSetting::first();
        $this->siteName = $settings?->site_name ?? config('app.name', 'Laravel');

        // ✅ Seguro ante null y JSON malformado
        $this->moreConfigs = json_decode($settings->more_configs ?? '[]', true) ?? [];
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect(route('login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.header');
    }
}
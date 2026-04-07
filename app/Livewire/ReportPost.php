<?php

namespace App\Livewire;

use App\Models\PostReport;
use Livewire\Component;

class ReportPost extends Component
{
    public int    $postId;
    public string $reason      = '';
    public string $description = '';
    public bool   $showModal   = false;
    public bool   $submitted   = false;
    public string $errorMsg    = '';

    protected $rules = [
        'reason'      => 'required|in:link_caido,contenido_incorrecto,otro',
        'description' => 'nullable|string|max:500',
    ];

    public function openModal(): void
    {
        $this->reset('reason', 'description', 'submitted', 'errorMsg');
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function submit(): void
    {
        $this->validate();

        $ip = request()->ip();

        // Verificar si ya reportó este post desde esta IP
        $alreadyReported = PostReport::where('post_id', $this->postId)
            ->where('ip_address', $ip)
            ->exists();

        if ($alreadyReported) {
            $this->errorMsg = 'Ya enviaste un reporte para este post anteriormente.';
            return;
        }

        PostReport::create([
            'post_id'     => $this->postId,
            'user_id'     => auth()->id(),
            'reason'      => $this->reason,
            'description' => $this->description ?: null,
            'ip_address'  => $ip,
            'status'      => 'pendiente',
        ]);

        $this->submitted = true;
        $this->errorMsg  = '';
    }

    public function render()
    {
        return view('livewire.report-post');
    }
}
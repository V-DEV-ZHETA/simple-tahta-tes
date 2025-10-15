<?php

namespace App\Livewire\Modal;

use App\Models\Bangkom;
use Livewire\Component;

class HistoriStatusModal extends Component
{
    public Bangkom $bangkom;

    public function render()
    {
        return view('livewire.modal.histori-status-modal');
    }
}

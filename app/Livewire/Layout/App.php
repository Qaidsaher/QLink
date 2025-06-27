<?php

namespace App\Livewire\Layout;

use Livewire\Component;

class App extends Component
{
     public bool $dark;

    public function mount()
    {
        // Initialize from session or default to false (light mode)
        $this->dark = session('theme', 'light') === 'dark';
    }

    public function toggleTheme()
    {
        $this->dark = !$this->dark;

        // Persist theme in session (or database, etc.)
        session(['theme' => $this->dark ? 'dark' : 'light']);

        // Emit event for frontend JS to react
        $this->dispatch('theme-changed', ['dark' => $this->dark]);
    }

    public function render()
    {
        return view('livewire.layout.app');
    }
}

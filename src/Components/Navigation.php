<?php

namespace Wsmallnews\Shop\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Navigation extends Component
{
    public function mount() {}

    /**
     * Log the current user out of the application.
     */
    public function logout(): void
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        $this->redirectRoute('sn-shop.index', navigate: true);
    }

    public function render()
    {
        return view('sn-shop::livewire.components.navigate');
    }
}

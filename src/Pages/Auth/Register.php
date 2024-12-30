<?php

namespace Wsmallnews\Shop\Pages\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Attributes\Title;
use Wsmallnews\Shop\Pages\Base;

class Register extends Base
{
    

    public function mount()
    {
        
    }



    #[Title('注册')] 
    public function render()
    {
        return view('sn-shop::livewire.auth.login', []);
    }
}

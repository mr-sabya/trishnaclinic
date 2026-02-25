<?php

namespace App\Livewire\Admin\Theme;

use Livewire\Component;

class PageTitle extends Component
{
    public $title;
    public $breadcrumb;

    public function mount($title, $breadcrumb)
    {
        $this->title = $title;
        $this->breadcrumb = $breadcrumb;
    }

    public function render()
    {
        return view('livewire.admin.theme.page-title');
    }
}

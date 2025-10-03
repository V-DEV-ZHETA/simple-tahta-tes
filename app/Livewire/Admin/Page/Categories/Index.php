<?php

namespace App\Livewire\Admin\Page\Categories;

use Livewire\Component;
use App\Models\Category;

class Index extends Component
{
    public $categories;

    public function mount()
    {
        $this->categories = Category::all();
    }

    public function render()
    {
        return view('livewire.admin.page.categories.index', [
            'categories' => $this->categories,
        ]);
    }
}

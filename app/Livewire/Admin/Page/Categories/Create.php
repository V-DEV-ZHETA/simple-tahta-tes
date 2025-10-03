<?php

namespace App\Livewire\Admin\Page\Categories;

use Livewire\Component;

class Create extends Component
{
    public $name;
    public $description;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    public function save()
    {
        $this->validate();

        // Create the category
        // Category::create([
        //     'name' => $this->name,
        //     'description' => $this->description,
        // ]);

        session()->flash('message', 'Category created successfully.');

        return redirect()->route('categories');
    }

    public function render()
    {
        return view('livewire.admin.page.categories.create');
    }
}

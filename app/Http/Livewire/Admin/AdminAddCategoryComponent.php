<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Str;

class AdminAddCategoryComponent extends Component
{
    public $name;
    public $slug;

    //generate Slug
    public function generateslug()
    {
      $this->slug = Str::slug($this->name);
    }

    //this function update the fields
    public function updated($fields)
    {
      $this->validateOnly($fields,[
        'name' => 'required',
        'slug' => 'required|unique:categories'
      ]);
    }

    //for store category_slug
    public function storeCategory()
    {
      $this->validate([
        'name' => 'required',
        'slug' => 'required|unique:categories'
      ]);
      $category = new Category();
      $category->name = $this->name;
      $category->slug = $this->slug;
      $category->save();
      session()->flash('message','Category has been created succesfully!');
    }
    public function render()
    {
        return view('livewire.admin.admin-add-category-component')->layout('layouts.base');
    }
}

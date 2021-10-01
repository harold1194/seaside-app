<?php

namespace App\Http\Livewire\Admin;

use App\Models\HomeSlider;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminAddHomeSliderComponent extends Component
{
    use WithFileUploads;
    public $title;
    public $subtitle;
    public $price;
    public $link;
    public $image;
    public $status;

    //this function for hook mount
    public function mount()
    {
      $this->status = 0;
    }

    // This function for adding Slides
    public function addSlides()
    {
      $slider = new HomeSlider();
      $slider->title = $this->title;
      $slider->subtitle = $this->subtitle ;
      $slider->price = $this->price;
      $slider->link = $this->link;
      $imagename = Carbon::now()->timestamp. '.' . $this->image->extension();
      $this->image->storeAs('sliders',$imagename);
      $slider->image = $imagename;
      $slider->status = $this->status;
      $slider->save();
      session()->flash('message','Slide has been added successfully!');
    }
    public function render()
    {
        return view('livewire.admin.admin-add-home-slider-component')->layout('layouts.base');
    }
}

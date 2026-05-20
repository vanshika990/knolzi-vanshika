<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class LayoutFrontHeader extends Component {

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render() {
//        $parentCategories = Cache::remember('parentCategories', 700, function () {
//                    return Category::where('parent_id', 0)->get();
//                });
        $parentCategories = Category::where('parent_id', 0)->get();
        return view('components.layout-front-header')->with("parentCategories", $parentCategories);
    }

}

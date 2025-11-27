<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Livewire\Component;

class CategoriesTreeDrop extends Component
{
    public $category_id;

    private function buildTree($categories, $parentId = null)
    {
        $branch = [];

        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {

                // recursion: find children of this category
                $children = $this->buildTree($categories, $category->id);

                if (! empty($children)) {
                    $category->children = $children;
                } else {
                    $category->children = [];
                }

                $branch[] = $category;
            }
        }

        return $branch;
    }

    public function render()
    {

        $categories = Category::all();
        $tree = $this->buildTree($categories);

        return view('livewire.admin.categories.categories-tree-drop', compact('tree'));
    }
}

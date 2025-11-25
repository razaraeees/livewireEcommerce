<?php

namespace App\Livewire\Admin\Variants;

use App\Models\Variant;
use App\Models\VariantValue;
use Illuminate\Support\Str;
use Livewire\Component;

class VariantsCreate extends Component
{
    public $variantList = [];
    public $status = true;
    public $slugAvailability = [];

    public function mount()
    {
        $this->addVariantField();
    }

    public function checkSlugAvailability($index, $slug)
    {
        if (empty($slug)) {
            unset($this->slugAvailability[$index]);
            return;
        }

        $exists = Variant::where('slug', $slug)->exists();
        $this->slugAvailability[$index] = !$exists;
    }

    public function addVariantField()
    {
        $this->variantList[] = [
            'name' => '',
            'slug' => '',
            'values' => []
        ];
    }

    public function removeVariantField($index)
    {
        unset($this->variantList[$index]);
        unset($this->slugAvailability[$index]);
        $this->variantList = array_values($this->variantList);
        $this->slugAvailability = array_values($this->slugAvailability);
    }

    public function addValue($variantIndex, $value)
    {
        $trimmedValue = trim($value);

        if (!empty($trimmedValue)) {
            if (!in_array($trimmedValue, $this->variantList[$variantIndex]['values'])) {
                $this->variantList[$variantIndex]['values'][] = $trimmedValue;
            }
        }
    }

    public function removeValue($variantIndex, $valueIndex)
    {
        unset($this->variantList[$variantIndex]['values'][$valueIndex]);
        $this->variantList[$variantIndex]['values'] = array_values($this->variantList[$variantIndex]['values']);
    }

    public function store()
    {
        $this->validate([
            'variantList.*.name' => 'required|string|max:255',
            'variantList.*.slug' => 'required|string|max:255|unique:variants,slug',
            'variantList.*.values' => 'required|array|min:1',
        ], [
            'variantList.*.name.required' => 'Variant name is required',
            'variantList.*.slug.required' => 'Slug is required',
            'variantList.*.slug.unique' => 'This slug is already taken',
            'variantList.*.values.required' => 'At least one variant value is required',
            'variantList.*.values.min' => 'At least one variant value is required',
        ]);

        foreach ($this->variantList as $index => $variant) {
            if (isset($this->slugAvailability[$index]) && $this->slugAvailability[$index] === false) {
                $this->dispatch('show-toast', type: 'error', message: 'Please ensure all slugs are unique!');
                return;
            }
        }

        try {
            foreach ($this->variantList as $variantData) {
                $variant = Variant::create([
                    'name' => $variantData['name'],
                    'slug' => $variantData['slug'] ?: Str::slug($variantData['name']),
                    'status' => $this->status ? 1 : 0,
                ]);

                foreach ($variantData['values'] as $value) {
                    $valueSlug = $this->generateUniqueSlug($value);
                    
                    VariantValue::create([
                        'variants_id' => $variant->id,
                        'value' => $value,
                        'slug' => $valueSlug,
                    ]);
                }
            }

            $this->reset();
            $this->mount();

            $this->dispatch('show-toast', type: 'success', message: 'Variants Created Successfully!');

            return redirect()->route('admin.variant');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    private function generateUniqueSlug($value)
    {
        $slug = Str::slug($value);
        $originalSlug = $slug;
        $counter = 1;

        while (VariantValue::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function saveDraft()
    {
        try {
            foreach ($this->variantList as $variantData) {
                if (!empty($variantData['name'])) {
                    $variant = Variant::create([
                        'name' => $variantData['name'],
                        'slug' => $variantData['slug'] ?: Str::slug($variantData['name']),
                        'status' => 0,
                    ]);

                    if (!empty($variantData['values'])) {
                        foreach ($variantData['values'] as $value) {
                            $valueSlug = $this->generateUniqueSlug($value);
                            
                            VariantValue::create([
                                'variants_id' => $variant->id,
                                'value' => $value,
                                'slug' => $valueSlug,
                            ]);
                        }
                    }
                }
            }

            $this->reset();
            $this->mount();

            $this->dispatch('show-toast', type: 'success', message: 'Draft Saved Successfully!');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.variants.variants-create');
    }
}
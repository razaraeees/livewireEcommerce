<?php

namespace App\Livewire\Admin\Variants;

use App\Models\Variant;
use App\Models\VariantValue;
use Illuminate\Support\Str;
use Livewire\Component;

class VariantsEdit extends Component
{
    public $variantSlug; // Slug parameter from route
    public $variantId; // Store variant ID
    public $name;
    public $slug;
    public $originalSlug;
    public $values = [];
    public $status = true;
    public $slugAvailable = null;

    public function mount($slug)
    {
        $this->variantSlug = $slug;
        
        // Load existing variant data
        $variant = Variant::where('slug', $slug)->with('variantValues')->firstOrFail();
        
        $this->variantId = $variant->id;
        $this->name = $variant->name;
        $this->slug = $variant->slug;
        $this->originalSlug = $variant->slug;
        $this->values = $variant->variantValues->pluck('value')->toArray();
        $this->status = $variant->status == 1;
    }

    public function checkSlugAvailability()
    {
        if (empty($this->slug)) {
            $this->slugAvailable = null;
            return;
        }

        // Check if slug exists, but exclude the current variant's original slug
        $exists = Variant::where('slug', $this->slug)
            ->where('slug', '!=', $this->originalSlug)
            ->exists();
            
        $this->slugAvailable = !$exists;
    }

    public function addValue($value)
    {
        $trimmedValue = trim($value);

        if (!empty($trimmedValue)) {
            if (!in_array($trimmedValue, $this->values)) {
                $this->values[] = $trimmedValue;
            }
        }
    }

    public function removeValue($valueIndex)
    {
        unset($this->values[$valueIndex]);
        $this->values = array_values($this->values);
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'values' => 'required|array|min:1',
        ], [
            'name.required' => 'Variant name is required',
            'slug.required' => 'Slug is required',
            'values.required' => 'At least one variant value is required',
            'values.min' => 'At least one variant value is required',
        ]);

        if ($this->slugAvailable === false) {
            $this->dispatch('show-toast', type: 'error', message: 'This slug is already taken!');
            return;
        }

        try {
            // Update existing variant
            $variant = Variant::where('slug', $this->originalSlug)->firstOrFail();
            
            $variant->update([
                'name' => $this->name,
                'slug' => $this->slug ?: Str::slug($this->name),
                'status' => $this->status ? 1 : 0,
            ]);

            // Delete old values
            VariantValue::where('variants_id', $variant->id)->delete();

            // Create new values
            foreach ($this->values as $value) {
                $valueSlug = $this->generateUniqueSlug($value);
                
                VariantValue::create([
                    'variants_id' => $variant->id,
                    'value' => $value,
                    'slug' => $valueSlug,
                ]);
            }

            $this->dispatch('show-toast', type: 'success', message: 'Variant Updated Successfully!');

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
            if (!empty($this->name)) {
                $variant = Variant::where('slug', $this->originalSlug)->firstOrFail();
                
                $variant->update([
                    'name' => $this->name,
                    'slug' => $this->slug ?: Str::slug($this->name),
                    'status' => 0,
                ]);

                VariantValue::where('variants_id', $variant->id)->delete();

                if (!empty($this->values)) {
                    foreach ($this->values as $value) {
                        $valueSlug = $this->generateUniqueSlug($value);
                        
                        VariantValue::create([
                            'variants_id' => $variant->id,
                            'value' => $value,
                            'slug' => $valueSlug,
                        ]);
                    }
                }
            }

            $this->dispatch('show-toast', type: 'success', message: 'Draft Saved Successfully!');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.variants.variants-edit');
    }
}
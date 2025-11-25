<?php

namespace App\Livewire\Admin\Attributes;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Livewire\Component;
use Illuminate\Support\Str;

class AttributesEdit extends Component
{
    public $attribute;
    public $attributeId;
    public $name;
    public $slug;
    public $values = [];
    public $status = true;
    
    public function mount($slug)
    {
        $this->attributeId = $slug;
        $this->attribute = Attribute::with('attributeValue')
            ->where('slug', $slug)
            ->firstOrFail();
        
        $this->name = $this->attribute->name;
        $this->slug = $this->attribute->slug;
        $this->status = $this->attribute->status == 1;
        
        // Load existing values
        foreach ($this->attribute->attributeValue as $value) {
            $this->values[] = $value->value;
        }
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
        // Validation
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'values' => 'required|array|min:1',
        ], [
            'name.required' => 'Attribute name is required',
            'slug.required' => 'Slug is required',
            'values.required' => 'At least one value is required',
            'values.min' => 'At least one value is required',
        ]);

        try {
            // Update Attribute
            $this->attribute->update([
                'name' => $this->name,
                'slug' => $this->slug ?: Str::slug($this->name),
                'status' => $this->status ? 1 : 0,
            ]);

            // Delete old values
            AttributeValue::where('attribute_id', $this->attribute->id)->delete();

            // Create new values
            foreach ($this->values as $value) {
                AttributeValue::create([
                    'attribute_id' => $this->attribute->id,
                    'value' => $value,
                    'slug' => Str::slug($value),
                ]);
            }

            // Dispatch toast event
            $this->dispatch('show-toast', type: 'success', message: 'Attribute Updated Successfully!');

            return redirect()->route('admin.attribute');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function saveDraft()
    {
        try {
            // Update as draft
            $this->attribute->update([
                'name' => $this->name,
                'slug' => $this->slug ?: Str::slug($this->name),
                'status' => 0, // Draft
            ]);

            // Delete old values
            AttributeValue::where('attribute_id', $this->attribute->id)->delete();

            // Create new values if any
            if (!empty($this->values)) {
                foreach ($this->values as $value) {
                    AttributeValue::create([
                        'attribute_id' => $this->attribute->id,
                        'value' => $value,
                        'slug' => Str::slug($value),
                    ]);
                }
            }

            $this->dispatch('show-toast', type: 'success', message: 'Draft Saved Successfully!');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.attributes.attributes-edit');
    }
}
<?php

namespace App\Livewire\Admin\Attributes;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Livewire\Component;
use Illuminate\Support\Str;

class AttributesCreate extends Component
{
    public $attributeList = [];
    public $status = true;
    
    public function mount()
    {
        // Initialize with one empty attribute
        $this->addAttributeField();
    }

    public function addAttributeField()
    {
        $this->attributeList[] = [
            'name' => '',
            'slug' => '',
            'values' => []
        ];
    }

    public function removeAttributeField($index)
    {
        unset($this->attributeList[$index]);
        $this->attributeList = array_values($this->attributeList);
    }

    public function addValue($attributeIndex, $value)
    {
        $trimmedValue = trim($value);
        
        if (!empty($trimmedValue)) {
            if (!in_array($trimmedValue, $this->attributeList[$attributeIndex]['values'])) {
                $this->attributeList[$attributeIndex]['values'][] = $trimmedValue;
            }
        }
    }

    public function removeValue($attributeIndex, $valueIndex)
    {
        unset($this->attributeList[$attributeIndex]['values'][$valueIndex]);
        $this->attributeList[$attributeIndex]['values'] = array_values($this->attributeList[$attributeIndex]['values']);
    }

    public function store()
    {
        // Validation
        $this->validate([
            'attributeList.*.name' => 'required|string|max:255',
            'attributeList.*.slug' => 'required|string|max:255',
            'attributeList.*.values' => 'required|array|min:1',
        ], [
            'attributeList.*.name.required' => 'Attribute name is required',
            'attributeList.*.slug.required' => 'Slug is required',
            'attributeList.*.values.required' => 'At least one value is required',
            'attributeList.*.values.min' => 'At least one value is required',
        ]);

        try {
            foreach ($this->attributeList as $attributeData) {
                // Create Attribute
                $attribute = Attribute::create([
                    'name' => $attributeData['name'],
                    'slug' => $attributeData['slug'] ?: Str::slug($attributeData['name']),
                    'status' => $this->status ? 1 : 0,
                ]);

                // Create Attribute Values
                foreach ($attributeData['values'] as $value) {
                    AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'value' => $value,
                        'slug' => Str::slug($value),
                    ]);
                }
            }

            $this->reset();
            $this->mount();

            // Dispatch toast event
            $this->dispatch('show-toast', type: 'success', message: 'Attributes Created Successfully!');

            return redirect()->route('admin.attribute');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function saveDraft()
    {
        try {
            foreach ($this->attributeList as $attributeData) {
                if (!empty($attributeData['name'])) {
                    // Create Attribute as draft
                    $attribute = Attribute::create([
                        'attributes' => $attributeData['name'],
                        'slug' => $attributeData['slug'] ?: Str::slug($attributeData['name']),
                        'status' => 0, // Draft
                    ]);

                    // Create values if any
                    if (!empty($attributeData['values'])) {
                        foreach ($attributeData['values'] as $value) {
                            AttributeValue::create([
                                'attribute_id' => $attribute->id,
                                'name' => $value,
                                'slug' => Str::slug($value),
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
        return view('livewire.admin.attributes.attributes-create');
    }
}
<div>
    <div class="col-lg-6">
        <div class="mb-8">
            <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                Category <span class="text-danger">*</span>
            </label>
            <select wire:model="category_id" class="form-control" required>
                <option value="">Select Category</option>

                @foreach ($tree as $category)
                    @php
                        // show main category
                        echo "<option value='{$category->id}'>{$category->category_name}</option>";

                        // call recursive function for children
                        showChildren($category->children, '— ');
                    @endphp
                @endforeach
            </select>
            @error('category_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

@php
function showChildren($children, $prefix)
{
    foreach ($children as $child) {

        echo "<option value='{$child->id}'>{$prefix}{$child->category_name}</option>";

        if (!empty($child->children)) {
            showChildren($child->children, $prefix . '— ');
        }
    }
}
@endphp
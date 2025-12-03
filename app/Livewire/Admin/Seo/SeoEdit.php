<?php

namespace App\Livewire\Admin\Seo;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SeoSetting;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SeoEdit extends Component
{
    use WithFileUploads;

    public $seosetting_id;
    public $page_name;
    public $page_url;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;
    public $meta_author;
    public $meta_robots;
    public $meta_image;
    public $existing_image; // Store original image separately
    public $og_title;
    public $og_description;
    public $og_type;
    public $og_url;
    public $status;

    protected $rules = [
        'page_name'        => 'required|string|max:255',
        'page_url'         => 'required|string|max:255',
        'meta_title'       => 'nullable|string|max:255',
        'meta_description' => 'nullable|string|max:500',
        'meta_keywords'    => 'nullable|string|max:255',
        'meta_author'      => 'nullable|string|max:100',
        'meta_robots'      => 'nullable|string|max:100',
        'meta_image'       => 'nullable|image|max:2048',
        'og_title'         => 'nullable|string|max:255',
        'og_description'   => 'nullable|string|max:500',
        'og_type'          => 'nullable|string|max:50',
        'og_url'           => 'nullable|url|max:255',
    ];

    public function mount($id)
    {
        $seo = SeoSetting::findOrFail($id);
        $this->seosetting_id = $seo->id;
        $this->page_name = $seo->page_name;
        $this->page_url = $seo->page_url;
        $this->meta_title = $seo->meta_title;
        $this->meta_description = $seo->meta_description;
        $this->meta_keywords = $seo->meta_keywords;
        $this->meta_author = $seo->meta_author;
        $this->meta_robots = $seo->meta_robots;
        $this->existing_image = $seo->meta_image; // Store existing image separately
        $this->meta_image = null; // Keep this null for new uploads
        $this->og_title = $seo->og_title;
        $this->og_description = $seo->og_description;
        $this->og_type = $seo->og_type;
        $this->og_url = $seo->og_url;
    }

    public function updatedPageUrl($value)
    {
        $this->validate([
            'page_url' => 'required|string|max:255|unique:seo_settings,page_url,' . $this->seosetting_id
        ]);
    }

    public function updatedOgUrl($value)
    {
        if ($value) {
            $this->validate(['og_url' => 'url|max:255']);
        }
    }

    public function updatedMetaImage()
    {
        $this->validate(['meta_image' => 'image|max:2048']);
    }

    public function removeImage()
    {
        // Delete old image from storage if exists
        if ($this->existing_image && Storage::disk('public')->exists($this->existing_image)) {
            Storage::disk('public')->delete($this->existing_image);
        }

        $this->dispatch('show-toast', type: 'success', message: 'SEO Page Delete Successfully!');

        $this->existing_image = null;
        $this->meta_image = null;
    }

    public function update()
    {
        try {
            // Step 1: Basic validation
            $this->validate([
                'page_name' => 'required|string|max:255',
                'page_url'  => 'required|string|max:255|unique:seo_settings,page_url,' . $this->seosetting_id,
            ]);

            // Step 2: Prepare data
            $data = [
                'page_name' => $this->page_name ?? '',
                'page_url' => $this->page_url ?? '',
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
                'meta_author' => $this->meta_author,
                'meta_robots' => $this->meta_robots,
                'og_title' => $this->og_title,
                'og_description' => $this->og_description,
                'og_type' => $this->og_type ?? 'website',
                'og_url' => !empty($this->og_url) ? $this->og_url : null,
            ];

            // Step 3: Handle image
            if ($this->meta_image) {
                if ($this->existing_image && Storage::disk('public')->exists($this->existing_image)) {
                    Storage::disk('public')->delete($this->existing_image);
                }
                $data['meta_image'] = $this->meta_image->store('seo-pages', 'public');
            } elseif ($this->existing_image === null) {
                $data['meta_image'] = null;
            } else {
                $data['meta_image'] = $this->existing_image;
            }

            // Step 4: Update record
            $updated = SeoSetting::where('id', $this->seosetting_id)->update($data);

            if ($updated) {  
                $this->dispatch('show-toast', type: 'success', message: 'SEO Page Updated Successfully!');
                return redirect()->route('admin.seo');
            } else {
                $this->dispatch('show-toast', type: 'warning', message: 'No changes detected.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Validation Error: ' . collect($e->errors())->flatten()->first());
        } catch (QueryException $e) {
            // Log::error('Database Error: ' . $e->getMessage());
            // Log::error('SQL: ' . $e->getSql());

            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                $this->dispatch('show-toast', type: 'error', message: 'Duplicate entry detected.');
            } else {
                $this->dispatch('show-toast', type: 'error', message: 'DB Error: ' . $e->getMessage());
            }
        } catch (Exception $e) {
            // Log::error('General Error: ' . $e->getMessage());
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.seo.seo-edit');
    }
}

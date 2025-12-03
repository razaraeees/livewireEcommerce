<?php

namespace App\Livewire\Admin\Seo;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SeoSetting; // ✅ Correct model
use Illuminate\Database\QueryException;
use Exception;

class SeoCreate extends Component
{
    use WithFileUploads;

    public $page_name;
    public $page_url;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;
    public $meta_author;
    public $meta_robots = 'index, follow';
    public $meta_image;
    public $og_title;
    public $og_description;
    public $og_type = 'website';
    public $og_url;
    public $status = true;

    protected $rules = [
        'page_name'        => 'required|string|max:255',
        'page_url'         => 'required|string|max:255|unique:seo_settings,page_url', 
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
        'status'           => 'boolean',
    ];

    // Real-time validation
    public function updatedPageUrl($value)
    {
        $this->validate(['page_url' => 'required|string|max:255|unique:seo_settings,page_url']);
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

    public function saveDraft()
    {
        $this->validate([
            'page_name' => 'required|string|max:255',
            'page_url'  => 'required|string|max:255|unique:seo_settings,page_url',
        ]);
        $this->store(isDraft: true);
    }

    public function store($isDraft = false)
    {
        try {
            $this->validate();

            $data = $this->only([
                'page_name', 'page_url', 'meta_title', 'meta_description',
                'meta_keywords', 'meta_author', 'meta_robots', 'og_title',
                'og_description', 'og_type', 'og_url', 'status'
            ]);

            $data['status'] = (bool) $this->status;

            if ($this->meta_image) {
                $data['meta_image'] = $this->meta_image->store('seo-pages', 'public');
            }

            SeoSetting::create($data); 

            $this->dispatch('show-toast', type: 'success', message: 'SEO Page Created Successfully!');
            return redirect()->route('admin.seo');

        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) { 
                $this->dispatch('show-toast', type: 'error', message: 'Page URL already exists. Please use a unique URL.');
            } else {
              
                $this->dispatch('show-toast', type: 'error', message: 'Database error. Please try again.');
            }
        } catch (Exception $e) {
            
            $this->dispatch('show-toast', type: 'error', message: 'Something went wrong. Please try again.');
        }
    }

    public function removeImage()
    {
        $this->meta_image = null;
    }

    public function render()
    {
        return view('livewire.admin.seo.seo-create');
    }
}
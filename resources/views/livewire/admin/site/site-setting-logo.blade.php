 <div>
     <div x-data="{}" class="dashboard-page-content">
         <form wire:submit.prevent="store">

             <div class="row mb-9 align-items-center">
                 <div class="col-lg-12">
                     <div class="row">
                         <div class="col-sm-6 mb-8 mb-sm-0">
                             <h2 class="fs-4 mb-0">Site Settings</h2>
                             <p>Manage page site settings.</p>

                         </div>

                         <div class="col-sm-6 d-flex flex-wrap justify-content-sm-end">
                           <button 
                            type="submit" 
                            class="btn btn-sm btn-primary" 
                            {{-- style="height:auto; padding:4px 10px;"  --}}
                            wire:loading.attr="disabled"
                            >
                                 <span wire:loading.remove wire:target="store">Save Settings</span>
                                 <span wire:loading wire:target="store">
                                     <span class="spinner-border spinner-border-sm"></span>
                                     Saving...
                                 </span>
                             </button>
                         </div>
                     </div>
                 </div>
             </div>

             <div class="row">
                 <div class="col-lg-12">
                     <div class="row">

                         <!-- Favicon -->
                         <div class="col-lg-3 col-md-6">
                             <div class="card mb-8 rounded-4">
                                 <div class="card-header p-7 bg-transparent">
                                     <h4 class="fs-18px mb-0 font-weight-500">Favicon</h4>
                                 </div>
                                 <div class="card-body p-7">
                                     <div class="input-upload text-center position-relative">
                                         @if ($favicon)
                                             <div class="position-relative">
                                                 <img src="{{ $favicon->temporaryUrl() }}" class="w-100 rounded mb-4"
                                                     style="max-height: 150px; object-fit: contain;">
                                                 <button type="button" wire:click="$set('favicon', null)"
                                                     class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">&times;</button>
                                             </div>
                                         @elseif($siteSetting && $siteSetting->favicon)
                                             <div class="position-relative">
                                                 <img src="{{ asset('storage/' . $siteSetting->favicon) }}"
                                                     class="w-100 rounded mb-4"
                                                     style="max-height: 150px; object-fit: contain;">
                                                 <button type="button" wire:click="removeFavicon"
                                                     class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">&times;</button>
                                             </div>
                                         @else
                                             <img src="{{ asset('assets/images/dashboard/upload.svg') }}" width="80"
                                                 class="d-block mx-auto mb-3">
                                             <p class="text-muted small">Upload Favicon</p>
                                         @endif

                                         <input type="file" wire:model="favicon"
                                             class="form-control @error('favicon') is-invalid @enderror"
                                             accept="image/*">
                                         <small class="text-muted d-block mt-2">32×32px or 64×64px</small>
                                         @error('favicon')
                                             <span class="text-danger d-block mt-2">{{ $message }}</span>
                                         @enderror

                                         <div wire:loading wire:target="favicon" class="mt-3">
                                             <div class="spinner-border spinner-border-sm text-primary"></div>
                                             <span class="ms-2 text-muted">Uploading...</span>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>

                         <!-- Website Logo -->
                         <div class="col-lg-3 col-md-6">
                             <div class="card mb-8 rounded-4">
                                 <div class="card-header p-7 bg-transparent">
                                     <h4 class="fs-18px mb-0 font-weight-500">Website Logo</h4>
                                 </div>
                                 <div class="card-body p-7">
                                     <div class="input-upload text-center position-relative">
                                         @if ($website_logo)
                                             <div class="position-relative">
                                                 <img src="{{ $website_logo->temporaryUrl() }}"
                                                     class="w-100 rounded mb-4"
                                                     style="max-height: 150px; object-fit: contain;">
                                                 <button type="button" wire:click="$set('website_logo', null)"
                                                     class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">&times;</button>
                                             </div>
                                         @elseif($siteSetting && $siteSetting->website_logo)
                                             <div class="position-relative">
                                                 <img src="{{ asset('storage/' . $siteSetting->website_logo) }}"
                                                     class="w-100 rounded mb-4"
                                                     style="max-height: 150px; object-fit: contain;">
                                                 <button type="button" wire:click="removeWebsiteLogo"
                                                     class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">&times;</button>
                                             </div>
                                         @else
                                             <img src="{{ asset('assets/images/dashboard/upload.svg') }}" width="80"
                                                 class="d-block mx-auto mb-3">
                                             <p class="text-muted small">Upload Website Logo</p>
                                         @endif

                                         <input type="file" wire:model="website_logo"
                                             class="form-control @error('website_logo') is-invalid @enderror"
                                             accept="image/*">
                                         <small class="text-muted d-block mt-2">300×100px</small>
                                         @error('website_logo')
                                             <span class="text-danger d-block mt-2">{{ $message }}</span>
                                         @enderror

                                         <div wire:loading wire:target="website_logo" class="mt-3">
                                             <div class="spinner-border spinner-border-sm text-primary"></div>
                                             <span class="ms-2 text-muted">Uploading...</span>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>

                         <!-- Footer Logo -->
                         <div class="col-lg-3 col-md-6">
                             <div class="card mb-8 rounded-4">
                                 <div class="card-header p-7 bg-transparent">
                                     <h4 class="fs-18px mb-0 font-weight-500">Footer Logo</h4>
                                 </div>
                                 <div class="card-body p-7">
                                     <div class="input-upload text-center position-relative">
                                         @if ($footer_logo)
                                             <div class="position-relative">
                                                 <img src="{{ $footer_logo->temporaryUrl() }}"
                                                     class="w-100 rounded mb-4"
                                                     style="max-height: 150px; object-fit: contain;">
                                                 <button type="button" wire:click="$set('footer_logo', null)"
                                                     class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">&times;</button>
                                             </div>
                                         @elseif($siteSetting && $siteSetting->footer_logo)
                                             <div class="position-relative">
                                                 <img src="{{ asset('storage/' . $siteSetting->footer_logo) }}"
                                                     class="w-100 rounded mb-4"
                                                     style="max-height: 150px; object-fit: contain;">
                                                 <button type="button" wire:click="removeFooterLogo"
                                                     class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">&times;</button>
                                             </div>
                                         @else
                                             <img src="{{ asset('assets/images/dashboard/upload.svg') }}"
                                                 width="80" class="d-block mx-auto mb-3">
                                             <p class="text-muted small">Upload Footer Logo</p>
                                         @endif

                                         <input type="file" wire:model="footer_logo"
                                             class="form-control @error('footer_logo') is-invalid @enderror"
                                             accept="image/*">
                                         <small class="text-muted d-block mt-2">300×100px</small>
                                         @error('footer_logo')
                                             <span class="text-danger d-block mt-2">{{ $message }}</span>
                                         @enderror

                                         <div wire:loading wire:target="footer_logo" class="mt-3">
                                             <div class="spinner-border spinner-border-sm text-primary"></div>
                                             <span class="ms-2 text-muted">Uploading...</span>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>

                         <!-- Admin Logo -->
                         <div class="col-lg-3 col-md-6">
                             <div class="card mb-8 rounded-4">
                                 <div class="card-header p-7 bg-transparent">
                                     <h4 class="fs-18px mb-0 font-weight-500">Admin Logo</h4>
                                 </div>
                                 <div class="card-body p-7">
                                     <div class="input-upload text-center position-relative">
                                         @if ($admin_logo)
                                             <div class="position-relative">
                                                 <img src="{{ $admin_logo->temporaryUrl() }}"
                                                     class="w-100 rounded mb-4"
                                                     style="max-height: 150px; object-fit: contain;">
                                                 <button type="button" wire:click="$set('admin_logo', null)"
                                                     class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">&times;</button>
                                             </div>
                                         @elseif($siteSetting && $siteSetting->admin_logo)
                                             <div class="position-relative">
                                                 <img src="{{ asset('storage/' . $siteSetting->admin_logo) }}"
                                                     class="w-100 rounded mb-4"
                                                     style="max-height: 150px; object-fit: contain;">
                                                 <button type="button" wire:click="removeAdminLogo"
                                                     class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">&times;</button>
                                             </div>
                                         @else
                                             <img src="{{ asset('assets/images/dashboard/upload.svg') }}"
                                                 width="80" class="d-block mx-auto mb-3">
                                             <p class="text-muted small">Upload Admin Logo</p>
                                         @endif

                                         <input type="file" wire:model="admin_logo"
                                             class="form-control @error('admin_logo') is-invalid @enderror"
                                             accept="image/*">
                                         <small class="text-muted d-block mt-2">200×60px</small>
                                         @error('admin_logo')
                                             <span class="text-danger d-block mt-2">{{ $message }}</span>
                                         @enderror

                                         <div wire:loading wire:target="admin_logo" class="mt-3">
                                             <div class="spinner-border spinner-border-sm text-primary"></div>
                                             <span class="ms-2 text-muted">Uploading...</span>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>

                     </div>
                 </div>
             </div>
         </form>
     </div>
 </div>

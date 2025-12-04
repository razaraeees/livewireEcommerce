<div>
    <div class="dashboard-page-content">

        <!-- FORM START -->
        <form wire:submit.prevent="store">

            <div class="row mb-9 align-items-center">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-sm-6 mb-8 mb-sm-0">
                            <h2 class="fs-4 mb-0">General Settings</h2>
                        </div>

                        <div class="col-sm-6 d-flex flex-wrap justify-content-sm-end">
                            <!-- Save Button -->
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="store">Save Settings</span>
                                <span wire:loading wire:target="store">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Saving...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MAIN CONTENT -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-8 rounded-4">
                        <div class="card-header p-7 bg-transparent">
                            <h4 class="fs-18 mb-0 font-weight-500">Currency & Location Settings</h4>
                        </div>
                        <div class="card-body p-7">
                            <div class="row">

                                <!-- Currency Dropdown with Search -->
                                <div class="col-lg-6">
                                    <div class="mb-8">
                                        <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Currency</label>
                                        <div class="position-relative">
                                            @if ($currency && !$showCurrencyDropdown)
                                                <!-- Display selected currency -->
                                                <div class="form-control d-flex justify-content-between align-items-center"
                                                    style="cursor: pointer;"
                                                    wire:click="$set('showCurrencyDropdown', true)">
                                                    <span><strong>{{ $currency }}</strong> <span
                                                            class="badge bg-primary">{{ $currency_symbol }}</span></span>
                                                    <i class="fas fa-edit text-muted"></i>
                                                </div>
                                            @else
                                                <!-- Search input -->
                                                <input type="text" wire:model.live="searchCurrency"
                                                    class="form-control" placeholder="Search currency..."
                                                    autocomplete="off">
                                            @endif

                                            <!-- Dropdown List -->
                                            @if ($showCurrencyDropdown || $searchCurrency)
                                                <div class="position-absolute w-100 bg-white border rounded shadow-lg mt-1"
                                                    style="max-height: 300px; overflow-y: auto; z-index: 1000;">
                                                    @forelse($this->currencies as $curr)
                                                        <div wire:click="selectCurrency('{{ $curr->code }}', '{{ $curr->symbol }}')"
                                                            class="p-3 border-bottom cursor-pointer hover-bg-light"
                                                            style="cursor: pointer;">
                                                            <strong>{{ $curr->name }}</strong> ({{ $curr->code }})
                                                            <span
                                                                class="badge bg-secondary ms-2">{{ $curr->symbol }}</span>
                                                        </div>
                                                    @empty
                                                        <div class="p-3 text-muted">No currencies found</div>
                                                    @endforelse
                                                </div>
                                            @endif

                                        </div>
                                        @error('currency')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Country Code Dropdown with Search -->
                                <div class="col-lg-6">
                                    <div class="mb-8">
                                        <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Country Code</label>
                                        <div class="position-relative">
                                            @if ($country_code && !$showCountryDropdown)
                                                <!-- Display selected country code -->
                                                <div class="form-control d-flex justify-content-between align-items-center"
                                                    style="cursor: pointer;"
                                                    wire:click="$set('showCountryDropdown', true)">
                                                    <span><strong
                                                            class="text-primary">{{ $country_code }}</strong></span>
                                                    <i class="fas fa-edit text-muted"></i>
                                                </div>
                                            @else
                                                <!-- Search input -->
                                                <input type="text" wire:model.live="searchCountry"
                                                    class="form-control" placeholder="Search country..."
                                                    autocomplete="off">
                                            @endif

                                            <!-- Dropdown List -->
                                            @if ($showCountryDropdown || $searchCountry)
                                                <div class="position-absolute w-100 bg-white border rounded shadow-lg mt-1"
                                                    style="max-height: 300px; overflow-y: auto; z-index: 1000;">
                                                    @forelse($this->countries as $country)
                                                        <div wire:click="selectCountry('{{ $country->name }}', '{{ $country->code }}')"
                                                            class="p-3 border-bottom cursor-pointer hover-bg-light"
                                                            style="cursor: pointer;">
                                                            <strong>{{ $country->name }}</strong>
                                                            <span
                                                                class="badge bg-primary ms-2">{{ $country->code }}</span>
                                                        </div>
                                                    @empty
                                                        <div class="p-3 text-muted">No countries found</div>
                                                    @endforelse
                                                </div>
                                            @endif

                                        </div>
                                        @error('country_code')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="col-lg-6">
                                    <div class="mb-8">
                                        <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Phone Number</label>
                                        <input type="text" wire:model="phone" class="form-control"
                                            placeholder="Enter phone number">
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-lg-6">
                                    <div class="mb-8">
                                        <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Email Address</label>
                                        <input type="email" wire:model="email" class="form-control"
                                            placeholder="Enter email address">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="col-lg-12">
                                    <div class="mb-8">
                                        <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Business Address</label>
                                        <textarea wire:model="address" class="form-control" rows="4" placeholder="Enter complete business address"></textarea>
                                        @error('address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
        <!-- FORM END -->

    </div>

    <style>
        .hover-bg-light:hover {
            background-color: #f8f9fa !important;
        }
    </style>
</div>

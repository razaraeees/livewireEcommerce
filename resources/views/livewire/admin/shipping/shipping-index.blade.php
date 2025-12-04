<div>
    <div class="dashboard-page-content">

        <!-- FORM START -->
        <form wire:submit.prevent="store">

            <div class="row mb-9 align-items-center">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-sm-6 mb-8 mb-sm-0">
                            <h2 class="fs-4 mb-0">Shipping Settings</h2>
                            <p class="mb-0">Manage shipping settings for your store.</p>
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
                            <h4 class="fs-18 mb-0 font-weight-500">Shipping Settings</h4>
                        </div>
                        <div class="card-body p-7">
                            <div class="row">

                                <!-- Fee -->
                                <div class="col-lg-6">
                                    <div class="mb-8">
                                        <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Fee</label>
                                        <input type="number" step="0.01" wire:model="fee" class="form-control" placeholder="e.g. 5.99">
                                        @error('fee') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Type -->
                                <div class="col-lg-6">
                                    <div class="mb-8">
                                        <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Type</label>
                                        <select wire:model="type" class="form-control">
                                            <option value="">-- Select Type --</option>
                                            <option value="flat">Flat Rate</option>
                                            <option value="percentage">Percentage</option>
                                        </select>
                                        @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Max Order Amount -->
                                <div class="col-lg-6">
                                    <div class="mb-8">
                                        <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Max Order Amount</label>
                                        <input type="number" step="0.01" wire:model="max_order_amount" class="form-control" placeholder="Leave blank for no limit">
                                        @error('max_order_amount') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-lg-6">
                                    <div class="mb-8">
                                        <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Status</label>
                                        <select wire:model="status" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
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

<div class="col-sm-6 col-xxl-3 mb-7">
    <div class="card rounded-4">
        <div class="card-body p-7">
            <div class="d-flex">
                <div class="me-6">
                    <span
                        class="square d-flex align-items-center justify-content-center fs-5 badge rounded-circle {{ $textColor }} {{ $bgColor }}"
                        style="--square-size: 48px">

                        {!! $icon !!}

                    </span>
                </div>
                <div class="media-body">
                    <h6 class="mb-4 card-title">{{ $title }}</h6>
                    <span class="fs-4 d-block font-weight-500 text-primary lh-12">${{ $value }}</span>
                    <span class="fs-14px">{{ $subtitle }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

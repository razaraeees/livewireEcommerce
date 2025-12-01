
<div class="dashboard-page-content">

    <div class="row mb-9 align-items-center">

        <div class="col-sm-6 mb-8 mb-sm-0">
            <h2 class="fs-4 mb-0">Dashboard</h2>
            <p class="mb-0">Whole data about your business here</p>
        </div>
        <div class="col-sm-6 d-flex flex-wrap justify-content-sm-end">


            <a href="#" class="btn btn-primary">

                <svg class="icon mt-n3">
                    <use xlink:href="#file-plus"></use>
                </svg>

                <span class="d-inline-block ml-1">Create report</span>

            </a>

        </div>
    </div>
        <div class="row">
            <livewire:admin.dashboard.stats-card
                title="Revenue"
                value="13,456.5"
                subtitle="Shipping fees are not included."
                icon='<i class="fas fa-dollar-sign"></i>'
                textColor="text-green"
                bgColor="bg-green-light"
            />
            <livewire:admin.dashboard.stats-card
                title="Orders"
                value="53.668"
                subtitle="Excluding orders in transit."
                icon='<i class="fas fa-truck"></i>'
                textColor="text-success"
                bgColor="bg-success-light"
            />
            <livewire:admin.dashboard.stats-card
                title="Products"
                value="9.856"
                subtitle="In 19 Categories."
                icon='<i class="fas fa-qrcode"></i>'
                textColor="text-warning"
                bgColor="bg-warning-light"
            />
            <livewire:admin.dashboard.stats-card
                title="Monthly Earning"
                value="6,982"
                subtitle="Based in your local time."
                icon='<i class="fas fa-shopping-bag"></i>'
                textColor="text-info"
                bgColor="bg-info-light"
            />
            
        </div>
    <div class="row">
        <div class="col-xl-8">
            <div class="card rounded-4 p-7 mb-7">
                <h5 class="card-title fs-6 mb-6">Sale statistics</h5>
                <div class="card-body p-0">
                    <canvas id="mychart" class="chartjs" data-chart-type="line"
                        data-chart-labels='["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"]'
                        data-chart-options='{"elements":{"line":{"tension":0.3}},"plugins":{"legend":{"labels":{"usePointStyle":true}}},"scales":{"y":{"ticks":{"display":true},"grid":{"display":true,"drawBorder":false,"drawTicks":true}},"x":{"ticks":{"display":true},"grid":{"display":true,"drawBorder":false,"drawTicks":true}}}}'
                        data-chart-datasets='[{"label":"Sales","data":[18,17,4,3,2,20,25,31,25,22,20,9],"backgroundColor":"#2C78DC33","hoverBackgroundColor":"#2C78DC33","borderColor":"#2C78DC","hoverBorderColor":"#2C78DC","borderWidth":1,"fill":true},{"label":"Visitors","data":[40,20,17,9,23,35,39,30,34,25,27,17],"backgroundColor":"#04D18233","hoverBackgroundColor":"#04D18233","borderColor":"#04D182","hoverBorderColor":"#04D182","borderWidth":1,"fill":true},{"label":"Products","data":[30,10,27,19,33,15,19,20,24,15,37,6],"backgroundColor":"#EF287830","hoverBackgroundColor":"#EF287830","borderColor":"#EF287391","hoverBorderColor":"#EF287391","borderWidth":1,"fill":true}]'
                        data-chart-additional-options='{"chatId":"mychart"}' height="265"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card rounded-4 p-7 mb-7">
                <h5 class="card-title fs-6 mb-6">Revenue Base on Area</h5>
                <div class="card-body p-0">

                    <canvas id="mychart01" class="chartjs" data-chart-type="bar"
                        data-chart-labels='["900","1200","1400","1600"]'
                        data-chart-options='{"plugins":{"legend":{"labels":{"usePointStyle":true}}},"scales":{"y":{"ticks":{"display":true},"grid":{"display":true,"drawBorder":false,"drawTicks":true}},"x":{"ticks":{"display":true},"grid":{"display":true,"drawBorder":false,"drawTicks":true}}}}'
                        data-chart-datasets='[{"label":"US","data":[233,321,783,900],"backgroundColor":"#5897FB","hoverBackgroundColor":"#5897FB","borderColor":"#5897FB","hoverBorderColor":"#5897FB","borderWidth":1,"fill":true},{"label":"Europe","data":[408,547,675,734],"backgroundColor":"#7BCF86","hoverBackgroundColor":"#7BCF86","borderColor":"#7BCF86","hoverBorderColor":"#7BCF86","borderWidth":1,"fill":true},{"label":"Asian","data":[208,447,575,634],"backgroundColor":"#FF9076","hoverBackgroundColor":"#FF9076","borderColor":"#FF9076","hoverBorderColor":"#FF9076","borderWidth":1,"fill":true},{"label":"Africa","data":[123,345,122,302],"backgroundColor":"#D595E5","hoverBackgroundColor":"#D595E5","borderColor":"#D595E5","hoverBorderColor":"#D595E5","borderWidth":"1","fill":true}]'
                        data-chart-additional-options='{"chatId":"mychart01"}' height="222"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4 rounded-4 p-7">
        <div class="card-header bg-transparent px-0 pt-0 pb-7">
            <h4 class="card-title fs-18px mb-6">Latest orders</h4>
            <div class="row align-items-center">
                <div class="col-md-3 col-12 me-auto mb-md-0 mb-6">
                    <select class="form-select">
                        <option selected="" data-select2-id="3">All Categories</option>
                        <option>Women's Clothing</option>
                        <option>Men's Clothing</option>
                        <option>Cellphones</option>
                        <option>Computer &amp; Office</option>
                        <option>Consumer Electronics</option>
                        <option>Jewelry &amp; Accessories</option>
                        <option>Home &amp; Garden</option>
                        <option>Luggage &amp; Bags</option>
                        <option>Shoes</option>
                        <option>Mother &amp; Kids</option>
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <input type="date" class="form-control bg-input border-0">
                </div>
                <div class="col-md-2 col-6">
                    <select class="form-select">
                        <option>Status</option>
                        <option>All</option>
                        <option>Paid</option>
                        <option>Chargeback</option>
                        <option>Refund</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body px-0 pt-7 pb-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-nowrap mb-0 table-borderless">
                    <thead class="table-light">
                        <tr>

                            <th scope="col" class="text-center">
                                <div class="form-check align-middle">
                                    <input class="form-check-input rounded-0 ms-0" type="checkbox"
                                        id="transactionCheck01">
                                    <label class="form-check-label" for="transactionCheck01"></label>
                                </div>
                            </th>
                            <th class="align-middle" scope="col">Order ID
                            </th>
                            <th class="align-middle" scope="col">Billing Name
                            </th>
                            <th class="align-middle" scope="col">Date
                            </th>
                            <th class="align-middle" scope="col">Total
                            </th>
                            <th class="align-middle" scope="col">Payment Status
                            </th>
                            <th class="align-middle" scope="col">Payment Method
                            </th>
                            <th class="align-middle text-center" scope="col">View Details
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>

                            <td class="text-center">
                                <div class="form-check">
                                    <input class="form-check-input rounded-0 ms-0" type="checkbox"
                                        id="transactionCheck-0">
                                    <label class="form-check-label" for="transactionCheck-0"></label>
                                </div>
                            </td>
                            <td><a href="#">#SK2540</a></td>

                            <td class="text-body-emphasis">Neal Matthews</td>
                            <td>07 Oct, 2021</td>
                            <td>$400</td>
                            <td>
                                <span
                                    class="badge rounded-lg badge-soft-success border-0 text-capitalize fs-12">paid</span>
                            </td>
                            <td>
                                <svg class="icon me-4">
                                    <use xlink:href="#credit-card"></use>
                                </svg>
                                Mastercard
                            </td>

                            <td class="text-center">
                                <a href="order-detail.html" class="btn btn-primary fs-13px btn-xs py-4"> View
                                    details</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <nav aria-label="Page navigation example" class="mt-6 mb-4">
        <ul class="pagination justify-content-start">
            <li class="page-item active mx-3"><a class="page-link" href="#">01</a></li>
            <li class="page-item mx-3"><a class="page-link" href="#">02</a></li>
            <li class="page-item mx-3"><a class="page-link" href="#">03</a></li>
            <li class="page-item mx-3"><a class="page-link dot" href="#">...</a></li>
            <li class="page-item mx-3"><a class="page-link" href="#">16</a></li>
            <li class="page-item mx-3">
                <a class="page-link" href="#"><i class="far fa-chevron-right"></i></a>
            </li>
        </ul>
    </nav>
</div>

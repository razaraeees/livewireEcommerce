<div>
    <div class="dashboard-page-content">

        <div class="row mb-9 align-items-center">
            <div class="col-12 col-md-3 mb-4 mb-md-0">
                <h2 class="fs-4 mb-0">Dashboard</h2>
                <p class="mb-0">Whole data about your business here</p>
            </div>

            <div class="col-12 col-md-9 d-flex flex-wrap align-items-end justify-content-md-end gap-2">
                <button type="button" class="btn btn-outline-primary d-flex align-items-center mb-2 mb-md-0"
                    data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="far fa-filter me-2"></i>
                    <span>Filters</span>
                </button>

                {{-- <a href="#" class="btn btn-primary d-flex align-items-center mb-2 mb-md-0">
                    <svg class="icon" style="width: 16px; height: 16px;">
                        <use xlink:href="#file-plus"></use>
                    </svg>
                    <span class="ms-2">Create report</span>
                </a> --}}
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row my-5">
            <livewire:admin.dashboard.stats-card :key="'revenue-' . $dateFrom . '-' . $dateTo" title="Revenue" :value="formatCurrency($revenue)"
                subtitle="Based on selected date range" icon='<i class="fas fa-dollar-sign"></i>' textColor="text-green"
                bgColor="bg-green-light" />

            <livewire:admin.dashboard.stats-card :key="'orders-' . $dateFrom . '-' . $dateTo" title="Total Orders" :value="$ordersCount"
                subtitle="Total orders in date range" icon='<i class="fas fa-truck"></i>' textColor="text-success"
                bgColor="bg-success-light" />

            <livewire:admin.dashboard.stats-card :key="'products-' . $dateFrom . '-' . $dateTo" title="Products" :value="$productsCount"
                subtitle="Total products available" icon='<i class="fas fa-qrcode"></i>' textColor="text-warning"
                bgColor="bg-warning-light" />

            <livewire:admin.dashboard.stats-card :key="'paid-' . $dateFrom . '-' . $dateTo" title="Paid Orders" :value="formatCurrency($monthlyEarning)"
                subtitle="Successfully paid orders" icon='<i class="fas fa-shopping-bag"></i>' textColor="text-info"
                bgColor="bg-info-light" />
        </div>

        <!-- Charts Row 1 -->
        <div class="row">
            <!-- Area Chart -->
            <div class="col-xl-8 mb-4">
                <div class="card rounded-4 p-7">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title fs-6 mb-0">Sales & Orders</h5>
                        <span class="badge bg-primary">{{ $dateFrom }} to {{ $dateTo }}</span>
                    </div>
                    <div class="card-body p-0" wire:ignore>
                        <div style="position: relative; height: 300px;">
                            <canvas id="areaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Polar Area Chart -->
            <div class="col-xl-4 mb-4">
                <div class="card rounded-4 p-7">
                    <h5 class="card-title fs-6 mb-4">Revenue Distribution</h5>
                    <div class="card-body p-0" wire:ignore>
                        <div style="position: relative; height: 300px;">
                            <canvas id="polarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="row">
            <!-- Radar Chart -->
            <div class="col-xl-4 mb-4">
                <div class="card rounded-4 p-7">
                    <h5 class="card-title fs-6 mb-4">Weekly Performance</h5>
                    <div class="card-body p-0" wire:ignore>
                        <div style="position: relative; height: 300px;">
                            <canvas id="radarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mixed Bar + Line Chart -->
            <div class="col-xl-8 mb-4">
                <div class="card rounded-4 p-7">
                    <h5 class="card-title fs-6 mb-4">Daily Orders Analysis</h5>
                    <div class="card-body p-0" wire:ignore>
                        <div style="position: relative; height: 300px;">
                            <canvas id="mixedChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 3 -->
        <div class="row">
            <!-- Bubble Chart -->
            <div class="col-xl-6 mb-4">
                <div class="card rounded-4 p-7">
                    <h5 class="card-title fs-6 mb-4">Orders vs Revenue Scatter</h5>
                    <div class="card-body p-0" wire:ignore>
                        <div style="position: relative; height: 300px;">
                            <canvas id="bubbleChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horizontal Bar Chart -->
            <div class="col-xl-6 mb-4">
                <div class="card rounded-4 p-7">
                    <h5 class="card-title fs-6 mb-4">Top Performing Days</h5>
                    <div class="card-body p-0" wire:ignore>
                        <div style="position: relative; height: 300px;">
                            <canvas id="horizontalBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Modal -->
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title fw-semibold" id="filterModalLabel">
                            <i class="far fa-filter me-2 text-primary"></i>
                            Dashboard Filters
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-medium fs-13px mb-2">Date From</label>
                                <input type="date" wire:model="dateFrom" class="form-control bg-white">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-medium fs-13px mb-2">Date To</label>
                                <input type="date" wire:model="dateTo" class="form-control bg-white">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top bg-light">
                        <button type="button" wire:click="clearFilters" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">
                            <i class="far fa-times me-1"></i>
                            Clear & Reload
                        </button>
                        <button type="button" wire:click="applyFilters" class="btn btn-primary"
                            data-bs-dismiss="modal">
                            <i class="far fa-filter me-1"></i>
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        console.log('🚀 Dashboard script loaded');
        
        // GLOBAL chart storage
        window.dashboardCharts = window.dashboardCharts || {};
        let isUpdating = false;

        function destroyAllCharts() {
            console.log('🗑️ Destroying charts...');
            Object.keys(window.dashboardCharts).forEach(key => {
                if (window.dashboardCharts[key]) {
                    try {
                        window.dashboardCharts[key].destroy();
                    } catch (e) {}
                    window.dashboardCharts[key] = null;
                }
            });
        }

        function createAllCharts(labels, salesData, ordersData, paidRevenue, totalRevenue) {
            console.log('🎨 Creating charts...', {
                labels: labels.length,
                sales: salesData.length,
                orders: ordersData.length
            });

            const pendingRevenue = totalRevenue - paidRevenue;

            // 1. AREA CHART
            const areaCtx = document.getElementById('areaChart');
            if (areaCtx) {
                const ctx = areaCtx.getContext('2d');
                const gradient1 = ctx.createLinearGradient(0, 0, 0, 400);
                gradient1.addColorStop(0, 'rgba(54, 162, 235, 0.5)');
                gradient1.addColorStop(1, 'rgba(54, 162, 235, 0.0)');

                const gradient2 = ctx.createLinearGradient(0, 0, 0, 400);
                gradient2.addColorStop(0, 'rgba(255, 99, 132, 0.5)');
                gradient2.addColorStop(1, 'rgba(255, 99, 132, 0.0)');

                window.dashboardCharts.area = new Chart(areaCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Sales Revenue',
                            data: salesData,
                            backgroundColor: gradient1,
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 6
                        }, {
                            label: 'Total Orders',
                            data: ordersData,
                            backgroundColor: gradient2,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: true, position: 'top' },
                            tooltip: { mode: 'index', intersect: false }
                        },
                        scales: {
                            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // 2. POLAR CHART
            const polarCtx = document.getElementById('polarChart');
            if (polarCtx) {
                window.dashboardCharts.polar = new Chart(polarCtx, {
                    type: 'polarArea',
                    data: {
                        labels: ['Paid Orders', 'Pending Orders', 'Processing'],
                        datasets: [{
                            data: [paidRevenue, pendingRevenue, totalRevenue * 0.1],
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(153, 102, 255, 0.7)'
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: true, position: 'bottom' } }
                    }
                });
            }

            // 3. RADAR CHART
            const radarCtx = document.getElementById('radarChart');
            if (radarCtx) {
                const last7Days = labels.slice(-7);
                const last7Sales = salesData.slice(-7);
                const last7Orders = ordersData.slice(-7);

                window.dashboardCharts.radar = new Chart(radarCtx, {
                    type: 'radar',
                    data: {
                        labels: last7Days,
                        datasets: [{
                            label: 'Sales',
                            data: last7Sales,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2
                        }, {
                            label: 'Orders',
                            data: last7Orders,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: true, position: 'top' } },
                        scales: { r: { beginAtZero: true, ticks: { backdropColor: 'transparent' } } }
                    }
                });
            }

            // 4. MIXED CHART
            const mixedCtx = document.getElementById('mixedChart');
            if (mixedCtx) {
                window.dashboardCharts.mixed = new Chart(mixedCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            type: 'bar',
                            label: 'Daily Orders',
                            data: ordersData,
                            backgroundColor: 'rgba(153, 102, 255, 0.7)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1,
                            borderRadius: 5
                        }, {
                            type: 'line',
                            label: 'Revenue Trend',
                            data: salesData,
                            borderColor: 'rgba(255, 159, 64, 1)',
                            backgroundColor: 'rgba(255, 159, 64, 0.2)',
                            borderWidth: 3,
                            fill: false,
                            tension: 0.4,
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: true, position: 'top' } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }

            // 5. BUBBLE CHART
            const bubbleCtx = document.getElementById('bubbleChart');
            if (bubbleCtx) {
                const bubbleData = labels.map((label, i) => ({
                    x: ordersData[i],
                    y: salesData[i],
                    r: Math.max(ordersData[i] * 2, 5)
                }));

                window.dashboardCharts.bubble = new Chart(bubbleCtx, {
                    type: 'bubble',
                    data: {
                        datasets: [{
                            label: 'Orders vs Sales',
                            data: bubbleData,
                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: true },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Orders: ${context.raw.x}, Sales: ${context.raw.y}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: { title: { display: true, text: 'Number of Orders' } },
                            y: { title: { display: true, text: 'Revenue (PKR)' } }
                        }
                    }
                });
            }

            // 6. HORIZONTAL BAR CHART
            const hBarCtx = document.getElementById('horizontalBarChart');
            if (hBarCtx) {
                const combined = labels.map((label, i) => ({
                    label: label,
                    value: salesData[i]
                }));
                combined.sort((a, b) => b.value - a.value);
                const top5 = combined.slice(0, 5);

                window.dashboardCharts.hbar = new Chart(hBarCtx, {
                    type: 'bar',
                    data: {
                        labels: top5.map(d => d.label),
                        datasets: [{
                            label: 'Revenue',
                            data: top5.map(d => d.value),
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.7)',
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(75, 192, 192, 0.7)',
                                'rgba(153, 102, 255, 0.7)'
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { x: { beginAtZero: true } }
                    }
                });
            }

            console.log('✅ All charts created!');
        }

        function updateCharts(labels, salesData, ordersData, paidRevenue, totalRevenue) {
            if (isUpdating) return;
            isUpdating = true;
            
            console.log('📊 UPDATING CHARTS NOW!', {
                labels: labels?.length,
                sales: salesData?.length,
                orders: ordersData?.length,
                revenue: totalRevenue
            });

            destroyAllCharts();
            createAllCharts(labels, salesData, ordersData, paidRevenue, totalRevenue);
            
            setTimeout(() => { isUpdating = false; }, 300);
        }

        // INITIAL LOAD
        document.addEventListener('DOMContentLoaded', function() {
            console.log('📱 DOM Ready - Loading initial charts');
            
            const labels = @json($chartLabels);
            const salesData = @json($salesChartData);
            const ordersData = @json($visitorsChartData);
            const paidRevenue = {{ $monthlyEarning }};
            const totalRevenue = {{ $revenue }};

            updateCharts(labels, salesData, ordersData, paidRevenue, totalRevenue);
        });

        // LIVEWIRE EVENT LISTENER - Multiple methods for compatibility
        if (typeof Livewire !== 'undefined') {
            // Method 1: Direct Livewire.on
            Livewire.on('refreshCharts', (data) => {
                console.log('🔥 LIVEWIRE EVENT CAUGHT!', data);
                const eventData = Array.isArray(data) ? data[0] : data;
                updateCharts(
                    eventData.labels,
                    eventData.salesData,
                    eventData.ordersData,
                    eventData.paidRevenue,
                    eventData.totalRevenue
                );
            });
            console.log('✅ Livewire listener registered');
        }

        // Method 2: Init hook (backup)
        document.addEventListener('livewire:init', () => {
            console.log('🔌 Livewire initialized');
            Livewire.on('refreshCharts', (event) => {
                console.log('🔥 INIT HOOK CAUGHT!', event);
                const data = event[0] || event;
                updateCharts(
                    data.labels,
                    data.salesData,
                    data.ordersData,
                    data.paidRevenue,
                    data.totalRevenue
                );
            });
        });

        // Method 3: Browser event (ultimate backup)
        window.addEventListener('chartDataUpdated', (e) => {
            console.log('🌐 BROWSER EVENT CAUGHT!', e.detail);
            const data = e.detail;
            updateCharts(
                data.labels,
                data.salesData,
                data.ordersData,
                data.paidRevenue,
                data.totalRevenue
            );
        });

        console.log('✅ All event listeners registered');
    </script>
    @endpush

</div>

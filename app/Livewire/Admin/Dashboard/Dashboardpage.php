<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Dashboardpage extends Component
{
    public $dateFrom;
    public $dateTo;

    public function mount()
    {
        $this->dateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
    }

    public function applyFilters()
    {
        if ($this->dateFrom && $this->dateTo) {
            $from = Carbon::parse($this->dateFrom);
            $to = Carbon::parse($this->dateTo);

            if ($from->gt($to)) {
                $temp = $this->dateFrom;
                $this->dateFrom = $this->dateTo;
                $this->dateTo = $temp;
            }
        }

        // Dispatch fresh chart data
        $this->dispatchChartData();
    }

    public function clearFilters()
    {
        $this->dateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');

        // Dispatch fresh chart data
        $this->dispatchChartData();
    }

    private function dispatchChartData()
    {
        $startDate = Carbon::parse($this->dateFrom);
        $endDate = Carbon::parse($this->dateTo);

        // Get orders data
        $ordersData = Order::whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->selectRaw('DATE(created_at) as date, COALESCE(SUM(grand_total), 0) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date');

        $salesChartData = [];
        $chartLabels = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateKey = $date->format('Y-m-d');
            $salesChartData[] = (float) ($ordersData->get($dateKey, 0));
            $chartLabels[] = $date->format('M d');
        }

        $ordersCountData = Order::whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        $visitorsChartData = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateKey = $date->format('Y-m-d');
            $visitorsChartData[] = (int) ($ordersCountData->get($dateKey, 0));
        }

        $revenue = Order::whereBetween('created_at', $this->getDateRange())->sum('grand_total') ?? 0;
        $monthlyEarning = Order::whereBetween('created_at', $this->getDateRange())
            ->where('payment_status', 'paid')
            ->sum('grand_total') ?? 0;

        // Log for debugging
        Log::info('📤 Dispatching chart data', [
            'labels_count' => count($chartLabels),
            'sales_count' => count($salesChartData),
            'orders_count' => count($visitorsChartData),
            'revenue' => $revenue
        ]);

        // TRIPLE DISPATCH for maximum compatibility
        
        // 1. Standard Livewire dispatch
        $this->dispatch('refreshCharts', 
            labels: $chartLabels,
            salesData: $salesChartData,
            ordersData: $visitorsChartData,
            paidRevenue: $monthlyEarning,
            totalRevenue: $revenue
        );

        // 2. JavaScript eval dispatch (guaranteed to work)
        $this->js("
            console.log('🚀 JS DISPATCH TRIGGERED');
            if (typeof updateCharts === 'function') {
                updateCharts(
                    " . json_encode($chartLabels) . ",
                    " . json_encode($salesChartData) . ",
                    " . json_encode($visitorsChartData) . ",
                    {$monthlyEarning},
                    {$revenue}
                );
            }
            
            // Also fire browser event
            window.dispatchEvent(new CustomEvent('chartDataUpdated', {
                detail: {
                    labels: " . json_encode($chartLabels) . ",
                    salesData: " . json_encode($salesChartData) . ",
                    ordersData: " . json_encode($visitorsChartData) . ",
                    paidRevenue: {$monthlyEarning},
                    totalRevenue: {$revenue}
                }
            }));
        ");
    }

    private function getDateRange()
    {
        return [
            Carbon::parse($this->dateFrom)->startOfDay(),
            Carbon::parse($this->dateTo)->endOfDay()
        ];
    }

    public function render()
    {
        $startDate = Carbon::parse($this->dateFrom);
        $endDate = Carbon::parse($this->dateTo);

        // Revenue
        $revenue = Order::whereBetween('created_at', $this->getDateRange())
            ->sum('grand_total') ?? 0;

        // Orders count
        $ordersCount = Order::whereBetween('created_at', $this->getDateRange())
            ->count();

        // Products count
        $productsCount = Product::count();

        // Monthly earning
        $monthlyEarning = Order::whereBetween('created_at', $this->getDateRange())
            ->where('payment_status', 'paid')
            ->sum('grand_total') ?? 0;

        // Chart data
        $ordersData = Order::whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->selectRaw('DATE(created_at) as date, COALESCE(SUM(grand_total), 0) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date');

        $salesChartData = [];
        $chartLabels = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateKey = $date->format('Y-m-d');
            $salesChartData[] = (float) ($ordersData->get($dateKey, 0));
            $chartLabels[] = $date->format('M d');
        }

        $ordersCountData = Order::whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        $visitorsChartData = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateKey = $date->format('Y-m-d');
            $visitorsChartData[] = (int) ($ordersCountData->get($dateKey, 0));
        }

        $latestOrders = Order::with('user')
            ->whereBetween('created_at', $this->getDateRange())
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.admin.dashboard.dashboardpage', [
            'revenue' => $revenue,
            'ordersCount' => $ordersCount,
            'productsCount' => $productsCount,
            'monthlyEarning' => $monthlyEarning,
            'chartLabels' => $chartLabels,
            'salesChartData' => $salesChartData,
            'visitorsChartData' => $visitorsChartData,
            'latestOrders' => $latestOrders,
        ]);
    }
}
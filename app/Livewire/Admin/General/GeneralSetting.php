<?php

namespace App\Livewire\Admin\General;

use App\Models\Country;
use App\Models\Currency;
use App\Models\GeneralSetting as GeneralSettingModel;
use Livewire\Component;

class GeneralSetting extends Component
{
    public $currency;
    public $currency_symbol;
    public $country_code;
    public $phone;
    public $email;
    public $address;

    public $searchCurrency = '';
    public $searchCountry = '';
    public $showCurrencyDropdown = false;
    public $showCountryDropdown = false;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $settings = GeneralSettingModel::first();

        if ($settings) {
            $this->currency = $settings->currency;
            $this->currency_symbol = $settings->currency_symbol;
            $this->country_code = $settings->country_code;
            $this->phone = $settings->phone;
            $this->email = $settings->email;
            $this->address = $settings->address;
        }
    }

    public function selectCurrency($currency, $symbol)
    {
        $this->currency = $currency;
        $this->currency_symbol = $symbol;
        $this->showCurrencyDropdown = false;
        $this->searchCurrency = '';
    }

    public function selectCountry($countryName, $code)
    {
        $this->country_code = $code;
        $this->showCountryDropdown = false;
        $this->searchCountry = '';
    }

    public function getCountriesProperty()
    {
        $query = Country::active()->orderBy('name');

        if ($this->searchCountry) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchCountry . '%')
                    ->orWhere('code', 'like', '%' . $this->searchCountry . '%');
            });
        }

        return $query->get();
    }

    public function getCurrenciesProperty()
    {
        $query = Currency::active()->orderBy('name');

        if ($this->searchCurrency) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchCurrency . '%')
                    ->orWhere('code', 'like', '%' . $this->searchCurrency . '%');
            });
        }

        return $query->get();
    }

    public function store()
    {
        $validated = $this->validate([
            'currency' => 'nullable|string|max:255',
            'currency_symbol' => 'nullable|string|max:10',
            'country_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $settings = GeneralSettingModel::first();

        if ($settings) {
            $settings->update($validated);
            $this->dispatch('show-toast', type: 'success', message: 'General Settings Updated Successfully!');
        } else {
            GeneralSettingModel::create($validated);
            $this->dispatch('show-toast', type: 'success', message: 'General Settings Created Successfully!');
        }
    }

    public function render()
    {
        return view('livewire.admin.general.general-setting');
    }
}

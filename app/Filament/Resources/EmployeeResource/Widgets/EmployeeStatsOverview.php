<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;
use App\Models\Employee;
use App\Models\Country;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {   
        $mx=Country::where('country_code','mx')->withCount('employees')->first();
        $pe=Country::where('country_code','pe')->withCount('employees')->first();
        return [
            Stat::make('Total Empleados', Employee::all()->count()),
            Stat::make($mx->name . ' Empleados', $mx->employees_count),
            Stat::make($pe->name . ' Empleados', $pe->employees_count),
        ];
    }
}

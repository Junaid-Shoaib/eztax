<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function index(Request $request)
    {
        return view('calculator.index');
    }

    public function calculate(Request $request)
{
    $monthly_income = (float) $request->income;
    $salary = $monthly_income * 12; // yearly salary
    $tax = 0;

    if ($salary <= 600000) {
        $tax = 0;
    } elseif ($salary <= 1200000) {
        $tax = ($salary - 600000) * 0.05;
    } elseif ($salary <= 2200000) {
        $tax = 30000 + ($salary - 1200000) * 0.15;
    } elseif ($salary <= 3200000) {
        $tax = 180000 + ($salary - 2200000) * 0.25;
    } elseif ($salary <= 4100000) {
        $tax = 430000 + ($salary - 3200000) * 0.30;
    } else {
        $tax = 700000 + ($salary - 4100000) * 0.35;
    }

    $monthly_tax = $tax / 12;
    $salary_after_tax = $monthly_income - $monthly_tax;
    $yearly_salary_after_tax = $salary - $tax;

    return response()->json([
        'monthly_income' => number_format($monthly_income),
        'monthly_tax' => number_format($monthly_tax, 2),
        'salary_after_tax' => number_format($salary_after_tax, 2),
        'yearly_income' => number_format($salary),
        'yearly_tax' => number_format($tax, 2),
        'yearly_income_after_tax' => number_format($yearly_salary_after_tax, 2),
    ]);
}

    

    

}

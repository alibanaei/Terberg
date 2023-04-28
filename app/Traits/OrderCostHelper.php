<?php

namespace App\Traits;

trait OrderCostHelper
{
    public function calculateOrderCost()
    {
        return $this->calculationServicesCost() +
            $this->calculationProductsCost() +
            $this->calculationOptionsCost();
    }

    private function calculationProductsCost()
    {
        return $this->products()->sum('price');
    }

    private function calculationServicesCost()
    {
        return $this->services()->sum('price');
    }

    private function calculationOptionsCost()
    {
        return $this->options()->sum('price');
    }
}

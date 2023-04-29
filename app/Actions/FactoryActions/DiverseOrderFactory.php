<?php

namespace App\Actions\FactoryActions;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

abstract class DiverseOrderFactory implements ResourceFactory
{
    protected Order $order;

    protected array $data;

    public function __construct()
    {
        $this->order = new Order();
    }

    abstract function addOrderItems();

    abstract function determineOrderType();

    public function createResource(array $data): Order
    {
        $this->data = $data;

        $this->createOrder();

        $this->addOrderItems();

        $this->addOrderOptions();

        $this->calculateOrderCost();

        $this->updateOrderStatus();

        $this->order->update();

        return $this->order;
    }

    private function createOrder()
    {
        $this->order->user_id = Auth::id();
        $this->order->status = OrderStatusEnum::Initialized->value;
        $this->determineOrderType();

        $this->order->save();
    }

    private function addOrderOptions()
    {
        $optionIds = $this->data['optionIds'] ?? [];
        $this->order->options()->sync($optionIds);
    }

    private function calculateOrderCost()
    {
        $this->order->cost = $this->order->calculateOrderCost();
    }

    private function updateOrderStatus()
    {
        $this->order->status = OrderStatusEnum::Pending->value;
    }
}

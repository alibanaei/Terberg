<?php

namespace App\Actions\FactoryActions;
use App\Enums\OrderStatusEnum;
use App\Enums\OrderTypeEnum;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderFactory implements ResourceFactory
{

    protected Order $order;

    protected array $data;

    public function __construct()
    {
        $this->order = new Order();
    }

    public function createResource(array $data): Order
    {
        $this->data = $data;

        $this->initializingOrder();

        $this->completeOrderProcess();

        return $this->order;
    }

    private function initializingOrder()
    {
        $this->order->user_id = Auth::id();
        $this->order->type = $this->data['type'];
        $this->order->status = OrderStatusEnum::Initialized->value;
        $this->order->save();
    }

    private function completeOrderProcess()
    {
        $this->addOrderItems();
        $this->addOrderOptions();
        $this->calculateOrderCost();
        $this->updateOrderStatus();
        $this->order->save();
    }

    private function addOrderItems()
    {
        if ($this->order->type == OrderTypeEnum::Service->value) {
            $this->addOrderServices();
        } else if ($this->order->type == OrderTypeEnum::Product->value) {
            $this->addOrderProducts();
        }

    }

    private function addOrderProducts()
    {
        $serviceIds = $this->data['productIds'];
        $this->order->products()->sync($serviceIds);
    }

    private function addOrderServices()
    {
        $serviceIds = $this->data['serviceIds'];
        $this->order->services()->sync($serviceIds);
    }

    private function addOrderOptions()
    {
        $optionIds = $this->data['optionIds'];
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

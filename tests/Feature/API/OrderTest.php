<?php

namespace Tests\Feature\API;

use App\Enums\OrderStatusEnum;
use App\Enums\OrderTypeEnum;
use App\Models\Option;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::factory()->create();
    }


    # tests for index route

    public function test_order_index__unauthorized_for_unauthenticated_user()
    {
        $response = $this->getJson(route('order.index'));
        $response->assertUnauthorized();
    }

    public function test_order_index__works_properly_for_authenticated_user()
    {
        $response = $this->actingAs($this->user)->getJson(route('order.index'));
        $response->assertOk();
    }

    public function test_order_index__no_data_works_properly()
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('order.index'));

        $response->assertOk()
            ->assertJsonCount(0, 'orders');
    }

    public function test_order_index__pagination_works_properly()
    {
        Order::factory(12)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->getJson(route('order.index'));

        $response->assertOk()
            ->assertJsonCount(10, 'orders');

        $response = $this->actingAs($this->user)
            ->getJson(route('order.index', ['page' => 2]));

        $response->assertOk()
            ->assertJsonCount(2, 'orders');
    }

    public function test_order_index__pagination_with_custom_per_page_count_works_properly()
    {
        $perPage = 20;

        Order::factory(25)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->getJson(route('order.index', ['per_page' => $perPage]));

        $response->assertOk()
            ->assertJsonCount(20, 'orders');

        $response = $this->actingAs($this->user)
            ->getJson(route('order.index', ['page' => 2, 'per_page' => $perPage]));

        $response->assertOk()
            ->assertJsonCount(5, 'orders');
    }


    # tests for show route

    public function test_order_show__unauthorized_for_unauthenticated_user()
    {
        $orderId = Order::factory()->create(['user_id' => $this->user->id])->first()->id;

        $response = $this->getJson(route('order.show', ['order' => $orderId]));

        $response->assertUnauthorized();
    }

    public function test_order_show__is_forbidden_for_different_user()
    {
        $otherUser = User::factory()->create()->first();

        $orderId = Order::factory()->create(['user_id' => $otherUser])->first()->id;


        $response = $this->actingAs($this->user)->getJson(route('order.show', ['order' => $orderId]));

        $response->assertForbidden();
    }

    public function test_order_show__expect_not_found_for_not_exist_order()
    {
        $response = $this->actingAs($this->user)->getJson(route('order.show', ['order' => 1]));

        $response->assertNotFound();
    }

    public function test_order_show__user_order()
    {
        $orderId = Order::factory()->create(['user_id' => $this->user->id])->first()->id;

        $response = $this->actingAs($this->user)->getJson(route('order.show', ['order' => $orderId]));

        $response->assertOk()
            ->assertJsonFragment(['id' => $orderId]);
    }


    # test for store route

    public function test_order_store__unauthorized_for_unauthenticated_user()
    {
        $response = $this->postJson(route('order.store'));

        $response->assertUnauthorized();
    }

    public function test_order_store__cannot_submit_without_data()
    {
        $response = $this->actingAs($this->user)->postJson(route('order.store'));

        $response->assertUnprocessable();
    }

    public function test_order_store__submit_product_type_properly()
    {
        $data = $this->getRequiredResourceIdsForSubmittingOrder(['productIds', 'serviceIds']);

        $data['type'] = OrderTypeEnum::Product->value;

        $response = $this->actingAs($this->user)->postJson(route('order.store', $data));

        $response->assertOk()
            ->assertJsonFragment([
                'status' => OrderStatusEnum::Pending->displayName(),
                'type' => $data['type']
            ]);

        $orderId = $response->json('order.id');

        // order should be saved
        $this->assertDatabaseHas('orders', ['id' => $orderId, 'user_id' => $this->user->id]);

        // all products should be saved for the order
        foreach ($data['productIds'] as $productId) {
            $this->assertDatabaseHas('order_product', ['order_id' => $orderId, 'product_id' => $productId]);
        }

        // no service should be saved for the order
        $this->assertDatabaseMissing('order_service', ['order_id' => $orderId]);
    }

    public function test_order_store__submit_service_type_properly()
    {
        $data = $this->getRequiredResourceIdsForSubmittingOrder(['serviceIds', 'productIds']);

        $data['type'] = OrderTypeEnum::Service->value;

        $response = $this->actingAs($this->user)->postJson(route('order.store', $data));

        $response->assertOk()
            ->assertJsonFragment([
                'status' => OrderStatusEnum::Pending->displayName(),
                'type' => $data['type']
            ]);

        $orderId = $response->json('order.id');

        // order should be saved
        $this->assertDatabaseHas('orders', ['id' => $orderId, 'user_id' => $this->user->id]);

        // all services should be saved for the order
        foreach ($data['serviceIds'] as $serviceId) {
            $this->assertDatabaseHas('order_service', ['order_id' => $orderId, 'service_id' => $serviceId]);
        }

        // no product should be saved for the order
        $this->assertDatabaseMissing('order_product', ['order_id' => $orderId]);
    }

    public function test_order_store__calculate_cost_with_service_and_option_properly()
    {
        $requiredData = $this->getRequiredResourceIdsForSubmittingOrder();

        $type = OrderTypeEnum::Product->value;

        $data = [
            'productIds' => $requiredData['productIds'],
            'optionIds' => $requiredData['optionIds'],
            'type' => $type
        ];

        $response = $this->actingAs($this->user)->postJson(route('order.store', $data));


        $products = $requiredData['products'];
        $options = $requiredData['options'];
        $expectedOrderCost = $products->sum('price') + $options->sum('price');

        $response->assertOk()
            ->assertJsonFragment([
                'status' => OrderStatusEnum::Pending->displayName(),
                'type' => $type,
                'cost' => $expectedOrderCost
            ]);

        $orderId = $response->json('order.id');

        // order options should be saved
        foreach ($requiredData['optionIds'] as $optionId) {
            $this->assertDatabaseHas('order_option', ['order_id' => $orderId, 'option_id' => $optionId]);
        }
    }

    public function test_order_store__cannot_submit_order_with_deactivated_product_or_service()
    {
        $requiredData = $this->getRequiredResourceIdsForSubmittingOrder(activeItems: false);

        $data = [
            'productIds' => $requiredData['productIds'],
            'type' => OrderTypeEnum::Product->value
        ];

        $response = $this->actingAs($this->user)->postJson(route('order.store', $data));

        $response->assertUnprocessable();

        $data = [
            'serviceIds' => $requiredData['serviceIds'],
            'type' => OrderTypeEnum::Service->value
        ];

        $response = $this->actingAs($this->user)->postJson(route('order.store', $data));

        $response->assertUnprocessable();
    }

    public function test_order_store__cannot_submit_order_with_wrong_type_and_non_related_item()
    {
        $requiredData = $this->getRequiredResourceIdsForSubmittingOrder();

        $data = [
            'productIds' => $requiredData['productIds'],
            'type' => OrderTypeEnum::Service->value
        ];

        $response = $this->actingAs($this->user)->postJson(route('order.store', $data));

        $response->assertUnprocessable();

        $data = [
            'serviceIds' => $requiredData['serviceIds'],
            'type' => OrderTypeEnum::Product->value
        ];

        $response = $this->actingAs($this->user)->postJson(route('order.store', $data));

        $response->assertUnprocessable();


        $data = [
            'serviceIds' => $requiredData['serviceIds'],
            'type' => 3
        ];

        $response = $this->actingAs($this->user)->postJson(route('order.store', $data));

        $response->assertUnprocessable();
    }


    /**
     * Helper function that provides required data
     * */
    private function getRequiredResourceIdsForSubmittingOrder(array $keys = [], $activeItems = true): array
    {
        ProductType::factory(1)->create();

        $products = Product::factory(2)->create(['active' => $activeItems]);

        $productIds = $products->pluck('id')->toArray();

        ServiceType::factory(1)->create();

        $services = Service::factory(2)->create(['active' => $activeItems]);

        $serviceIds = $services->pluck('id')->toArray();

        $options = Option::factory(2)->create(['active' => $activeItems]);

        $optionIds = $options->pluck('id')->toArray();

        $result = compact('products', 'productIds', 'services', 'serviceIds', 'options', 'optionIds');

        return empty($keys) ? $result : array_intersect_key($result, array_flip($keys));
    }

}

<?php

namespace Tests\Feature\API;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->admin = User::factory()->create();


        Role::create(['name' => 'admin']);
        $this->admin->assignRole('admin');
    }


    # test for index route

    public function test_product_index__no_data_works_properly()
    {
        $response = $this->getJson(route('product.index'));

        $response->assertOk()
            ->assertJsonCount(0, 'products');
    }

    public function test_product_index__pagination_works_properly()
    {
        $this->createProduct(count: 15);

        $response = $this->getJson(route('product.index'));

        $response->assertOk()
            ->assertJsonCount(10, 'products');
    }

    public function test_product_index__pagination_with_custom_per_page_count_works_properly()
    {
        $perPage = 20;

        $this->createProduct(count: 25);

        $response = $this->getJson(route('product.index', ['per_page' => $perPage]));

        $response->assertOk()
            ->assertJsonCount(20, 'products');

        $response = $this->getJson(route('product.index', ['page' => 2, 'per_page' => $perPage]));

        $response->assertOk()
            ->assertJsonCount(5, 'products');
    }

    public function test_product_index__does_not_display_deactivated_products_to_non_admin_user()
    {
        $this->createProduct(count: 5);
        $this->createProduct(active: false, count: 3);

        $responseForUnauthenticatedUser = $this->getJson(route('product.index'));

        $responseForUnauthenticatedUser->assertOk()
            ->assertJsonCount(5, 'products');

        $responseForAuthenticatedUser = $this->actingAs($this->user)->getJson(route('product.index'));

        $responseForAuthenticatedUser->assertOk()
            ->assertJsonCount(5, 'products');
    }

    public function test_product_index__display_deactivated_products_to_admin_user()
    {
        $this->createProduct(count: 5);
        $this->createProduct(active: false, count: 3);

        $response = $this->actingAs($this->admin)->getJson(route('product.index'));

        $response->assertOk()
            ->assertJsonCount(8, 'products');
    }


    # test for show route

    public function test_product_show__expect_not_found_for_not_exist_product()
    {
        $response = $this->getJson(route('product.show', ['product' => 1]));

        $response->assertNotFound();
    }

    public function test_product_show__only_admin_can_see_deactivate_product()
    {
        $deActivatedProduct = $this->createProduct(active: false)->first();

        $responseForUnauthenticatedUser = $this
            ->getJson(route('product.show', ['product' => $deActivatedProduct->id]));

        $responseForAuthenticatedUser = $this->actingAs($this->user)
            ->getJson(route('product.show', ['product' => $deActivatedProduct->id]));

        $responseForAdminUser = $this->actingAs($this->admin)
            ->getJson(route('product.show', ['product' => $deActivatedProduct->id]));


        $responseForUnauthenticatedUser->assertNotFound();

        $responseForAuthenticatedUser->assertNotFound();

        $responseForAdminUser->assertOk();
    }

    public function test_product_show__product_details_properly()
    {
        $product = $this->createProduct()->first();

        $response = $this->getJson(route('product.show', ['product' => $product->id]));

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description
            ]);
    }


    # test for store route

    public function test_product_store__unauthorized_for_unauthenticated_user()
    {
        $productPayload = $this->productPayload();

        $response = $this->postJson(route('product.store'), $productPayload);

        $response->assertUnauthorized();
    }

    public function test_product_store__forbidden_for_authenticated_but_non_admin_user()
    {
        $productPayload = $this->productPayload();

        $response = $this->actingAs($this->user)->postJson(route('product.store'), $productPayload);

        $response->assertForbidden();
    }

    public function test_product_store__works_properly_for_admin_user()
    {
        $expectedData = $productPayload = $this->productPayload();

        $response = $this->actingAs($this->admin)->postJson(route('product.store'), $productPayload);

        $expectedData['id'] = $response->json('product.id');
        unset($expectedData['product_type_id']);

        $response->assertOk()
            ->assertJsonFragment($expectedData);

        $this->assertDatabaseHas('products', $expectedData);
    }

    public function test_product_store__cannot_create_with_wrong_payloads()
    {
        $productPayload = $this->productPayload();
        $wrongName = $wrongPrice = $wrongProductTypeId = $incompletePayload = $productPayload;
        unset($incompletePayload['name']);
        $wrongProductTypeId['product_type_id'] = 200;
        $wrongPrice['price'] = -50;
        $wrongName['name'] = 'a';

        $responseWithNoPayload = $this->actingAs($this->admin)
            ->postJson(route('product.store'), []);

        $responseWithIncompletePayload = $this->actingAs($this->admin)
            ->postJson(route('product.store'), $incompletePayload);

        $responseWithWrongProductTypeId = $this->actingAs($this->admin)
            ->postJson(route('product.store'), $wrongProductTypeId);

        $responseWithWrongPrice = $this->actingAs($this->admin)
            ->postJson(route('product.store'), $wrongPrice);

        $responseWithWrongName = $this->actingAs($this->admin)
            ->postJson(route('product.store'), $wrongName);


        $responseWithNoPayload->assertUnprocessable();
        $responseWithIncompletePayload->assertUnprocessable();
        $responseWithWrongProductTypeId->assertUnprocessable();
        $responseWithWrongPrice->assertUnprocessable();
        $responseWithWrongName->assertUnprocessable();
    }


    # test for update route

    public function test_product_update__unauthorized_for_unauthenticated_user()
    {
        $productPayload = $this->productPayload();

        $product = $this->createProduct()->first();

        $response = $this->putJson(route('product.update', ['product' => $product->id]), $productPayload);

        $response->assertUnauthorized();
    }

    public function test_product_update__forbidden_for_authenticated_but_non_admin_user()
    {
        $productPayload = $this->productPayload();

        $product = $this->createProduct()->first();

        $response = $this->actingAs($this->user)
            ->putJson(route('product.update', ['product' => $product->id]), $productPayload);

        $response->assertForbidden();
    }

    public function test_product_update__complete_update_works_properly_for_admin_user()
    {
        $product = $this->createProduct()->first();

        $expectedData = $productPayload = $this->productPayload(active: false, price: 500);

        $response = $this->actingAs($this->admin)
            ->putJson(route('product.update', ['product' => $product->id]), $productPayload);


        $expectedData['id'] = $response->json('product.id');
        unset($expectedData['product_type_id']);

        $response->assertOk()
            ->assertJsonFragment($expectedData);

        $this->assertDatabaseHas('products', $expectedData);
    }

    public function test_product_update__partial_update_works_properly_for_admin_user()
    {
        $product = $this->createProduct()->first();

        $expectedData = $productPayload = ['active' => false, 'price' => 500];
        $expectedData['name'] = $product->name;
        $expectedData['description'] = $product->description;

        $response = $this->actingAs($this->admin)
            ->putJson(route('product.update', ['product' => $product->id]), $productPayload);


        $expectedData['id'] = $response->json('product.id');

        $response->assertOk()
            ->assertJsonFragment($expectedData);

        $this->assertDatabaseHas('products', $expectedData);
    }

    public function test_product_update__cannot_update_product_with_wrong_payloads()
    {
        $product = $this->createProduct()->first();

        $productPayload = $this->productPayload();

        $wrongName = $wrongPrice = $wrongProductTypeId = $productPayload;
        $wrongProductTypeId['product_type_id'] = 200;
        $wrongPrice['price'] = -50;
        $wrongName['name'] = 'a';

        $responseWithNoPayload = $this->actingAs($this->admin)
            ->postJson(route('product.store'), []);

        $responseWithWrongProductTypeId = $this->actingAs($this->admin)
             ->putJson(route('product.update', ['product' => $product->id]), $wrongProductTypeId);

        $responseWithWrongPrice = $this->actingAs($this->admin)
             ->putJson(route('product.update', ['product' => $product->id]), $wrongPrice);

        $responseWithWrongName = $this->actingAs($this->admin)
             ->putJson(route('product.update', ['product' => $product->id]), $wrongName);


        $responseWithNoPayload->assertUnprocessable();
        $responseWithWrongProductTypeId->assertUnprocessable();
        $responseWithWrongPrice->assertUnprocessable();
        $responseWithWrongName->assertUnprocessable();
    }


    # test for destroy route

    public function test_product_destroy__unauthorized_for_unauthenticated_and_non_admin_user()
    {
        $product = $this->createProduct()->first();

        $response = $this->deleteJson(route('product.destroy', ['product' => $product->id]));

        $response->assertUnauthorized();

    }

    public function test_product_destroy__forbidden_for_authenticated_but_non_admin_user()
    {
        $product = $this->createProduct()->first();

        $response = $this->actingAs($this->user)
            ->deleteJson(route('product.destroy', ['product' => $product->id]));

        $response->assertForbidden();
    }

    public function test_product_destroy__expect_not_found_for_not_exist_product()
    {
        $response = $this->actingAs($this->admin)->deleteJson(route('product.destroy', ['product' => 1]));

        $response->assertNotFound();
    }

    public function test_product_destroy__destroy_works_properly_for_admin_user()
    {
        $product = $this->createProduct()->first();

        $response = $this->actingAs($this->admin)
            ->deleteJson(route('product.destroy', ['product' => $product->id]));

        $response->assertOk();

        $this->assertSoftDeleted('products', ['id' => $product->id])
            ->assertDatabaseMissing('products', ['id' => $product->id, 'deleted_at' => null]);
    }



    /**
     * Helper funtion that provides product payload
     * */
    private function productPayload(bool $active = true, $price = 50): array
    {
        $productTypeId = ProductType::factory()->create()->first()->id;
        return [
            'name' => fake()->title,
            'description' => fake()->sentence,
            'active' => $active,
            'price' => $price,
            'product_type_id' => $productTypeId
        ];
    }

    private function createProduct(bool $active = true, int $count = 1): Collection
    {
        ProductType::factory(2)->create();
        return Product::factory($count)->create(['active' => $active]);
    }
}

<?php

namespace Tests\Feature;

use App\Enums\Offer\OfferStatusEnum;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Поиск клиента из базы в форме заказа и сохранение offers.client_id.
 */
class OfferClientSearchTest extends TestCase
{
    use RefreshDatabase;

    private User $worker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->worker = User::forceCreate([
            'name' => 'Xodim',
            'phone' => '901112233',
            'role' => User::WORKER,
            'password' => bcrypt('password'),
        ]);
    }

    public function test_search_finds_client_by_phone_and_phone2(): void
    {
        $byPhone = Client::create(['name' => 'Ali Valiyev', 'phone' => '991234567', 'phone2' => null]);
        $byPhone2 = Client::create(['name' => 'Bobur Karimov', 'phone' => '935550000', 'phone2' => '971234500']);
        Client::create(['name' => 'Boshqa', 'phone' => '881112233', 'phone2' => null]);

        $response = $this->actingAs($this->worker)
            ->getJson(route('offers.clients.search', ['phone' => '(99) 123-45']));

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'clients')
            ->assertJsonPath('clients.0.id', $byPhone->id)
            ->assertJsonPath('clients.0.phone', '991234567')
            ->assertJsonPath('clients.0.phone_number', '+998 (99) 123-45-67');

        $ids = $this->actingAs($this->worker)
            ->getJson(route('offers.clients.search', ['phone' => '1234']))
            ->assertOk()
            ->json('clients.*.id');

        $this->assertEqualsCanonicalizing([$byPhone->id, $byPhone2->id], $ids);
    }

    public function test_search_strips_country_code(): void
    {
        $client = Client::create(['name' => 'Ali', 'phone' => '991234567']);

        $this->actingAs($this->worker)
            ->getJson(route('offers.clients.search', ['phone' => '+998 99 123 45 67']))
            ->assertOk()
            ->assertJsonCount(1, 'clients')
            ->assertJsonPath('clients.0.id', $client->id);
    }

    public function test_search_requires_at_least_three_digits(): void
    {
        $this->actingAs($this->worker)
            ->getJson(route('offers.clients.search', ['phone' => '9-9']))
            ->assertStatus(422)
            ->assertJsonValidationErrors('phone');
    }

    public function test_guest_cannot_search(): void
    {
        $this->get(route('offers.clients.search', ['phone' => '991']))
            ->assertRedirect(route('login'));
    }

    public function test_offer_form_has_client_search_modal(): void
    {
        $this->actingAs($this->worker)
            ->get(route('offers.form'))
            ->assertOk()
            ->assertSee('clientSearchModal')
            ->assertSee(route('offers.clients.search'));
    }

    public function test_offer_copy_form_has_client_search_modal(): void
    {
        $this->actingAs($this->worker)->postJson(route('offers.save'), $this->payload())->assertOk();
        $offerId = DB::table('offers')->value('id');

        $this->actingAs($this->worker)
            ->get(route('offers.copy', $offerId))
            ->assertOk()
            ->assertSee('clientSearchModal');
    }

    public function test_offer_saves_selected_client_id(): void
    {
        $client = Client::create(['name' => 'Ali', 'phone' => '991234567']);

        $this->actingAs($this->worker)
            ->postJson(route('offers.save'), $this->payload(['id' => $client->id]))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('offers', [
            'client_id' => $client->id,
            'client_name' => 'Ali',
            'phone' => '991234567',
        ]);
    }

    public function test_offer_without_selected_client_keeps_client_id_null(): void
    {
        $this->actingAs($this->worker)
            ->postJson(route('offers.save'), $this->payload())
            ->assertOk();

        $this->assertDatabaseHas('offers', ['client_name' => 'Ali', 'client_id' => null]);
    }

    public function test_offer_with_unknown_client_id_fails_validation(): void
    {
        $this->actingAs($this->worker)
            ->postJson(route('offers.save'), $this->payload(['id' => 999]))
            ->assertStatus(422)
            ->assertJsonValidationErrors('client_id');

        $this->assertDatabaseCount('offers', 0);
    }

    private function payload(array $client = []): array
    {
        $categoryId = DB::table('service_categories')->insertGetId(['title' => 'Oyna']);
        $serviceId = DB::table('services')->insertGetId([
            'name' => 'Ko\'zgu',
            'category_id' => $categoryId,
            'active' => true,
            'unit' => 'dona',
            'price' => 10000,
        ]);

        return [
            'client' => array_merge([
                'name' => 'Ali',
                'phone_number' => '(99) 123-45-67',
                'phone_number2' => '',
                'note' => '',
            ], $client),
            'status' => OfferStatusEnum::Draft->value,
            'discount' => 0,
            'created_at' => now()->format('Y-m-d'),
            'offer_items' => [[
                'service_id' => $serviceId,
                'quantity' => 1,
                'price' => 10000,
                'original_price' => 10000,
                'item_cost' => 10000,
                'cost' => 10000,
                'x' => null,
                'y' => null,
                'dimension_amount' => 1,
                'sub_items' => [],
            ]],
        ];
    }
}

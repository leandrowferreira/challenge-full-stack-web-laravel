<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\DevUsersTableSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $endpoint;
    protected $userEndpoint;
    protected $adminEmail;
    protected $userEmail;

    /**
     * Set some variables before startup tests.
     */
    public function __construct()
    {
        parent::__construct();

        $this->endpoint     = 'api/v' . env('API_VERSION') . '/';
        $this->userEndpoint = 'api/v' . env('API_VERSION') . '/users/';
        $this->adminEmail   = 'admin@edtech.tmp.br';
        $this->userEmail    = 'user@edtech.tmp.br';
    }

    /**
     * Make fresh migrations to the test database
     */
    protected function setUp() : void
    {
        parent::setUp();

        $this->seed([
            RolesTableSeeder::class,
            DevUsersTableSeeder::class
        ]);
    }

    /**
     * When tests need some fake data, they'll get here
     */
    protected function getFakeData()
    {
        return [
            'id'         => null,
            'ra'         => $this->faker->unique()->numberBetween(1, 9999),
            'name'       => $this->faker->unique()->name(),
            'email'      => $this->faker->unique()->safeEmail(),
            'password'   => Hash::make('password'),
            'role_id'    => Role::where('name', 'Student')->firstOrFail()->id,
        ];
    }

    /**
     * @test
     * @group users
     */
    public function api_is_responding()
    {
        //We expect that root endpoint returns "HTTP 200"
        $this->json('get', $this->endpoint)
                ->assertStatus(200);
    }

    /**
     * @test
     * @group users
     */
    public function admin_can_create_user()
    {
        Sanctum::actingAs(User::where('email', $this->adminEmail)->firstOrFail());

        $data = $this->getFakeData();

        $user = $this->json('post', $this->userEndpoint, $data)
                ->assertStatus(201)
                ->getContent();
        $data['id'] = (string) json_decode($user, true)['id'];

        $this->assertDatabaseHas('users', $data);
    }

    /**
     * @test
     * @group users
     */
    public function admin_can_update_user()
    {
        Sanctum::actingAs(User::where('email', $this->adminEmail)->firstOrFail());

        $data = [
            'name' => $this->faker->unique()->name(),
        ];

        $user = User::factory()->create();

        $this->json('put', $this->userEndpoint . $user->id, $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('users', $data);
    }

    /**
     * @test
     * @group users
     */
    public function admin_can_delete_user()
    {
        Sanctum::actingAs(User::where('email', $this->adminEmail)->firstOrFail());

        $user = User::factory()->create();

        $this->json('delete', $this->userEndpoint . $user->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('users', $user->toArray());
    }

    /**
     * @test
     * @group users
     */
    public function cant_insert_incomplete_records()
    {
        Sanctum::actingAs(User::where('email', $this->adminEmail)->firstOrFail());

        $data = [
            'name' => 'whatever, the input is incomplete...',
        ];

        $this->json('post', $this->userEndpoint, $data)
            ->assertStatus(422);

        $this->assertDatabaseMissing('users', $data);
    }

    /**
     * @test
     * @group users
     */
    public function user_cannot_create_user()
    {
        Sanctum::actingAs(User::where('email', $this->userEmail)->firstOrFail());

        $data = $this->getFakeData();

        $this->json('post', $this->userEndpoint, $data)
            ->assertStatus(403);

        $this->assertDatabaseMissing('users', $data);
    }

    /**
     * @test
     * @group users
     */
    public function user_cannot_update_user()
    {
        Sanctum::actingAs(User::where('email', $this->userEmail)->firstOrFail());

        $data = [
            'name' => $this->faker->unique()->name(),
        ];

        $user = User::factory()->create();

        $this->json('put', $this->userEndpoint . $user->id, $data)
            ->assertStatus(403);

        $this->assertDatabaseMissing('users', $data);
    }

    /**
     * @test
     * @group users
     */
    public function user_cannot_delete_user()
    {
        Sanctum::actingAs(User::where('email', $this->userEmail)->firstOrFail());

        $user = User::factory()->create();

        $this->json('delete', $this->userEndpoint . $user->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('users', $user->toArray());
    }
}

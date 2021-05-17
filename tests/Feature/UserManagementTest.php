<?php

namespace Tests\Feature;

use App\Http\Resources\UserResource;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
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
    protected $studentEmail;

    /**
     * Set some variables before startup tests.
     */
    public function __construct()
    {
        parent::__construct();

        $this->endpoint     = 'api/v' . env('API_VERSION') . '/';
        $this->userEndpoint = 'api/v' . env('API_VERSION') . '/users/';
        $this->adminEmail   = 'admin@edtech.tmp.br';
        $this->studentEmail = 'student@edtech.tmp.br';
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
            'ra'    => $this->faker->unique()->numberBetween(1, 9999),
            'cpf'   => $this->faker->unique()->cpf(false),
            'name'  => $this->faker->unique()->name(),
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }

    /**
     * @test
     * @group users
     */
    public function api_is_on()
    {
        $this->json('get', $this->endpoint)
                ->assertStatus(200);
    }

    /**
     * @test
     * @group users
     */
    public function admin_can_list_users()
    {
        Sanctum::actingAs(User::where('email', $this->adminEmail)->firstOrFail(), ['admin']);

        $data = $this->json('get', $this->userEndpoint)
            ->assertStatus(200)
            ->getContent();

        foreach (json_decode($data, true)['data'] as $user) {
            unset($user['role']);
            $this->assertDatabaseHas('users', $user);
        }
    }

    /**
     * @test
     * @group users
     */
    public function admin_can_create_user()
    {
        Sanctum::actingAs(User::where('email', $this->adminEmail)->firstOrFail(), ['admin']);

        $data = $this->getFakeData();

        $this->assertDatabaseMissing('users', $data);

        $this->json('post', $this->userEndpoint, $data)
            ->assertStatus(201)
            ->getContent();

        $this->assertDatabaseHas('users', $data);
    }

    /**
     * @test
     * @group users
     */
    public function admin_can_show_user()
    {
        Sanctum::actingAs(User::where('email', $this->adminEmail)->firstOrFail(), ['admin']);

        $user = User::factory()->create()->makeHidden(['created_at', 'updated_at', 'deleted_at'])->toArray();

        $this->assertDatabaseHas('users', $user);

        $user = $this->json('get', $this->userEndpoint . $user['id'])
            ->assertStatus(200)
            ->getContent();

        $user = json_decode($user, true);
        unset($user['role']);

        $this->assertDatabaseHas('users', $user);
    }

    /**
     * @test
     * @group users
     */
    public function admin_can_update_user()
    {
        Sanctum::actingAs(User::where('email', $this->adminEmail)->firstOrFail(), ['admin']);

        $data = [
            'name' => $this->faker->unique()->name(),
        ];

        $user = User::factory()->create();

        $this->assertDatabaseMissing('users', $data);

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
        Sanctum::actingAs(User::where('email', $this->adminEmail)->firstOrFail(), ['admin']);

        $user = User::factory()->create()->makeHidden(['created_at', 'updated_at', 'deleted_at'])->toArray();

        $this->assertDatabaseHas('users', $user);

        $this->json('delete', $this->userEndpoint . $user['id'])
            ->assertStatus(204);

        $this->assertDatabaseMissing('users', $user);
    }

    /**
     * @test
     * @group users
     */
    public function cannot_insert_incomplete_records()
    {
        Sanctum::actingAs(User::where('email', $this->adminEmail)->firstOrFail(), ['admin']);

        foreach (['ra', 'cpf', 'name', 'email'] as $field) {
            $data = $this->getFakeData();
            unset($data[$field]);

            $this->json('post', $this->userEndpoint, $data)
                ->assertStatus(422);

            $this->assertDatabaseMissing('users', $data);
        }
    }

    /**
     * @test
     * @group users
     */
    public function user_cannot_list_users()
    {
        Sanctum::actingAs(User::where('email', $this->studentEmail)->firstOrFail(), ['student']);

        $this->json('get', $this->userEndpoint)
            ->assertStatus(403);
    }

    /**
     * @test
     * @group users
     */
    public function user_cannot_create_user()
    {
        Sanctum::actingAs(User::where('email', $this->studentEmail)->firstOrFail(), ['student']);

        $data = $this->getFakeData();

        $this->assertDatabaseMissing('users', $data);

        $this->json('post', $this->userEndpoint, $data)
            ->assertStatus(403)
            ->getContent();

        $this->assertDatabaseMissing('users', $data);
    }

    /**
     * @test
     * @group users
     */
    public function user_cannot_show_user()
    {
        Sanctum::actingAs(User::where('email', $this->studentEmail)->firstOrFail(), ['student']);

        $user = User::factory()->create()->makeHidden(['created_at', 'updated_at', 'deleted_at'])->toArray();

        $this->assertDatabaseHas('users', $user);

        $user = $this->json('get', $this->userEndpoint . $user['id'])
            ->assertStatus(403);
    }

    /**
     * @test
     * @group users
     */
    public function user_cannot_update_user()
    {
        Sanctum::actingAs(User::where('email', $this->studentEmail)->firstOrFail(), ['student']);

        $data = [
            'name' => $this->faker->unique()->name(),
        ];

        $user = User::factory()->create();

        $this->assertDatabaseMissing('users', $data);

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
        Sanctum::actingAs(User::where('email', $this->studentEmail)->firstOrFail(), ['student']);

        $user = User::factory()->create()->makeHidden(['created_at', 'updated_at', 'deleted_at'])->toArray();

        $this->assertDatabaseHas('users', $user);

        $this->json('delete', $this->userEndpoint . $user['id'])
            ->assertStatus(403);

        $this->assertDatabaseHas('users', $user);
    }
}

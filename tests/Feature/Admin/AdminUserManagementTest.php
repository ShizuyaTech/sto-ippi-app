<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user for testing
        $this->admin = User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);
    }

    /**
     * Test admin can access user management dashboard.
     */
    public function test_admin_can_access_user_management_page(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Manage Users');
    }

    /**
     * Test non-admin cannot access user management.
     */
    public function test_non_admin_cannot_access_user_management(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)
            ->get(route('admin.users.index'));

        $response->assertStatus(403);
    }

    /**
     * Test admin can access create user form.
     */
    public function test_admin_can_access_create_user_form(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.create'));

        $response->assertStatus(200);
        $response->assertSee('Create New User');
        $response->assertSee('Name');
        $response->assertSee('Email');
        $response->assertSee('Password');
        $response->assertSee('Role');
    }

    /**
     * Test complete flow: admin login, navigate to create user, and successfully create new user.
     */
    public function test_admin_can_successfully_create_new_user_complete_flow(): void
    {
        // Step 1: Admin login (simulated with actingAs)
        $this->actingAs($this->admin);

        // Step 2: Access dashboard
        $dashboardResponse = $this->get(route('admin.dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Admin Dashboard');

        // Step 3: Navigate to users index
        $usersIndexResponse = $this->get(route('admin.users.index'));
        $usersIndexResponse->assertStatus(200);
        $usersIndexResponse->assertSee('Manage Users');

        // Step 4: Navigate to create user form
        $createFormResponse = $this->get(route('admin.users.create'));
        $createFormResponse->assertStatus(200);
        $createFormResponse->assertSee('Create New User');

        // Step 5: Prepare new user data with unique email
        $uniqueEmail = 'john.doe.'.time().'@test.com';
        $newUserData = [
            'name' => 'John Doe',
            'email' => $uniqueEmail,
            'password' => 'SecurePassword123',
            'password_confirmation' => 'SecurePassword123',
            'role' => 'user',
        ];

        // Step 6: Submit create user form
        $createResponse = $this->post(route('admin.users.store'), $newUserData);

        // Step 7: Verify redirect to users index with success message
        $createResponse->assertRedirect(route('admin.users.index'));
        $createResponse->assertSessionHas('success', 'User berhasil ditambahkan.');

        // Step 8: Verify user exists in database
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => $uniqueEmail,
            'role' => 'user',
        ]);

        // Step 9: Verify password is hashed
        $createdUser = User::where('email', $uniqueEmail)->first();
        $this->assertNotNull($createdUser);
        $this->assertTrue(Hash::check('SecurePassword123', $createdUser->password));

        // Step 10: Verify user appears in the list
        $listResponse = $this->get(route('admin.users.index'));
        $listResponse->assertSee('John Doe');
        $listResponse->assertSee($uniqueEmail);
    }

    /**
     * Test admin can create user with admin role.
     */
    public function test_admin_can_create_user_with_admin_role(): void
    {
        $userData = [
            'name' => 'New Admin User',
            'email' => 'newadmin@test.com',
            'password' => 'AdminPass123',
            'password_confirmation' => 'AdminPass123',
            'role' => 'admin',
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.store'), $userData);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'newadmin@test.com',
            'role' => 'admin',
        ]);
    }

    /**
     * Test validation: cannot create user with existing email.
     */
    public function test_cannot_create_user_with_duplicate_email(): void
    {
        $existingUser = User::factory()->create([
            'email' => 'existing@test.com',
        ]);

        $userData = [
            'name' => 'Duplicate Email User',
            'email' => 'existing@test.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'role' => 'user',
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.store'), $userData);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Test validation: password confirmation must match.
     */
    public function test_password_confirmation_must_match(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'Password123',
            'password_confirmation' => 'DifferentPassword',
            'role' => 'user',
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.store'), $userData);

        $response->assertSessionHasErrors('password');
    }

    /**
     * Test validation: all required fields must be filled.
     */
    public function test_all_required_fields_must_be_filled(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.store'), []);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'role']);
    }

    /**
     * Test validation: email must be valid format.
     */
    public function test_email_must_be_valid_format(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'invalid-email-format',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'role' => 'user',
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.store'), $userData);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Test admin can view list of users after creating.
     */
    public function test_admin_can_view_newly_created_user_in_list(): void
    {
        // Create a new user
        $newUser = User::factory()->create([
            'name' => 'Listed User',
            'email' => 'listed@test.com',
            'role' => 'user',
        ]);

        // Access users index
        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Listed User');
        $response->assertSee('listed@test.com');
    }

    /**
     * Test complete automation: multiple users creation in sequence.
     */
    public function test_admin_can_create_multiple_users_in_sequence(): void
    {
        $this->actingAs($this->admin);

        $users = [
            ['name' => 'User One', 'email' => 'user1@test.com', 'role' => 'user'],
            ['name' => 'User Two', 'email' => 'user2@test.com', 'role' => 'user'],
            ['name' => 'Admin Two', 'email' => 'admin2@test.com', 'role' => 'admin'],
        ];

        foreach ($users as $userData) {
            $response = $this->post(route('admin.users.store'), [
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => 'Password123',
                'password_confirmation' => 'Password123',
                'role' => $userData['role'],
            ]);

            $response->assertRedirect(route('admin.users.index'));
            $response->assertSessionHas('success');

            $this->assertDatabaseHas('users', [
                'email' => $userData['email'],
                'role' => $userData['role'],
            ]);
        }

        // Verify all users appear in the list
        $listResponse = $this->get(route('admin.users.index'));
        foreach ($users as $userData) {
            $listResponse->assertSee($userData['name']);
            $listResponse->assertSee($userData['email']);
        }
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_succsess_login()
    {
        $this->post('/api/login', [
            'email' => 'mgs@mgs.com',
            'password' => 'mgs',
        ])->assertStatus(200);
    }

    public function test_wrong_login()
    {
        $this->post('/api/login', [
            'email' => 'a@aa.com',
            'password' => 'a',
        ])->assertStatus(302);
    }

    public function test_create_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/')
            ->assertOk();
    }

    public function test_already_user()
    {
        $this->post('api/users', [
            'name' => 'MGS',
            'email' => 'mgs@mgs.com',
            'password' => 'mgs',
            'type' => 'admin',
            'balance' => 100,
        ])->assertStatus(500);
    }

    public function test_delete_user() 
    {
        $user1 =  User::factory()->create(['type' => 'admin']);
        $this->actingAs($user1);

        $user2 =  User::factory()->create(['type' => 'user']);

        $this->deleteJson('api/users/' . $user2->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('users', ['id' => $user2->id]);
        //$this->json('delete', 'api/user/' .)
        //$this->actingAs($user)
        //    ->deleteJson('api/users/' . $user->id)
        //    ->assertOk();
    }
    
    public function test_get_user() 
    {
        $user = User::factory()->create();
        
        $this->getJson('api/users')->assertUnauthorized();

        $this->actingAs($user)->getJson('api/users')->assertOk();
    }

    public function test_amount_operations()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->putJson('api/amountOperations', ['email' => 'b@b.com', 'service_name' => 'Cila', 'quantity' => 5])
            ->assertOk();
    }

    public function test_money_operations()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->putJson('api/moneyOperations', ['email' => 'b@b.com', 'money' => 50])
            ->assertOk();
    }

    public function test_get_history_balance() 
    {
        $user = User::factory()->create();
        
        $this->getJson('api/historyBalance')->assertUnauthorized();

        $this->actingAs($user)->getJson('api/historyBalance')->assertOk();
    }
}

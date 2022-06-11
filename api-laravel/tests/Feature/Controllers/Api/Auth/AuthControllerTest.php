<?php

namespace tests\Feature\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{   
    /**
     * @test
     */
    public function shouldBeAbleToRegisterANewUser()
    {   
        $data = [
            'name' => 'teste',
            'email' => 'teste@gmail.com',
            'password' => 'password'
        ];

        $response = $this->postJson('/api/register', $data);

        $response
            ->assertStatus(201)
            ->assertJson([
                'success' => true
            ]);
        
        $this->assertDatabaseHas('users',[
            'name' => 'teste',
            'email' => 'teste@gmail.com'
        ]);

        $user = User::where('email', 'teste@gmail.com')->firstOrFail();
        
        $this->assertTrue(
            Hash::check('password', $user->password),
            'Check if password was saved'
        );
    }

    /**
     * @test
     */
    public function shouldBeAbleToLogin()
    {
        User::factory()->create([
            'email' => 'teste@gmail.com',
            'password' => bcrypt('password')
        ]);

        $data = [
            'email' => 'teste@gmail.com',
            'password' => 'password'
        ];

        $response = $this->postJson('/api/login', $data);

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
        
        $this->assertDatabaseHas('users',[
            'email' => 'teste@gmail.com'
        ]);
    
        $user = User::where('email', 'teste@gmail.com')->firstOrFail();
            
        $this->assertTrue(
            Hash::check('password', $user->password),
            'Check if password was saved'
        );
    }

    /**
     * @dataProvider requestProvider
     * @test
     */
    public function requestIsValid($value,$expectedResult, $fieldError, $route)
    {
        $response = $this->postJson("/api/$route", [
            'name' => $value['name'],
            'email' => $value['email'],
            'password' => $value['password']
        ])->assertStatus(422);

        $response->assertInvalid([
            $fieldError => $expectedResult
        ]);
    }

    /**
     * @test
     */
    public function registerEmailShouldBeUnique()
    {   
        $user = User::factory()->create([
            'email' => 'teste@gmail.com'
        ]);

        $response = $this->postJson('/api/register', [
            'password' => 'password',
            'name' => 'teste',
            'email' => 'teste@gmail.com'
        ])->assertStatus(422);

        $response->assertInvalid([
            'email' => 'The email has already been taken.'
        ]);
    }
    

    public function requestProvider()
    {
        return [
           'registerEmailShouldBeRequired' => [
               'value' => ['email' => '', 'name' => 'teste', 'password' => 'password'],
               'expectedResult' => 'The email field is required.',
               'fieldError' => 'email',
               'route' => 'register'
           ],
           'registerEmailShouldBeValid' => [
                'value' => ['email' => 'teste.com', 'name' => 'teste', 'password' => 'password'],
                'expectedResult' => 'The email must be a valid email address.',
                'fieldError' => 'email',
                'route' => 'register'
           ],
           'registerNameShouldBeRequired' => [
                'value' => ['email' => 'teste@gmail.com', 'name' => '', 'password' => 'password'],
                'expectedResult' => 'The name field is required.',
                'fieldError' => 'name',
                'route' => 'register'
           ],
           'registerNameShouldBeMinThreeCharacters' => [
                'value' => ['email' => 'teste@gmail.com', 'name' => 'te', 'password' => 'password'],
                'expectedResult' => 'The name must be at least 3 characters.',
                'fieldError' => 'name',
                'route' => 'register'
           ],
           'registerNameShouldBeMaxTwoHundredAndFiftyFiveLength' => [
                'value' => ['email' => 'teste@gmail.com', 'name' => str_repeat('a', 256), 'password' => 'password'],
                'expectedResult' => 'The name must not be greater than 255 characters.',
                'fieldError' => 'name',
                'route' => 'register'
            ],
            'registerPasswordShouldBeRequired' => [
                'value' => ['email' => 'teste@gmail.com', 'name' => 'teste', 'password' => ''],
                'expectedResult' => 'The password field is required.',
                'fieldError' => 'password',
                'route' => 'register'
            ],
            'registerPassowrdShouldBeMinSixCharacters' => [
                'value' => ['email' => 'teste@gmail.com', 'name' => 'teste', 'password' => str_repeat('1', 5)],
                'expectedResult' => 'The password must be at least 6 characters',
                'fieldError' => 'password',
                'route' => 'register'
            ],
            'registerPasswordShouldBeMaxTwoHundredAndFiftyFiveCharacters' => [
                'value' => ['email' => 'teste@gmail.com', 'name' => 'teste', 'password' => str_repeat('1', 256)],
                'expectedResult' => 'The password must not be greater than 255 characters.',
                'fieldError' => 'password',
                'route' => 'register'
            ],
            'loginEmailShouldBeRequired' => [
                'value' => ['email' => '', 'name' => 'teste', 'password' => 'password'],
               'expectedResult' => 'The email field is required.',
               'fieldError' => 'email',
               'route' => 'login'
            ],
            'loginEmailShouldBeValid' => [
                'value' => ['email' => 'teste.com', 'name' => 'teste', 'password' => 'password'],
               'expectedResult' => 'The email must be a valid email address.',
               'fieldError' => 'email',
               'route' => 'login'
            ],
            'loginPasswordShouldBeRequired' => [
                'value' => ['email' => 'teste@gmail.com', 'name' => 'teste', 'password' => ''],
                'expectedResult' => 'The password field is required.',
                'fieldError' => 'password',
                'route' => 'login'
            ],
            'loginPassowrdShouldBeMinSixCharacters' => [
                'value' => ['email' => 'teste@gmail.com', 'name' => 'teste', 'password' => str_repeat('1', 5)],
                'expectedResult' => 'The password must be at least 6 characters',
                'fieldError' => 'password',
                'route' => 'login'
            ],
            'loginPasswordShouldBeMaxTwoHundredAndFiftyFiveCharacters' => [
                'value' => ['email' => 'teste@gmail.com', 'name' => 'teste', 'password' => str_repeat('1', 256)],
                'expectedResult' => 'The password must not be greater than 255 characters.',
                'fieldError' => 'password',
                'route' => 'login'
            ],
        ];
    }
}
<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'referrer_id' => factory(User::class)->create()->id,
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'johndoe@example.com',
            'password' => Hash::make('password'),
        ]);
    }

    /** @test */
    public function it_has_a_name()
    {
        $this->assertEquals('John Doe', $this->user->name);
    }

    /** @test */
    public function it_has_a_username()
    {
        $this->assertEquals('johndoe', $this->user->username);
    }

    /** @test */
    public function it_has_an_email()
    {
        $this->assertEquals('johndoe@example.com', $this->user->email);
    }

    /** @test */
    public function it_has_a_password()
    {
        $this->assertTrue(Hash::check('password', $this->user->password));
    }

    /** @test */
    public function it_has_a_referral_link()
    {
        $this->assertEquals($this->user->referral_link, route('register', ['ref' => $this->user->username]));
    }

    /** @test */
    public function it_has_a_referrer()
    {
        $this->assertInstanceOf(User::class, $this->user->referrer);
    }

    /** @test */
    public function it_has_many_referrals()
    {
        $this->assertInstanceOf(Collection::class, $this->user->referrals);
    }
}

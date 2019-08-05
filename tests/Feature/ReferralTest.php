<?php

namespace Tests\Feature;

use App\Notifications\ReferrerBonus;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReferralTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    private $user;
    private $registerForm;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->registerForm = [
            'name'                  => $this->faker->name,
            'username'              => $this->faker->firstName,
            'email'                 => $this->faker->safeEmail,
            'password'              => 'password',
            'password_confirmation' => 'password',
        ];
    }

    /** @test */
    public function session_contains_referrer_variable_when_guests_visit_with_a_referral_link()
    {
        $this->withoutExceptionHandling();

        $this->get($this->user->referral_link)
            ->assertViewIs('auth.register')
            ->assertSessionHas('referrer');
    }

    /** @test */
    public function guests_registering_via_a_referral_link_have_a_referrer()
    {
        $this->withoutExceptionHandling();

        $this->get($this->user->referral_link);

        $this->post(route('register'), $this->registerForm);

        $freshUser = User::whereEmail($this->registerForm['email'])->first();

        $this->assertEquals($freshUser->referrer_id, $this->user->id);
    }

    /** @test */
    public function guests_registering_without_a_referral_link_do_not_have_a_referrer()
    {
        $this->withoutExceptionHandling();

        $this->post(route('register'), $this->registerForm);

        $freshUser = User::whereEmail($this->registerForm['email'])->first();

        $this->assertNull($freshUser->referrer_id);
    }

    /** @test */
    public function users_get_notified_when_guests_register_with_their_referral_link()
    {
        $this->withoutExceptionHandling();

        Notification::fake();

        $this->get($this->user->referral_link);

        $this->post(route('register'), $this->registerForm);

        Notification::assertSentTo($this->user, ReferrerBonus::class);
    }
}

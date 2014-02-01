<?php namespace Zizaco\Confide;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Zizaco\Confide\Facade as ConfideFacade;
use Illuminate\Support\Facades\App as App;

class ConfideUserTest extends PHPUnit_Framework_TestCase
{
    /**
     * Calls Mockery::close
     *
     * @return void
     */
    public function tearDown()
    {
        m::close();
    }

    public function testShouldConfirm()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $user = new ConfideUser;
        $user->confirmation_code = '12345';

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        ConfideFacade::shouldReceive('confirm')
            ->once()->with($user->confirmation_code)
            ->andReturn(true);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $user->confirm();
    }

    public function testShouldForgotPassword()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $user = new ConfideUser;
        $user->email = 'someone@somewhere.com';

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        ConfideFacade::shouldReceive('forgotPassword')
            ->once()->with($user->email)
            ->andReturn(true);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $user->forgotPassword();
    }

    public function testIsValid()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $user = new ConfideUser;
        $validator = m::mock('Zizaco\Confide\Validator');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        App::shouldReceive('make')
            ->once()->with('ConfideUserValidator')
            ->andReturn($validator);

        $validator->shouldReceive('validate')
            ->once()->with($user)
            ->andReturn(true);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $user->isValid();
    }

    public function testShouldGetAuthIdentifier()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $user = m::mock('Zizaco\Confide\ConfideUser[getKey]');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $user->shouldReceive('getKey')
            ->once()
            ->andReturn(1);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertEquals(1, $user->getAuthIdentifier());
    }

    public function testShouldGetAuthPassword()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $user = m::mock('Zizaco\Confide\ConfideUser[getKey]');
        $user->password = '1234';

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertEquals('1234', $user->getAuthPassword());
    }

    public function testShouldGetErrors()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $user = new ConfideUser;
        $newMessageBag = m::mock('Illuminate\Support\MessageBag');
        $existentMessageBag = m::mock('Illuminate\Support\MessageBag');
        $user->errors = $existentMessageBag;

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        App::shouldReceive('make')
            ->once()->with('Illuminate\Support\MessageBag')
            ->andReturn($newMessageBag);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertEquals($existentMessageBag, $user->errors());
        $user->errors = null;
        $this->assertEquals($newMessageBag, $user->errors());
    }
}

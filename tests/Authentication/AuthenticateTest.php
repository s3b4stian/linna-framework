<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

use Linna\Authentication\Authenticate;
use Linna\Authentication\Password;
use Linna\Session\Session;
use PHPUnit\Framework\TestCase;

/**
 * Authenticate Test.
 */
class AuthenticateTest extends TestCase
{
    /**
     * @var Session The session class.
     */
    protected $session;

    /**
     * @var Password The password class.
     */
    protected $password;

    /**
     * @var Authenticate The authenticate class
     */
    protected $authenticate;

    /**
     * Setup.
     */
    public function setUp()
    {
        $session = new Session();
        $password = new Password();
        
        $this->password = $password;
        $this->session = $session;
        $this->authenticate = new Authenticate($session, $password);
    }

    /**
     * Test login.
     *
     * @runInSeparateProcess
     */
    public function testLogin()
    {
        $this->session->start();

        //hash password
        $storedPassword = $this->password->hash('password');
        $storedUser = 'root';

        //attemp first login
        $loginResult = $this->authenticate->login('root', 'password', $storedUser, $storedPassword, 1);

        //attemp check if logged
        $logged = $this->authenticate->isLogged();

        //simulate expired login
        $this->session->loginTime = time() - 3600;

        //attemp second login
        $secondLogin = new Authenticate($this->session, $this->password);
        $notLogged = $secondLogin->isNotLogged();

        $this->assertEquals(true, $loginResult);
        $this->assertEquals(true, $logged);
        $this->assertEquals(true, $notLogged);

        $this->session->destroy();
    }

    /**
     * Test logout.
     *
     * @runInSeparateProcess
     */
    public function testLogout()
    {
        $this->session->start();

        //hash password
        $storedPassword = $this->password->hash('password');
        $storedUser = 'root';

        //attemp first login
        $this->authenticate->login('root', 'password', $storedUser, $storedPassword, 1);
        $loginResult = $this->authenticate->isLogged();

        //do logout
        $this->authenticate->logout();

        //create new login instance
        $login = new Authenticate($this->session, $this->password);
        $noLoginResult = $login->isNotLogged();

        $this->assertEquals(true, $loginResult);
        $this->assertEquals(true, $noLoginResult);

        $this->session->destroy();
    }

    /**
     * Test incorrect login.
     *
     * @runInSeparateProcess
     */
    public function testIncorrectLogin()
    {
        $this->session->start();

        //hash password
        $storedPassword = $this->password->hash('password');
        $storedUser = 'root';

        //try login with bad credentials
        $loginResult = $this->authenticate->login('root', 'badPassword', $storedUser, $storedPassword, 1);
        $loginResult2 = $this->authenticate->login('badUser', 'password', $storedUser, $storedPassword, 1);

        $this->assertEquals(false, $loginResult);
        $this->assertEquals(false, $loginResult2);

        $this->session->destroy();
    }

    /**
     * Test login refresh.
     *
     * @runInSeparateProcess
     */
    public function testLoginRefresh()
    {
        $this->session->start();

        //hash password
        $storedPassword = $this->password->hash('password');
        $storedUser = 'root';

        //attemp first login
        $login = new Authenticate($this->session, $this->password);
        $firstLogin = $login->login('root', 'password', $storedUser, $storedPassword, 1);
        //attemp check if logged
        $firstLogged = $login->isLogged();

        $this->session->commit();

        $this->session->start();

        //create second instance
        $login = new Authenticate($this->session, $this->password);
        //attemp check if logged
        $secondLogged = $login->isLogged();

        //simulate expired login
        $this->session->loginTime = time() - 3600;

        //attemp second login
        $secondLogin = new Authenticate($this->session, $this->password);
        $notLogged = $secondLogin->isNotLogged();

        $this->assertEquals(true, $firstLogin);
        $this->assertEquals(true, $firstLogged);
        $this->assertEquals(true, $secondLogged);
        $this->assertEquals(true, $notLogged);

        $this->session->destroy();
    }
}
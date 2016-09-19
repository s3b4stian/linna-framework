<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

namespace Linna\Session;

use \SessionHandlerInterface;
use \Memcached;

/**
 * Store sessions in Memcached.
 *
 * Check below link for PHP session Handler
 * http://php.net/manual/en/class.sessionhandler.php
 *
 */
class MemcachedSessionHandler implements SessionHandlerInterface
{
    /**
     * @var object $memcached Memcached resource
     */
    private $memcached;

    /**
     * Constructor
     *
     */
    public function __construct(Memcached $memcached)
    {
        $this->memcached = $memcached;
    }

    /**
     * Open session storage
     *
     * http://php.net/manual/en/sessionhandler.open.php.
     *
     * @param string $savePath
     * @param string $sessionName
     *
     * @return bool
     */
    public function open($savePath, $sessionName)
    {
        unset($savePath, $sessionName);
        
        return true;
    }

    /**
     * Delete old sessions from storage
     *
     * http://php.net/manual/en/sessionhandler.gc.php.
     *
     * @param string $maxLifetime
     *
     * @return bool
     */
    public function gc($maxLifetime)
    {
        //too do
        return true;
    }

    /**
     * Read sessio data from storage
     *
     * http://php.net/manual/en/sessionhandler.read.php.
     *
     * @param string $sessionId
     *
     * @return string
     */
    public function read($sessionId)
    {
        //fix for php7
        return (string) $this->memcached->get($sessionId);
    }

    /**
     * Write session data to storage
     *
     * http://php.net/manual/en/sessionhandler.write.php.
     *
     * @param string $sessionId
     * @param array  $data
     *
     * @return bool
     */
    public function write($sessionId, $data)
    {
        $this->memcached->set($sessionId, $data);
        
        return true;
    }

    /**
     * Close session
     *
     * http://php.net/manual/en/sessionhandler.close.php.
     *
     * @return bool
     */
    public function close()
    {
        return true;
    }

    /**
     * Destroy session data
     *
     * http://php.net/manual/en/sessionhandler.destroy.php.
     *
     * @param string $sessionId
     *
     * @return bool
     */
    public function destroy($sessionId)
    {
        $this->memcached->delete($sessionId);
        
        return true;
    }
}
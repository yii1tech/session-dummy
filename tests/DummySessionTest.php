<?php

namespace yii1tech\session\dummy\test;

use PHPUnit\Framework\TestCase;
use yii1tech\session\dummy\DummySession;

class DummySessionTest extends TestCase
{
    public function testOpenSession(): void
    {
        $session = new DummySession();

        $this->assertFalse($session->getIsStarted());
        $this->assertNull($session->getSessionID());

        $session->open();

        $this->assertTrue($session->getIsStarted());
        $this->assertNotEmpty($session->getSessionID());
    }

    /**
     * @depends testOpenSession
     */
    public function testMultipleOpenSession(): void
    {
        $session = new DummySession();

        $session->open();

        $_SESSION['foo'] = 'bar';
        $sessionId = $session->getSessionID();

        $session->open();

        $this->assertNotEmpty($_SESSION);
        $this->assertEquals($sessionId, $session->getSessionID());
    }

    /**
     * @depends testOpenSession
     */
    public function testAutoStart(): void
    {
        $session = new DummySession();

        $session->init();

        $this->assertTrue($session->getIsStarted());
        $this->assertNotEmpty($session->getSessionID());
    }

    /**
     * @depends testOpenSession
     */
    public function testCloseSession(): void
    {
        $session = new DummySession();

        $session->open();

        $_SESSION['foo'] = 'bar';

        $session->close();

        $this->assertEmpty($_SESSION);

        $this->assertFalse($session->getIsStarted());
        $this->assertNull($session->getSessionID());
    }

    /**
     * @depends testOpenSession
     */
    public function testDestroySession(): void
    {
        $session = new DummySession();

        $session->open();

        $_SESSION['foo'] = 'bar';

        $session->destroy();

        $this->assertEmpty($_SESSION);

        $this->assertFalse($session->getIsStarted());
        $this->assertNull($session->getSessionID());
    }

    public function testRegenerateSessionId(): void
    {
        $session = new DummySession();

        $session->open();
        $_SESSION['foo'] = 'bar';

        $originalId = $session->getSessionID();
        $originalSessionData = $_SESSION;

        $session->regenerateID();

        $this->assertNotSame($originalId, $session->getSessionID());
        $this->assertSame($originalSessionData, $_SESSION);

        $session->regenerateID(true);

        $this->assertEmpty($_SESSION);
    }
}
<?php

namespace yii1tech\session\dummy;

/**
 * DummySession is a mock for the standard Yii session, which avoids direct operations over PHP standard session.
 *
 * This class is useful for the unit tests as it avoids sending headers and cookies to the StdOut.
 *
 * It may also come in handy in API development. For example: if you need to authenticate user via OAuth token, but
 * keep tracking him in the code using {@see \CWebUser} abstraction.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class DummySession extends \CHttpSession
{
    /**
     * @var string|null mocked session ID.
     */
    private $_id;

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        \CApplicationComponent::init(); // skip parent call, avoiding unnecessary shutdown function registration

        if ($this->autoStart) {
            $this->open();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function open()
    {
        if ($this->_id === null) {
            $this->_id = uniqid();
            $_SESSION = [];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        $this->_id = null;
        $_SESSION = [];
    }

    /**
     * {@inheritdoc}
     */
    public function destroy()
    {
        $this->_id = null;
        $_SESSION = [];
    }

    /**
     * {@inheritdoc}
     */
    public function getIsStarted()
    {
        return !empty($this->_id);
    }

    /**
     * {@inheritdoc}
     */
    public function getSessionID()
    {
        return $this->_id;
    }

    /**
     * {@inheritdoc}
     */
    public function setSessionID($value)
    {
        $this->_id = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function regenerateID($deleteOldSession = false)
    {
        $this->_id = uniqid();

        if ($deleteOldSession) {
            $_SESSION = [];
        }
    }
}

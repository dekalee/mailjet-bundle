<?php

namespace Dekalee\Message;

use Swift_Message;

/**
 * Class SwiftCustomVarsMessage
 */
class SwiftCustomVarsMessage extends Swift_Message
{
    /**
     * @var string[]
     */
    protected $vars = [];

    /**
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * @param array $vars
     *
     * @return $this
     */
    public function setVars($vars)
    {
        $this->vars = $vars;

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function addVar($key, $value)
    {
        $this->vars[$key] = $value;

        return $this;
    }
}

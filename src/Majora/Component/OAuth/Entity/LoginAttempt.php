<?php

namespace Majora\Component\OAuth\Entity;

/**
 * Value object to represent a login attempt on an application.
 */
class LoginAttempt
{
    /**
     * @var array
     */
    protected $query;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $headers;

    /**
     * Construct.
     *
     * @param array $query
     * @param array $data
     * @param array $headers
     */
    public function __construct(array $query, array $data, array $headers)
    {
        $this->query = $query;
        $this->data = $data;
        $this->headers = $headers;
    }

    /**
     * Returns query value under given key if defined, null otherwise.
     *
     * @param string $query
     *
     * @return mixed
     */
    public function getQuery($key)
    {
        return isset($this->query[$key]) ?
            $this->query[$key] :
            null
        ;
    }

    /**
     * Returns data value under given key if defined, null otherwise.
     *
     * @param string $data
     *
     * @return mixed
     */
    public function getData($key)
    {
        return isset($this->data[$key]) ?
            $this->data[$key] :
            null
        ;
    }

    /**
     * Returns headers value under given key if defined, null otherwise.
     *
     * @param string $headers
     *
     * @return mixed
     */
    public function getHeaders($key)
    {
        return isset($this->headers[$key]) ?
            $this->headers[$key] :
            null
        ;
    }
}

<?php

namespace Majora\OAuthServerBundle\Bridge;

use OAuth2\ResponseInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class Response bridges the HttpFoundation Response and OAuth2 Response.
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 * @author Brent Shaffer <bshafs@gmail.com>
 */
class Response extends JsonResponse implements ResponseInterface
{
    /**
     * Add parameters.
     *
     * @param array $parameters
     *
     * @throws \Exception
     */
    public function addParameters(array $parameters)
    {
        // if there are existing parameters, add to them
        if ($this->content && $data = json_decode($this->content, true)) {
            $parameters = array_merge($data, $parameters);
        }
        // this will encode the php array as json data
        $this->setData($parameters);
    }

    /**
     * Add headers.
     *
     * @param array $httpHeaders
     */
    public function addHttpHeaders(array $httpHeaders)
    {
        foreach ($httpHeaders as $key => $value) {
            $this->headers->set($key, $value);
        }
    }

    /**
     * Gets a parameter.
     *
     * @param $name
     */
    public function getParameter($name)
    {
        if ($this->content && $data = json_decode($this->content, true)) {
            return isset($data[$name]) ? $data[$name] : null;
        }
    }

    /**
     * Set an error.
     *
     * @param $statusCode
     * @param $error
     * @param null $description
     * @param null $uri
     */
    public function setError($statusCode, $error, $description = null, $uri = null)
    {
        $this->setStatusCode($statusCode);
        $this->addParameters(array_filter(array(
            'error' => $error,
            'error_description' => $description,
            'error_uri' => $uri,
        )));
    }

    /**
     * Set a redirection.
     *
     * @param int $statusCode
     * @param $url
     * @param null $state
     * @param null $error
     * @param null $errorDescription
     * @param null $errorUri
     */
    public function setRedirect($statusCode, $url, $state = null, $error = null, $errorDescription = null, $errorUri = null)
    {
        $this->setStatusCode($statusCode);
        $params = array_filter(array(
            'state' => $state,
            'error' => $error,
            'error_description' => $errorDescription,
            'error_uri' => $errorUri,
        ));
        if ($params) {
            // add the params to the URL
            $parts = parse_url($url);
            $sep = isset($parts['query']) && count($parts['query']) > 0 ? '&' : '?';
            $url .= $sep.http_build_query($params);
        }
        $this->headers->set('Location', $url);
    }
}

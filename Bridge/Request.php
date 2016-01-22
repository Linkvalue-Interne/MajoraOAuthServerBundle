<?php

namespace Majora\OAuthServerBundle\Bridge;

use OAuth2\RequestInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request as BaseRequest;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Request bridges HttpFoundation Request and OAuth2 Request.
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 * @author Brent Shaffer <bshafs@gmail.com>
 */
class Request extends BaseRequest implements RequestInterface
{
    /**
     * Creates an instance of this class from the HttpFoundation Request.
     *
     * @param BaseRequest $request
     *
     * @return static
     */
    public static function createFromRequest(BaseRequest $request)
    {
        return new static($request->query->all(), $request->request->all(), $request->attributes->all(), $request->cookies->all(), $request->files->all(), $request->server->all(), $request->getContent());
    }

    /**
     * Creates an instance of this class from the RequestStack.
     *
     * @param RequestStack $request
     *
     * @return Request
     */
    public static function createFromRequestStack(RequestStack $request)
    {
        $request = $request->getCurrentRequest();

        return self::createFromRequest($request);
    }

    /**
     * Gets a query parameter.
     *
     * @param $name
     * @param null $default
     *
     * @return mixed
     */
    public function query($name, $default = null)
    {
        return $this->query->get($name, $default);
    }

    /**
     * Gets a request parameter.
     *
     * @param $name
     * @param null $default
     *
     * @return mixed
     */
    public function request($name, $default = null)
    {
        return $this->request->get($name, $default);
    }

    /**
     * Gets a server parameter.
     *
     * @param $name
     * @param null $default
     *
     * @return mixed
     */
    public function server($name, $default = null)
    {
        return $this->server->get($name, $default);
    }

    /**
     * Gets a header parameter.
     *
     * @param $name
     * @param null $default
     *
     * @return array|string
     */
    public function headers($name, $default = null)
    {
        return $this->headers->get($name, $default);
    }

    /**
     * Gets all query parameters.
     *
     * @return array
     */
    public function getAllQueryParameters()
    {
        return $this->query->all();
    }

    /**
     * Creates a new request with values from PHP's super globals.
     * Overwrite to fix an apache header bug. Read more here:
     * http://stackoverflow.com/questions/11990388/request-headers-bag-is-missing-authorization-header-in-symfony-2%E2%80%94.
     *
     * @return Request A new request
     *
     * @api
     */
    public static function createFromGlobals()
    {
        $request = parent::createFromGlobals();

        //fix the bug.
        self::fixAuthHeader($request->headers);

        return $request;
    }

    /**
     * PHP does not include HTTP_AUTHORIZATION in the $_SERVER array, so this header is missing.
     * We retrieve it from apache_request_headers().
     *
     * @see https://github.com/symfony/symfony/issues/7170
     *
     * @param HeaderBag $headers
     */
    protected static function fixAuthHeader(HeaderBag $headers)
    {
        if (!$headers->has('Authorization') && function_exists('apache_request_headers')) {
            $all = apache_request_headers();
            if (isset($all['Authorization'])) {
                $headers->set('Authorization', $all['Authorization']);
            }
        }
    }
}

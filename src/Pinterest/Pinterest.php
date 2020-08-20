<?php
/**
 * Copyright 2015 Waleed Ahmad
 *
 * (c) Waleed Ahmad <waleedgplus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WaleedAhmad\Pinterest;

use WaleedAhmad\Pinterest\Auth\PinterestOAuth;
use WaleedAhmad\Pinterest\Endpoints\Users;
use WaleedAhmad\Pinterest\Endpoints\Following;
use WaleedAhmad\Pinterest\Endpoints\Boards;
use WaleedAhmad\Pinterest\Endpoints\Pins;
use WaleedAhmad\Pinterest\Utils\CurlBuilder;
use WaleedAhmad\Pinterest\Transport\Request;
use WaleedAhmad\Pinterest\Exceptions\InvalidEndpointException;

/**
 * @property \WaleedAhmad\Pinterest\Endpoints\Boards boards
 * @property \WaleedAhmad\Pinterest\Endpoints\Following following
 * @property \WaleedAhmad\Pinterest\Endpoints\Pins pins
 * @property \WaleedAhmad\Pinterest\Endpoints\Users users
 */
class Pinterest {

    /**
     * Reference to authentication class instance
     *
     * @var Auth\PinterestOAuth
     */
    public $auth;

    /**
     * A reference to the request class which travels
     * through the application
     *
     * @var Transport\Request
     */
    public $request;

    /**
     * A array containing the cached endpoints
     *
     * @var array
     */
    private $cachedEndpoints = [];

    /**
     * Constructor
     *
     * @param  string       $client_id
     * @param  string       $client_secret
     * @param  CurlBuilder  $curlbuilder
     */
    public function __construct($client_id, $client_secret, $curlbuilder = null)
    {
        if ($curlbuilder == null) {
            $curlbuilder = new CurlBuilder();
        }

        // Create new instance of Transport\Request
        $this->request = new Request($curlbuilder);

        // Create and set new instance of the OAuth class
        $this->auth = new PinterestOAuth($client_id, $client_secret, $this->request);
    }

    /**
     * Get a cached instance of Users
     * @return mixed
     * @throws InvalidEndpointException
     */
    public function user(){
        $endpoint = strtolower('users');
        $class = "\\WaleedAhmad\\Pinterest\\Endpoints\\" . ucfirst($endpoint);

        // Check if an instance has already been initiated
        if (!isset($this->cachedEndpoints[$endpoint])) {
            // Check endpoint existence
            if (!class_exists($class)) {
                throw new InvalidEndpointException;
            }

            $obj = new Users($this->request, $this);

            $this->cachedEndpoints[$endpoint] = $obj;
        }

        return $this->cachedEndpoints[$endpoint];
    }

    /**
     * Get a cached instance of Boards
     * @return mixed
     * @throws InvalidEndpointException
     */
    public function boards(){
        $endpoint = strtolower('boards');
        $class = "\\WaleedAhmad\\Pinterest\\Endpoints\\" . ucfirst($endpoint);

        // Check if an instance has already been initiated
        if (!isset($this->cachedEndpoints[$endpoint])) {
            // Check endpoint existence
            if (!class_exists($class)) {
                throw new InvalidEndpointException;
            }

            $obj = new Boards($this->request, $this);


            $this->cachedEndpoints[$endpoint] = $obj;
        }

        return $this->cachedEndpoints[$endpoint];
    }

    /**
     * Get a cached instance of Following
     * @return mixed
     * @throws InvalidEndpointException
     */
    public function following(){
        $endpoint = strtolower('following');
        $class = "\\WaleedAhmad\\Pinterest\\Endpoints\\" . ucfirst($endpoint);

        // Check if an instance has already been initiated
        if (!isset($this->cachedEndpoints[$endpoint])) {
            // Check endpoint existence
            if (!class_exists($class)) {
                throw new InvalidEndpointException;
            }

            $obj = new Following($this->request, $this);

            $this->cachedEndpoints[$endpoint] = $obj;
        }

        return $this->cachedEndpoints[$endpoint];
    }

    /**
     * Get a cached instance of Pins
     * @return mixed
     * @throws InvalidEndpointException
     */
    public function pins(){
        $endpoint = strtolower('pins');
        $class = "\\WaleedAhmad\\Pinterest\\Endpoints\\" . ucfirst($endpoint);

        // Check if an instance has already been initiated
        if (!isset($this->cachedEndpoints[$endpoint])) {
            // Check endpoint existence
            if (!class_exists($class)) {
                throw new InvalidEndpointException;
            }

            $obj = new Pins($this->request, $this);

            $this->cachedEndpoints[$endpoint] = $obj;
        }

        return $this->cachedEndpoints[$endpoint];
    }

    /**
     * Get rate limit from the headers
     *
     * @access public
     * @return integer
     */
    public function getRateLimit()
    {
        $header = $this->request->getHeaders();
        return (isset($header['X-RateLimit-Limit']) ? $header['X-RateLimit-Limit'] : 1000);
    }

    /**
     * Get rate limit remaining from the headers
     *
     * @access public
     * @return mixed
     */
    public function getRateLimitRemaining()
    {
        $header = $this->request->getHeaders();
        return (isset($header['X-RateLimit-Remaining']) ? $header['X-RateLimit-Remaining'] : 'unknown');
    }

    /**
     * @access public
     * @return PinterestOAuth
     */
    public function auth(){
        return $this->auth;
    }
}

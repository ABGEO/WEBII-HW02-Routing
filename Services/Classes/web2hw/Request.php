<?php

namespace web2hw;

class Request
{
    /**
     * Get request HTTP headers
     *
     * @param null|array|string $key
     * @return array|string
     */
    public function getHeaders($key = null)
    {
        $headers = getallheaders();
        $return = null;

        if ($key != null) {
            if (is_array($key)) {
                foreach ($key as $k) {
                    $value = isset($headers[$k]) ? $headers[$k] : null;

                    $return[$k] = $value;
                }
            } else
                $return = isset($headers[$key]) ? $headers[$key] : null;
        } else
            $return = $headers;

        return $return;
    }

    /**
     * Check if request has header
     *
     * @param $key
     * @return bool
     */
    public function hasHeader($key)
    {
        return $this->getHeaders($key) != null;
    }

    /**
     * Get request HTTP method
     *
     * @return string
     */
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Get request path
     *
     * @return string
     */
    public function getPath()
    {
        return $_SERVER['PATH_INFO'];
    }

    /**
     * Get request scheme(http or https)
     *
     * @return string
     */
    function getScheme()
    {
        return stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
    }

    /**
     * Get request query parameters
     *
     * @return array
     */
    function getQueryParams()
    {
        $queryString = $_SERVER['QUERY_STRING'];
        $queryArray = explode('&', $queryString);
        $response = array();
        foreach ($queryArray as $query) {
            $query = explode('=', $query);
            $response[$query[0]] = $query[1];
        }
        return $response;
    }

    /**
     * Get request query parameter by key
     *
     * @param $key
     * @return string|null
     */
    function getQuery($key)
    {
        $queryArray = $this->getQueryParams();

        if (isset($queryArray[$key]))
            return $queryArray[$key];

        return null;
    }

    /**
     * Get request data by method
     *
     * @return array
     */
    function getData()
    {
        $method  = $this->getMethod();
        $return = null;

        if ($method == 'GET')
            $return = $_POST;
        else if ($method == 'POST')
            $return = $_POST;

        return $return;
    }
}
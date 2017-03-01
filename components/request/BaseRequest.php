<?php

namespace app\components\request;

/**
 * @class BaseRequest
 * @brief This class will return  data as per user request (i.e Headers, post, get....)
 * @version : 1.0
 */
abstract class BaseRequest
{

    /**
     * @var holds app http request
     */
    protected $request;

    /**
     * @array holds app request attributes
     */
    protected $requestAttributes = [];

    /**
     * @var holds app request data
     */
    protected $requestData;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @brief returns header value for given key
     * @param string $key header key name to return value for that key
     * @return string if key is not found in header returns false
     *                        else returns value for that key  
     */
    final public function getHeaderValue($key)
    {
        if (!empty($key)) {
            return $this->headerValue($key);
        }
        return null;
    }

    /**
     * @brief returns reference location of app params
     * @return array
     */
    final public function &getAppParams()
    {
        return $this->appParams();
    }

    /**
     * @brief process http requested key value pairs
     * @param array $apiRequestKeys
     */
    final public function processRequestParams($apiRequestKeys)
    {
        $this->processParams($apiRequestKeys);
    }

    /**
     * 
     * @return type
     */
    final public function &getRequestAttributes()
    {
        return $this->requestAttributes;
    }

    /**
     * 
     * @return type
     */
    final public function &getRequestData()
    {
        return $this->requestData;
    }

    /**
     * @brief abstract method to get header value
     */
    abstract function headerValue($key);

    /**
     * @brief abstract method to get http request values
     */
    abstract function processParams($apiRequestKeys);

    /**
     * @brief abstract method to get the app config param values
     */
    abstract function appParams();
}

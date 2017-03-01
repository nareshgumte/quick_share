<?php

namespace app\components\authentication;

/**
 * @class Signature 
 * @brief This class will be used to calculate signature based on request data along with nonce 
 * @version : 1.0
 */
class Signature
{

    /**
     * @brief calculates signature key
     * @param array $requestAttributes api requestAttributes
     * @param string $accessToken value sent in api request for signature calculation 
     * @param string $nonce value sent in api request for signature calculation 
     * @param string $publickey key taken from app params used for signature calculation
     * @return string
     */
    public static function calculateSignature(&$requestAttributes, $accessToken, $nonce, $publickey)
    {
        $requestData = self::convertKeyValuePairsToString($requestAttributes, '&');
        //print_r($requestData);exit();
        //internally it will be order the data
        //echo($requestData . $accessToken . $nonce . $publickey);exit();
        return sha1($requestData . $accessToken . $nonce . $publickey);
    }

    /**
     * @brief converts associative key value pairs to string by concatenation with given operator 
     * @param array $requestAttributes api requested params
     * @param string $operator to seperate key values pairs while forming a string  
     * @return string
     */
    private static function convertKeyValuePairsToString($requestAttributes, $operator = '')
    {
        $response = '';
        //print_r($requestAttributes);exit();
        ksort($requestAttributes); // sort array keys in ascending order
        foreach ($requestAttributes as $key => $value) {
            $response .= $key . '=' . $value . $operator;
        }
        return rtrim($response, $operator);
    }

}
<?php


namespace app\components\cache;

use Yii;

/**
 * @class Caching
 * @brief This class will be used to perform cache operations
 * @version : 1.0
 */
class Caching
{

    /**
     * @brief sets a key value pair to cache with duration
     * @param string $key 
     * @param string $value
     * @param type $duration how much time the stored value must be in Cache. 
     *        if 0 won't expire 
     */
    public static function setKey($key, $value, $duration = 0)
    {
        Yii::$app->cache->set($key, $value, $duration);
    }

    /**
     * @brief returns value for given key
     * @param string $key
     * @return string value for given string
     */
    public static function getKey($key)
    {
        return Yii::$app->cache->get($key);
    }

    /**
     * @brief check whether given key exists in cache
     * @param type $key
     * @return boolean
     */
    public static function isKeyExists($key)
    {
        if (Yii::$app->cache->exists($key)) {
            return true;
        }
        return false;
    }

    /**
     * Deletes a value with the specified key from cache
     * @param mixed $key a key identifying the value to be deleted from cache. This can be a simple string or
     * a complex data structure consisting of factors representing the key.
     * @return boolean if no error happens during deletion
     */
    public static function deleteKey($key)
    {
        return Yii::$app->cache->delete($key);
    }

}
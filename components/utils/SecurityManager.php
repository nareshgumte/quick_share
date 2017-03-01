<?php


namespace app\components\utils;

use app\components\utils\Uid;

/**
 * @class SecurityManager 
 * @brief Wrapper class with security functions
 * @version : 1.0
 */
class SecurityManager
{

    /**
     * @brief Generates a secure hash from a password and a random salt.
     * @param string $password The password to be hashed.
     * @return string The password hash string.
     * @throws \yii\base\Exception when an empty password is sent.
     */
    public static function encryptPassword($password = '')
    {
        if ($password) {
            return \Yii::$app->getSecurity()->generatePasswordHash($password, 8);
        } else {
            throw new \yii\web\HttpException(403, \Yii::t('app-utils', 'generating_password_hash_failed'));
        }
    }

    /**
     * @brief Verifies a password against a hash.
     * @param string $password The password to verify.
     * @param string $hash The hash to verify the password against.
     * @return boolean whether the password is correct.
     */
    public static function validatePassword($password, $hash)
    {   
        //print_r($password);echo "\n";print_r($hash);exit();
        //var_dump(\Yii::$app->getSecurity()->validatePassword($password, $hash));exit();
        if (\Yii::$app->getSecurity()->validatePassword($password, $hash)) {
            //print_r($password);echo "\n";print_r($hash);exit();
            return true;
        }
        return false;
    }

    /**
     * @brief generates random Key
     * @return string
     */
    public static function generateRandomKey()
    {
        return \Yii::$app->getSecurity()->generateRandomKey();
    }

    /**
     * @brief generates random string
     * @return string
     */
    public static function generateRandomString()
    {
        return \Yii::$app->getSecurity()->generateRandomString();
    }

    /**
     * @brief generates password reset token with current time concatinated
     * @return string password reset token 
     */
    public static function generatePasswordResetToken()
    {
        return self::generateRandomString() . '_' . time();
    }

    /**
     * @brief Generates access token using userid,saltKey  
     * @param integer $userId
     * @return string access token for api validation
     */
    public static function generateAccessToken($userId)
    {
        $accessToken = md5($userId . self::generateRandomKey()) . Uid::generateUUID();
        return $accessToken;
    }

}

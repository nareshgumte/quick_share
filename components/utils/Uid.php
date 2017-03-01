<?php

namespace app\components\utils;


use Rhumsaa\Uuid\Uuid;


use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;


//use Ramsey\Uuid\Uuid;
//use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

/**
 * @class Uid 
 * @brief Wrapper class to generate uuid.
 * @version : 1.0
 */
class Uid
{

    /**
     * @brief Generate a version 4 (random) UUID.
     * @return uuid
     */
    public static function generateUUID()
    {
        try {
            $uuid4 = Uuid::uuid4();
            return $uuid4->getHex();
        } catch (Exception $exc) {
            \Yii::error($exc->getTraceAsString());
        }
    }

}

<?php


namespace app\components\request;

use Yii;
use app\components\request\BaseRequest;

/**
 * @class HttpRequest 
 * @brief This class will return user requested data (i.e Headers, post, get....)
 * @version : 1.0
 */

final class HttpRequest extends BaseRequest
{

    /**
     * @brief returns value for given header key 
     * @param string $key key tocheck in headers 
     * @return string if key is not found in header returns null
     *                else returns value for that key
     */
    public function headerValue($key)
    {
        if (!$this->request->getHeaders()->get($key)) {
            return $this->request->getHeaders()->get($key);
        }
        return null;
    }

    /**
     * @brief returns application configuration files 
     * @return array 
     */
    public function &appParams()
    {
        return Yii::$app->params;
    }

    /**
     * @brief process http request key value pairs
     * @param array $apiRequestKeys 
     */
    public function processParams($apiRequestKeys)
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->requestAttributes = $this->request->get();
                Yii::info("GET Request ::" . json_encode($this->requestAttributes), 'requestResponse', __METHOD__);
                break;
            case 'POST':
                $params = $this->request->post();
                Yii::info("POST Request ::" . json_encode($params), 'requestResponse', __METHOD__);
                if (count(array_keys($params)) > 0) {
                    if (count(array_intersect($apiRequestKeys, array_keys($params))) == 1) {
                        Yii::info("Attributes$$ Request ::" . @$params['attributes'], 'requestResponse', __METHOD__);
                        $attributes = json_decode(@$params['attributes'], true);
                        Yii::info("Attributes$$ JSON ::" . serialize($attributes), 'requestResponse', __METHOD__);
                        $this->requestData = @$params['data'];
                        if (count($attributes) > 0) {
                            $this->requestAttributes = $attributes;
                        }
                    } else {
                        throw new \yii\web\HttpException(400, 'invalid request');
                    }
                }
                break;
        }
    }

}

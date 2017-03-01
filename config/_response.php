<?php
return [
    'format' => yii\web\Response::FORMAT_JSON,
    'charset' => 'UTF-8',
    'class' => 'yii\web\Response',
    'on beforeSend' => function ($event) {
        $response = $event->sender;
        $data = $response->data;
        Yii::info("Response ::" . json_encode($response->data), 'requestResponse', __METHOD__);
        if ($data !== null) {
            $message = isset($data['message']) ? $data['message'] : "Api request success.";
            unset($data['message']);
            if ($response->isSuccessful) { // if api call response status code with 200 
                $response->data = [
                    'response' => 'success',
                    'status_code' => $response->getStatusCode(),
                    'message' => $message,
                ];
                if (!empty($data)) {
                    $response->data['data'] = $data;
                }
            } else {  // if api call response status code other then 200
                $response->data = [
                    'response' => 'failed',
                    'status_code' => $response->getStatusCode(),
                    'message' => $message
                ];
            }
        }
    },
];




<?php

namespace App\Src\Helpers;

use App\Src\Exceptions\BaseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Throwable;

class SendResponse
{

    static $headers = [];

    /**
     * Send the success response.
     *
     * @param array $result
     * @param string $message
     * @param integer $code
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public static function success($result = [], $message = null, $code = 200)
    {
        $response = [
            'code' => $code,
            'message' => $message ? $message : 'Successfully proccess the request!',
            'data' => $result,
        ];

        return new JsonResponse($response, $code);
    }

    /**
     * Send the error response.
     *
     * @param array $result
     * @param string $message
     * @param Throwable $th
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public static function error($result = [], $message = 'An error occurred!', Throwable $th = null, $errorCode = 500)
    {
        $throwableCode = $th ? (int) $th->getCode() : $errorCode;
        $code = ($throwableCode > 505 || $throwableCode < 200) ? 500 : $throwableCode;

        $originMessage = '';
        // if (Str::contains($message, ['SQLSTATE', 'SQL:'])) {
        //     $originMessage = $message;
        //     $message = "Error when execute query, please check log to see detail.";
        // }

        if ($th instanceof ValidationException) {
            $result = $th->errors();
            $code = $th->status;
        }

        if ($th instanceof BaseException) {
            $result = $th->errors();
        }

        $response = [
            'code' => $code,
            'message' => $message,
            'error' => $result,
        ];

        try {
            $params = fn_request()->all();
            self::hideParams($params, ['password']);

            Logging::error("$message $originMessage" , [
                'payloads' => $params,
                'response' => $response,
                'trace' => $th->getTraceAsString(),
                'path' => fn_request()->path(),
                'query' => fn_request()->query(),
                // 'trace' =>  explode("#10", $th->getTraceAsString())[0],
            ]);
        } catch (\Throwable $th) {
            // throw $th;
        }

        return new JsonResponse($response, $code, self::getHeaders());
    }

    /**
     * Set the headers to response.
     *
     * @param array $headers
     * @return static
     */
    public static function setHeaders($headers = [])
    {
        self::$headers = $headers;
        return new static;
    }

    /**
     * Get the headers.
     *
     * @return array
     */
    public static function getHeaders()
    {
        return self::$headers;
    }

    /**
     * Hide some sensitive attribut from logged
     * 
     * @param array $params
     * @param array $key 
     */
    public static function hideParams(&$params, array $keys)
    {
        foreach ($keys as $value) {
            if (array_key_exists($value, $params)) {
                $params[$value] = 'xxxxxx';
            }
        }
    }
}

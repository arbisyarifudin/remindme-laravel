<?php

namespace App\Http\Controllers;

class ApiController extends Controller
{
    private static $validHttpCodeList = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required',
    ];

    private function getErrorName($code)
    {
        if (isset(static::$validHttpCodeList[$code])) {
            $errorName = 'ERR_';
            $errorCodeName = trim(str_replace(' ', '_', strtoupper(static::$validHttpCodeList[$code])));
            return $errorName . $errorCodeName;
        }

        return 'ERR_INTERVAL_SERVER_ERROR';
    }

    public function responseFailed($messages = '', $statusCode = 400, $statusName = null)
    {
        return response()->json(
            [
                'ok' => false,
                'err' => $statusName ? $statusName :$this->getErrorName($statusCode),
                'msg' => $messages
            ],
            $statusCode
        );
    }

    public function responseSuccess($data = [], $statusCode = 400)
    {

        return response()->json(
            [
                'ok' => true,
                'data' => $data,
            ],
            $statusCode
        );
    }
}

<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Kernel\Http;

use App\Constants\ErrorCode;
use Hyperf\HttpMessage\Cookie\Cookie;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\HttpServer\Request;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Response
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ResponseInterface
     */
    protected $response;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->response = $container->get(ResponseInterface::class);
    }

    public function redirect($url, $status = 302)
    {
        return $this->response()
            ->withAddedHeader('Location', (string) $url)
            ->withStatus($status);
    }

    public function api($result = [], $code = ErrorCode::HTTP_OK, $message = [])
    {
        if (is_array($message)) {
            $message = ErrorCode::getMessage($code, ...$message);
        }
        $httpCode = ((int) ErrorCode::getHttpCode($code)) ?: 200;
        $result = [
            'status' => $code,
            'result' => $result,
            'message' => $message,
        ];
        return $this->response->json($result)->withStatus($httpCode)->withoutHeader('Server');
    }

    public function cookie(Cookie $cookie)
    {
        $response = $this->response()->withCookie($cookie);
        Context::set(PsrResponseInterface::class, $response);
        return $this;
    }

    /**
     * @return \Hyperf\HttpMessage\Server\Response
     */
    public function response()
    {
        return Context::get(PsrResponseInterface::class);
    }

    /**
     * @return null|mixed|RequestInterface
     */
    public function request()
    {
        return Context::get(ServerRequestInterface::class);
    }
}

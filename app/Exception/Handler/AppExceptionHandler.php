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

namespace App\Exception\Handler;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Kernel\Http\Response;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\Utils\ApplicationContext;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function __construct(StdoutLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->logger->error(sprintf(
            '%s[%s] in %s',
            $throwable->getMessage(),
            $throwable->getLine(),
            $throwable->getFile()
        ));
        $this->logger->error($throwable->getTraceAsString());
        $response = ApplicationContext::getContainer()->get(Response::class);
        return $response->api(
            [],
            $this->getErrorCode($throwable),
            $this->getErrorMessage($throwable)
        )->withStatus($this->getStatusCode($throwable));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }

    public function getErrorCode(Throwable $throwable)
    {
        if ($throwable instanceof BusinessException) {
            return (int) $throwable->getCode();
        }
        return 500;
    }

    public function getStatusCode(Throwable $throwable)
    {
        if ($throwable instanceof BusinessException) {
            return (int) ErrorCode::getHttpCode($throwable->getCode());
        }
        return 500;
    }

    protected function getErrorMessage(Throwable $throwable)
    {
        return $throwable->getMessage();
    }
}

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

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 * @method static string getMessage(int $code, ...$message)
 * @method static string getHttpCode(int $code)
 */
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("OK")
     * @HttpCode(200)
     */
    const HTTP_OK = 200;

    /**
     * @Message("Server Error！")
     * @HttpCode(500)
     */
    const SERVER_ERROR = 500;
}

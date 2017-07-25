<?php
/**
 * Created by David Cardenas
 * GitHub: https://github.com/dcardenascom
 * GitLab: https://gitlab.com/dcardenas
 * Site: http://dcardenas.com
 * LinkedIn: https://www.linkedin.com/in/davidcardenasguia/.
 */

namespace Lemonway\Exceptions;

use Throwable;

/**
 * Class ApiException.
 */
class ApiException extends CommonException
{
    /**
     * ApiException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 101, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->description = 'The API request threw an exception';
    }
}

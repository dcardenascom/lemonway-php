<?php
/**
 * Created by David Cardenas
 * GitHub: https://github.com/dcardenascom
 * GitLab: https://gitlab.com/dcardenas
 * Site: http://dcardenas.com
 * LinkedIn: https://www.linkedin.com/in/davidcardenasguia/.
 */

namespace Lemonway\Exceptions;

use Exception;

/**
 * Class CommonException.
 */
class CommonException extends Exception
{
    protected $description = 'Common Exception';

    /****
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}

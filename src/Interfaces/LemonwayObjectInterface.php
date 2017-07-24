<?php
/**
 * Created by David Cardenas
 * GitHub: https://github.com/davidcardenasguia
 * GitLab: https://gitlab.com/dcardenas
 * Site: http://dcardenas.com
 * LinkedIn: https://www.linkedin.com/in/davidcardenasguia/.
 */

namespace Lemonway\Interfaces;

use stdClass;

/**
 * Interface LemonwayObjectInterface.
 */
interface LemonwayObjectInterface
{
    public function bindFromLemonway(stdClass $object): bool;

    public function toArray(): array;
}

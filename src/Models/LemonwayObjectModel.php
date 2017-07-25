<?php
/**
 * Created by David Cardenas
 * GitHub: https://github.com/dcardenascom
 * GitLab: https://gitlab.com/dcardenas
 * Site: http://dcardenas.com
 * LinkedIn: https://www.linkedin.com/in/davidcardenasguia/.
 */

namespace Lemonway\Models;

use Lemonway\Interfaces\LemonwayObjectInterface;
use stdClass;

/**
 * Class LemonwayObjectModel.
 *
 * @codeCoverageIgnore
 */
class LemonwayObjectModel extends CommonModel implements LemonwayObjectInterface
{
    /**
     * @param stdClass $object
     *
     * @return bool
     */
    public function bindFromLemonway(stdClass $object): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [];
    }
}

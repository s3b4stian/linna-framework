<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Router;

use Linna\TypedArrayObject\ClassArrayObject;

/**
 * Route Collection
 */
class RouteCollection extends ClassArrayObject
{
    /**
     * Contructor.
     *
     * @param array<Route> $array
     */
    public function __construct(array $array = [])
    {
        parent::__construct(Route::class, $array);
    }
}

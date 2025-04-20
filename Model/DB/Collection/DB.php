<?php

namespace RouteDB\Model\DB\Collection;

use MVC\DB\Model\DbCollection;
use MVC\DB\Trait\TraitDbInit;


/**
 * DB
 */
class DB extends DbCollection
{
    use TraitDbInit;

    use TraitRoute;
}

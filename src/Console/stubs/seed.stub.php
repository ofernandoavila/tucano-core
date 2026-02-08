<?php

use App\Model\##{{NAME}}##;
use Ofernandoavila\TucanoCore\Trait\SeedDatabaseTrait;

return new class
{
    use SeedDatabaseTrait;
    
    public function seed()
    {
        return self::save([
            // Add data here...

        ], ##{{NAME}}##::class);
    }
};

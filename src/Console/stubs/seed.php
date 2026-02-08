<?php

use App\Models\##{{ model }}##;
use Ofernandoavila\TucanoCore\Trait\SeedDatabaseTrait;

return new class
{
    use SeedDatabaseTrait;
    
    public function seed()
    {
        return self::save_registers([
            // Add data here...

        ], ##{{ model }}##::class);
    }
};

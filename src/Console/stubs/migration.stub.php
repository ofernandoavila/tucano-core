<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

return new class
{
    public function Up()
    {
        if (!Capsule::schema()->hasTable("##{{TABLE_NAME}}##")) {
            Capsule::schema()->create("##{{TABLE_NAME}}##", function (Blueprint $table) {
                $table->uuid("id")->primary(true);
                $table->timestamps();
            });
        }
    }

    public function Down()
    {
        return Capsule::schema()->dropIfExists("##{{TABLE_NAME}}##");
    }
};

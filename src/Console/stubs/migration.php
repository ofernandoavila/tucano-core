<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

return new class
{
    public function Up()
    {
        if (!Capsule::schema()->hasTable("##{{ table_name }}##")) {
            Capsule::schema()->create("##{{ table_name }}##", function (Blueprint $table) {
                $table->uuid("id")->primary(true);
                $table->boolean("ativo")->nullable()->default(true);
                $table->timestamps();
            });
        }
    }

    public function Down()
    {
        return Capsule::schema()->dropIfExists("##{{ table_name }}##");
    }
};

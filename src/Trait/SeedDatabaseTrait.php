<?php

namespace Ofernandoavila\TucanoCore\Trait;

trait SeedDatabaseTrait
{
    protected static function save(array $registers, string $model)
    {
        foreach ($registers as $register) {

            $entity = $model::create($register);

            if (isset($register['id']))
                $entity->id = $register['id'];

            $entity->save();
        }
    }
}

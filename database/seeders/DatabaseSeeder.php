<?php

namespace Database\Seeders;

use App\Models\Division;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        // OCRUZADO - DOCUMENTACIÓN FAKER
        // https://fakerphp.github.io/formatters/numbers-and-strings/

        $faker = Faker::create();

        DB::table('division')->truncate();

        foreach (range(1, 50) as $data) {
            Division::create([
                'disu_IdDivisionSuperior' => $faker->numberBetween(0, 50),
                'divi_Nombre' => $faker->unique()->state(),

                'divi_Nivel' => $faker->randomDigitNotZero(),
                'divi_Colaborador_Cantidad' => $faker->randomDigitNotZero(),

                'divi_Embajador_Nombre' => $faker->name(),

            ]);
        }

    }
}

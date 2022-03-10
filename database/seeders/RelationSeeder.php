<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Relation\Relation;

class RelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Relation::create([
            'relationId' => 85,
            'genderId' => 1,
            'reverseRelationId' => 87
        ]);
        Relation::create([
            'relationId' => 85,
            'genderId' => 2,
            'reverseRelationId' => 88
        ]);
        Relation::create([
            'relationId' => 86,
            'genderId' => 1,
            'reverseRelationId' => 87
        ]);
        Relation::create([
            'relationId' => 86,
            'genderId' => 2,
            'reverseRelationId' => 88
        ]);
        Relation::create([
            'relationId' => 90,
            'genderId' => 1,
            'reverseRelationId' => 90
        ]);
        Relation::create([
            'relationId' => 90,
            'genderId' => 2,
            'reverseRelationId' => 89
        ]);
        Relation::create([
            'relationId' => 89,
            'genderId' => 1,
            'reverseRelationId' => 90
        ]);
        Relation::create([
            'relationId' => 89,
            'genderId' => 2,
            'reverseRelationId' => 89
        ]);
        Relation::create([
            'relationId' => 87,
            'genderId' => 1,
            'reverseRelationId' => 85
        ]);
        Relation::create([
            'relationId' => 87,
            'genderId' => 2,
            'reverseRelationId' => 86
        ]);
        Relation::create([
            'relationId' => 88,
            'genderId' => 1,
            'reverseRelationId' => 85
        ]);
        Relation::create([
            'relationId' => 88,
            'genderId' => 2,
            'reverseRelationId' => 86
        ]);
    }
}

<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            "name" => "editor",
            "email" => "editor@cursosdesarrolloweb.es",
            "password" => bcrypt("password")
        ]);

        factory(User::class)->create([
            "name" => "reader",
            "email" => "reader@cursosdesarrolloweb.es",
            "password" => bcrypt("password")
        ]);

        app()->setLocale('es');
        factory(\App\Models\Category::class)->create([
            "name" => "Fruta"
        ]);

        factory(\App\Models\Category::class)->create([
            "name" => "Bollería"
        ]);

        $category1 = \App\Models\Category::find(1);
        $category1->setTranslation('name', 'en', "Fruit");
        $category1->save();

        $category2 = \App\Models\Category::find(2);
        $category2->setTranslation('name', 'en', "Pastries");
        $category2->save();
    }
}

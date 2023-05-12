<?php

namespace Modules\Acc\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Acc\Entities\Setting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'registration_state'        =>  true, // Allow the registration on the APP.
            'app_name'                  =>  'Yezz.Design', // Change the Name of the App.
            'app_name_backend'          =>  'Yezz.Backend', // Change the name of the backend.
        ];
    }
}


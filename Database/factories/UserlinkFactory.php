<?php

namespace Modules\Acc\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserlinkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Acc\Entities\Userlink::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'   =>   rand(1,10),
            'link_name' =>  $this->faker->word,
            'link_address'  =>  'https://www.'.$this->faker->word.'.de',
            'link_icon' =>  null,
        ];
    }
}


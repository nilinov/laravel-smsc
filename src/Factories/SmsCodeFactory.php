<?php

namespace Papalapa\Laravel\Smsc\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Papalapa\Laravel\Smsc\Models\SmsCode;

final class SmsCodeFactory extends Factory
{
    protected $model = SmsCode::class;

    public function create($attributes = [], ?Model $parent = null) : Collection|SmsCode
    {
        return parent::create($attributes, $parent);
    }

    public function make($attributes = [], ?Model $parent = null) : Collection|SmsCode
    {
        return parent::make($attributes, $parent);
    }

    public function definition() : array
    {
        return [
            'id' => $this->faker->uuid,
            'number' => $this->generateNumber(),
            'code' => $this->generateCode(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => null,
        ];
    }

    private function generateNumber() : string
    {
        return sprintf('77%09d', $this->faker->numberBetween(0, 10 ** 9 - 1));
    }

    private function generateCode() : string
    {
        return sprintf('%06d', $this->faker->numberBetween(0, 10 ** 6 - 1));
    }
}

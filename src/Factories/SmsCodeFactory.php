<?php

namespace Papalapa\Laravel\Smsc\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Papalapa\Laravel\Smsc\Models\SmsCode;
use Papalapa\Laravel\Smsc\PhoneNumber;
use Papalapa\Laravel\Smsc\Services\CodeGenerator;

final class SmsCodeFactory extends Factory
{
    protected $model = SmsCode::class;

    public function create($attributes = [], ?Model $parent = null): Collection|SmsCode
    {
        return parent::create($attributes, $parent);
    }

    public function make($attributes = [], ?Model $parent = null): Collection|SmsCode
    {
        return parent::make($attributes, $parent);
    }

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'number' => PhoneNumber::asNumeric($this->faker->phoneNumber),
            'code' => CodeGenerator::generateStatic(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => null,
        ];
    }
}

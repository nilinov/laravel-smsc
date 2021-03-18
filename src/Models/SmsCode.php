<?php

namespace Papalapa\Laravel\Smsc\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Papalapa\Laravel\Smsc\Database\Factories\SmsCodeFactory;
use Ramsey\Uuid\Uuid;

/**
 * @property string      $id
 * @property int         $serial
 * @property string      $number
 * @property string      $code
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static SmsCodeFactory factory(...$parameters)
 */
final class SmsCode extends Model
{
    use SoftDeletes, HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected $hidden = [];

    protected $casts = [
        'serial' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        $this->id = Uuid::uuid4()->toString();

        parent::__construct($attributes);
    }

    public function isAliveAfter(int $seconds): bool
    {
        return $this->created_at->addSeconds($seconds)->isAfter(Carbon::now());
    }
}

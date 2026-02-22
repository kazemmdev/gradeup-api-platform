<?php

namespace Module\Payment\Models;

use Module\Auth\Models\User;
use Module\Payment\Enums\PaymentStatus;
use Shared\Contract\HasUuid;
use Shared\Models\BaseModel;

/**
 * @property int $id
 * @property string $uuid
 * @property int $amount
 * @property PaymentStatus $status
 * @property User $user
 * @property mixed $billable
 * @property string $ref_id
 * @property string $confirm_id
 * @property int $discount
 * @property mixed $created_at
 * @property mixed $confirmed_at
 * @property int $user_id
 * @property bool $is_internal
 */
class Transaction extends BaseModel
{
    use HasUuid;

    protected $casts = [
        'status' => PaymentStatus::class,
    ];
}

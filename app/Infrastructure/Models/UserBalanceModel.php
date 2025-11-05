<?php

declare(strict_types=1);

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class UserBalanceModel extends Model
{
    protected $table = 'user_balances';

    protected $fillable = [
        'user_id',
        'balance',
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }

    protected $casts = [
        'balance' => 'decimal:2',
    ];
}

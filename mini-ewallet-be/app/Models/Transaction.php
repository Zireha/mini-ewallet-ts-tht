<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(["wallet_id", "amount", "type", "description"])]
class Transaction extends Model
{
    public $incrementing = false;
    protected $keyType = "string";

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($transaction) {
            $date = now()->format("ymd");
            $count = Transaction::count() + 1;

            // ensure unique ID, prevents race condition
            do {
                $id = $date . "-" . str_pad($count, 4, "0", STR_PAD_LEFT);
                $count++;
            } while (Transaction::where("id", $id)->exists());

            $transaction->id = $id;
        });
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}

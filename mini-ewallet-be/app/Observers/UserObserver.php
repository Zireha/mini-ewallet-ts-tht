<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Wallet;

class UserObserver
{
    
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {

        $user->wallet()->create([
            "balance" => 100000,
            "wallet_number" => $this->generateWalletNumber(),
        ]);
    }

    private function generateWalletNumber(): int
    {
        do {
            $number = random_int(10000000, 99999999);
        } while (Wallet::where("wallet_number", $number)->exists());

        return $number;
    }
    
    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}

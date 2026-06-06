<?php

namespace App\Services;

use app\Models\User;
use app\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Exception;

class TransferService {
    public function executeTransfer(User $sender, User $receiver, $amount) {
        if($sender->wallet->balance < $amount) {
            throw new Exception("Insufficient Balance");
        }

        return DB::transaction(function() use($sender, $receiver, $amount) {

            // deduct amount from sender balance account
            $sender->wallet->decrement('balance', $amount);

            // add amount to receiver acc balance
            $receiver->wallet->increment('balance', $amount);

            // make tx for sender
            $sender->wallet->transactions()->create([
                'type' => "debit",
                "amount" => $amount,
                "description" => "Transfer to ". $receiver->name
            ]);

            // make tx for receiver also
            $receiver->wallet->transactions()->create([
                'type' => 'credit',
                'amount' => $amount,
                "description" => "Receive transfer from ". $sender->name,
            ]);

            return true;
        });
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TransferService;
use Illuminate\Http\Request;
use App\Models\Wallet;
use Exception;
use App\Http\Resources\TransactionResource;

class TransactionController extends Controller
{
    // return all trx data
    public function index(Request $request)
    {
        $transactions = $request
            ->user()
            ->transactions()
            ->latest()
            ->paginate(10);
        return response()->json([
            "data" => TransactionResource::collection($transactions->items()),
            "meta" => [
                "current_page" => $transactions->currentPage(),
                "last_page" => $transactions->lastPage(),
                "per_page" => $transactions->perPage(),
                "total" => $transactions->total(),
            ],
        ]);
    }

    public function transfer(Request $request, TransferService $service)
    {
        // input validation
        $request->validate([
            "receiver_number" =>
                "required|numeric|digits:8|exists:wallets,wallet_number",
            "amount" => "required|numeric|min:1",
        ]);

        $sender = $request->user();

        $receiverWallet = Wallet::where(
            "wallet_number",
            $request->receiver_number,
        )->first();
        $receiver = $receiverWallet->user;

        // block self transfer
        if ($sender->id === $receiver->id) {
            return response()->json(
                [
                    "message" => "can't transfer to yourself!",
                ],
                400,
            );
        }

        try {
            $service->executeTransfer($sender, $receiver, $request->amount);
            return response()->json(["message" => "transfer success"], 200);
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], 400);
        }
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "amount" => $this->amount,
            "type" => $this->type,
            "description" => $this->description,
            "date" => $this->created_at->format('d/m/Y H:i')
        ];
    }
}

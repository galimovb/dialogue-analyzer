<?php

namespace App\Http\Resources;

use App\Models\Dialogue;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Dialogue
 */
class DialogueResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'outcome' => $this->outcome,
            'manager' => new UserResource($this->whenLoaded('manager')),
            'client' => new UserResource($this->whenLoaded('client')),
            'messages_count' => $this->whenCounted('messages'),
            'created_at' => $this->created_at,
        ];
    }
}

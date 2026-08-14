<?php

namespace App\Modules\Dialogues\Resources;

use App\Modules\Dialogues\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Message
 */
class MessageResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'sent_at' => $this->sent_at,
            'sender' => new UserResource($this->whenLoaded('sender')),
        ];
    }
}

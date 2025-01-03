<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         // Format tanggal
        $formattedDate = Carbon::parse($this->tanggal)
        ->locale('id')  // Set locale ke bahasa Indonesia
        ->translatedFormat('j F Y');

        return [
            'id' => $this->id,
            'reading_time' => $this->reading_time,
            'thumbnail' => $this->thumbnail,
            'title' => $this->title,
            'content' => $this->content,
            'slug' => $this->slug,
            'category_id' => $this->category_id,
            'is_published' => $this->is_published,
            'tanggal' => $formattedDate,
        ];
    }
}

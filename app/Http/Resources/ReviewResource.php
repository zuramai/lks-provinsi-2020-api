<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Rating;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $rating = Rating::where('user_id', $this->user_id)->where('book_id',$this->book_id)->first();

        return [
            'id' => $this->id,
            'user_name' => $this->user->first_name.' '.$this->user->last_name,
            'book_id' => $this->book_id,
            'review' => $this->review,
            'created_at' => "$this->created_at",
            'rating' => $rating->rating
        ];
    }
}

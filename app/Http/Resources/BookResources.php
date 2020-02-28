<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Rating;

class BookResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $authors = explode(';',$this->authors);

        
        $ratings = Rating::where('book_id', $this->id)->avg('rating');
        unset($authors[count($authors)-1]);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'authors' => $authors,
            'average_rating' => ($ratings==null?0:number_format($ratings,2,',',',')),
        ];
        // return parent::toArray($request);
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Rating;
use App\Review;

class BookResource extends JsonResource
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
        $five_star_count = Rating::where('book_id', $this->id)->where('rating',5)->count();
        $four_star_count = Rating::where('book_id', $this->id)->where('rating',4)->count();
        $three_star_count = Rating::where('book_id', $this->id)->where('rating',3)->count();
        $two_star_count = Rating::where('book_id', $this->id)->where('rating',2)->count();
        $one_star_count = Rating::where('book_id', $this->id)->where('rating',1)->count();
        $total_rating = Rating::where('book_id', $this->id)->count();
        $reviews = Review::where('book_id',$this->id)->orderBy('updated_at','desc')->get();
        $ratings = Rating::where('book_id', $this->id)->avg('rating');
        
        unset($authors[count($authors)-1]);
        return [
            'title' => $this->title,
            'pages' => $this->pages,
            'isbn' => $this->isbn,
            'authors' => $authors,
            'added_by' => $this->added_by,
            'rating' => [
                'total' => $total_rating,
                'average' => number_format($ratings,2,',',','),
                'five_star_count' => $five_star_count,
                'five_star_percentage' => number_format(($five_star_count/($total_rating==0?1:$total_rating)*100),2,',',','),
                'four_star_count' => $four_star_count,
                'four_star_percentage' => number_format(($four_star_count/($total_rating==0?1:$total_rating)*100),2,',',','),
                'three_star_count' => $three_star_count,
                'three_star_percentage' => number_format(($three_star_count/($total_rating==0?1:$total_rating)*100),2,',',','),
                'two_star_count' => $two_star_count,
                'two_star_percentage' => number_format(($two_star_count/($total_rating==0?1:$total_rating)*100),2,',',','),
                'one_star_count' => $one_star_count,
                'one_star_percentage' => number_format(($one_star_count/($total_rating==0?1:$total_rating)*100),2,',',','),
            ],
            'review' => [
                'total' => $this->reviews->count(),
                'data' => ReviewResource::collection($reviews)
            ],
            'created_at' => $this->created_at
        ];
        return parent::toArray($request);
    }
}

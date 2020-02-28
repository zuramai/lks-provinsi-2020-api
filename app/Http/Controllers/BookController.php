<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Validator;
use App\Http\Resources\BookResources;
use App\Http\Resources\BookResource;
use App\Rating;
use App\Review;


class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $last_id = $request->get('last_id');

        if(!$last_id) {
            $books = BookResources::collection(Book::orderBy('created_at','desc')->limit(20)->get());
        }else{
            $books = BookResources::collection(Book::orderBy('created_at','desc')->offset($last_id)->limit(20)->get());
        }

        return response()->json(['status' => true,'message'=>'Get All Books', 'data' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'pages' => 'required',
            'isbn' => 'required|digits:10',
            'authors' => 'required|array'
        ]);
        if($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid field', 'errors' => $validator->errors()], 422);
        }

        $checkIsbn = $this->checkISBN("$request->isbn");
        if(!$checkIsbn) {
            return response()->json(['status' => false, 'message' => 'invalid field', 'errors' => ['isbn' => ['Invalid ISBN Number']]], 422);
        }

        $authors = "";
        foreach($request->authors as $author) {
            $authors .= $author.';';
        }


        $book = new Book;
        $book->title = $request->title;
        $book->pages = $request->pages;
        $book->isbn = $request->isbn;
        $book->authors = $authors;
        $book->added_by = Auth::user()->id;
        $book->save();

        return response()->json(['status' => false, 'message' => 'create book success', 'data' => $book], 201);
    }

    public function store_rating(Request $request, $book_id) {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5'
        ]);

        if($validator->fails()) {
            return response()->json(['status' => true, 'message' => 'invalid field'],422);
        }

        $rating = new Rating;
        $rating->rating = $request->rating;
        $rating->user_id = Auth::user()->id;
        $rating->book_id = $book_id;
        $rating->save();

        return response()->json(['status' => true, 'message' => 'rating added'], 204);
    }

    public function store_review(Request $request, $book_id) {
        $validator = Validator::make($request->all(), [
            'review' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['status' => true, 'message' => 'invalid field'],422);
        }

        $rating = new Review;
        $rating->review = $request->review;
        $rating->user_id = Auth::user()->id;
        $rating->book_id = $book_id;
        $rating->save();

        return response()->json(['status' => true, 'message' => 'rating added'], 204);
    }

    public function checkISBN($isbn) {
        $totalNum = 0;
        for($i=0; $i< 10;$i++)  {
            $dikali = 10;
            if($i == 0) $dikali = 10;
            else if($i == 1) $dikali = 9;
            else if($i == 2) $dikali = 8;
            else if($i == 3) $dikali = 7;
            else if($i == 4) $dikali = 6;
            else if($i == 5) $dikali = 5;
            else if($i == 6) $dikali = 4;
            else if($i == 7) $dikali = 3;
            else if($i == 8) $dikali = 2;
            else if($i == 9) $dikali = 1;

            $total = $isbn[$i] * $dikali;
            $totalNum += $total;
        }

        if($totalNum % 11 == 0) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        $book = new BookResource($book);
        return response()->json(['status' => true, 'message' => 'Get book details', 'data' => $book]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        //
    }
}

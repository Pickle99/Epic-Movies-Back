<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Models\Genre;
use App\Models\GenreMovie;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MovieController extends Controller
{
	public function store(StoreMovieRequest $request): JsonResponse
	{
		$movie = new Movie;
		$movie->title = ['en' => $request->title_en, 'ka' => $request->title_ka];
		$movie->director = ['en' => $request->director_en, 'ka' => $request->director_ka];
		$movie->description = ['en' => $request->director_en, 'ka' => $request->director_ka];
		$movie->year = $request->year;
		$movie->budget = $request->budget;
		$movie->slug = Str::slug($request->title_en);
		$movie->user_id = auth()->user()->getAuthIdentifier();
		if ($request->hasFile('image'))
		{
			File::delete(public_path('images/') . $movie->image);
			$file = $request->file('image');
			$filename = $file->getClientOriginalName();
			$file->move('images/', $filename);
			$movie->image = $filename;
		}
		$movie->save();

		$genres = $request->genres;
		foreach ($genres as $genre)
		{
//			$genre = new Genre();
//			$genre->name = $genre;
			$genreMovie = new GenreMovie();
//			$genreMovie->genre_id = $genre->id();
//			$genreMovie->movie_id = $movie->id();
			Genre::create([
				'name'                => $genre->name,
				$genreMovie->genre_id => $genre->id(),
				$genreMovie->user_id  => $genre->id(),
			]);
		}
//		if (count($request->genres) === 1)
//		{
//			$genre->name = $request->genres;
//			$genreMovie = new GenreMovie();
//			$genreMovie->genre_id = $genre->id();
//			$genreMovie->movie_id = $movie->id();
//		}
		$genre->save();

		return response()->json('Movie created successfully');
	}
}

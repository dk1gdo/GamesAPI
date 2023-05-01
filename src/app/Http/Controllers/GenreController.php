<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;
use App\Models\Game;

class GenreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $id = null)
    {
        if (is_null($id)){
            return response(Genre::all());
        }
        $genre = Genre::find($id);
        if (is_null($genre)) {
            return response()->json([
                "message" => "There is no genre with this id"
            ]);
        }
        $games = $genre->games->pluck(['id'])->all();
        foreach($games as $game){
            $modelGame = Game::find($game);
            if (is_null($modelGame)) break;
            $result[] = (new GameController)->prepareGame($modelGame);
        }
        if(!isset($result)) return response()->json([
            "message" => "There are no games in this genre",

        ]);
        return response($result);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Importation de la classe Request
use App\Models\Question; // Importation du modèle Question

class QuestionController extends Controller
{
    public function index()
    {
        // Récupération de toutes les questions avec les informations de l'utilisateur
        $questions = Question::with('user')->get();
        // Retourne les questions
        return response()->json($questions);
    }

    // public function show($id)
    // {
    //     // Récupération d'une question par son id avec les informations de l'utilisateur et des réponses
    //     $question = Question::with('user', 'answers.user')->findOrFail($id);
    //     // Retourne la question
    //     return response()->json($question);
    // }

    public function show($id)
    {
        $question = Question::with('answers')->find($id);

        if (!$question) {
            return response()->json(['message' => 'Question not found'], 404);
        }

        return response()->json($question);
    }

    public function store(Request $request)
    {
        // Validation des données de la question
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        // Création d'une nouvelle question
        $question = new Question();
        $question->title = $request->title;
        $question->body = $request->body;
        $question->user_id = $request->user()->id;
        $question->save();

        // Retourne les informations de la question créée avec un statut 201
        return response()->json($question, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $question = Question::findOrFail($id);
        $question->title = $request->title;
        $question->body = $request->body;
        $question->save();

        return response()->json($question, 200);
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        // return response()->json(null, 204);
        return response()->json([
            'message' => 'Question deleted successfully',
            'code' => 200
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $questions = Question::where('title', 'LIKE', "%{$query}%")
                             ->orWhere('body', 'LIKE', "%{$query}%")
                             ->with('user')
                             ->get();
        return response()->json($questions);
    }
}



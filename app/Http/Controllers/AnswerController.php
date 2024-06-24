<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Answer;

class AnswerController extends Controller
{
    // Récupérer toutes les réponses
    public function index()
    {
        $answers = Answer::all();
        return response()->json($answers);
    }

    // Récupérer une réponse par son ID
    public function show($id)
    {
        $answer = Answer::findOrFail($id);
        return response()->json($answer);
    }

    // Ajouter une nouvelle réponse
    public function store(Request $request)
    {
        // Valider les données de la requête
        $request->validate([
            'body' => 'required|string',
            'question_id' => 'required|exists:questions,id'
        ]);

        // Créer une nouvelle réponse
        $answer = new Answer();
        $answer->body = $request->body;
        $answer->question_id = $request->question_id;
        $answer->user_id = $request->user()->id;
        $answer->save();

        // Retourner la réponse créée avec le statut 201
        return response()->json($answer, 201);
    }

    // Mettre à jour une réponse existante
    public function update(Request $request, $id)
    {
        // Valider les données de la requête
        $request->validate([
            'body' => 'required|string',
            'question_id' => 'required|exists:questions,id'
        ]);

        // Trouver la réponse à mettre à jour
        $answer = Answer::findOrFail($id);
        $answer->body = $request->body;
        $answer->question_id = $request->question_id;
        $answer->save();

        // Retourner la réponse mise à jour
        return response()->json($answer, 200);
    }

    // Supprimer une réponse
    public function destroy($id)
    {
        // Trouver la réponse à supprimer
        $answer = Answer::findOrFail($id);
        $answer->delete();

        // Retourner une réponse vide avec le statut 204
        return response()->json([
            'message' => 'Answer deleted successfully',
            'code' => 200
        ]);;
    }

    // Valider une réponse (opération personnalisée)
    public function validateAnswer($id, Request $request)
    {
        // Trouver la réponse à valider
        $answer = Answer::findOrFail($id);

        // Vérifier les autorisations de l'utilisateur
        if ($request->user()->is_supervisor) {
            $answer->is_validated = true;
            $answer->save();

            // Mettre à jour le statut de l'utilisateur si nécessaire
            $user = $answer->user;
            if ($user->answers()->where('is_validated', true)->count() > 10) {
                $user->is_supervisor = true;
                $user->save();
            }

            // Retourner la réponse validée
            return response()->json($answer);
        } else {
            // Retourner une erreur 403 si l'utilisateur n'est pas un superviseur
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }

        // Exemple de méthode dans un contrôleur pour récupérer les réponses d'une question
    public function getAnswers($question_id)
    {
        $answers = Answer::where('question_id', $question_id)->get();
        return response()->json($answers, 200);
    }

}

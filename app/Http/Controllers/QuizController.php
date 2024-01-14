<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quiz = Quiz::with('questions')->get();
        return view('quiz.index', compact('quiz'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attr = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $quiz = Quiz::create([
            'title' => $attr['title'],
            'description' => $attr['description'],
        ]);

        $user = auth()->user();
        $quiz->users()->attach($user);

        return redirect()->route('quiz.index')
            ->with('success','quiz created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $quiz = Quiz::find($id);
        return view('quiz.show', compact('quiz'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
//        $request->validate([
//            'title' => 'required|max:255',
//            'body' => 'required',
//        ]);
//        $quiz = Quiz::find($id);
//        $quiz->update($request->all());
//        return redirect()->route('quiz.index')
//            ->with('success', 'quiz updated successfully.');

        $attr = $request->validate([
            'question' => 'required',
            'answer_1' => 'required',
            'answer_2' => 'required',
            'answer_3' => 'required',
            'answer_4' => 'required',
        ]);

        $answerFields = [
            ['answer' => $attr['answer_1'], 'is_correct' => isset($_POST['check_answer_1'])],
            ['answer' => $attr['answer_2'], 'is_correct' => isset($_POST['check_answer_2'])],
            ['answer' => $attr['answer_3'], 'is_correct' => isset($_POST['check_answer_3'])],
            ['answer' => $attr['answer_4'], 'is_correct' => isset($_POST['check_answer_4'])]
        ];

        $quiz = Quiz::find($id);

        $question = $quiz->questions()->create(['question' => $attr['question']]);
        $question->answers()->createMany($answerFields);

        return redirect()->route('quiz.index')
            ->with('success','question added to quiz successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $quiz = Quiz::find($id);
        $quiz->delete();
        return redirect()->route('quiz.index')
            ->with('success', 'quiz deleted successfully');
    }

    public function create()
    {
        return view('quiz.create');
    }

    public function edit($id)
    {
        $quiz = quiz::find($id);
        return view('quiz.edit', compact('quiz'));
    }
}

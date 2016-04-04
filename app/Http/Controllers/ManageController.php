<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Comment;
use App\Events\SeriesUpdateEvent;
use App\Examination;
use App\Http\Requests\LessonsRequest;
use App\Http\Requests\SeriesRequest;
use App\Jobs\PublishesANewSeries;
use App\Question;
use App\Series;
use App\Skill;
use App\Tag;
use App\User;
use App\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Event;

class ManageController extends Controller
{
    /**
     * Overview index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('manage.index');
    }

    /**
     * Show series
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSeries()
    {
        $series = Series::latest()->paginate();
        return view('manage.series', compact('series'));
    }

    /**
     * Show lessons
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLessons()
    {
        $lessons = Video::latest()->paginate();
        return view('manage.lessons', compact('lessons'));
    }

    /**
     * Show users
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUsers()
    {
        $users = User::tutorsFirst()->paginate();
        return view('manage.users', compact('users'));
    }

    /**
     * Show comments
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showComments()
    {
        $comments = Comment::latest()->paginate();
        return view('manage.comments', compact('comments'));
    }

    /**
     * Show skills
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSkills()
    {
        $skills = Skill::all();
        return view('manage.skills', compact('skills'));
    }

    /**
     * Show the page for editing a skill
     *
     * @param Skill $skill
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEditSkill(Skill $skill)
    {
        return view('manage.skills.edit', compact('skill'));
    }

    /**
     * Update a skill
     *
     * @param Request $request
     * @param Skill $skill
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSkill(Request $request, Skill $skill)
    {
        return $skill->update($request->all()) ? redirect('manage/skills')->with('status', trans('messages.update_success')) : redirect()->back()->with('status', trans('messages.retry'));
    }

    /**
     * Show page for creating new series
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showCreateSeries()
    {
        return view('manage.series/create', ["series" => new Series()]);
    }

    /**
     * Show page for creating a new lesson(video)
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showCreateLesson()
    {
        return view('manage.lesson/create', ["lesson" => new Video()]);
    }

    /**
     * @param Series $series
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEditSeries(Series $series)
    {
        return view('manage.series.edit', compact('series'));
    }

    /**
     * @param Video $lesson
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEditLesson(Video $lesson)
    {
        return view('manage.lesson.edit', compact('lesson'));
    }

    /**
     * Create a new series
     *
     * @param SeriesRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function createSeries()
    {
        $this->dispatch(new PublishesANewSeries);

        return redirect('manage/series')->with('status', trans('messages.create_success'));
    }

    /**
     * Create a new lesson
     *
     * @param LessonsRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function createLesson(LessonsRequest $request)
    {
        $lesson = Video::create(array_add($request->all(), 'user_id', $request->user()->id));

        // Something went wrong
        if (!$lesson) {
            redirect()->back()->withInput($request->all());
        }

        // Create tags
        if ($request->has('tags')) {
            foreach ($request->input('tags') as $tagName) {
                $tag = Tag::where('name', $tagName)->first();
                // Does not exists
                if (is_null($tag))
                    $tag = Tag::create(["name" => $tagName]);

                $lesson->tags()->save($tag);
            }
        }
        $lesson->published_at = Carbon::now();
        $lesson->save();

        Event::fire(new SeriesUpdateEvent($lesson->series));

        return redirect('manage/lessons')->with('status', trans('messages.create_success'));
    }

    /**
     * Update a series
     *
     * @param SeriesRequest $request
     * @param Series $series
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function updateSeries(SeriesRequest $request, Series $series)
    {
        if (!$request->has('published'))
            $series->published = "off";
        if (!$request->has('completed'))
            $series->completed = "off";
        $series->save();

        $series->skills()->sync($request->input('skills'));

        $updated = $series->update($request->all());

        return $updated ? redirect()->back()->with('status', trans('messages.update_success')) : redirect()->back()->withInput($request->all());
    }

    /**
     * Update a lesson
     *
     * @param LessonsRequest $request
     * @param Video $lesson
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateLesson(LessonsRequest $request, Video $lesson)
    {
        if (!$lesson->update($request->all())) {
            return redirect()->back()->withInput($request->all());
        }

        $tag_ids = [];
        // Create tags
        foreach ($request->input('tags') as $tagName) {
            $tag = Tag::where('name', $tagName)->first();
            // Does not exists
            if (is_null($tag))
                $tag = Tag::create(["name" => $tagName]);
            array_push($tag_ids, $tag->id);
        }
        $lesson->tags()->sync($tag_ids);

        return redirect('manage/lessons')->with('status', trans('messages.update_success'));
    }

    /**
     * Promote a user to tutor role
     *
     * @param User $user
     * @return array
     */
    public function promoteUser(User $user)
    {
        if (!$user->isTutor()) {
            $user->role = "Tutor";
            $user->save();
        } else {
            $user->role = "Member";
            $user->save();
        }
        return [
            "status" => "success",
            "message" => trans('manage/users.promote-success')
        ];
    }

    /**
     * Deletes a series
     *
     * @param Series $series
     * @return array
     * @throws \Exception
     */
    public function deleteSeries(Series $series)
    {
        return $series->delete() ? [
            "status" => "success",
            "message" => trans('messages.delete_success')
        ] : [
            "status" => "error",
            "message" => trans('messages.delete_error')
        ];
    }

    /**
     * Deletes a lesson
     *
     * @param Video $lesson
     * @return array
     * @throws \Exception
     */
    public function deleteLesson(Video $lesson)
    {
        return $lesson->delete() ? [
            "status" => "success",
            "message" => trans('messages.delete_success')
        ] : [
            "status" => "error",
            "message" => trans('messages.delete_error')
        ];
    }

    /**
     * Deletes a comment
     *
     * @param Comment $comment
     * @return array
     * @throws \Exception
     */
    public function deleteComment(Comment $comment)
    {
        return $comment->delete() ? [
            "status" => "success",
            "message" => trans('messages.delete_success')
        ] : [
            "status" => "error",
            "message" => trans('messages.delete_error')
        ];
    }

    /**
     * Search by a given keyword
     *
     * @param $keyword
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchSeries($keyword)
    {
        $series = Series::search($keyword)->paginate();
        return view('manage.series', compact('keyword', 'series'));
    }

    /**
     * Search lessons by a given keyword
     *
     * @param $keyword
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchLessons($keyword)
    {
        $lessons = Video::search($keyword)->paginate();
        return view('manage.lessons', compact('keyword', 'lessons'));
    }

    /**
     * Search omments by a given keyword
     *
     *
     * @param $keyword
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchComments($keyword)
    {
        $comments = Comment::search($keyword)->paginate();
        return view('manage.comments', compact('keyword', 'comments'));
    }

    /**
     * Show all examinations
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showExaminations()
    {
        $examinations = Examination::all();
        return view('manage.examinations', compact('examinations'));
    }

    /**
     * Show create form for examination
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showCreateExamination()
    {
        return view('manage.examination.create');
    }

    /**
     * Create an examination
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function createExamination(Request $request)
    {
        $this->validate($request, [
            "title" => "required"
        ]);

        return Examination::create($request->all()) ? redirect('manage/examinations')->with('status', "创建成功") : back()->withInput($request->all());
    }

    /**
     * Show form for editing an examination
     *
     * @param Examination $examination
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEditExamination(Examination $examination)
    {
        return view('manage.examination.edit', compact('examination'));
    }

    /**
     * Updates an examination
     *
     * @param Request $request
     * @param Examination $examination
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function updateExamination(Request $request, Examination $examination)
    {
        $this->validate($request, [
            "title" => "required"
        ]);

        return $examination->update($request->all()) ? back()->with('status', '更新成功') : back()->withInput($request->all());
    }

    /**
     * Deletes an examination
     *
     * @param Examination $examination
     * @return array
     * @throws \Exception
     */
    public function deleteExamination(Examination $examination)
    {
        return $examination->delete() ? [
            'status' => "success",
            'message' => "删除成功"
        ] : [
            'status' => "error",
            'message' => "删除失败"
        ];
    }

    /**
     * Show all questions
     *
     * @param Examination $examination
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showExamQuestions(Examination $examination)
    {
        $questions = $examination->questions;

        return view('manage.examination.questions.all', compact('examination', 'questions'));
    }

    /**
     * Show form for creating a question
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showCreateQuestion()
    {
        return view('manage.examination.questions.create');
    }

    /**
     * Creates a question
     *
     * @param Request $request
     * @param Examination $examination
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createQuestion(Request $request, Examination $examination)
    {
        $this->validate($request, [
            "correct" => "required",
            "title" => "required"
        ]);

        $question = Question::create($request->only('title'));
        $question->examination()->associate($examination);
        $question->save();

        $i = 1;
        foreach ($request->input('answers') as $title) {
            $answer = new Answer;
            $answer->title = $title;
            $answer->question()->associate($question);
            if ($request->input('correct') == $i)
                $answer->correct = true;
            $answer->save();

            $i++;
        }

        return redirect('manage/examination/'.$examination->id.'/questions')->with('status', '添加成功');
    }

    /**
     * Show form for editing question
     *
     * @param Question $question
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEditQuestion(Question $question)
    {
        return view('manage.examination.questions.edit', compact('question'));
    }

    /**
     * Updates a question
     *
     * @param Request $request
     * @param Question $question
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateQuestion(Request $request, Question $question)
    {
        $this->validate($request, [
            "correct" => "required",
            "title" => "required"
        ]);

        $question->update($request->only('title'));

        $i = 1;
        foreach ($request->input('answers') as $title) {
            $answer = $question->answers[$i-1];
            $answer->title = $title;
            if ($request->input('correct') == $i)
                $answer->correct = true;
            else
                $answer->correct = false;
            $answer->save();
            $i++;
        }

        return back()->with('status', '更新成功');
    }

    /**
     * Deletes a question
     *
     * @param Question $question
     * @return array
     * @throws \Exception
     */
    public function deleteQuestion(Question $question)
    {
        return $question->delete() ? [
            'status' => "success",
            'message' => "删除成功"
        ] : [
            'status' => "error",
            'message' => "删除失败"
        ];
    }
}

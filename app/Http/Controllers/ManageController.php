<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\SeriesUpdateEvent;
use App\Http\Requests\LessonsRequest;
use App\Http\Requests\SeriesRequest;
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
    public function createSeries(SeriesRequest $request)
    {
        $series = Series::create($request->all());
        if (!$series)
            return redirect()->back()->withInput($request->all());
        $series->skills()->attach($request->input('skills'));

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
}

<?php

namespace App\Http\Controllers;

use App\Events\SeriesUpdateEvent;
use App\Http\Requests;
use App\Http\Requests\LessonsRequest;
use App\Series;
use App\Skill;
use App\Tag;
use App\User;
use App\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;

class HomeController extends Controller
{
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['only' => 'index']);
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show FAQ
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function faq()
    {
        return view('pages.faq');
    }

    /**
     * Show about page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Show contact page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Show testimonials page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function testimonials()
    {
        return view('pages.testimonials');
    }

    /**
     * Show apply page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function apply()
    {
        return view('pages.apply');
    }

    /**
     * Switch language by cookie
     *
     * @param $language
     * @return $this
     */
    public function switchLanguage($language)
    {
        return redirect()->back()->withCookie(cookie()->forever('lang', $language));
    }

    /**
     * User allows our cookie policy
     *
     * @return mixed
     */
    public function allowsCookie()
    {
        $response = Response::create('Allowed');
        return $response->withCookie(cookie()->forever('allows_cookie', 'yes'));
    }

    /**
     * Show for a skill page
     *
     * @param $skill
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSkill($skill)
    {
        $skill = Skill::skillByName($skill);
        return view('skills.index', compact('skill'));
    }

    /**
     * Show all tags
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showTags()
    {
        $tags = Tag::all();
        return view('tags.index', compact('tags'));
    }

    /**
     * Show the tag by its name
     *
     * @param $tag
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showTag($tag)
    {
        $tag = Tag::tagByName($tag);
        if (!$tag) {
            abort(404);
        }
        return view('tags.details', compact('tag'));
    }

    /**
     * Search everything and return the html
     *
     * @param $keyword
     * @return array
     */
    public function searchEverything($keyword)
    {
        $keyword = trim($keyword);

        $allSeries = Series::search($keyword)->take(8)->get();
        $lessons = Video::search($keyword)->take(15)->get();
        $users = User::search($keyword)->take(10)->get();
        $tags = Tag::search($keyword)->take(10)->get();

        return [
            "html" => view('search.ajax', compact('allSeries', 'lessons', 'users', 'tags'))->render()
        ];
    }

    /**
     * Show lessons
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLessons(Request $request)
    {
        $lessons = Video::tutor($request->user()->id)->latest()->paginate();
        return view('series.lessons.publish', compact('lessons'));
    }

    /**
     * Show page for creating a new lesson(video)
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showCreateLesson()
    {
        return view('series.lessons.create', ["lesson" => new Video()]);
    }

    /**
     * @param Video $lesson
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEditLesson(Video $lesson)
    {
        return view('series.lessons.edit', compact('lesson'));
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

        return redirect('publish/lessons')->with('status', trans('messages.create_success'));
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

        return redirect('publish/lessons')->with('status', trans('messages.update_success'));
    }
}

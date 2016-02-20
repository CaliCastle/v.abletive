<?php

namespace App\Http\Controllers;

use App\Series;
use App\Video;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SeriesController extends Controller
{

    protected $perPage = 35;

    /**
     * Show series details
     *
     * @param $name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($name)
    {
        $series = Series::seriesBySlug($name);
        return view('series.index', compact('series'));
    }

    /**
     * Show all series
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSeries()
    {
        $allSeries = Series::newest()->get();
        return view('series.all', compact('allSeries'));
    }

    /**
     * Show an episode lesson
     *
     * @param $series
     * @param $episode
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLesson($series, $episode)
    {
        $series = Series::seriesBySlug($series);
        $lesson = $series->getEpisode($episode);
        $comments = $lesson->superComments()->paginate($this->perPage);

        return view('series.lessons.index', compact('lesson', 'episode', 'series', 'comments'));
    }

    /**
     * Show all lessons
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLessons()
    {
        $lessons = Video::latest()->paginate();
        return view('series.lessons.all', compact('lessons'));
    }

    /**
     * Fetch more comments of the lesson
     *
     * @param Video $lesson
     * @param $page
     * @return array
     */
    public function fetchMoreComments(Video $lesson, $page)
    {
        $comments = $lesson->superComments()->skip($page * $this->perPage)->take($this->perPage)->get();

        return [
            "html" => view('discussion.comments', ["comments" => $comments])->render()
        ];
    }

    /**
     * Sort in difficulties
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sortLessonsInDifficulty(Request $request)
    {
        $which = $request->query('s');

        switch ($which) {
            case "beginner":
                $lessons = Video::whereHas('series', function ($query) {
                    $query->where('difficulty', "beginner");
                })->paginate();
                break;
            case "intermediate":
                $lessons = Video::whereHas('series', function ($query) {
                    $query->where('difficulty', "intermediate");
                })->paginate();
                break;
            default:
                $lessons = Video::whereHas('series', function ($query) {
                    $query->where('difficulty', "advanced");
                })->paginate();
                break;
        }

        return view('series.lessons.all', compact('which', 'lessons'));
    }

    /**
     * Sort in type
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sortLessonsInType(Request $request)
    {
        $which = $request->query('s');

        switch ($which) {
            case "oldest":
                // Oldest
                $lessons = Video::oldest()->paginate();
                break;
            default:
                // Hottest
                $lessons = Video::hottest();
                break;
        }

        return view('series.lessons.all', compact('which', 'lessons'));
    }
}

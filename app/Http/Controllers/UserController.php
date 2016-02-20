<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Series;
use App\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Add a lesson to my watch laters
     *
     * @param Video $lesson
     * @return array
     */
    public function addLessonToWatchLater(Video $lesson)
    {
        return ["message" => auth()->user()->watchLaterLesson($lesson)];
    }

    /**
     * Add a series to my watch laters
     *
     * @param Series $series
     * @return array
     */
    public function addSeriesToWatchLater(Series $series)
    {
        auth()->user()->watchLaterSeries($series);
        return ["message" => trans('messages.watch_later_success')];
    }

    /**
     * Remove a series from my watch laters
     *
     * @param Series $series
     * @return array
     */
    public function removeSeriesFromWatchLater(Series $series)
    {
        auth()->user()->unwatchLaterSeries($series);
        return ["message" => trans("messages.un_watch_later_success")];
    }

    /**
     * Add a lesson to my favorites
     *
     * @param Video $lesson
     * @return array
     */
    public function addLessonToFavorite(Video $lesson)
    {
        return ["message" => auth()->user()->favoriteLesson($lesson)];
    }

    /**
     * Add a series to my favorites
     *
     * @param Series $series
     * @return array
     */
    public function addSeriesToFavorite(Series $series)
    {
        auth()->user()->favoriteSeries($series);
        return ["message" => trans('messages.favorite_success')];
    }

    /**
     * Remove a series from my favorites
     *
     * @param Series $series
     * @return array
     */
    public function removeSeriesFromFavorite(Series $series)
    {
        auth()->user()->unfavoriteSeries($series);
        return ["message" => trans("messages.un_favorite_success")];
    }

    /**
     * Notify when a series updates
     *
     * @param Series $series
     * @return array
     */
    public function seriesNotify(Series $series)
    {
        return ["message" => auth()->user()->notifySeries($series)];
    }

    /**
     * User has watched a lesson
     *
     * @param Video $lesson
     * @return array
     */
    public function watchedLesson(Video $lesson)
    {
        $level_up = auth()->user()->watched($lesson);
        return [
            "message" => trans('messages.watched', ["xp" => $lesson->experience]),
            "xp" => auth()->user()->experience,
            "level_up" => $level_up ? trans('messages.level_up', ["level" => auth()->user()->level()]) : "no"
        ];
    }

    /**
     * Submit a comment
     *
     * @param Request $request
     * @param Video $lesson
     * @return array
     */
    public function submitLessonComment(Request $request, Video $lesson)
    {
        if ($request->input('parent_id')) {
            $parent = Comment::find($request->parent_id);
        }
        $comment = new Comment([
            "video_id" => $lesson->id,
            "user_id" => $request->user()->id,
            "parent_id" => $request->input('parent_id') ? $request->input('parent_id') : 0,
            "message" => $request->input('parent_id') ? $parent->replyLink() . $request->input('content') : $request->input('content'),
            "user_agent" => $request->header('user-agent')
        ]);

        return $lesson->comments()->save($comment) ? [
            "status" => "success",
            "message" => trans('messages.comment_success'),
            "html" => view('discussion.comment-list', ["comment" => $comment])->render()] : [
            "status" => "error",
            "message" => trans('messages.retry')
        ];
    }

    /**
     * Show history timeline
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showHistory()
    {
        $time_list = collect(DB::table('user_watched')->orderBy('created_at', 'desc')->where('user_id', auth()->user()->id)->lists('created_at','video_id'));
        $time_list = $time_list->map(function($item, $key) {
            return Carbon::parse($item);
        });

        $lessons = collect($time_list->keys())->map(function ($id) {
            return Video::find($id);
        });

        return view('profile.history', compact('lessons', 'time_list'));
    }

    /**
     * Show watch laters
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showWatchLaters()
    {
        $lessons = auth()->user()->watchLaters;
        return view('profile.laters', compact('lessons'));
    }

    /**
     * Show favorites
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showFavorites()
    {
        $lessons = auth()->user()->favoriteLessons;
        return view('profile.favorites', compact('lessons'));
    }

    /**
     * Cancel notification
     *
     * @param Series $series
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelNotification(Series $series)
    {
        return redirect()->back()->with('status', auth()->user()->notifySeries($series));
    }

    /**
     * Like a comment
     *
     * @param Request $request
     * @param Comment $comment
     * @return array
     */
    public function likeComment(Request $request, Comment $comment)
    {
        $liked = $request->user()->likedComment($comment);
        if (!$liked)
            $comment->likes()->attach($request->user()->id);
        return $liked ? [
            "status" => "error",
            "message" => trans('messages.retry')
        ] : [
            "status" => "success",
        ];
    }

    /**
     * Return the rendered html of a like list
     *
     * @param Comment $comment
     * @return array
     */
    public function fetchLikeList(Comment $comment)
    {
        return [
            "html" => view('discussion.likes', ['users' => $comment->likes()->take(8)->get()])->render()
        ];
    }

    /**
     * Upload an image
     *
     * @param Request $request
     * @return array
     */
    public function uploadImage(Request $request)
    {
        $file = $request->file('image');

        $name = time() . $file->getClientOriginalName();

        $file->move('uploads/images/' . $request->user()->user_id, $name);

        return [
            'status' => 'ok',
            'html' => "<img src=\"" . url('/uploads/images/' . $request->user()->user_id) . "/{$name}\" alt=\"comment_image\" />"
        ];
    }
}

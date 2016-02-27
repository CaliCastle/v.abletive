<?php

namespace App\Http\Controllers;

use App\Series;
use App\Skill;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class APIController extends Controller
{
    public $baseURL;

    /**
     * APIController constructor.
     */
    public function __construct()
    {
        $this->baseURL = url('tvOS/') . '/';
    }

    public function showCatalogs()
    {
        $series = Series::all();
        $skills = Skill::all();
        return [
            "series" => [
                "count" => $series->count(),
                "list" => $series->toArray()
            ],
            "skills" => [
                "count" => $skills->count(),
                "list" => $skills->toArray()
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function showIndexTVML()
    {
        $series = Series::all();
        $skills = Skill::all();
        $content = 'var Template = function () { return `<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<document>
  <head>
    <style>
      .darkBackgroundColor {
        background-color: #573e81;
      }
    </style>
  </head>
  <stackTemplate theme=\"dark\" class=\"darkBackgroundColor\">
    <identityBanner>
      <background>
        <img src=\"'.$this->baseURL.'resources/images/dark-banner.jpg\" width=\"1920\" height=\"350\" aspectFill="1" />
      </background>
      <title>Abletive视频教学站</title>
      <subtitle>只学有用的</subtitle>
    </identityBanner>
    <collectionList>
      <shelf>
        <header>
          <title>系列课程</title>
        </header>
        <section>';
        foreach ($series as $s) {
            $content .= "<lockup>
            <img src=\"{$s->thumbnail}\" width=\"320\" height=\"320\" />
            <title>" . htmlspecialchars($s->title) . "</title>
          </lockup>";
        }
        $content .= '</section>
      </shelf>
      <shelf>
        <header>
          <title>技能方向</title>
        </header>
        <section>';
        foreach ($skills as $skill) {
            $content .= "<lockup>
            <img src=\"{$skill->thumbnail}\" width=\"450\" height=\"338\" />
            <title>" . trans('skills.' . $skill->name, [], '', 'zh') . "</title>
          </lockup>";
        }
        $content .= '</section>
      </shelf>
    </collectionList>
  </stackTemplate>
</document>`;}';
        return response($content)->header('Content-Type', "application/x-javascript");
    }
}

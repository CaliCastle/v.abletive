<?php

namespace App\Http\Controllers;

use App\Series;
use App\Skill;
use App\User;
use App\Video;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class APIController extends Controller
{
    public $baseURL;
    public $templateURL;

    /**
     * APIController constructor.
     */
    public function __construct()
    {
        $this->baseURL = url('tvOS/') . '/';
        $this->templateURL = $this->baseURL . "templates/";
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
     * Show index TVML template
     *
     * @return mixed
     */
    public function showIndexTVML()
    {
        $series = Series::latest()->get();
        $skills = Skill::all();
        $tutors = User::tutors()->take(8)->get();
        $content = 'var Template = function () { return `<?xml version="1.0" encoding="UTF-8" ?>
<document>
  <head>
    <style>
      .darkBackgroundColor {
        background-color: #573e81;
      }
      .monogram {
        background-color: transparent;
        margin: 10;
      }
      .avatar {
        tv-img-treatment: circle;
      }
      .cornered {
        tv-img-treatment: corner-medium;
      }
      .showTextOnHighlight {
        tv-text-highlight-style: show-on-highlight;
      }
    </style>
  </head>
  <stackTemplate theme="dark" class="darkBackgroundColor">
    <identityBanner>
      <background>
        <img src="'.$this->baseURL.'resources/images/dark-banner.jpg" width="1920" height="280" />
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
            $content .= "<lockup template=\"" . $this->templateURL . "Series.{$s->id}.xml\" presentation=\"pushDocument\">
            <img class=\"cornered\" src=\"{$s->thumbnail}\" width=\"320\" height=\"320\" />
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
            $content .= "<lockup template=\"" . $this->templateURL . "Skill.{$skill->id}.xml\" presentation=\"pushDocument\">
            <img class=\"cornered\" src=\"{$skill->thumbnail}\" width=\"450\" height=\"338\" />
            <title class=\"showTextOnHighlight\">" . trans('skills.' . $skill->name, [], '', 'zh') . "</title>
          </lockup>";
        }
        $content .= '</section>
      </shelf>
      <shelf>
        <header>
          <title>精选讲师</title>
        </header>
        <section>';
        foreach ($tutors as $tutor) {
            $content .= "<monogramLockup class='monogram' template=\"" . $this->templateURL . "Tutor.{$tutor->id}.xml\" presentation=\"pushDocument\">
                <img class='avatar' src='{$tutor->avatar}' width='235' height='235' />
                <title>".htmlspecialchars($tutor->display_name)."</title>
            </monogramLockup>";
        }
        $content .= '</section>
        </shelf>
    </collectionList>
  </stackTemplate>
</document>`;}';
        return response($content)->header('Content-Type', "application/x-javascript");
    }

    /**
     * Show series TVML template
     *
     * @param Series $series
     * @return mixed
     */
    public function showSeriesTVML(Series $series)
    {
        $content = 'var Template = function () { return `<?xml version="1.0" encoding="UTF-8" ?>
<document>
<head>
    <style>
      .desc {
        padding: 15;
        background-color: transparent;
        color: rgba(255,255,255,0.65);
      }
    </style>
  </head>
<compilationTemplate theme="dark">
   <list>
      <relatedContent>
         <itemBanner>
            <heroImg src="'.$series->thumbnail.'" width="400" height="400" />
            <row>
                <buttonLockup>
                    <badge src="resource://button-add" />
                    <title>稍后观看</title>
                </buttonLockup>
                <buttonLockup>
                    <badge src="resource://button-rate" />
                    <title>添加最爱</title>
                </buttonLockup>
            </row>
         </itemBanner>
      </relatedContent>
      <header>
         <title>'.htmlspecialchars($series->title).'</title>
         <subtitle>难度: '.trans('lessons.difficulty.' . strtolower($series->difficulty), [], '', 'zh').'</subtitle>
         <row>
            <text>'.$series->lessons()->count().'节课 </text>
            <text>'.$series->totalMinutes().'分钟</text>
         </row>
      </header>
      <section>
        <description class="desc">'.htmlspecialchars($series->description).'</description>
      </section>
      <section>
      ';
        foreach ($series->lessons as $lesson) {
            $content .= "<listItemLockup videoURL='{$lesson->source}'>
                <ordinal>{$lesson->episode()}</ordinal>
                <title>".htmlspecialchars($lesson->title)."</title>
                <decorationLabel>{$lesson->duration}</decorationLabel>
         </listItemLockup>";
        }
         $content .= '
      </section>
   </list>
</compilationTemplate>
</document>`;}';
        return response($content)->header('Content-Type', "application/x-javascript");
    }

    /**
     * Show skill TVML template
     *
     * @param Skill $skill
     * @return mixed
     */
    public function showSkillTVML(Skill $skill)
    {
        $firstLesson = $skill->firstLesson();
        $content = 'var Template = function () { return `<?xml version="1.0" encoding="UTF-8" ?>
<document>
    <head>
    <style>
        .series-shelf {
            padding 15;
            background-color: rgba(10,10,10,0.85);
        }
        .cornered {
            tv-img-treatment: corner-medium;
        }
    </style>
    </head>
    <productBundleTemplate theme="dark">
        <banner>
            <stack>
                <title>&lt;'.trans('skills.'.$skill->name, [], '', 'zh').'&gt;技能</title>
                <row>
                    <text>'.$skill->series()->count().'个系列课程</text>
                    <text>'.$skill->lessonsCount().'个教程视频</text>
                </row>
                <description allowsZooming="true" moreLabel="more">'.htmlspecialchars($skill->description).'</description>
                ';
            if ($firstLesson != false) {
                $content .= '<row>
                   <buttonLockup videoURL="'.$firstLesson->source.'">
                      <badge src="resource://button-preview" />
                      <title>预览第一集</title>
                   </buttonLockup>
                </row>';
            }
            $content .= '
            </stack>
            <heroImg src="'.$skill->thumbnail.'" width="450" height="338" />
        </banner>
        <shelf class="series-shelf">
            <header>
                <title>'.$skill->series()->count().'个系列课程</title>
            </header>
            <section>
                ';
            foreach ($skill->series as $series) {
                $content .= '<lockup template="' . $this->templateURL . 'Series.'.$series->id.'.xml" presentation="pushDocument">
                    <img class="cornered" src="'.$series->thumbnail.'" width="290" height="290" />
                    <title>'.htmlspecialchars($series->title).'</title>
                </lockup>';
            }
            $content .= '
            </section>
            </shelf>
    </productBundleTemplate>
</document>`;}';
        return response($content)->header('Content-Type', "application/x-javascript");
    }

    /**
     * Show tutor TVML template
     *
     * @param User $tutor
     * @return mixed
     */
    public function showTutorTVML(User $tutor)
    {
        $content = 'var Template = function() { return `<?xml version="1.0" encoding="UTF-8" ?>
<document>
  <head>
    <style>
        .lesson {
            margin: 20 0;
            padding: 15 10;
            itml-item-height: 90;
        }
        .series-title {
            font-size: 20pt;
            color: rgba(220,250,255, 0.6);
            font-weight: medium;
            margin: 0 20;
            tv-highlight-color: rgba(0,0,0,0.55);
        }
    </style>
  </head>
  <listTemplate theme="dark">
    <list>
      <relatedContent>
        <itemBanner>
          <heroImg src="'.$tutor->avatar.'" />
        </itemBanner>
      </relatedContent>
      <header>
        <title>'.htmlspecialchars($tutor->display_name).'讲师的课程列表</title>
      </header>
      <section>
        <header>
          <title>共'.$tutor->lessons()->count().'个教学视频</title>
        </header>
        ';
        if ($tutor->profileLessons()->count()) {
            foreach ($tutor->profileLessons() as $lesson) {
                $content .= '<listItemLockup class="lesson" videoURL="'.$lesson->source.'">
          <title>'.htmlspecialchars($lesson->title).'</title>
          <text class="series-title">'.htmlspecialchars($lesson->series->title).'</text>
          <decorationLabel>'.$lesson->duration.'</decorationLabel>
        </listItemLockup>';
            }
        } else {
            $content .= '<listItemLockup class="lesson">
                <title>暂无任何课程</title>
            </listItemLockup>';
        }

        $content .= '
      </section>
    </list>
  </listTemplate>
</document>`;}';
        return response($content)->header('Content-Type', "application/x-javascript");
    }

    /**
     * Search content for TVML
     *
     * @param $keyword
     * @return array
     */
    public function searchContent($keyword)
    {
        $keyword = trim($keyword);

        $allSeries = Series::search($keyword)->take(8)->get();
        $lessons = Video::search($keyword)->take(15)->get();

        $series_list = collect([]);
        foreach ($allSeries as $series) {
            $series_list->push(["id" => $series->id, "title" => htmlspecialchars($series->title), "thumbnail" => $series->thumbnail]);
        }

        $lesson_list = collect([]);
        foreach ($lessons as $lesson) {
            $lesson_list->push(["source" => $lesson->source, "title" => htmlspecialchars($lesson->title), "thumbnail" => $lesson->series->thumbnail, "series_title" => htmlspecialchars($lesson->series->title)]);
        }

        return [
            'status' => 'success',
            'series' => [
                "count" => $allSeries->count(),
                "list" => $series_list
            ],
            'lessons' => [
                "count" => $lessons->count(),
                "list" => $lesson_list
            ]
        ];
    }
}

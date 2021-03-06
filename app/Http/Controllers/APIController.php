<?php

namespace App\Http\Controllers;

use App\Http\Requests\APIRequest;
use App\Series;
use App\Skill;
use App\User;
use App\Video;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

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
        app()->setLocale('zh');
    }

    /**
     * Show catalogs
     *
     * @return array
     */
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
     * @param APIRequest $request
     * @return array
     */
    public function showIndex(APIRequest $request)
    {
        $series = Series::published()->featured();
        $tutors = User::tutors()->take(8)->get();

//        $tutor_list = collect([]);
        $series_list = collect([]);
        $testimonials = collect([]);

        for($i = 1; $i <= 10; $i++) {
            $testimonials->push([
                "avatar" => url('assets/images/testimonials') . '/' . trans("testimonials.{$i}.avatar"),
                "name" => trans("testimonials.{$i}.name"),
                "caption" => trans("testimonials.{$i}.caption"),
                "message" => trans("testimonials.{$i}.message")]);
        }
//
//        foreach ($tutors as $tutor) {
//            $array = $tutor->toArray();
//            $array = array_add($array, "profile_slug", $tutor->profileLink())
//        }

        foreach ($series as $s) {
            $array = $s->toArray();
            $array = array_add($array, "episodes", $s->lessons()->count());
            $array = array_add($array, "recently_published", $s->recentlyPublished() == true ? 1 : 0);
            $array = array_add($array, "recently_updated", $s->recentlyUpdated() == true ? 1 : 0);
            $series_list->push($array);
        }

        return [
            "series" => [
                "count" => $series->count(),
                "list" => $series_list
            ],
            "tutors" => [
                "count" => $tutors->count(),
                "list" => $tutors
            ],
            "testimonials" => $testimonials
        ];
    }

    /**
     * @param Series $series
     * @return array
     */
    public function showSeries(Series $series)
    {
        $episodes = $series->lessons;

        return [
            "episodes" => $episodes
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
      .status {
        background-color: rgba(0,0,0,0.65);
        padding: 10;
        margin: 10;
        width: 45pt;
        color: #eee;
        tv-position: bottom-right;
        font-size: 18pt;
        highlight-color: #fff;
        text-align: right;
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
            <overlay class='status'>";
            if ($s->recentlyUpdated()) {
                $content .= "<title style='tv-text-style: footnote;'>新</title>";
            } else if ($s->recentlyUpdated()) {
                $content .= "<title style='tv-text-style: footnote;'>新</title>";
            }
            $content .= "</overlay><subtitle class='showTextOnHighlight'>共".$s->lessons()->count()."集</subtitle>
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
        tv-text-max-lines: 4;
      }
    </style>
  </head>
<compilationTemplate theme="dark">
   <list>
      <relatedContent>
         <itemBanner>
            <heroImg src="'.$series->thumbnail.'" width="400" height="400" />
            <row>
                <buttonLockup series-id="'.$series->id.'" later="true">
                    <badge src="resource://button-add" />
                    <title>稍后观看</title>
                </buttonLockup>
                <buttonLockup series-id="'.$series->id.'" favorite="true">
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
            <text>'.$series->created_at->format('Y-m-d').'</text>
            <text>系列'. ($series->completed ? "已" : "未") .'完结</text>
         </row>
      </header>
      <section>
        <description class="desc">'.htmlspecialchars(str_replace('<br>', ' ', $series->description)).'</description>
      </section>
      <section>
      ';
        foreach ($series->lessons as $lesson) {
            $content .= "<listItemLockup videoURL='{$lesson->source}' title='".htmlspecialchars($lesson->title)."' description='".htmlspecialchars(str_replace('<br>', ' ', $lesson->description))."' cover='".$lesson->series->thumbnail."'>
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
                    <relatedContent>
                        <infoTable>
                            <header>
                                <title>系列教程: '.htmlspecialchars($series->title).'</title>
                            </header>
                            <info>
                                <description style="text-align: center;">
                                '.htmlspecialchars(str_replace('<br>', ' ', $series->description)).
                                '</description>
                            </info>
                        </infoTable>
                    </relatedContent>
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
            font-size: 23pt;
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
                $content .= '<listItemLockup class="lesson" videoURL="'.$lesson->source.'" title="'.htmlspecialchars($lesson->title).'" description="'.htmlspecialchars(str_replace('<br>', ' ', $lesson->description)).'" cover="'.$lesson->series->thumbnail.'">
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
            $lesson_list->push([
                "source" => $lesson->source,
                "title" => htmlspecialchars($lesson->title),
                "thumbnail" => $lesson->series->thumbnail,
                "series_title" => htmlspecialchars($lesson->series->title),
                "description" => htmlspecialchars($lesson->description)
            ]);
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

    /**
     * @return mixed
     */
    public function showLoginTVML()
    {
        $token = str_random(15);
        Cache::put($token, $token, Carbon::now()->addMinutes(10));

        $content = 'var Template = function() { return `<?xml version="1.0" encoding="UTF-8" ?>
<document>
    <formTemplate>
        <banner>
            <img src="http://qr.liantu.com/api.php?text=abletive://tvOS_auth_'.$token.'&bg=EEEEEE&fg=111111&w=800&el=5" width="800" height="800"/>
            <description>打开Abletive iOS客户端扫描二维码</description>
        </banner>
        <footer>
            <text>版本1.1以上登录后扫描二维码即可, 二维码将在10分钟后无效</text>
        </footer>
     </formTemplate>
</document>`;}';

        return response($content)->header('Content-Type', "application/x-javascript");
    }

    /**
     * @param Request $request
     * @return array
     */
    public function showMySeriesTVML(Request $request)
    {
        $later_ids = $request->input('later_ids');
        $favorite_ids = $request->input('favorite_ids');

        $later_count = 0;
        if ($later_ids && $later_ids != "") {
            $ids = explode("-", $later_ids);
            $later_list = collect([]);
            foreach ($ids as $id) {
                if ($id == "")
                    continue;
                $series = Series::find($id);
                $later_list->push(["id" => $series->id, "thumbnail" => $series->thumbnail, "title" => htmlspecialchars($series->title)]);
                $later_count++;
            }
        }

        $favorite_count = 0;
        if ($favorite_ids && $favorite_ids != "") {
            $ids = explode("-", $favorite_ids);
            $favorite_list = collect([]);
            foreach ($ids as $id) {
                if ($id == "")
                    continue;
                $series = Series::find($id);
                $favorite_list->push(["id" => $series->id, "thumbnail" => $series->thumbnail, "title" => htmlspecialchars($series->title)]);
                $favorite_count++;
            }
        }

        /** @var Collection $later_list */
        /** @var Collection $favorite_list */
        return [
            "status" => "success",
            "later_count" => $later_count,
            "favorite_count" => $favorite_count,
            "laters" => [
                "list" => isset($later_list) ? $later_list : ""
            ],
            "favorites" => [
                "list" => isset($favorite_list) ? $favorite_list : ""
            ]
        ];
    }
}

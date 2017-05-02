<?php

namespace App\Facade;

use App\Models\Article;
use App\Models\Journal;
use App\Models\Organize;
use Carbon\Carbon;
use Html;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

class VciHelper extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'VciHelper';
    }

    /**
     * Hỗ trợ debug qua get param ?debug=true
     * @param \Illuminate\Http\Request $request
     * @param array $context
     */
    public static function debug(Request $request, $context)
    {
        if (config('app.debug') && $request->has('debug')) {
            $debug = $request->get('debug');
            if (in_array($debug, ['true', 1])) {
                dd($context);
            } else {
                $keys = explode('.', $debug);
                $target = $context;
                $error = false;
                foreach ($keys as $key) {
                    if (isset($target[$key])) {
                        $target = $target[$key];
                    } else {
                        $error = true;
                        break;
                    }
                }
                if (!$error) {
                    dd($target);
                }
            }
        }
    }

    /**
     * Kiểm tra route hiện tại có tên nằm trong danh sách ko
     * @param array $names
     * @return bool
     */
    public static function currentRouteNameIn($names)
    {
        return in_array(\Route::currentRouteName(), $names);
    }

    /**
     * @param string $str
     * @return string
     */
    public static function bibtextNormalize($str)
    {
        $rules = [
            ['{\`a}', 'à'],
            ["{\'a}", 'á'],
            ['{\~a}', 'ã'],
            ['{\u{a}}', 'ă'],
            ['{\^a}', 'â'],

            ['{\`A}', 'À'],
            ["{\'A}", 'Á'],
            ['{\~A}', 'Ã'],
            ['{\u{A}}', 'Ă'],
            ['{\^A}', 'Â'],

            ['{\`e}', 'è'],
            ["{\'e}", 'é'],
            ['{\~e}', 'ẽ'],
            ['{\^e}', 'ê'],

            ['{\`E}', 'È'],
            ["{\'E}", 'É'],
            ['{\~E}', 'Ẽ'],
            ['{\^E}', 'Ê'],

            ['{\`o}', 'ò'],
            ["{\'o}", 'ó'],
            ['{\~o}', 'õ'],
            ['{\^o}', 'ô'],

            ['{\`O}', 'Ò'],
            ["{\'O}", 'Ó'],
            ['{\~O}', 'Õ'],
            ['{\^O}', 'Ô'],

            ['{\`u}', 'ù'],
            ["{\'u}", 'ú'],
            ['{\~u}', 'ũ'],
            ['{\"u}', 'ü'],


            ['{\`U}', 'Ù'],
            ["{\'U}", 'Ú'],
            ['{\~U}', 'Ũ'],


            ['{\`y}', 'ỳ'],
            ["{\\'y", 'ý'],

            ['{\`Y}', 'Ỳ'],
            ["{\\'Y}", 'Ý'],

            ['{\~\i}', 'ĩ'],
            ['{\`\i}', 'ì'],

            ["{\\'\\i}", 'í'],
            ["{\\'i}", 'í'],
            ['{\`i}', 'ì'],

            ["{\\'\\I}", 'Í'],
            ["{\\'I}", 'Í'],
            ['{\~\I}', 'Ĩ'],
            ['{\`\I}', 'Ì'],
            ['{\`I}', 'Ì'],

            ['{\DJ}', 'Đ'],
            ['{\dj}', 'đ'],


        ];
        if ($str != null && $str != '') {
            foreach ($rules as $rule) {
                $str = str_replace($rule[0], $rule[1], $str);
            }
        } else {
            return '';
        }

        return $str;
    }

    /**
     * Format 1 số về số không âm
     * @param int|string $number
     * @return int|string nếu null hoặc số âm trả về string rỗng, còn lại trả về $number
     */
    public static function formatNumber($number)
    {
        if ($number == null) {
            return '';
        }

        try {
            $number = (int)$number;
            return $number >= 0 ? $number : '';
        } catch (\Exception $e) {
            return $number;
        }
    }

    /**
     * Format 1 string dạng datetime thành format khác
     * @param string $string
     * @param string $from
     * @param string $to
     * @return string
     */
    public static function formatDateTime($string, $from = 'm-d-Y H:i:s', $to = 'H:i:s d/m/Y')
    {
        return Carbon::createFromFormat($from, $string)->format($to);
    }

    /**
     * @param array|Collection $authors
     * @return string
     */
    public static function mapAuthorsToNames($authors)
    {
        if (!($authors instanceof Collection)) {
            $authors = collect($authors);
        }

        return $authors->map(function ($author) {
            return $author->name;
        })->map(function ($name) {
            return collect(explode(', ', $name))->reverse()->implode(' ');
        })->implode(', ');
    }

    /**
     * @param string $names
     * @return Collection
     */
    public static function mapNamesToAuthors($names)
    {
        return collect(explode(', ', $names))->map(function ($name) {
            return (object)compact('name');
        });
    }

    public static function removeWhiteSpace($text)
    {

        if ($text != null && $text != "") {
            $text = preg_replace('/[\t\n\r\0\x0B]/', '', $text);
            $text = preg_replace('/([\s])\1+/', ' ', $text);
            $text = trim($text);
            return $text;
        }
        return null;
    }

    /**
     * @param \App\Models\Journal $journal
     * @param $number
     * @param $volume
     * @param $year
     * @param bool $onlyText
     * @return string
     */
    public static function journalWithInfo($journal, $number, $volume, $year, $onlyText = false)
    {
        $parts = [];
        if ($journal) {
            $parts[] = $onlyText ? $journal->name : \Html::link(route('journal.articles', $journal->id), $journal->name)->toHtml();
            $parts[] = \VciHelper::journalInfo($journal, $number, $volume, $year, $onlyText);
        }
        return implode(' ', $parts);
    }

    /**
     * @param \App\Models\Journal $journal
     * @param $number
     * @param $volume
     * @param $year
     * @param bool $onlyText
     * @return string
     */
    public static function journalInfo($journal, $number, $volume, $year, $onlyText = false)
    {
        $hasYear = $year != null;
        $hasNo = $number != null && $hasYear;
        $hasVol = $volume != null && $hasNo;
        $parts = [];
        if ($journal) {
            $id = $journal->id;
            if ($hasVol || $hasNo || $hasYear) {
                $parts[] = '-';
                if ($hasVol || $hasNo) {
                    $vol_no = [];
                    if ($hasVol) {
                        $vol_no[] = $onlyText ? "Vol. $volume" : \Html::link(route('journal.articles', compact('id', 'year', 'number', 'volume')), "Vol. $volume");
                    }
                    if ($hasNo) {
                        $vol_no[] = $onlyText ? "No. $number" : \Html::link(route('journal.articles', compact('id', 'year', 'number')), "No. $number")->toHtml();
                    }
                    $parts[] = implode(', ', $vol_no);
                }
                if ($hasYear) {
                    $partYear = $onlyText ? $year : Html::link(route('journal.articles', compact('id', 'year')), $year)->toHtml();
                    if ($hasVol || $hasNo) {
                        $partYear = "($partYear)";
                    }
                    $parts[] = $partYear;
                }
            }
        }
        return implode(' ', $parts);
    }

    /**
     * @param Article $article
     * @return string
     */
    public static function authorsShortImplode($article)
    {
        return $article->authors->map(function ($author) {
            return Html::link(route('author.articles', $author->id), $author->name, [], null, false)->toHtml();
        })->implode(', ');
    }

    /**
     * @param Organize $organize
     * @param string $routeName
     * @return string
     */
    public static function organizeWithAncestors($organize, $routeName = null)
    {
        return $organize
            ->getAncestorsWithSelf()
            ->reverse()
            ->map(function ($organize) use ($routeName) {
                /**
                 * @var Organize $organize
                 */
                if ($routeName != null && \Route::has($routeName)) {
                    return Html::link(route($routeName, $organize->id), $organize->name)->toHtml();
                } else {
                    return $organize->name;
                }
            })
            ->implode(' - ');
    }

    /**
     * @param Request $request
     * @param array $params
     * @return string
     */
    public static function requestToHidden(Request $request, $params) {
        return collect($params)
            ->filter(function ($param) use ($request) {
                return $request->has($param);
            })
            ->map(function ($param) use ($request) {
                return \Form::hidden($param, $request->get($param))->toHtml();
            })
            ->implode('');
    }
}
<?php

namespace App\Services;

use Illuminate\Support\Carbon;

class CheckData
{

    public static function set_authority_names($data)
    {
        foreach ($data as $datum) {
            $datum = CheckData::set_authority_name($datum);
        }
        return $data;
    }

    public static function set_authority_name($data)
    {
        $authorityList = config('ur.authorityList');
        foreach ($authorityList as $authority) {
            if ($data->authority_id == $authority['authorityId']) {
                $data->authority_name = $authority['authorityName'];
            }
        }

        return $data;
    }

    public static function set_sex_names($data)
    {
        foreach ($data as $datum) {
            $datum = CheckData::set_sex_name($datum);
        }
        return $data;
    }

    public static function set_sex_name($data)
    {
        if ($data->sex == 1) {
            $data->sex_name = "男性";
        } else if ($data->sex == 2) {
            $data->sex_name = "女性";
        } else if ($data->sex == 3) {
            $data->sex_name = "その他";
        } else if ($data->sex == 4) {
            $data->sex_name = "未回答";
        } else {
            $data->sex_name = "不明";
        }
        return $data;
    }

    public static function mask_tel($data)
    {
        $vowels = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
        $data->tel = str_replace($vowels, "*", $data->tel);
        return $data;
    }

    /**
     * 住所にマスクをかける
     *
     * @param [type] $data
     * @return void
     */
    public static function mask_address($data)
    {
        // 対象の文字列の文字数を取得
        $length = mb_strlen($data->addr21);
        // 文字数と同じ数だけ置換用文字列を生成
        $data->addr21 = str_repeat("*", $length);

        // 対象の文字列の文字数を取得
        $length = mb_strlen($data->strt21);
        // 文字数と同じ数だけ置換用文字列を生成
        $data->strt21 = str_repeat("*", $length);

        return $data;
    }

    /**
     * 生年月日から年齢を求めて表示用の文字列を作成する
     *
     * @param [type] $data
     * @return void
     */
    public static function set_birthday($data)
    {
        $birthday_month = str_pad($data->birthday_month, 2, 0, STR_PAD_LEFT); // 01
        $birthday_day = str_pad($data->birthday_day, 2, 0, STR_PAD_LEFT); // 01
        if (checkdate($birthday_month, $birthday_day, $data->birthday_year) === true) {
            $age = floor((date('Ymd') - ($data->birthday_year . $birthday_month . $birthday_day)) / 10000) . '歳';

            $data->birthday = $data->birthday_year . "年" . $data->birthday_month . "月" . $data->birthday_day . "日生まれ　 満" . $age;
        } else {
            $data->birthday = $data->birthday_year . "年" . $birthday_month . "月" . $birthday_day . "日生まれ";
        }
        return $data;
    }

    /**
     * 誕生日から年齢を計算し、表示する多面尾文字列を生成する
     * @param int|null $year
     * @param int|null $month
     * @param int|null $day
     * @return string
     */
    public static function displayCalcAge(int $year = null, int $month = null, int $day = null): string
    {
        // 年が不明な場合
        if (empty($year)) return '不明';

        // 値が入っている場合
        if (!empty($year) && !empty($month) && !empty($day)) {
            $birthday = new Carbon();
            $birthday->setDate($year, $month, $day);
            return $birthday->age . '歳';
        }

        // 年だけの場合
        if (!empty($year) && empty($month) && empty($day)) {
            $birthday = new Carbon();
            return ($birthday->year - $year - 1) . '～' . ($birthday->year - $year) . '歳';
        }

        // 年月の場合
        if (!empty($year) && !empty($month) && empty($day)) {
            $birthday = new Carbon();
            // 月が今月よりも前なら
            if ($month < $birthday->month) return ($birthday->year - $year) . '歳';

            // 月が来月以降なら
            if ($month > $birthday->month) return ($birthday->year - $year - 1) . '歳';

            // 月が今月なら
            return ($birthday->year - $year - 1) . '～' . ($birthday->year - $year) . '歳';
        }

        return '';
    }

    /**
     * 生年月日の文字列を生成する
     * @param int|null $year
     * @param int|null $month
     * @param int|null $day
     * @return string
     */
    public static function displayBirthday(int $year = null, int $month = null, int $day = null): string
    {
        $strY = $year ?: '____';
        $strM = $month ?: '__';
        $strD = $day ?: '__';
        return sprintf('%s年%s月%s日', $strY, $strM, $strD);
    }

}

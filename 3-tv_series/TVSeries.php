<?php

/**
 * Model of tv_series database table
 */
class TVSeries extends Model {
    protected array $columns = array('id', 'title', 'channel', 'gender');

    protected array $hidden = ['id'];


    protected string $table = 'tv_series';
    /**
     * Next TVSeries to air
     * @param string|null $date
     * @param string|null $title
     * @return Model|array
     */
    public static function next(string $date = null, string $title = null) {
        if($date === null)
            $date = date("Y-m-d H:i:s");
        $timestamp = strtotime($date);
        //weeks => 0 to 6
        $dayofweek = date('w', $timestamp);
        $time = date('H:i:s', $timestamp);
        $weekSec = 86400 * $dayofweek + strtotime('1970-01-01 ' . $time . 'GMT');
        $joinTable = TVSeriesInterval::getTable();
        $timeInterval = "86400 * $joinTable.week_day + TIME_TO_SEC($joinTable.show_time)";
        $query = self::query()
        ->join(TVSeriesInterval::class, "id_tv_series", 'id')
        ->setBinding('weekSec', $weekSec)
        ->orderBy("(abs($timeInterval - :weekSec) + if($timeInterval < :weekSec, 7 * 86400, 0))", 'asc')
        ->first();
        if($title !== null)
            $query->where('title', '=', $title);
        return $query->get();
    }
}
<?php

/**
 * Model of tv_series_intervals table
 */
class TVSeriesInterval extends Model {
    protected array $columns = ['id_tv_series', 'week_day', 'show_time'];

    protected array $hidden = ['id_tv_series'];

    protected string $table = 'tv_series_intervals';
}
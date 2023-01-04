#SQL scripts that create and populate the DB
create table `tv_series` (`id` bigint unsigned not null auto_increment primary key, `title` text not null, `channel` text not null, `gender` varchar(255) not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
create table `tv_series_intervals` (`id_tv_series` bigint unsigned not null, `week_day` tinyint not null, `show_time` time not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `tv_series_intervals` add primary key `tv_series_intervals_id_tv_series_week_day_show_time_primary`(`id_tv_series`, `week_day`, `show_time`);
insert into `tv_series` (`channel`, `gender`, `title`) values ("AMC", "Crime", "Breaking Bad"), ("HBO", "Adventure", "Game of Thrones"), ("NBC", "Comedy", "The Office");
insert into `tv_series_intervals` (`id_tv_series`, `show_time`, `week_day`) values (1, "18:00", 1), (1, "15:00", 3), (2, "13:00", 2), (2, "11:00", 4), (3,  "20:00", 7), (3,  "17:00", 5);
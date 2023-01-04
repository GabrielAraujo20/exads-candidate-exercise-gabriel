<?php
require_once "Model.php";
require_once "QueryBuilder.php";
require_once "TVSeriesInterval.php";
require_once "TVSeries.php";

echo "Next TVSeries to air: \n";
echo json_encode(TVSeries::next()->toJson()). "\n";

echo "TVSeries to air in 2023-01-04 01:00:00: \n";
echo json_encode(TVSeries::next('2023-01-04 01:00:00')->toJson()). "\n";

echo "Next time Breaking Bad will ar in 2023-01-02 18:00:00: \n";
echo json_encode(TVSeries::next('2023-01-02 18:00:00', 'Breaking Bad')->toJson()). "\n";

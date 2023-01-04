<?php

use Exads\ABTestData;

class ABTest {
    protected ABTestData $data;
    protected int $userId;
    /**
     * Instanciate an ABTest for a certain promotion and user
     * @param int $promoId
     * @param int $userId
     */
    public function __construct(int $promoId, int $userId)
    {
        $this->data = new ABTestData($promoId);
        $this->userId = $userId;
    }

    /**
     * Get generated design
     * @return string
     */
    public function getDesign():string {
        $promotion = $this->data->getPromotionName();
        $designs = $this->data->getAllDesigns();
        $promoUserString = $this->userId . '|' . $promotion;
        $hashNum = crc32($promoUserString);
        $value = $hashNum % 100;
        foreach($designs as $design) {
            $percent = $design['splitPercent'];
            $value -= $percent;
            if($value < 0) {
                return $design['designName'];
            }
        }
        return '';
    }
}
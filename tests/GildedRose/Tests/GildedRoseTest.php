<?php

namespace GildedRose\Tests;

use GildedRose\Item;
use GildedRose\Program;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    private $daysPast = 5;

    private function asertSingleItemProgram(Program $program, $updateItemSellIn, $updateItemQuality){

        $sellIn = $program->items[0]->sellIn;
        $quality = $program->items[0]->quality;
        for($days = 0; $days < abs($sellIn + $this->daysPast); $days++){
            $program->UpdateQuality();

            $sellIn = $this->$updateItemSellIn($sellIn);
            $quality = $this->$updateItemQuality($sellIn, $quality);

            $this->assertEquals($sellIn, $program->items[0]->sellIn, "SellIn Is not calculating correctly for day #$days");
            $this->assertEquals($quality, $program->items[0]->quality, "Quality Is not calculating correctly for day #$days");
        }
    }

    private function updateNormalItemSellIn($sellIn){
        return $sellIn - 1;
    }
    private function updateNormalItemQuality($sellIn, $quality){
        if($sellIn < 0 && $quality > 0) {
            --$quality;
        }
        if($quality > 0){
            --$quality;
        }
        return $quality;
    }

    public function testItemNormal(){
        $sellIn = 1;
        $quality = 20;
        $app = new Program([new Item(['name' => "+5 Dexterity Vest",'sellIn' => $sellIn,'quality' => $quality])]);

        $this->asertSingleItemProgram($app, 'updateNormalItemSellIn', 'updateNormalItemQuality');
    }
}

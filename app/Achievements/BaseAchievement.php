<?php


namespace App\Achievements;


use Assada\Achievements\Achievement;

class BaseAchievement extends Achievement
{
    public function getLogicalNameAttribute() {
        return "fasz";
    }
}

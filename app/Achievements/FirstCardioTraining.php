<?php
declare(strict_types=1);

namespace App\Achievements;


use Assada\Achievements\Achievement;

/**
 * Class Registered
 *
 * @package App\Achievements
 */
class FirstCardioTraining extends Achievement
{
    /*
     * The achievement name
     */
    public $name = 'First Cardio Training';

    /*
     * A small description for the achievement
     */
    public $description = 'You completed your first Cardio training!';
}

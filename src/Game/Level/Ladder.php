<?php declare(strict_types=1);

namespace AerysPlayground\Game\Level;

class Ladder
{
    private $levels;

    public function addLevel(Level $level)
    {
        $this->levels[$level->getExperiencePoints()] = $level;
    }

    public function getLevelBasedOnExperiencePoints(int $userExperiencePoints): Level
    {
        $userLevel = reset($this->levels);

        foreach ($this->levels as $experiencePoints => $level) {
            if ($experiencePoints > $userExperiencePoints) {
                break;
            }

            $userLevel = $level;
        }

        return $userLevel;
    }
}

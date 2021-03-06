<?php

/**
 *
 * MMP""MM""YMM               .M"""bgd
 * P'   MM   `7              ,MI    "Y
 *      MM  .gP"Ya   ,6"Yb.  `MMb.   `7MMpdMAo.  ,pW"Wq.   ,pW"Wq.`7MMpMMMb.
 *      MM ,M'   Yb 8)   MM    `YMMNq. MM   `Wb 6W'   `Wb 6W'   `Wb MM    MM
 *      MM 8M""""""  ,pm9MM  .     `MM MM    M8 8M     M8 8M     M8 MM    MM
 *      MM YM.    , 8M   MM  Mb     dM MM   ,AP YA.   ,A9 YA.   ,A9 MM    MM
 *    .JMML.`Mbmmd' `Moo9^Yo.P"Ybmmd"  MMbmmd'   `Ybmd9'   `Ybmd9'.JMML  JMML.
 *                                     MM
 *                                   .JMML.
 * This file is part of TeaSpoon.
 *
 * TeaSpoon is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * TeaSpoon is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with TeaSpoon.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Xenophilicy\TableSpoon
 * @link https://CortexPE.xyz
 *
 */

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item;

use pocketmine\entity\Entity;
use pocketmine\item\Tool;
use pocketmine\Player;

/**
 * Class Trident
 * @package Xenophilicy\TableSpoon\item
 */
class Trident extends Tool {
    
    public const TAG_TRIDENT = "Trident";
    
    /**
     * Trident constructor.
     * @param int $meta
     * @param int $count
     */
    public function __construct($meta = 0, $count = 1){
        parent::__construct(self::TRIDENT, $meta, "Trident");
    }
    
    public function getMaxDurability(): int{
        return 251;
    }
    
    public function onReleaseUsing(Player $player): bool{
        $diff = $player->getItemUseDuration();
        $p = $diff / 10;
        $force = \min((($p ** 2) + $p * 2) / 3, 1) * 2;
        if($force < 0.15 or $diff < 2){
            return false;
        }
        $nbt = Entity::createBaseNBT($player->add(0, $player->getEyeHeight(), 0), $player->getDirectionVector()->multiply($force), ($player->yaw > 180 ? 360 : 0) - $player->yaw, -$player->pitch);
        if($player->isSurvival()){
            $this->applyDamage(1);
        }
        $nbt->setTag($this->nbtSerialize(-1, self::TAG_TRIDENT));
        $entity = Entity::createEntity(Entity::TRIDENT, $player->getLevel(), $nbt, $player, $this);
        $entity->spawnToAll();
        if($player->isSurvival()){
            $this->setCount(0);
        }
        
        return true;
    }
    
    public function getMaxStackSize(): int{
        return 1;
    }
    
    public function onAttackEntity(Entity $victim): bool{
        return $this->applyDamage(1);
    }
    
    public function getAttackPoints(): int{
        return 8;
    }
}

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

use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

/**
 * Class Record
 * @package Xenophilicy\TableSpoon\item
 */
class Record extends Item {
    /**
     * Record constructor.
     * @param int $id
     * @param int $meta
     * @param string $name
     */
    public function __construct(int $id, int $meta = 0, string $name){
        parent::__construct($id, $meta, $name);
    }
    
    public function getMaxStackSize(): int{
        return 1;
    }
    
    public function isValid(): bool{
        return ($this->getId() >= 500 && $this->getId() <= 511);
    }
    
    /**
     * @return int
     */
    public function getSoundId(){
        $cal = LevelSoundEventPacket::SOUND_RECORD_13 + ($this->getRecordId() - 2255);
        $cal -= 1;
        
        return $cal;
    }
    
    public function getRecordId(): int{
        return 1756 + $this->getId(); // so that it matches the wiki...
    }
    
    public function getRecordName(): string{
        $names = [Item::RECORD_13 => "13", Item::RECORD_CAT => "cat", Item::RECORD_BLOCKS => "blocks", Item::RECORD_CHIRP => "chirp", Item::RECORD_FAR => "far", Item::RECORD_MALL => "mall", Item::RECORD_MELLOHI => "mellohi", Item::RECORD_STAL => "stal", Item::RECORD_STRAD => "strad", Item::RECORD_WARD => "ward", Item::RECORD_11 => "11", Item::RECORD_WAIT => "wait",];
        
        return $names[$this->getId()];
    }
}
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

namespace Xenophilicy\TableSpoon\block;

use pocketmine\block\Block;
use pocketmine\block\EnchantingTable as PMEnchantingTable;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\tile\EnchantTable;
use pocketmine\tile\Tile;
use Xenophilicy\TableSpoon\inventory\EnchantInventory;
use Xenophilicy\TableSpoon\network\types\WindowIds;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class EnchantingTable
 * @package Xenophilicy\TableSpoon\block
 */
class EnchantingTable extends PMEnchantingTable {
    
    public function onActivate(Item $item, Player $player = null): bool{
        if(TableSpoon::$EnchantingTableEnabled && !(TableSpoon::$limitedCreative && $player->isCreative())){
            if($player instanceof Player){
                $this->getLevel()->setBlock($this, $this, true, true);
                Tile::createTile(Tile::ENCHANT_TABLE, $this->getLevel(), EnchantTable::createNBT($this));
            }
            
            $player->addWindow(new EnchantInventory($this), WindowIds::ENCHANT);
        }
        
        return true;
    }
    
    public function countBookshelf(): int{
        $count = 0;
        $level = $this->getLevel();
        
        for($y = 0; $y <= 1; $y++){
            for($x = -1; $x <= 1; $x++){
                for($z = -1; $z <= 1; $z++){
                    if($z == 0 && $x == 0) continue;
                    if($level->getBlock($this->add($x, 0, $z))->isTransparent()){
                        if($level->getBlock($this->add(0, 1, 0))->isTransparent()){
                            //diagonal and straight
                            if($level->getBlock($this->add($x << 1, $y, $z << 1))->getId() == Block::BOOKSHELF){
                                $count++;
                            }
                            
                            if($x != 0 && $z != 0){
                                //one block diagonal and one straight
                                if($level->getBlock($this->add($x << 1, $y, $z))->getId() == Block::BOOKSHELF){
                                    ++$count;
                                }
                                
                                if($level->getBlock($this->add($x, $y, $z << 1))->getId() == Block::BOOKSHELF){
                                    ++$count;
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return $count;
    }
}
<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\network;

use pocketmine\network\mcpe\protocol\InventoryTransactionPacket as PMInventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\types\ContainerIds;
use Xenophilicy\TableSpoon\network\types\NetworkInventoryAction;

/**
 * Class InventoryTransactionPacket
 * @package Xenophilicy\TableSpoon\network
 */
class InventoryTransactionPacket extends PMInventoryTransactionPacket {
    
    protected function decodePayload(): void{
        $this->transactionType = $this->getUnsignedVarInt();
        
        for($i = 0, $count = $this->getUnsignedVarInt(); $i < $count; ++$i){
            $this->actions[] = $action = (new NetworkInventoryAction())->read($this);
            
            if($action->sourceType === NetworkInventoryAction::SOURCE_CONTAINER and $action->windowId === ContainerIds::UI and $action->inventorySlot === 50 and !$action->oldItem->equalsExact($action->newItem)){
                $this->isCraftingPart = true;
                if(!$action->oldItem->isNull() and $action->newItem->isNull()){
                    $this->isFinalCraftingPart = true;
                }
            }elseif($action->sourceType === NetworkInventoryAction::SOURCE_TODO and ($action->windowId === NetworkInventoryAction::SOURCE_TYPE_CRAFTING_RESULT or $action->windowId === NetworkInventoryAction::SOURCE_TYPE_CRAFTING_USE_INGREDIENT)){
                $this->isCraftingPart = true;
            }
        }
        
        $this->trData = new \stdClass();
        
        switch($this->transactionType){
            case self::TYPE_NORMAL:
            case self::TYPE_MISMATCH:
                //Regular ComplexInventoryTransaction doesn't read any extra data
                break;
            case self::TYPE_USE_ITEM:
                $this->trData->actionType = $this->getUnsignedVarInt();
                $this->getBlockPosition($this->trData->x, $this->trData->y, $this->trData->z);
                $this->trData->face = $this->getVarInt();
                $this->trData->hotbarSlot = $this->getVarInt();
                $this->trData->itemInHand = $this->getSlot();
                $this->trData->playerPos = $this->getVector3();
                $this->trData->clickPos = $this->getVector3();
                $this->trData->blockRuntimeId = $this->getUnsignedVarInt();
                break;
            case self::TYPE_USE_ITEM_ON_ENTITY:
                $this->trData->entityRuntimeId = $this->getEntityRuntimeId();
                $this->trData->actionType = $this->getUnsignedVarInt();
                $this->trData->hotbarSlot = $this->getVarInt();
                $this->trData->itemInHand = $this->getSlot();
                $this->trData->playerPos = $this->getVector3();
                $this->trData->clickPos = $this->getVector3();
                break;
            case self::TYPE_RELEASE_ITEM:
                $this->trData->actionType = $this->getUnsignedVarInt();
                $this->trData->hotbarSlot = $this->getVarInt();
                $this->trData->itemInHand = $this->getSlot();
                $this->trData->headPos = $this->getVector3();
                break;
            default:
                throw new \UnexpectedValueException("Unknown transaction type $this->transactionType");
        }
    }
    
    protected function encodePayload(): void{
        $this->putUnsignedVarInt($this->transactionType);
        
        $this->putUnsignedVarInt(count($this->actions));
        foreach($this->actions as $action){
            $action->write($this);
        }
        
        switch($this->transactionType){
            case self::TYPE_NORMAL:
            case self::TYPE_MISMATCH:
                break;
            case self::TYPE_USE_ITEM:
                $this->putUnsignedVarInt($this->trData->actionType);
                $this->putBlockPosition($this->trData->x, $this->trData->y, $this->trData->z);
                $this->putVarInt($this->trData->face);
                $this->putVarInt($this->trData->hotbarSlot);
                $this->putSlot($this->trData->itemInHand);
                $this->putVector3($this->trData->playerPos);
                $this->putVector3($this->trData->clickPos);
                $this->putUnsignedVarInt($this->trData->blockRuntimeId);
                break;
            case self::TYPE_USE_ITEM_ON_ENTITY:
                $this->putEntityRuntimeId($this->trData->entityRuntimeId);
                $this->putUnsignedVarInt($this->trData->actionType);
                $this->putVarInt($this->trData->hotbarSlot);
                $this->putSlot($this->trData->itemInHand);
                $this->putVector3($this->trData->playerPos);
                $this->putVector3($this->trData->clickPos);
                break;
            case self::TYPE_RELEASE_ITEM:
                $this->putUnsignedVarInt($this->trData->actionType);
                $this->putVarInt($this->trData->hotbarSlot);
                $this->putSlot($this->trData->itemInHand);
                $this->putVector3($this->trData->headPos);
                break;
            default:
                throw new \InvalidArgumentException("Unknown transaction type $this->transactionType");
        }
    }
}
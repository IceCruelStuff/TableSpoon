<?php

/*
 *
 *  _____   _____   __   _   _   _____  __    __  _____
 * /  ___| | ____| |  \ | | | | /  ___/ \ \  / / /  ___/
 * | |     | |__   |   \| | | | | |___   \ \/ /  | |___
 * | |  _  |  __|  | |\   | | | \___  \   \  /   \___  \
 * | |_| | | |___  | | \  | | |  ___| |   / /     ___| |
 * \_____/ |_____| |_|  \_| |_| /_____/  /_/     /_____/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author iTX Technologies
 * @link https://itxtech.org
 *
 */

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\inventory;

use pocketmine\inventory\CraftingManager;
use pocketmine\inventory\Recipe;
use pocketmine\item\Item;
use pocketmine\utils\UUID;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class BrewingRecipe
 * @package Xenophilicy\TableSpoon\inventory
 */
class BrewingRecipe implements Recipe {
    
    private $id = null;
    
    /** @var Item */
    private $output;
    
    /** @var Item */
    private $ingredient;
    
    /** @var Item */
    private $potion;
    
    /**
     * BrewingRecipe constructor.
     * @param Item $result
     * @param Item $ingredient
     * @param Item $potion
     */
    public function __construct(Item $result, Item $ingredient, Item $potion){
        $this->output = clone $result;
        $this->ingredient = clone $ingredient;
        $this->potion = clone $potion;
    }
    
    /**
     * @return Item
     */
    public function getPotion(){
        return clone $this->potion;
    }
    
    /**
     * @return null
     */
    public function getId(){
        return $this->id;
    }
    
    /**
     * @param UUID $id
     */
    public function setId(UUID $id){
        if($this->id !== null){
            throw new \InvalidStateException("ID is already set");
        }
        
        $this->id = $id;
    }
    
    /**
     * @param Item $item
     */
    public function setInput(Item $item){
        $this->ingredient = clone $item;
    }
    
    /**
     * @return Item
     */
    public function getInput(){
        return clone $this->ingredient;
    }
    
    /**
     * @return Item
     */
    public function getResult(){
        return clone $this->output;
    }
    
    public function registerToCraftingManager(CraftingManager $manager): void{
        TableSpoon::getInstance()->getBrewingManager()->registerBrewingRecipe($this);
    }
}
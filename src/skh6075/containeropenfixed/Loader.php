<?php

declare(strict_types=1);

namespace skh6075\containeropenfixed;

use pocketmine\event\EventPriority;
use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\plugin\PluginBase;

final class Loader extends PluginBase{

	/**
	 * @phpstan-var array<string, IWindowType>
	 * @var IWindowType[]
	 */
	private array $containers = [];

	protected function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvent(InventoryOpenEvent::class, function(InventoryOpenEvent $event): void{
			if(!$event->isCancelled() && ($inventory = $event->getInventory()) instanceof IWindowType){
				$this->containers[$event->getPlayer()->getUniqueId()->toString()] = $inventory;
			}
		}, EventPriority::MONITOR, $this, false);
		$this->getServer()->getPluginManager()->registerEvent(DataPacketSendEvent::class, function(DataPacketSendEvent $event): void{
			foreach($event->getPackets() as $packet){
				if(!$packet instanceof ContainerOpenPacket){
					continue;
				}
				foreach($event->getTargets() as $target){
					$player = $target->getPlayer();
					if($player === null){
						continue;
					}
					$rawUUID = $player->getUniqueId()->toString();
					if(!isset($this->containers[$rawUUID])){
						continue;
					}
					$inventory = $this->containers[$rawUUID];
					unset($this->containers[$rawUUID]);
					$packet->windowType = $inventory->getWindowType();
				}
			}
		}, EventPriority::MONITOR, $this, false);
	}
}
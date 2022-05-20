# ContainerOpenFixed
Container Open Inventory ID send bug fix PocketMine-MP plugin

# Example
```php
use skh6075\containeropenfixed\IWindowType;
use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;

class ShopKeeperInventory implements IWindowType{
    // Your code..
    
    public function getWindowType() : int{
        return WindowTypes::DROPPER;
    }
}
```
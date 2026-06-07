<?php
declare(strict_types=1);
namespace MyPlot\subcommand;

use MyPlot\forms\subforms\RentInfoForm;
use MyPlot\MyPlot;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class RentSubCommand extends SubCommand {
    public function __construct(MyPlot $plugin, string $name) {
        parent::__construct($plugin, $name);
    }

    public function canUse(CommandSender $sender) : bool {
        return ($sender instanceof Player) and $sender->hasPermission("myplot.command.rent");
    }

    public function getForm(?Player $player = null) : ?\MyPlot\forms\MyPlotForm {
        if($player !== null) return new RentInfoForm($player);
        return null;
    }

    public function execute(CommandSender $sender, array $args) : bool {
        if(!($sender instanceof Player)) return true;
        if(isset($args[0]) && strtolower($args[0]) === "pay") {
            $sender->sendForm(new RentInfoForm($sender));
            return true;
        }
        $sender->sendForm(new RentInfoForm($sender));
        return true;
    }
}

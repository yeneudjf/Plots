<?php
declare(strict_types=1);
namespace MyPlot\forms\subforms;

use MyPlot\forms\SimpleMyPlotForm;
use MyPlot\MyPlot;
use MyPlot\Plot;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class RentConfirmForm extends SimpleMyPlotForm {
    public function __construct(Player $player) {
        $this->plot = MyPlot::getInstance()->getPlotByPosition($player->getPosition());
        if(!isset($this->plot)) {
            parent::__construct(
                MyPlot::getInstance()->getLanguage()->get("error"),
                MyPlot::getInstance()->getLanguage()->get("notinplot"),
                [MyPlot::getInstance()->getLanguage()->get("form.close")],
                function(Player $player, int $selected) : void {
                },
                function(Player $player) : void {
                }
            );
            return;
        }

        $amount = number_format($this->plot->getRentAmount(), 2);
        $title = MyPlot::getInstance()->getLanguage()->get("rent.confirm.title");
        $content = MyPlot::getInstance()->getLanguage()->translateString("rent.confirm.content", [$this->plot, $amount]);

        $options = [
            MyPlot::getInstance()->getLanguage()->get("rent.confirm.pay"),
            MyPlot::getInstance()->getLanguage()->get("form.cancel")
        ];

        parent::__construct($title, $content, $options, function(Player $player, int $selected) : void {
            $plugin = MyPlot::getInstance();
            if($selected === 0) {
                $economy = $plugin->getEconomyProvider();
                if($economy === null) {
                    $player->sendMessage(MyPlot::getPrefix() . TextFormat::RED . $plugin->getLanguage()->get("rent.nosystem"));
                    return;
                }
                $amount = $this->plot->getRentAmount();
                if(!$economy->reduceMoney($player, $amount)) {
                    $player->sendMessage(MyPlot::getPrefix() . TextFormat::RED . $plugin->getLanguage()->get("rent.nofunds"));
                    return;
                }
                $this->plot->flags["rental.status"] = Plot::RENT_STATUS_ACTIVE;
                $this->plot->flags["rental.due"] = time() + $plugin->getRentalPeriodSeconds();
                $this->plot->flags["rental.grace_until"] = 0;
                $plugin->savePlot($this->plot);
                $player->sendMessage(MyPlot::getPrefix() . TextFormat::GREEN . $plugin->getLanguage()->get("rent.paid", [number_format($amount, 2)]));
            }
        });
    }
}

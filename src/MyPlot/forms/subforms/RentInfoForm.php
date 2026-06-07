<?php
declare(strict_types=1);
namespace MyPlot\forms\subforms;

use MyPlot\forms\SimpleMyPlotForm;
use MyPlot\MyPlot;
use MyPlot\Plot;
use pocketmine\player\Player;

class RentInfoForm extends SimpleMyPlotForm {
    public function __construct(Player $player) {
        $this->plot = MyPlot::getInstance()->getPlotByPosition($player->getPosition());
        if(!isset($this->plot)) return;

        $status = $this->plot->getRentalStatus();
        $amount = number_format($this->plot->getRentAmount(), 2);
        $due = $this->plot->getRentalDue();
        $dueText = $due > 0 ? date("Y-m-d H:i", $due) : "N/A";

        $title = MyPlot::getInstance()->getLanguage()->get("rent.info.title");
        $content = MyPlot::getInstance()->getLanguage()->translateString("rent.info.content", [$this->plot, $status, $amount, $dueText]);

        $options = [
            MyPlot::getInstance()->getLanguage()->get("rent.pay"),
            MyPlot::getInstance()->getLanguage()->get("form.close")
        ];

        parent::__construct($title, $content, $options, function(Player $player, int $selected) : void {
            switch($selected) {
                case 0:
                    $player->sendForm(new RentConfirmForm($player));
                break;
                default:
                break;
            }
        });
    }
}

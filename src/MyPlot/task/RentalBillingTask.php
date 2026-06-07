<?php

declare(strict_types=1);

namespace MyPlot\task;

use MyPlot\MyPlot;
use pocketmine\scheduler\Task;

class RentalBillingTask extends Task {
	private MyPlot $plugin;

	public function __construct(MyPlot $plugin) {
		$this->plugin = $plugin;
	}

	public function onRun() : void {
		$this->plugin->processPlotRentals();
	}
}

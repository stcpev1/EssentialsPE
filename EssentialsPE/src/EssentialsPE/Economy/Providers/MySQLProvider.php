<?php

namespace EssentialsPE\Economy\Providers;

use EssentialsPE\Economy\IProvider;
use EssentialsPE\Economy\Provider;
use EssentialsPE\Loader;

class MySQLProvider extends Provider implements IProvider {

	private $database;

	public function __construct(Loader $loader) {
		parent::__construct($loader);
	}

	public function prepare() {
		$economyConfig = $this->getLoader()->getConfigurableData()->getEconomyConfiguration();
		$this->database = new \mysqli($economyConfig->get("MySQL.Host"), $economyConfig->get("MySQL.User"), $economyConfig->get("MySQL.Password"), $economyConfig->get("MySQL.Database"), $economyConfig->get("MySQL.Port"));
		if($this->database->connect_error !== null) {
			throw new \mysqli_sql_exception("No connection could be made to the MySQL server. " . $this->database->connect_error);
		}
		$query = "CREATE TABLE IF NOT EXISTS Economy(Player VARCHAR(20) PRIMARY KEY, Balance INT);";
		$success = $this->database->query($query);
		if(!$success) {
			throw new \mysqli_sql_exception("An error occured when creating the main table. " . $this->database->error);
		}
	}
}
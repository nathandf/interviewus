<?php

namespace Model\Services;

class TestBoi
{
	public function __construct(
		Config $Config,
		Container $Container,
		CsvGenerator $CsvGenerator,
		EntityFactory $EntityFactory
	) {
		$this->Config = $Config
		$this->Container = $Container
		$this->CsvGenerator = $CsvGenerator
		$this->EntityFactory = $EntityFactory
	}
}
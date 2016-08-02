<?php
	if (php_sapi_name() != 'cli') die('Morate da pokrenete setup iz konzole!');

	function command_exists($command) {
		$whereIsCommand = (PHP_OS == 'WINNT') ? 'where' : 'which';

		$process = proc_open(
			"$whereIsCommand $command",
			array(
				0 => array("pipe", "r"), //STDIN
				1 => array("pipe", "w"), //STDOUT
				2 => array("pipe", "w"), //STDERR
			),
			$pipes
		);

		if ($process !== false) {
			$stdout = stream_get_contents($pipes[1]);
			$stderr = stream_get_contents($pipes[2]);
			fclose($pipes[1]);
			fclose($pipes[2]);
			proc_close($process);

			return $stdout != '';
		}

		return false;
	}

	echo "Proveravam da li su na sistemu dostupne potrebni programi... sacekajte...\n\n";
	
	$mustHave = ['php', 'mysql', 'composer', 'bower'];
	
	foreach ($mustHave as $command) {
		if (!command_exists($command)) {
			echo "ERROR: Morate da imate instaliran program {$command} i da je upisan u environment PATH da bi ova skripta mogla da nastavi rad!\n";
			exit;
		}
	}

	echo "Instalacija potrebnih komponenata:\n";

	system('composer install');

	system('bower install');

	echo "Podesavanje Old Milantex Core aplikacije:\n";

	echo "\nPodesavanje baze podataka:\n";
	$DB_HOSTNAME = trim(readline("  Unesite adresu database servera [localhost]: "));
	$DB_USERNAME = trim(readline("  Unesite ime korisnika baze podataka [root]: "));
	$DB_PASSWORD = trim(readline("  Unesite lozinku za korisnika {$DB_USERNAME} []: "));
	$DB_BASENAME = trim(readline("  Unesite ime baze podataka [old_milantex_app_database]: "));

	echo "\nPodesavanje aplikacije:\n";

	$BASE_URL = trim(readline("  Unesite URL public direktorijuma aplikacije [http://localhost/" . basename(__DIR__) . "/public/]: "));

	if (!$DB_HOSTNAME) $DB_HOSTNAME = 'localhost';
	if (!$DB_USERNAME) $DB_USERNAME = 'root';
	if (!$DB_PASSWORD) $DB_PASSWORD = '';
	if (!$DB_BASENAME) $DB_BASENAME = 'old_milantex_app_database';
	if (!$BASE_URL) $BASE_URL = "http://localhost/" . basename(__DIR__) . "/public/";

	$configuration = file_get_contents('private/.Configuration.php.template');

	$configuration = str_replace('{{DB_HOSTNAME}}', $DB_HOSTNAME, $configuration);
	$configuration = str_replace('{{DB_USERNAME}}', $DB_USERNAME, $configuration);
	$configuration = str_replace('{{DB_PASSWORD}}', $DB_PASSWORD, $configuration);
	$configuration = str_replace('{{DB_BASENAME}}', $DB_BASENAME, $configuration);
	$configuration = str_replace('{{BASE_URL}}', $BASE_URL, $configuration);

	file_put_contents('private/Configuration.php', $configuration);

	echo "\nConfiguration file written.\n";

	try {
		$db = new \PDO('mysql:hostname=' . $DB_HOSTNAME . ';dbname=' . $DB_BASENAME . ';charset=utf8', $DB_USERNAME, $DB_PASSWORD);
		$db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
		echo "\nDatabase connection test PASSED.\n";
	} catch (\Exception $e) {
		echo "\n* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *\n";
		echo "\nDatabase connection test FAILED! Please review your database connection parameters and make sure the database exists!\n";
		echo "\n* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *\n";
	}

	echo "\n";

	$unosSQL = strtoupper(trim(readline("Da li zelite da u bazu podataka `{$DB_BASENAME}` unesete DEMO strukturu osnovne aplikacije? [Y/n] ")));
	if ($unosSQL === 'Y') {
		$passwordArg = '';

		if ($DB_PASSWORD) {
			$passwordArg = " -p{$DB_PASSWORD}";
		}

		echo system("mysql -h {$DB_HOSTNAME} -D {$DB_BASENAME} -u {$DB_USERNAME} {$passwordArg} < ./sql/database-dump.sql");
	}

	echo "\nGotovo! You've earned a cookie. Go bake some!\n";

	system("start /max {$BASE_URL}");

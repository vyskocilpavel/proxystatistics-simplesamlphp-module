<?php
include ("DatabaseConnector.php");
/**
 * @author Pavel Vyskočil <vyskocilpavel@muni.cz>
 */

class DatabaseCommand
{

	public static function insertLogin(&$request, &$date)
	{
		$databaseConnector = new DatabaseConnector();
		$conn = $databaseConnector->getConnection();
		assert($conn != NULL);
		$identityProvidersTableName = $databaseConnector->getIdentityProvidersTableName();
		$identityProvidersMapTableName = $databaseConnector->getIdentityProvidersMapTableName();
		$serviceProvidersTableName = $databaseConnector->getServiceProvidersTableName();
		$serviceProvidersMapTableName = $databaseConnector->getServiceProvidersMapTableName();
		$idpEntityID = $request['saml:sp:IdP'];
		$idpName = $request['Attributes']['sourceIdPName'][0];
		$spEntityId = $request['Destination']['entityid'];
		$spName = $request['Destination']['name']['en'];
		$year = $date->format('Y');
		$month = $date->format('m');
		$day = $date->format('d');

		if (is_null($idpEntityID) || empty($idpEntityID) || is_null($spEntityId) || empty($spEntityId)) {
			SimpleSAML\Logger::error("Some from attribute: 'idpEntityId', 'idpName', 'spEntityId' and 'spName' is null or empty and login log wasn't inserted into the database.");
		} else {
			$stmt = $conn->prepare("INSERT INTO ".$identityProvidersTableName."(year, month, day, sourceIdp, count) VALUES (?, ?, ?, ?, '1') ON DUPLICATE KEY UPDATE count = count + 1");
			$stmt->bind_param("iiis", $year, $month, $day, $idpEntityID);
			if ($stmt->execute() === FALSE) {
				SimpleSAML\Logger::error("The login log wasn't inserted into table: " . $identityProvidersTableName . ".");
			}

			$stmt = $conn->prepare("INSERT INTO ".$serviceProvidersTableName."(year, month, day, service, count) VALUES (?, ?, ?, ?, '1') ON DUPLICATE KEY UPDATE count = count + 1");
			$stmt->bind_param("iiis", $year, $month, $day, $spEntityId);
			if ($stmt->execute() === FALSE) {
				SimpleSAML\Logger::error("The login log wasn't inserted into into table: " . $serviceProvidersTableName . ".");
			}

			if (is_null($idpName) || empty($idpName)) {
				$stmt->prepare("INSERT INTO " . $identityProvidersMapTableName . "(entityId, name) VALUES (?, ?) ON DUPLICATE KEY UPDATE name = ?");
				$stmt->bind_param("sss", $idpEntityID, $idpName, $idpName);
				$stmt->execute();
			}

			if (is_null($spName) || empty($spName)) {
				$stmt->prepare("INSERT INTO " . $serviceProvidersMapTableName . "(identifier, name) VALUES (?, ?) ON DUPLICATE KEY UPDATE name = ?");
				$stmt->bind_param("sss", $spEntityId, $spName, $spName);
				$stmt->execute();
			}
		}

		SimpleSAML\Logger::error("The login log was successfully stored in database");

		$conn->close();
	}

	public static function getLoginCountPerDay($days)
	{
		$databaseConnector = new DatabaseConnector();
		$conn = $databaseConnector->getConnection();
		assert($conn != NULL);
		$table_name = $databaseConnector->getIdentityProvidersTableName();
		if($days == 0) {	// 0 = all time
			$stmt = $conn->prepare("SELECT year, month, day, SUM(count) AS count FROM ".$table_name." GROUP BY year DESC,month DESC,day DESC");
		} else {
			$stmt = $conn->prepare("SELECT year, month, day, SUM(count) AS count FROM ".$table_name." WHERE CONCAT(year,'-',LPAD(month,2,'00'),'-',LPAD(day,2,'00')) BETWEEN CURDATE() - INTERVAL ".$days." DAY AND CURDATE() GROUP BY year DESC,month DESC,day DESC");
		}
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc()) {
			echo "[new Date(".$row["year"].",". ($row["month"] - 1 ). ", ".$row["day"]."), {v:".$row["count"]."}],";
		}
		$conn->close();
	}


	public static function getLoginCountPerDeyPerService()
	{
		$databaseConnector = new DatabaseConnector();
		$conn = $databaseConnector->getConnection();
		assert($conn != NULL);
		$identityProvidersTableName = $databaseConnector->getIdentityProvidersTableName();
		$identityProvidersMapTableName = $databaseConnector->getIdentityProvidersMapTableName();
		$stmt = $conn->prepare("SELECT year, month, IFNULL(name,sourceIdp) AS idPName, SUM(count) AS count FROM ".$identityProvidersTableName. " LEFT OUTER JOIN " . $identityProvidersMapTableName . " ON sourceIdp = entityId GROUP BY year, month, sourceIdp HAVING sourceIdp != '' ORDER BY year DESC, month DESC, count DESC");
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc()) {
			echo "[new Date(".$row["year"].",".($row["month"] - 1 )."),'".str_replace("'","\'",$row["idPName"])."', {v:".$row["count"]."}],";
		}
		$conn->close();
	}

	public static function getAccessToServicesPerMonth()
	{
		$databaseConnector = new DatabaseConnector();
		$conn = $databaseConnector->getConnection();
		assert($conn != NULL);
		$serviceProvidersTableName = $databaseConnector->getServiceProvidersTableName();
		$serviceProvidersMapTableName = $databaseConnector->getServiceProvidersMapTableName();
		$stmt = $conn->prepare("SELECT year, month, IFNULL(name,service) AS spName, SUM(count) AS count FROM ".$serviceProvidersTableName." LEFT OUTER JOIN " . $serviceProvidersMapTableName . " ON service = identifier GROUP BY year DESC, month DESC, service HAVING service != '' ORDER BY year DESC, month DESC, count DESC");
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc()) {
			echo "[new Date(".$row["year"].",".($row["month"] - 1 )."),'".str_replace("'","\'",$row["spName"])."', {v:".$row["count"]."}],";
		}
		$conn->close();
	}

	public static function getCountOfAllLogins()
	{
		$databaseConnector = new DatabaseConnector();
		$conn = $databaseConnector->getConnection();
		assert($conn != NULL);
		$table_name = $databaseConnector->getIdentityProvidersTableName();
		$stmt = $conn->prepare("SELECT SUM(count) AS count FROM " . $table_name);
		$stmt->execute();
		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			$count = $row["count"];
		}
		$conn->close();
		if ($count === null)
		{
			$count = 0;
		}
		echo $count;
	}

	public static function getCountOfAllLoginsForToday()
	{
		$count = 0;
		$dateTime = new DateTime();
		$databaseConnector = new DatabaseConnector();
		$conn = $databaseConnector->getConnection();
		assert($conn != NULL);
		$table_name = $databaseConnector->getIdentityProvidersTableName();
		$stmt = $conn->prepare("SELECT SUM(count) AS count FROM " . $table_name." WHERE year = ".$dateTime->format('Y')." AND month=".$dateTime->format('m')." AND day = ".$dateTime->format('d'));
		$stmt->execute();
		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			$count = $row["count"];
		}
		$conn->close();
		if ($count === null)
		{
			$count = 0;
		}
		echo $count;
	}


	public static function getAccessCountPerService($days)
	{
		$databaseConnector = new DatabaseConnector();
		$conn = $databaseConnector->getConnection();
		assert($conn != NULL);
		$serviceProvidersTableName = $databaseConnector->getServiceProvidersTableName();
		$serviceProvidersMapTableName = $databaseConnector->getServiceProvidersMapTableName();
		if($days == 0) {	// 0 = all time
			$stmt = $conn->prepare("SELECT IFNULL(name,service) AS spName, SUM(count) AS count FROM " . $serviceProvidersTableName . " LEFT OUTER JOIN " . $serviceProvidersMapTableName . " ON service = identifier GROUP BY service HAVING service != ''  ORDER BY count DESC");
		} else {
			$stmt = $conn->prepare("SELECT year, month, day, IFNULL(name,service) AS spName, SUM(count) AS count FROM " . $serviceProvidersTableName . " LEFT OUTER JOIN " . $serviceProvidersMapTableName . "  ON service = identifier WHERE CONCAT(year,'-',LPAD(month,2,'00'),'-',LPAD(day,2,'00')) BETWEEN CURDATE() - INTERVAL ".$days." DAY AND CURDATE() GROUP BY service HAVING service != ''  ORDER BY count DESC");
		}
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc()) {
			echo "['" . str_replace("'", "\'", $row["spName"]) . "', " . $row["count"] . "],";
		}
		$conn->close();
	}

	public static function getLoginCountPerIdp($days)
	{
		$databaseConnector = new DatabaseConnector();
		$conn = $databaseConnector->getConnection();
		assert($conn != NULL);
		$identityProvidersTableName = $databaseConnector->getIdentityProvidersTableName();
		$identityProvidersMapTableName = $databaseConnector->getIdentityProvidersMapTableName();
		if($days == 0) {	// 0 = all time
			$stmt = $conn->prepare("SELECT IFNULL(name,sourceIdp) AS idPName, SUM(count) AS count FROM ".$identityProvidersTableName. " LEFT OUTER JOIN " . $identityProvidersMapTableName . " ON sourceIdp = entityId GROUP BY sourceIdp HAVING sourceIdp != '' ORDER BY count DESC");
		} else {
			$stmt = $conn->prepare("SELECT year, month, day, IFNULL(name,sourceIdp) AS idPName, SUM(count) AS count FROM ".$identityProvidersTableName. " LEFT OUTER JOIN " . $identityProvidersMapTableName . " ON sourceIdp = entityId WHERE CONCAT(year,'-',LPAD(month,2,'00'),'-',LPAD(day,2,'00')) BETWEEN CURDATE() - INTERVAL ".$days." DAY AND CURDATE() GROUP BY sourceIdp HAVING sourceIdp != '' ORDER BY count DESC");
		}
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc()) {
			echo "['" . str_replace("'", "\'", $row["idPName"]) . "', " . $row["count"] . "],";
		}
		$conn->close();
	}

	public static function getCountOfUsedIdp()
	{
		$databaseConnector = new DatabaseConnector();
		$conn = $databaseConnector->getConnection();
		assert($conn != NULL);
		$table_name = $databaseConnector->getIdentityProvidersTableName();
		$stmt = $conn->prepare("SELECT COUNT(*) AS count FROM (SELECT DISTINCT sourceIdp FROM ".$table_name." ) AS idps WHERE sourceIdp != ''");
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc()) {
			$count = $row["count"];
		}
		$conn->close();
		if ($count === null)
		{
			$count = 0;
		}
		echo $count;
	}

	public static function getCountOfAccesedServices()
	{
		$databaseConnector = new DatabaseConnector();
		$conn = $databaseConnector->getConnection();
		assert($conn != NULL);
		$table_name = $databaseConnector->getServiceProvidersTableName();
		$stmt = $conn->prepare("SELECT COUNT(*) AS count FROM (SELECT DISTINCT service FROM ".$table_name." ) AS services WHERE service != ''");
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc()) {
			$count = $row["count"];
		}
		$conn->close();
		if ($count === null)
		{
			$count = 0;
		}
		echo $count;
	}

	public static function getAverageLoginCountPerDay()
	{
		$databaseConnector = new DatabaseConnector();
		$conn = $databaseConnector->getConnection();
		assert($conn != NULL);
		$table_name = $databaseConnector->getServiceProvidersTableName();
		$stmt = $conn->prepare("SELECT AVG(count) as avg_count FROM (SELECT year, month, day, SUM(count) AS count FROM " . $table_name .  " GROUP BY year,month,day ) AS average_count;");
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc()) {
			$avg_count = $row["avg_count"];
		}
		$conn->close();
		if ($avg_count === null)
		{
			$avg_count = 0;
		}
		echo round($avg_count);
	}

	public static function getMaxLoginCountPerDay()
	{
		$databaseConnector = new DatabaseConnector();
		$conn = $databaseConnector->getConnection();
		assert($conn != NULL);
		$table_name = $databaseConnector->getServiceProvidersTableName();
		$stmt = $conn->prepare("SELECT MAX(count) as max_count FROM (SELECT year, month, day, SUM(count) AS count FROM " . $table_name .  " GROUP BY year,month,day ) AS maximal_count;");
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc()) {
			$max_count = $row["max_count"];
		}
		$conn->close();
		if ($max_count === null)
		{
			$max_count = 0;
		}
		echo $max_count;
	}
}
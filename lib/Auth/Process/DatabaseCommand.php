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
        $serviceProvidersTableName = $databaseConnector->getServiceProvidersTableName();
        $sourceIdp = $request['Attributes']['sourceIdPName'][0];
        $service = $request['Destination']['name']['en'];
        $year = $date->format('Y');
        $month = $date->format('m');
        $day = $date->format('d');

        $stmt = $conn->prepare("INSERT INTO ".$identityProvidersTableName."(year, month, day, sourceIdp, count) VALUES (?, ?, ?, ?, '1') ON DUPLICATE KEY UPDATE count = count + 1");
        $stmt->bind_param("iiis", $year, $month, $day, $sourceIdp);
        if ($stmt->execute() === FALSE) {
            SimpleSAML\Logger::error("The login log wasn't inserted into the database.");
        }

        $stmt = $conn->prepare("INSERT INTO ".$serviceProvidersTableName."(year, month, day, service, count) VALUES (?, ?, ?, ?, '1') ON DUPLICATE KEY UPDATE count = count + 1");
        $stmt->bind_param("iiis", $year, $month, $day, $service);
        if ($stmt->execute() === FALSE) {
            SimpleSAML\Logger::error("The login log wasn't inserted into the database.");
        }

        $conn->close();
    }

    public static function getLoginCountPerDay()
    {
        $databaseConnector = new DatabaseConnector();
        $conn = $databaseConnector->getConnection();
        assert($conn != NULL);
        $table_name = $databaseConnector->getIdentityProvidersTableName();
        $stmt = $conn->prepare("SELECT year, month, day, SUM(count) AS count FROM ".$table_name." GROUP BY year,month,day");
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
        $table_name = $databaseConnector->getIdentityProvidersTableName();
        $stmt = $conn->prepare("SELECT year, month, sourceIdp, SUM(count) AS count FROM ".$table_name. " GROUP BY year, month, sourceIdp HAVING sourceIdp != ''");
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            echo "[new Date(".$row["year"].",".($row["month"] - 1 )."),'".$row["sourceIdp"]."', {v:".$row["count"]."}],";
        }
        $conn->close();
    }

    public static function getAccessToServicesPerMonth()
    {
        $databaseConnector = new DatabaseConnector();
        $conn = $databaseConnector->getConnection();
        assert($conn != NULL);
        $table_name = $databaseConnector->getServiceProvidersTableName();
        $stmt = $conn->prepare("SELECT year, month, service, SUM(count) AS count FROM ".$table_name." GROUP BY year, month, service HAVING service != ''");
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            echo "[new Date(".$row["year"].",".($row["month"] - 1 )."),'".$row["service"]."', {v:".$row["count"]."}],";        }
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


    public static function getAccessCountPerService()
    {
        $databaseConnector = new DatabaseConnector();
        $conn = $databaseConnector->getConnection();
        assert($conn != NULL);
        $table_name = $databaseConnector->getServiceProvidersTableName();
        $stmt = $conn->prepare("SELECT service, SUM(count) AS count FROM ".$table_name." GROUP BY service HAVING service != ''");
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            echo "['".$row["service"]."', ".$row["count"]."],";
        }
        $conn->close();
    }

    public static function getLoginCountPerIdp()
    {
        $databaseConnector = new DatabaseConnector();
        $conn = $databaseConnector->getConnection();
        assert($conn != NULL);
        $table_name = $databaseConnector->getIdentityProvidersTableName();
        $stmt = $conn->prepare("SELECT sourceIdp, SUM(count) AS count FROM ".$table_name." GROUP BY sourceIdp HAVING sourceIdp != ''");
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            echo "['".$row["sourceIdp"]."', ".$row["count"]."],";
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
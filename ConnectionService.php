<?php

namespace Service;

use PDO;

class ConnectionService
{
    public function createNewConnection(): PDO
    {
        $db = parse_url(getenv("DATABASE_URL"));

        $pdo = new PDO(
            "pgsql:" . sprintf(
                "host=%s;port=%s;user=%s;password=%s;dbname=%s",
                $db["host"],
                $db["port"],
                $db["user"],
                $db["pass"],
                ltrim($db["path"], "/")
            )
        );

        return $pdo;
    }

    public function getRandomReasonForExcuse(PDO $connection): string
    {
        $sql = 'SELECT r.reason
                    FROM d56dm3jpas8cjd.public.reasons r
                ORDER BY RANDOM()
                LIMIT 1;
                ';

        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchColumn();

        return $result;
    }

    public function updateLastCommand(PDO $connection, ?string $command = null): void
    {
        $sql = 'UPDATE "d56dm3jpas8cjd"."public"."last_command"
                SET "command" = :command
                WHERE "id" = 1;
                ';

        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':command', $command);
        $stmt->execute();
    }

    public function getLastCommand(PDO $connection): ?string
    {
        $sql = 'SELECT lc.command
                    FROM d56dm3jpas8cjd.public.last_command lc
                LIMIT 1;
                ';

        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchColumn();

        return $result;
    }

    public function addNewReason(PDO $connection, string $reason): void
    {
        $sql = 'INSERT INTO "d56dm3jpas8cjd"."public"."reasons"(reason)
                VALUES (:reason);
                ';

        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':reason', $reason);
        $stmt->execute();
    }
}

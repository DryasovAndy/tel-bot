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
                    FROM public.reasons r
                ORDER BY RANDOM()
                LIMIT 1;
                ';

        $stmt = $connection->query($sql);

        return $stmt->fetchColumn();
    }

    public function getAllReasonsForExcuse(PDO $connection): array
    {
        $sql = 'SELECT r.id, r.reason
                    FROM public.reasons r
                ';

        $stmt = $connection->query($sql);

        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function updateLastCommand(PDO $connection, ?string $command = null): void
    {
        $sql = 'UPDATE "public"."last_command"
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
                    FROM public.last_command lc
                LIMIT 1;
                ';

        $stmt = $connection->query($sql);

        return $stmt->fetchColumn();
    }

    public function addNewReason(PDO $connection, string $reason): void
    {
        $sql = 'INSERT INTO "public"."reasons"(reason)
                VALUES (:reason);
                ';

        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':reason', $reason);
        $stmt->execute();
    }

    public function getLastReasonId(PDO $connection): ?string
    {
        $sql = 'SELECT r.id
                    FROM public.reasons r
                ORDER BY r.id DESC
                LIMIT 1;
                ';

        $stmt = $connection->query($sql);

        return $stmt->fetchColumn();
    }

    public function getLastReason(PDO $connection): ?string
    {
        $sql = 'SELECT r.reason
                    FROM public.reasons r
                ORDER BY r.id DESC
                LIMIT 1;
                ';

        $stmt = $connection->query($sql);

        return $stmt->fetchColumn();
    }

    public function deleteReason(PDO $connection, string $id): void
    {
        $sql = 'DELETE FROM "public"."reasons"
                WHERE "id" = :id;
                ';

        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function getReasonById(PDO $connection, string $id): ?string
    {
        $sql = 'SELECT r.reason
                    FROM public.reasons r
                WHERE "id" = :id;
                ';

        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetchColumn();
    }
}

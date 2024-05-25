<?php
declare(strict_types=1);

namespace App\Database;
use App\Utils;
use App\Model\User;

class UserTable
{
    private const MYSQL_DATETIME_FORMAT = 'Y-m-d H:i:s';

    public array $userList;

    public function __construct(private \PDO $connection)
    {
    }

    public function saveUserToDatabase(User $user): int
    {
        $query = <<<SQL
            INSERT INTO user 
                (first_name, last_name, middle_name, gender, birth_date, email, phone, avatar_path) 
            VALUES (:firstName, :lastName, :middleName, :gender, :birthDate, :email, :phone, :avatarPath)
        SQL;
        $statement = $this->connection->prepare($query);
        try {
            $statement->execute([
                ':firstName' => $user->getFirstName(), 
                ':lastName' => $user->getLastName(), 
                ':middleName' => $user->getMiddleName(), 
                ':gender' => $user->getGender(), 
                ':birthDate' => $this->convertDateTimeToString($user->getBirthDate()), 
                ':email' => $user->getEmail(), 
                ':phone' => $user->getPhone(), 
                ':avatarPath' => $user->getAvatarPath(), 
            ]);
            return (int)$this->connection->lastInsertId();
        }
        catch (\PDOException $exception) 
        {
            throw new \RuntimeException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }       
    }

    public function updateUser(User $user): void
    {
        $query = <<<SQL
            UPDATE user SET
                first_name = :firstName, 
                last_name = :lastName, 
                middle_name = :middleName, 
                gender = :gender, 
                birth_date = :birthDate, 
                email = :email, 
                phone = :phone, 
                avatar_path = :avatarPath
            WHERE user_id = :userId
        SQL;
        $statement = $this->connection->prepare($query);
        try {
            $statement->execute([
                ':userId' => $user->getId(),
                ':firstName' => $user->getFirstName(), 
                ':lastName' => $user->getLastName(), 
                ':middleName' => $user->getMiddleName(), 
                ':gender' => $user->getGender(), 
                ':birthDate' => $this->convertDateTimeToString($user->getBirthDate()), 
                ':email' => $user->getEmail(), 
                ':phone' => $user->getPhone(), 
                ':avatarPath' => $user->getAvatarPath(), 
            ]);
        }
        catch (\PDOException $exception) 
        {
            throw new \RuntimeException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }       
    }

    public function deleteUser(User $user): void
    {
        $sql = "DELETE FROM user WHERE user_id = :userId";
        $statement = $this->connection->prepare($sql);
        try {
            $statement->execute([
                ':userId' => $user->getId(),
            ]);
        }
        catch (\PDOException $exception) 
        {
            throw new \RuntimeException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }       
    }

    public function saveAvatarToDatabase(int $userId, string $avatarPath): void
    {
        $sql = "UPDATE user SET avatar_path = :avatarPath WHERE user_id = :userId";
        $statement = $this->connection->prepare($sql);
        try {
            $statement->execute([
                ':avatarPath' => $avatarPath,
                ':userId' => $userId,
            ]);
        }
        catch (\PDOException $exception) 
        {
            throw new \RuntimeException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }     
    }

    public function find(int $id): ?User
    {
        $query = "SELECT user_id, first_name, last_name, middle_name, gender, birth_date, email, phone, avatar_path FROM user WHERE user_id=$id";
        $statement = $this->connection->query($query);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            return $this->createUserFromRow($row);
            
        }
        return null; 
    }

    public function getAllUsers(): array
    {
        $userList = [];
        $query = "SELECT user_id, first_name, last_name, middle_name, gender, birth_date, email, phone, avatar_path FROM user";
        $statement = $this->connection->query($query);
        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            array_push($userList, $this->createUserFromRow($row));
            //array_push($userList, $row);
        }
        return $userList;
    }

    public function findByEmail(string $email): ?User
    {
        $query = "SELECT user_id, first_name, last_name, middle_name, gender, birth_date, email, phone, avatar_path FROM user WHERE email='$email'";
        $statement = $this->connection->query($query);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            return $this->createUserFromRow($row);
        }
        return null; 
    }

    private function createUserFromRow(array $row): User
    {
        return new User(
            (int)$row['user_id'],
            $row['first_name'],
            $row['last_name'],
            $row['middle_name'] ?? null, 
            $row['gender'],
            Utils::parseDateTime($row['birth_date'], self::MYSQL_DATETIME_FORMAT),
            $row['email'],
            $row['phone'] ?? null,
            $row['avatar_path'] ?? null,
        );
    }

    private function convertDateTimeToString(\DateTimeImmutable $date): ?string
    {
        if ($date === null)
        {
            return null;
        }
        return $date->format(self::MYSQL_DATETIME_FORMAT);
    }
}
<?php
declare(strict_types=1);

namespace App\Service;

use App\Service\Data\UserData;
use App\Entity\User;

interface UserServiceInterface
{
    public function saveUser(
        string $firstName, 
        string $lastName,
        ?string $middleName,
        string $gender,    
        ?\DateTimeImmutable $birthDate,
        string $email,
        ?string $phone,
        ?string $avatarPath
    ): int;

    public function getUser(int $userId): UserData;

    public function updateUser(User $user): void;

    public function getUserById(int $userId): ?User;

    public function getUserByEmail(string $email): ?User;

    public function deleteUser(int $userId): void;

    public function listUsers(): array;
}
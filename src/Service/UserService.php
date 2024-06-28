<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Data\UserData;

class UserService implements UserServiceInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function saveUser(
        string $firstName, 
        string $lastName,
        ?string $middleName,
        string $gender,    
        ?\DateTimeImmutable $birthDate,
        string $email,
        ?string $phone,
        ?string $avatarPath
    ): int
    {
        $user = new User(
            null,
            $firstName,
            $lastName,
            $middleName,
            $gender,
            new \DateTimeImmutable(),
            $email,
            $phone,
            $avatarPath
        );
        return $this->userRepository->store($user);
    }

    public function getUser(int $userId): UserData
    {
        $user = $this->userRepository->findById($userId);
        if ($user === null)
        {
            throw new \InvalidArgumentException();
        }

        return new UserData(
            $user->getId(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getMiddleName(),
            $user->getGender(),
            $user->getBirthDate(),
            $user->getEmail(),
            $user->getPhone(),
            $user->getAvatarPath()
        );
    }

    public function updateUser(User $user): void {
        $this->userRepository->store($user);
    }

    public function getUserById(int $id): ?User {
        $user = $this->userRepository->findById($id);
        return $user;
    }

    public function getUserByEmail(string $email): ?User {
        $user = $this->userRepository->findByEmail($email);
        return $user;
    }

    public function deleteUser(int $userId): void
    {
        $user = $this->userRepository->findById($userId);
        if ($user === null)
        {
            throw new \InvalidArgumentException();
        }

        $this->userRepository->delete($user);
    }

    public function listUsers(): array
    {
        $users = $this->userRepository->listAll();

        $usersView = [];
        foreach ($users as $user)
        {
            $usersView[] = new UserData(
                $user->getId(),
                $user->getFirstName(),
                $user->getLastName(),
                $user->getMiddleName(),
                $user->getGender(),
                $user->getBirthDate(),
                $user->getEmail(),
                $user->getPhone(),
                $user->getAvatarPath()
            );
        }

        return  $usersView;
    }
}
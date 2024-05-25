<?php
declare(strict_types=1);

namespace App\Controller;

use App\Database\ConnectionProvider;
use App\Model\User;
use App\Database\UserTable;
use App\Utils;
use App\View\PhpTemplateEngine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    private const DATE_TIME_FORMAT = 'Y-m-d';
    private UserTable $table;

    public function __construct()
    {
        $connection = ConnectionProvider::connectDatabase();
        $this->table = new UserTable($connection);
    }

    public function index(): Response
    {
        return $this->render('register_user_form.html.twig');
    }

    public function registerUser(Request $data): ?Response
    {
        $birthDate = Utils::parseDateTime($data->get('birth_date'), self::DATE_TIME_FORMAT);
        $birthDate = $birthDate->setTime(0, 0, 0);

        $user = new User(
            null, 
            $data->get('first_name'),
            $data->get('last_name'),
            empty($data->get('middle_name')) ? null : $data->get('middle_name'),
            $data->get('gender'),
            $birthDate,
            $data->get('email'),
            empty($data->get('phone')) ? null : $data->get('phone'),
            null,
        );

        if ($this->table->findByEmail($data->get('email')) != null) {
            return $this->redirectToRoute('error_page');
        } 

        $userId = $this->table->saveUserToDatabase($user);
        $file = $this->downloadImage($userId);

        if ($file != null){
            $this->table->saveAvatarToDatabase($userId, $file);
        }

        return $this->redirectToRoute('view_user', ['userId' => $userId], Response::HTTP_SEE_OTHER);
    }

    public function updateUser(int $userId, Request $data): Response
    {
        $user = $this->table->find($userId);
        if (!$user)
        {
            $mess = 'You can not update user with this ID';
            return $this->redirectToRoute('error_page', ['mess' => $mess]);     
        }

        if ($data->isMethod('post')) {
            $user = $this->updateUsersData($data);
            $this->table->updateUser($user);  
            echo 'OK';
        }

        return $this->render('update_user_form.html.twig', [
            'userId' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'middleName' => $user->getMiddleName(),
            'gender' => $user->getGender(),
            'birthDate' => Utils::convertDateTimeToStringForm($user->getBirthDate()),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'avatarPath' => $user->getAvatarPath(),
        ]);
    }

    private function updateUsersData(Request $data): User{
        $id = (int)$data->get('user_id');
        $user = $this->table->find($id);

        $birthDate = Utils::parseDateTime($data->get('birth_date'), self::DATE_TIME_FORMAT);
        $birthDate = $birthDate->setTime(0, 0, 0);

        if ($this->table->findByEmail($data->get('email')) != null) {
            header('Location: ' . '/error_page.php', true, 303);
        } 

        if ($user != null) {
            $user->setFirstName($data->get('first_name'));
            $user->setLastName($data->get('last_name'));
            $user->setMiddleName(empty($data->get('middle_name')) ? null : $data->get('middle_name'));
            $user->setGender($data->get('gender'));
            $user->setBirthDate($birthDate);
            $user->setEmail(empty($data->get('email')) ? null : $data->get('email'));
            $user->setPhone(empty($data->get('phone')) ? null : $data->get('phone'));
        } else {
            header('Location: ' . '/error_page.php', true, 303);
        }

        $file = $this->downloadImage($id);

        if ($file != null){
            $user->setAvatarPath($file);
        }
        
        $this->table->updateUser($user); 
        return $user;
    }

    private function downloadImage(int $id): ?string 
    {
        $uploadfile = __DIR__ . '/../../public/uploads/avatar';
        $file = null;

        if ($_FILES['avatar_path']['error'] == 0) {
            $extension = $this->getAvatarExtension($_FILES['avatar_path']['type']);
            if ($extension == null) {
                return $this->redirectToRoute('error_page');
            } 
            if (move_uploaded_file($_FILES['avatar_path']['tmp_name'], $uploadfile . $id . '.' . $extension)) {
                $file = 'avatar' . $id . '.' . $extension;   
            }
        }
        return $file;
    }

    public function deleteUser(int $userId): Response
    {
        $user = $this->table->find($userId);
        if (!$user)
        {
            return $this->redirectToRoute('error_page');
        }
        $this->table->deleteUser($user);
        if ($user->getAvatarPath() != null) {
            $this->deleteImage($user);
        }  
        return $this->redirectToRoute('user_list');
    }

    private function deleteImage(User $user): void
    {
        $avatarPath = $user->getAvatarPath();
        $filePath = __DIR__ . '/../../public/uploads/' . $avatarPath;
        if (file_exists($filePath)) 
        {
            unlink($filePath);
            echo "File Successfully Delete."; 
        } else {
            echo "File does not exists"; 
        }
    }

    private function getAvatarExtension(string $mimeType): ?string
    {
        $supportedMimeTypes = [
            'image/jpeg' => 'jpeg',
            'image/png' => 'png',
            'image/gif' => 'gif',
        ];
        return $supportedMimeTypes[$mimeType] ?? null;
    }

    public function viewUser(int $userId): Response
    {
        $user = $this->table->find($userId);
        if (!$user)
        {
            $mess = 'There is not user with this ID';
            return $this->redirectToRoute('error_page', ['mess' => $mess]);       
        }

        return $this->render('view_user.html.twig', [
            'userId' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'middleName' => $user->getMiddleName(),
            'gender' => $user->getGender(),
            'birthDate' => Utils::convertDateTimeToStringForm($user->getBirthDate()),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'avatarPath' => $user->getAvatarPath(),
        ]);
    }

    public function userList(): Response
    {
        $userList = $this->table->getAllUsers();
        return $this->render('user_list.html.twig', ['userList' => $userList]);
    }

    public function errorPage(string $mess): Response
    {
        return $this->render('error_page.html.twig', ['mess' => $mess]);
    }
}
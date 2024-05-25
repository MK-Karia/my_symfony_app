<?php
use App\Utils;
?>
<head>
    <title>User</title>
</head>
<body style="font-size: 18px">
    <a href="/user_list" ><button>Все пользователи</button></a>
    <h1>Пользователь:</h1>
    <label>First Name: </label><?= htmlentities($user->getFirstName())?><br/>
    <label>Last Name: </label><?= htmlentities($user->getLastName())?><br/>
    <label>Middle Name: </label><?= htmlentities($user->getMiddleName())?><br/>
    <label>Gender: </label><?= htmlentities($user->getGender())?><br/>
    <label>Birth Date: </label><?= htmlentities(Utils::convertDateTimeToStringForm($user->getBirthDate()))?><br/>
    <label>Email: </label><?= htmlentities($user->getEmail())?><br/>
    <label>Phone: </label><?= htmlentities($user->getPhone())?><br/>
    <label>Avatar path: 
        <?php 
            if (htmlentities($user->getAvatarPath()) != null):
        ?>
    </label><img width=200px src="<?='/../uploads/' . htmlentities($user->getAvatarPath())?>">
        <?php
            endif;
        ?>
    <br><br>
    <a href="/update_user/<?= htmlentities($user->getId())?>"><button>Изменить</button></a> 
    <a href="/delete_user/<?= htmlentities($user->getId())?>"><button>Удалить</button></a>
</body>
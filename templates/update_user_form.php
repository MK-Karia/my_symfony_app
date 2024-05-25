<?php
use App\Utils;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Update user</title>
</head>
<body>
<form  enctype="multipart/form-data" action="/update_user/<?= htmlentities($user->getId())?>" method="post">
    <input name="user_id" id="user_id" type="hidden" value="<?= htmlentities($user->getId())?>">
    <div>
        <label for="first_name">First Name:</label>
        <input name="first_name" id="first_name" type="text" value="<?= htmlentities($user->getFirstName())?>">
    </div>
    <div>
        <label for="last_name">Last Name:</label>
        <input name="last_name" id="last_name" type="text" value="<?= htmlentities($user->getLastName())?>">
    </div>
    <div>
        <label for="middle_name">Middle Name:</label>
        <input name="middle_name" id="middle_name" type="text" value="<?= htmlentities($user->getMiddleName())?>">
    </div>
    <div>
        <label for="gender">Gender:</label>
        <input name="gender" id="gender" type="text" value="<?= htmlentities($user->getGender())?>">
    </div>
    <div>
        <label for="birth_date">Birth Date:</label>
        <input name="birth_date" id="birth_date" type="date" value="<?= htmlentities(Utils::convertDateTimeToStringForm($user->getBirthDate()))?>">
    </div>
    <div>
        <label for="email">Email:</label>
        <input name="email" id="email" type="text" value="<?= htmlentities($user->getEmail())?>">
    </div>
    <div>
        <label for="phone">Phone:</label>
        <input name="phone" id="phone" type="text" value="<?= htmlentities($user->getPhone())?>">
    </div>
    <div>
        <label for="avatar_path">Avatar Path:</label>
        <input name="avatar_path" id="avatar_path" type="file" accept="image/png, image/jpeg, image/gif">
    </div>
    <button type="submit">Update</button>
    <a href="/view_user/<?= htmlentities($user->getId())?>">Назад</a> 
</form>
</body>
</html>

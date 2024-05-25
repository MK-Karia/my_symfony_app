<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Add user</title>
</head>
<body>
<form  enctype="multipart/form-data" action="/register_user" method="post">
    <div>
        <label for="first_name">First Name:</label>
        <input name="first_name" id="first_name" type="text">
    </div>
    <div>
        <label for="last_name">Last Name:</label>
        <input name="last_name" id="last_name" type="text">
    </div>
    <div>
        <label for="middle_name">Middle Name:</label>
        <input name="middle_name" id="middle_name" type="text">
    </div>
    <div>
        <label for="gender">Gender:</label>
        <input name="gender" id="gender" type="text">
    </div>
    <div>
        <label for="birth_date">Birth Date:</label>
        <input name="birth_date" id="birth_date" type="date">
    </div>
    <div>
        <label for="email">Email:</label>
        <input name="email" id="email" type="text">
    </div>
    <div>
        <label for="phone">Phone:</label>
        <input name="phone" id="phone" type="text">
    </div>
    <div>
        <label for="avatar_path">Avatar Path:</label>
        <input name="avatar_path" id="avatar_path" type="file" accept="image/png, image/jpeg, image/gif">
    </div>
    <button type="submit">Submit</button>
</form>
</body>
</html>

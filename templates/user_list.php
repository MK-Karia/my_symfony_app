<head>
    <title>User list</title>
</head>
<body style="font-size: 30px">
    <style>
        h1 {
            text-align: center;
        }

        .user-list-block {
            display: flex;
            flex-direction: column;
        }

        .link-to-user {                   
            color: #000000;
            cursor: pointer;
        }

        .container {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-around;
        }
    </style>
    <h1>Все пользователи:</h1> 
    <div class="user-list-block">
        <?php 
        foreach ($userList as $user) {
        ?>
        <div class="container">
            <a class="link-to-user" href="/view_user/<?= $user->getId() ?>">
                <label><?= $user->getFirstName() . ' ' . $user->getLastName()?></label>
            </a>
            <a href="/delete_user/<?= $user->getId() ?>" ><button>Удалить</button></a>    
        </div>
        
        <?php
        }  
        ?>  
    </div>
       
</body>
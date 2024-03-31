<?php
session_start();

if (!isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]) {
    header("location: index.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <script src="./script.js" defer></script>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/burguerButton/burguer.css">
    <script src="./css/burguerButton/script.js" defer></script>
</head>
<body>
    <header>
        <div class="container header-content">
        <div class="logo-field">
            <img/>
            <span>Todo Heaven</span>
        </div>   
        
        <div class="burguer-menu">
            <div class="burguer-button">
            <p id="username"></p>
            </div>
            <ul class="burguer-content">
                <li>
                    <p>Settings</p>
                </li>
                <li id="logout-button">
                    <p>Logout</p>
                </li>
            </ul>
        </div>
        </div>
    </header>

<main class="container">
    <div class="todo-info">
    <h2>Todo List</h2>
    <button id="modal-button">+</button>
    </div>

    <div id="todo-list">
    </div>

    <div id="modal" class="hidden">
    <form>
        <div class="input-container">
        <label for="task-name">Task Name</label>
        <input id="task-name" type="text">
        </div>
        <div class="input-container">
        <label for="description">Description</label>
        <textarea id="description"></textarea>
        </div>
        <div class="action-buttons">
        <button id="add-button">Add</button>
        <button id="cancel-button">Cancel</button>
        </div>
    </form>
    </div>
</main>
</body>
</html>
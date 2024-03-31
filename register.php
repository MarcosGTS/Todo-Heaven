<?php
require_once "config.php";

$method = $_SERVER["REQUEST_METHOD"];

if ($method == "POST"){
    header("Content-Type: application/json");

    $form = json_decode(file_get_contents("php://input"));
    $username = $form->username;
    $password = $form->password;
    $email = $form->email;

    $usernameExp = "/^([a-zA-Z0-9]+ ?)+$/";
    $passwordExp = "/^[\w0-9]{5,}$/";
    $emailExp = "/^[\w\.-]+@([\w-]+\.)+[\w-]{2,4}$/";

    if (!preg_match($usernameExp, $username)) {
        print json_encode(["success" => false, "message" => "Invalid Username"]);
        exit();
    }
    if (preg_match($emailExp, $email) == false) {
        print json_encode(["success" => false, "message" => "Invalid Email"]);
        exit();
    }
    if (preg_match($passwordExp, $password) == false) {
        print json_encode(["success" => false, "message" => "Invalid Password"]);
        exit();
    }


    $stmt = $pdo->prepare("SELECT id from User WHERE username=?");
    $stmt->execute([$username]);
    $userInstance = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT id from User WHERE email=?");
    $stmt->execute([$email]);
    $emailInstance = $stmt->fetch();

    if ($userInstance!= false) {
        print json_encode(["success" => false, "message" => "Username is already in use"]);
        exit();
    }
    if ($emailInstance != false) {
        print json_encode(["success" => false, "message" => "Email is already in use"]);
        exit();
    }

    //encrypt Password
    $encryptedPassword = hash("sha256", $password);

    $stmt = $pdo->prepare("INSERT INTO User (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $encryptedPassword]);

    print json_encode(["success" => true, "message" => "Registered Successfully"]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
   <form class="form">
   <h1>Sign Up</h1>
   <div class="field-pair">
    <label>Username</label>
    <input id="username" type="text" name="username">
   </div>

   <div class="field-pair">
    <label>Email</label>
    <input id="email" type="email" name="email">
   </div>

   <div class="field-pair">
    <label>Password</label>
    <input id="password" type="password" name="password">
   </div>
   
   <button id="submit-btn">Register</button>
   <span>Already has an account? <a href="./index.php">Sing in right now!</a></span>
   </form> 

   <div id="modal" class="modal" style="display: none;">
        <div class="modal-body">
            <div>
                <h3 class="modal-tilte">Response</h3>
                <p class="modal-message"></p>
            </div>
            <button class="modal-button">Ok</button>
        </div>
   </div>

   <script>
        const submitBtn = document.querySelector("#submit-btn");
        submitBtn.addEventListener("click", (e) => {
            e.preventDefault();
            register();
        });

        async function register() {
            const usernameField = document.querySelector("#username");
            const passwordField = document.querySelector("#password");
            const emailField = document.querySelector("#email");

            const form = {
                username: usernameField.value,
                password: passwordField.value,
                email: emailField.value,
            }

            const res = await fetch("http://localhost/basicCrud/register.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(form),
            })
            
            const data = await res.json();
            console.log(data)

            if (data.success) {
                showModal(data.message, () => {
                   window.location.href = "http://localhost/basicCrud";
                })
            } else {
                showModal(data.message, () => {
                   const modal = document.querySelector(".modal");
                   modal.style.display = "none";
                })
            }

        }

        function showModal(message, callback) {
            const modal = document.querySelector(".modal");
            modal.style.display = "block";

            const messageNode = modal.querySelector(".modal-message");
            messageNode.innerText = message;

            const oldButton = modal.querySelector(".modal-button");
            const modalButton = oldButton.cloneNode(true);
            oldButton.parentNode.replaceChild(modalButton, oldButton);

            modalButton.addEventListener("click", (e) => callback(e));
        }

    </script>
</body>
</html>
<?php
include_once("config.php");

session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]) {
    header("location: home.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header("Content-Type: application/json");


    $form = json_decode(file_get_contents("php://input"));
    $identifier = $form->identifier;
    $password = $form->password;
    $encryptedPassword = hash("sha256", $password);

    $stmt = $pdo->prepare("SELECT id FROM User WHERE (username=? OR email=?) AND password=?");
    $stmt->execute([$identifier, $identifier, $encryptedPassword]);
    $result = $stmt->fetch();
    
    if ($stmt == false || !isset($result["id"])) {
        print json_encode(["success" => false, "message" => "Username or password are incorrect"]);
        exit();
    }

    $_SESSION["loggedin"] = true;
    $_SESSION["id"] = $result["id"];

    print json_encode(["success" => true, "query" => $_SESSION, "form" => $result]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="./css/login.css">
    <link rel="stylesheet" href="./css/register.css">
</head>
<body>
<main>
    <form class="form">
    <h1>Sign In</h1>
    <div class="field-pair">
        <label>Username or Email</label>
        <input id="identifier" type="text" name="identifier">
    </div>

    <div class="field-pair">
        <label>Password</label>
        <input id="password" type="password" name="password">
    </div>
    <button id="submit-button">Login</button>
    <span>Don't have an account? <a href="./register.php">Create a new account!</a></span>
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
</main>

   <script>
        const URL = "http://localhost/Todo-Heaven/";
        const submitButton = document.querySelector("#submit-button");
        submitButton.addEventListener("click", (e) => {
            e.preventDefault()
            const identifier = document.querySelector("#identifier").value;
            const password = document.querySelector("#password").value;

            login({identifier, password});
        });

        async function login(formData) {
            const res = await fetch(URL, {
                method: "POST",
                header: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(formData),
            });

            const data = await res.json();
            if (data.success) {
                window.location.href = URL + "home.php";
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
<?php
defined('BASE_PATH') or die("Permision Denied");

#----- Auth Functions-------

function getCurrentUserId()
{
    if (getLoggedInUser()) {
        $user = getLoggedInUser();
        return $user->id;
    }
}

function getLoggedInUser()
{
    return $_SESSION['login'] ?? null;
}

function isLoggedIn(): bool
{
    return $_SESSION['login'] ? true : false;
}

function getUserByEmail($email): object
{
    global $pdo;
    $sql = "SELECT * FROM users WHERE email=:email;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":email" => $email]);
    return $stmt->fetch(PDO::FETCH_OBJ);
}


function logout()
{
    session_unset();
}

function login($email, $password): bool
{
    $user = getUserByEmail($email);
    if (is_null($user)) {
        return false;
    }
    if ($user->email === $email && password_verify($password, $user->password)) {
        $_SESSION['login'] = $user;
        $user->image = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($user->email)));
        return true;
    }
    return false;

}

function register($userData)
{
    global $pdo;
    $auth_url = siteUrl('auth.php');
    $name = $userData['name'];
    $email = $userData['email'];
    $pass = $userData['password'];

    // Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        msgError("This is an invalid $email<br>
                      <a href='$auth_url'>Back</a>");
        die();
    }

    // Validate password strength

    $uppercase = preg_match('@[A-Z]@', $pass);
    $lowercase = preg_match('@[a-z]@', $pass);
    $number = preg_match('@[0-9]@', $pass);
    $special_chars = preg_match('@[^\w]@', $pass);

    if (!$uppercase || !$lowercase || !$number || !$special_chars || strlen($pass) < 8) {
        msgError("Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.<br>
                      <a href='$auth_url'>Back</a>");
        die();
    }
    $pass_hash = password_hash($pass, PASSWORD_BCRYPT);
    $sql = "insert into users  (name,email,password) values (:name,:email,:password);";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":name" => $name, ":email" => $email, ":password" => $pass_hash]);
    return $stmt->rowCount() ? true : false;
}


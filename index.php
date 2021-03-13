<!doctype html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Titkosítás</title>
</head>
<body>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <input type="text" placeholder="Titkosítani kívánt szöveg" name="cryptval">
        <input type="submit">
    </form>
    <a href="keys/crypto.key">Privát kulcs</a>
    <a href="keys/crypto_p.key">Publikus kulcs</a>
    <br>
    <?php
    include "crypto.php";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = $_POST["cryptval"];
        $encrypted = []; /* ebben a tömbben lesz eltárolva a titkosított (0), és a visszafejtett (1) adat */

        /* titkosítás */
        try {
            $encrypted[0] = crypto::Encrypt($data, true);
        } catch (Exception $e) {
            crypto::WriteException("titkosítás", $e->getMessage());
        }

        /* visszafejtés */
        try {
            $encrypted[1] = crypto::Decrypt($data, true);
        }catch (Exception $e) {
            crypto::WriteException("visszafejtés", $e->getMessage());
        }

        echo "<br>Titkosított érték: <i>" . (crypto::Encrypt($_POST["cryptval"], false)) . "</i><br><br>";
        echo "Visszafejtett titkosítás: <i>" . (crypto::Decrypt(crypto::Encrypt($_POST["cryptval"], false), false)) . "</i>";
    }
    ?>
</body>
</html>

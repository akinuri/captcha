<?php
require "helpers.php";
session_start();
deleteOldFlashData();
trackFlashData();
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $captcha = setCaptcha();
}
else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST as $key => $value) {
        if (is_string($value)) {
            $value = trim($value);
            if ($value === "") {
                $value = null;
            }
            $_POST[$key] = $value;
        }
    }
    $pageURL = "http://localhost/captcha/";
    $captchaResult = checkCaptcha();
    $errors = [];
    $name = $_POST["name"] ?? null;
    if (empty($name)) {
        $errors[] = "Name field must not be empty.";
    }
    if (strlen($name) < 2 || strlen($name) > 10) {
        $errors[] = "Name field length must be in the range of 2-10.";
    }
    if ($captchaResult["match"] == false) {
        $errors = array_merge($errors, $captchaResult["errors"]);
    }
    if ($errors) {
        saveFormData();
        addNotifications($errors);
        redirect($pageURL);
    }
    // process form data
    addNotification("The form is submitted.");
    redirect($pageURL);
}
?>
<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title>CAPTCHA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50">
    
    <div class="p-4 w-fit">
        
        <?php printNotifications(getNotifications(), "mb-4") ?>
        
        <form class="flex flex-col gap-2 w-fit" action="" method="post">
            
            <div class="flex gap-2">
                <span class="inline-flex items-center min-w-[80px]">Name</span>
                <input
                    type="text"
                    class="px-2 py-1 border rounded bg-white w-full"
                    name="name"
                    value="<?= getOldFormValue("name") ?>"
                    />
            </div>
            
            <div class="flex gap-2">
                <span class="inline-flex items-center w-[80px]">CAPTCHA</span>
                <div class="flex gap-1">
                    <img src="<?= $captcha["image"] ?>">
                    <input
                        type="text"
                        class="px-2 py-1 border rounded bg-white"
                        name="captcha"
                        >
                </div>
            </div>
            
            <button
                class="px-2 py-1 border rounded bg-slate-200 hover:bg-slate-300/70 active:bg-slate-300/90"
                >Submit</button>
            
        </form>
        
    </div>
    
</body>

</html>
<?php defined("PARENT") OR exit("Direct script access is not allowed."); ?><!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title>CAPTCHA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 h-screen flex justify-center items-center">
    
    <div class="pb-[10%]">
        <div class="p-4 w-fit h-fit">
            
            <?php printNotifications(getNotifications(), "mb-4") ?>
            
            <form class="flex flex-col gap-4 w-fit" action="" method="post">
                
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
                    <div class="flex gap-1 items-center">
                        <img src="<?= $captcha->getImage() ?>">
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
    </div>
    
</body>

</html>
<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title>CAPTCHA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 p-4">
    
    <form class="flex flex-col gap-2 w-fit" action="" method="post">
        
        <div class="flex gap-2">
            <span class="inline-flex items-center min-w-[80px]">Name</span>
            <input
                type="text"
                class="px-2 py-1 border rounded bg-white w-full"
                name="name"
                />
        </div>
        
        <div class="flex gap-2">
            <span class="inline-flex items-center w-[80px]">CAPTCHA</span>
            <div class="flex gap-1">
                <img src="">
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
    
</body>

</html>
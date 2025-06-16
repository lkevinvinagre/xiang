<!DOCTYPE html>
<html lang="pt-br" >
<head>
    @vite(['resources/css/app.css'])
    <title>Xiang-Accounts</title>
    <script src="//unpkg.com/alpinejs" defer></script>
     <script>
        if (!localStorage.getItem('token')) {
            window.location.href = '/';
        }
        else{
            window.authToken = localStorage.getItem('token');
            window.authUser = localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : null;
        }
    </script>
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] flex flex-col ">
    @include('fragments.change-themeBTN')
    <header class="w-full flex items-center justify-between p-4 bg-gradient-to-r from-blue-700 to-blue-900 dark:from-gray-900  dark:to-gray-700 ">
        <span><p class=" text-[#DDDD] dark:text-[#FFFFFF] p-4 ml-4 text-2xl">Xiang</p></span>
        <nav class="flex gap-4" x-data>
            <span class="text-white" x-text="window.authUser ? window.authUser.name : ''"></span>
            @include('fragments.logoutBTN')
        </nav>
    </header>
    @include('fragments.listaccounts')
</body>
</html>
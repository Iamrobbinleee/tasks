<!DOCTYPE html>
<html lang="en">
<meta name="csrf-token" content="{{ csrf_token() }}">
<head>
    @vite('resources/css/app.css')
</head>
<body>
    <div id="tasks"></div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    @vite('resources/js/tasks/app.js')
</body>
</html>
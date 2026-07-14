<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Access Denied</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="max-w-sm text-center">
        <p class="text-6xl mb-4">🔒</p>
        <h1 class="text-xl font-bold mb-2">Access Denied</h1>
        <p class="text-sm text-gray-500 mb-6">{{ $exception->getMessage() ?: 'Your account does not have permission to view this page.' }}</p>
        <a href="{{ route('dashboard') }}" class="inline-block px-5 py-2.5 bg-gray-900 text-white rounded text-xs font-bold uppercase">Back to Dashboard</a>
    </div>
</body>
</html>

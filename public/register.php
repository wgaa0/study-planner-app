<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="./assets/css/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Create an Account</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="mb-4 p-3 text-sm text-red-700 bg-red-100 border border-red-300 rounded">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div class="mb-4 p-3 text-sm text-green-700 bg-green-100 border border-green-300 rounded">
                Account created successfully! You can now log in.
            </div>
        <?php endif; ?>

        <form action="register_process.php" method="POST" class="space-y-4">
            <div>
                <label for="name" class="block text-gray-700">Name:</label>
                <input type="text" id="name" name="name" required
                       class="mt-1 p-2 w-full border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="email" class="block text-gray-700">Email:</label>
                <input type="email" id="email" name="email" required
                       class="mt-1 p-2 w-full border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="password" class="block text-gray-700">Password:</label>
                <input type="password" id="password" name="password" required
                       class="mt-1 p-2 w-full border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit"
                    class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">
                Register
            </button>
        </form>

        <p class="mt-4 text-center text-gray-600 text-sm">
            Already have an account?
            <a href="login.php" class="text-blue-500 hover:underline">Login here</a>.
        </p>
    </div>

</body>
</html>

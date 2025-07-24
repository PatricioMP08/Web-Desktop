<!-- resources/views/login.blade.php -->
@extends('layouts.app')

@section('content')
<div class="h-screen w-screen flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-700">
    <div class="bg-white p-8 rounded shadow-xl w-96">
        <h2 class="text-xl font-bold mb-4 text-center">Iniciar Sesión</h2>
        <form onsubmit="login(event)" class="space-y-4">
            <select id="loginUser" class="w-full border p-2 rounded" required>
                <option value="">Selecciona un usuario</option>
                <option value="admin">admin</option>
                <option value="user">user</option>
                <option value="user1">user1</option>
                <option value="user2">user2</option>
            </select>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Entrar</button>
        </form>
    </div>
</div>

<script>
    function login(e) {
        e.preventDefault();
        const selectedUser = document.getElementById('loginUser').value;
        if (!selectedUser) return alert("Selecciona un usuario");
        localStorage.setItem('user', selectedUser);
        window.location.href = '/desktop';
    }
</script>
@endsection

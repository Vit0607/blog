@extends('admin.layouts.app')

@section('title', 'Список постов Laravel 12')

@section('content')
    <div class="overflow-hidden rounded-2xl border border-white/10">
        <table class="w-full text-sm">
            <thead class="bg-white/5 text-gray-300">
                <tr>
                    <th class="text-left px-4 py-3">ID</th>
                    <th class="text-left px-4 py-3">Email</th>
                    <th class="text-left px-4 py-3">Имя</th>
                    <th class="text-left px-4 py-3">Роль</th>
                    <th class="text-left px-4 py-3">Создан</th>
                </tr>
            </thead>
            <tbody class="[&>tr:nth-child(even)]:bg-white/5">
                @foreach ($users as $user)
                    <tr>
                        <td class="px-4 py-3">{{ $user->id }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">{{ $user->name }}</td>
                        <td class="px-4 py-3">
                            <span class="badge">{{ $user->created_at->format('d.m.Y') }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <select class="border px-2 py-1 rounded-lg change-role" data-id="{{ $user->id }}">
                                <option class="bg-[#000]" value='user' @selected($user->role->value === 'user')>Пользователь</option>
                                <option class="bg-[#000]" value='admin' @selected($user->role->value == 'admin')>Админ</option>
                            </select>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-center gap-2">
        {{ $users->links() }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.change-role').forEach(select => {
                select.addEventListener('change', async (e) => {
                    const userId = e.target.dataset.id;
                    const newRole = e.target.value;

                    try {
                        const response = await fetch('/admin/users/' + userId + '/role', {
                            method: "PATCH",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                role: newRole
                            })
                        });


                        if (!response.ok) throw new Error('Ошибка при обновлении роли');

                        const json = await response.json();
                        console.log(json.message);
                    } catch (error) {
                        alert('Не удалось обновить роль');
                        console.error(error);
                    }
                })
            })
        })
    </script>

@endsection

<x-admin-layout>
    <div class="max-w-screen-xl mx-auto px-4 mb-8">
        <div class="my-3">
            @if(!$current_user->isUser())
                <a href="{{ route('users.create') }}" class="no-underline">Create User</a>
            @endif
        </div>
        <div class="overflow-auto mb-3">
            <table class="w-full bg-white shadow">
                <thead>
                    <tr>
                        <th class="px-4 py-2 font-semibold text-white bg-black">ID</th>
                        <th class="px-1 py-2 font-semibold text-white bg-black w-12 text-center">Image</th>
                        <th class="px-4 py-2 font-semibold text-white bg-black">Name</th>
                        <th class="px-4 py-2 font-semibold text-white bg-black">Role</th>
                        <th class="px-4 py-2 font-semibold text-white bg-black">Childrens</th>
                        <th class="px-4 py-2 font-semibold text-white bg-black">Action</th>
                    </tr>
                </thead>
                <tbody class="border border-t-0 border-solid border-gray-200">
                    @if(!empty($items) && count($items))
                        @foreach($items as $item)
                            <tr>
                                <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm">
                                    {{ $item->id }}
                                </td>
                                <td class="px-1 py-2 border-0 border-b border-solid border-gray-100 text-sm text-center">
                                    <img src="{{ $item->profile_photo_url }}" alt="avatar" class="inline-block w-10 h-10" />
                                </td>
                                <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm leading-tight">
                                    {{ $item->name }}<br><small>{{ $item->email }}</small>
                                </td>
                                <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm">
                                    {{ $item->role }}
                                </td>
                                <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm">
                                    @if(count($item?->children))
                                        @foreach($item->children as $child)
                                            <span>{{ $child->email }}{{ $loop->last ? '' : ',' }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm">
                                    @if($current_user->isSuperAdmin() || $current_user->isAdmin())
                                        <a href="{{ route('users.edit', $item->id) }}">Edit</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @if(empty($items) || !count($items))
                        <tr>
                            <td colspan="6" class="px-4 py-2 text-sm text-center">Nothing found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        {{ $items->onEachSide(2)->links() }}
    </div>
</x-admin-layout>
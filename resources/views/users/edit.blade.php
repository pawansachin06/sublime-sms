<x-admin-layout toastify="1" users="1">
    <div x-data="userEditData" class="max-w-screen-xl mx-auto px-4 my-4">
        <div class="mb-3">
            <a href="{{ route('users.index') }}" class="no-underline">Back to all Users</a>
        </div>
        <form action="{{ route('users.update', $item) }}" @submit.prevent="handleEditForm($el)" method="post">
            @method('PUT')
            <div class="flex flex-wrap -mx-1">
                <div class="w-full sm:w-6/12 px-1 mb-2">
                    <div class="flex flex-col">
                        <span>Name</span>
                        <input type="text" name="name" value="{{ $item->name }}" required class="rounded focus:border-primary-500 focus:ring-primary-400" />
                    </div>
                </div>
                <div class="w-full sm:w-6/12 px-1 mb-2">
                    <div class="flex flex-col">
                        <span>Surname</span>
                        <input type="text" name="lastname" value="{{ $item->lastname }}" class="rounded focus:border-primary-500 focus:ring-primary-400" />
                    </div>
                </div>
                <div class="w-full sm:w-6/12 px-1 mb-2">
                    <div class="flex flex-col">
                        <span>Company Name</span>
                        <input type="text" name="company" value="{{ $item->company }}" class="rounded focus:border-primary-500 focus:ring-primary-400" />
                    </div>
                </div>
                <div class="w-full sm:w-6/12 px-1 mb-2">
                    <div class="flex flex-col">
                        <span>Email</span>
                        <input type="email" name="email" value="{{ $item->email }}" required class="rounded focus:border-primary-500 focus:ring-primary-400" />
                    </div>
                </div>
                <div class="w-full sm:w-6/12 px-1 mb-2">
                    <div class="flex flex-col">
                        <span>Phone</span>
                        <input type="number" name="phone" value="{{ $item->phone }}" required class="rounded focus:border-primary-500 focus:ring-primary-400" />
                    </div>
                </div>
                <div class="w-full sm:w-6/12 px-1 mb-2">
                    <div class="flex flex-col">
                        <span>Username</span>
                        <input type="text" name="username" value="{{ $item->username }}" required class="rounded focus:border-primary-500 focus:ring-primary-400" />
                    </div>
                </div>
                @if( ($currentUser->isSuperAdmin() || $currentUser->isAdmin()) )
                    <div class="w-full sm:w-6/12 px-1 mb-2">
                        <div @click.away="isParentsDropdownOpen = false" class="flex flex-col">
                            <span><span x-show="isLoadingSelectedParents">Loading Parents...</span><span x-cloak x-show="!isLoadingSelectedParents">Parents</span></span>
                            <div class="relative">
                                <input type="text" x-model="parentsKeyword" @focus="handleParentsKeywordFocus()" @input.debounce.500ms="handleParentsKeywordChange()" @keydown.enter.prevent.stop="handleFormEnter()" spellcheck="false" :disabled="isLoadingSelectedParents" :placeholder="isLoadingSelectedParents ? 'Loading Parents...' : 'Type Parent User...'" class="py-2 pl-8 font-title w-full text-sm rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400" />
                                <span class="absolute left-0 top-0 bottom-0 pointer-events-none w-8 inline-flex items-center justify-center">
                                    <span x-show="isLoadingParents" class="absolute top-0 left-0 bottom-0 right-0 inline-flex items-center justify-center text-primary-500" x-transition.opacity><x-loader class="w-4 h-4" /></span>
                                    <svg x-show="!isLoadingParents" x-cloak x-transition.opacity xmlns="http://www.w3.org/2000/svg" width="13" height="13" class="w-3 h-3" fill="none"><path fill="#515151" d="M10 5.067C10 2.273 7.757 0 5 0S0 2.273 0 5.067c0 2.793 2.243 5.066 5 5.066s5-2.273 5-5.066Zm-5 3.8c-2.068 0-3.75-1.705-3.75-3.8 0-2.096 1.682-3.8 3.75-3.8s3.75 1.704 3.75 3.8c0 2.095-1.682 3.8-3.75 3.8Zm7.317 3.614a.619.619 0 0 1-.884 0L8.507 9.516c.326-.265.623-.565.884-.896l2.926 2.966a.64.64 0 0 1 0 .895Z"/></svg>
                                </span>
                            </div>
                            <div class="relative mb-2">
                                <div x-show="isParentsDropdownOpen" x-cloak x-transition.scale.top class="absolute min-h-12 max-h-40 w-full max-w-sm overflow-y-auto bg-white rounded border border-solid border-gray-200 shadow-lg">
                                    <div x-show="isLoadingParents" class="absolute text-center py-2 top-0 bottom-0 left-0 right-0 backdrop-blur-sm">
                                        <x-loader />
                                    </div>
                                    <div x-show="!isLoadingParents && !parents?.length" class="px-3 py-2 text-gray-400">
                                        Nothing found
                                    </div>
                                    <div x-show="parents?.length">
                                        <template x-for="parent in parents" :key="parent.id">
                                            <button type="button" @click="handleParentsDropdownClick(parent)" class="w-full px-3 py-2 leading-tight inline-flex items-center justify-between truncate border-0 bg-transparent hover:bg-gray-100">
                                                <span x-text="parent.name + ' ' + parent?.lastname + ' ' + parent?.company" class="grow text-left truncate"></span>
                                                <span x-show="parent?.added" class="inline-flex flex-none items-center text-gray-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="w-4 h-4" fill="currentColor" viewBox="0 -960 960 960">
                                                        <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z" />
                                                    </svg>
                                                </span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="selectedParent in selectedParents" :key="selectedParent.id">
                                    <div class="inline-flex max-w-56 truncate rounded pl-3 text-sm leading-tight text-gray-600 border border-solid border-gray-200 bg-gray-50">
                                        <input type="hidden" name="parent_id[]" :value="selectedParent.id" />
                                        <span class="self-center truncate" x-text="selectedParent.name + ' ' + selectedParent?.company"></span>
                                        <button type="button" @click="handleRemoveSelectedParent(selectedParent)" title="Clear" class="px-2 py-2 text-gray-600 border-0 bg-transparent">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="w-4 h-4" fill="currentColor" viewBox="0 -960 960 960">
                                                <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                @endif




                {{--
                @if( ($currentUser->isSuperAdmin() || $currentUser->isAdmin()) )
                    <div class="w-full sm:w-6/12 px-1 mb-2">
                        <div @click.away="isChildrenDropdownOpen = false" class="flex flex-col">
                            <span><span x-show="isLoadingSelectedChildren">Loading Children...</span><span x-cloak x-show="!isLoadingSelectedChildren">Childrens</span></span>
                            <div class="relative">
                                <input type="text" x-model="childrenKeyword" @focus="handleChildrenKeywordFocus()" @input.debounce.500ms="handleChildrenKeywordChange()" @keydown.enter.prevent.stop="handleFormEnter()" spellcheck="false" :disabled="isLoadingSelectedChildren" :placeholder="isLoadingSelectedChildren ? 'Loading children...' : 'Type Children User...'" class="py-2 pl-8 font-title w-full text-sm rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400" />
                                <span class="absolute left-0 top-0 bottom-0 pointer-events-none w-8 inline-flex items-center justify-center">
                                    <span x-show="isLoadingChildren" class="absolute top-0 left-0 bottom-0 right-0 inline-flex items-center justify-center text-primary-500" x-transition.opacity><x-loader class="w-4 h-4" /></span>
                                    <svg x-show="!isLoadingChildren" x-cloak x-transition.opacity xmlns="http://www.w3.org/2000/svg" width="13" height="13" class="w-3 h-3" fill="none"><path fill="#515151" d="M10 5.067C10 2.273 7.757 0 5 0S0 2.273 0 5.067c0 2.793 2.243 5.066 5 5.066s5-2.273 5-5.066Zm-5 3.8c-2.068 0-3.75-1.705-3.75-3.8 0-2.096 1.682-3.8 3.75-3.8s3.75 1.704 3.75 3.8c0 2.095-1.682 3.8-3.75 3.8Zm7.317 3.614a.619.619 0 0 1-.884 0L8.507 9.516c.326-.265.623-.565.884-.896l2.926 2.966a.64.64 0 0 1 0 .895Z"/></svg>
                                </span>
                            </div>
                            <div class="relative mb-2">
                                <div x-show="isChildrenDropdownOpen" x-cloak x-transition.scale.top class="absolute min-h-12 max-h-40 w-full max-w-sm overflow-y-auto bg-white rounded border border-solid border-gray-200 shadow-lg">
                                    <div x-show="isLoadingChildren" class="absolute text-center py-2 top-0 bottom-0 left-0 right-0 backdrop-blur-sm">
                                        <x-loader />
                                    </div>
                                    <div x-show="!isLoadingChildren && !children?.length" class="px-3 py-2 text-gray-400">
                                        Nothing found
                                    </div>
                                    <div x-show="children?.length">
                                        <template x-for="child in children" :key="child.id">
                                            <button type="button" @click="handleChildrenDropdownClick(child)" class="w-full px-3 py-2 leading-tight inline-flex items-center justify-between truncate border-0 bg-transparent hover:bg-gray-100">
                                                <span x-text="child.name + ' ' + child?.lastname + ' ' + child?.company" class="grow text-left truncate"></span>
                                                <span x-show="child?.added" class="inline-flex flex-none items-center text-gray-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="w-4 h-4" fill="currentColor" viewBox="0 -960 960 960">
                                                        <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z" />
                                                    </svg>
                                                </span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="selectedChild in selectedChildren" :key="selectedChild.id">
                                    <div class="inline-flex max-w-56 truncate rounded pl-3 text-sm leading-tight text-gray-600 border border-solid border-gray-200 bg-gray-50">
                                        <input type="hidden" name="children_id[]" :value="selectedChild.id" />
                                        <span class="self-center truncate" x-text="selectedChild.name + ' ' + selectedChild?.company"></span>
                                        <button type="button" @click="handleRemoveSelectedChild(selectedChild)" title="Clear" class="px-2 py-2 text-gray-600 border-0 bg-transparent">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="w-4 h-4" fill="currentColor" viewBox="0 -960 960 960">
                                                <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                @endif
                --}}


                <div class="w-full px-1">
                    <div x-show="saveStatus?.length" x-cloak class="font-semibold w-full mb-2" x-text="saveStatus"></div>
                    <div class="flex flex-wrap gap-2">
                        <x-button type="submit" x-bind:disabled="isSaving">Update User</x-button>
                        <x-danger-button type="button" data-bs-toggle="modal" data-bs-target="#deleteUserModal">Delete</x-danger-button>
                    </div>
                </div>
            </div>
        </form>


        <!-- modal start -->
        <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <form @submit.prevent="handleUserDeleteForm($el)" action="{{ route('users.destroy', $item->id) }}" class="modal-content">
                    @method('DELETE')
                    <div class="modal-body">
                        <div class="mb-4">
                            <h4 class="modal-title text-lg text-center font-title font-bold" id="deleteUserModalLabel">
                                Are you sure you want to DELETE?
                            </h4>
                            <p class="text-center">This action can not be undone.</p>
                        </div>
                        <div class="flex justify-center">
                            <button type="submit" :disabled="isDeletingUser" class="inline-flex gap-3 font-title font-semibold rounded px-4 py-3 text-sm justify-center items-center leading-none no-underline border border-solid border-primary-500 text-white bg-primary-500 disabled:opacity-50">
                                <span x-show="isDeletingUser">Deleting...</span>
                                <span x-show="!isDeletingUser">Delete Now</span>
                            </button>
                        </div>
                        <div class="absolute top-0 right-0 max-w-72">
                            <button type="button" title="Close" data-bs-dismiss="modal" aria-label="Close" class="absolute -top-2 -right-1 w-7 h-7 px-0 py-0 border text-gray-500 border-solid border-gray-400 inline-flex items-center justify-center rounded-full bg-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="w-5 h-5" fill="currentColor" viewBox="0 -960 960 960">
                                    <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- modal end -->

        <br><br><br><br><br><br><br>
    </div>
    <script type="text/javascript">
        var USER_EDIT_ID = "{{ $item->id }}";
        var ROUTE_USERS_INDEX = "{{ route('users.index') }}";
    </script>
</x-admin-layout>
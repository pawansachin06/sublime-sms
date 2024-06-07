<x-admin-layout contacts="1" toastify="1">
    <div x-data="contacts" class="max-w-screen-xl mx-auto px-4 mb-8">

        <div class="flex flex-wrap justify-between gap-3 mt-4 mb-3">
            <div class="inline-flex flex-wrap gap-3">
                <x-header.nav-btn :href="route('dashboard')">ACTIVITY</x-header.nav-btn>
                <x-header.nav-btn :href="route('contact-groups.index')">GROUPS</x-header.nav-btn>
                <x-header.nav-btn active="1" :href="route('contacts.index')">CONTACTS</x-header.nav-btn>
                <x-header.nav-btn :href="route('templates.index')">TEMPLATES</x-header.nav-btn>
                <x-header.add-btn data-bs-toggle="modal" data-bs-target="#newContactsModal">NEW CONTACT</x-header.add-btn>
            </div>
            <div class="">
                <select name="" title="Company" class="font-title py-2 leading-tight rounded border-gray-400 focus:border-primary-500 focus:ring-primary-400">
                    <option>BGCG Fixed Income Solutions</option>
                    <option>BGCG Fixed Income Solutions</option>
                    <option>BGCG Fixed Income Solutions</option>
                    <option>BGCG Fixed Income Solutions</option>
                    <option>BGCG Fixed Income Solutions</option>
                </select>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-2 my-3">
            <div class="flex-none flex flex-wrap gap-2">
                <div class="inline-block relative">
                    <input type="text" x-model="searchKeywordName" @input.debounce.750ms="handleSearchKeywordName()" placeholder="User" spellcheck="false" class="py-2 pl-8 font-title text-sm rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400" />
                    <button type="button" x-show="showSearchKeywordNameClearBtn" x-cloak x-transition @click="handleClearSearchKeywordName()" title="Clear" class="absolute end-0 top-0 bottom-0 inline-flex items-center justify-center py-2 border-0 text-gray-500 bg-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="w-5 h-5" fill="currentColor" viewBox="0 -960 960 960">
                            <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z" />
                        </svg>
                    </button>
                    <span class="absolute w-8 left-0 top-0 bottom-0 pointer-events-none inline-flex items-center justify-center">
                        <svg x-show="!isLoadingContacts" x-transition.opacity xmlns="http://www.w3.org/2000/svg" width="13" height="13" class="" fill="none"><path fill="#515151" d="M10 5.067C10 2.273 7.757 0 5 0S0 2.273 0 5.067c0 2.793 2.243 5.066 5 5.066s5-2.273 5-5.066Zm-5 3.8c-2.068 0-3.75-1.705-3.75-3.8 0-2.096 1.682-3.8 3.75-3.8s3.75 1.704 3.75 3.8c0 2.095-1.682 3.8-3.75 3.8Zm7.317 3.614a.619.619 0 0 1-.884 0L8.507 9.516c.326-.265.623-.565.884-.896l2.926 2.966a.64.64 0 0 1 0 .895Z"/></svg>
                        <span x-show="isLoadingContacts" x-cloak x-transition.opacity class="absolute top-0 left-0 bottom-0 right-0 inline-flex items-center justify-center text-gray-400">
                            <x-loader class="w-4 h-4" />
                        </span>
                    </span>
                </div>
                <select name="" class="py-2 font-title text-sm rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400">
                    <option value="">Alert</option>
                    <option value="">Failed</option>
                </select>
                <div class="inline-block relative">
                    <input type="text" x-model="searchKeywordPhone" @input.debounce.750ms="handleSearchKeywordPhone()" placeholder="Phone Number" spellcheck="false" class="py-2 pl-8 font-title text-sm rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400" />
                    <button type="button" x-show="showSearchKeywordPhoneClearBtn" x-cloak x-transition @click="handleClearSearchKeywordPhone()" title="Clear" class="absolute end-0 top-0 bottom-0 inline-flex items-center justify-center py-2 border-0 text-gray-500 bg-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="w-5 h-5" fill="currentColor" viewBox="0 -960 960 960">
                            <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z" />
                        </svg>
                    </button>
                    <span class="absolute w-8 left-0 top-0 bottom-0 pointer-events-none inline-flex items-center justify-center">
                        <svg x-show="!isLoadingContacts" x-transition.opacity xmlns="http://www.w3.org/2000/svg" width="13" height="13" class="" fill="none"><path fill="#515151" d="M10 5.067C10 2.273 7.757 0 5 0S0 2.273 0 5.067c0 2.793 2.243 5.066 5 5.066s5-2.273 5-5.066Zm-5 3.8c-2.068 0-3.75-1.705-3.75-3.8 0-2.096 1.682-3.8 3.75-3.8s3.75 1.704 3.75 3.8c0 2.095-1.682 3.8-3.75 3.8Zm7.317 3.614a.619.619 0 0 1-.884 0L8.507 9.516c.326-.265.623-.565.884-.896l2.926 2.966a.64.64 0 0 1 0 .895Z"/></svg>
                        <span x-show="isLoadingContacts" x-cloak x-transition.opacity class="absolute top-0 left-0 bottom-0 right-0 inline-flex items-center justify-center text-gray-400">
                            <x-loader class="w-4 h-4" />
                        </span>
                    </span>
                </div>
            </div>
            <div class="">
                <button type="button" class="inline-flex gap-2 font-title text-sm font-semibold rounded px-4 py-2 justify-center items-center leading-none no-underline border border-solid border-primary-500 text-white bg-primary-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24" height="24" fill="currentColor" viewBox="0 -960 960 960"><path d="M440-367v127q0 17 11.5 28.5T480-200q17 0 28.5-11.5T520-240v-127l36 36q6 6 13.5 9t15 2.5q7.5-.5 14.5-3.5t13-9q11-12 11.5-28T612-388L508-492q-6-6-13-8.5t-15-2.5q-8 0-15 2.5t-13 8.5L348-388q-12 12-11.5 28t12.5 28q12 11 28 11.5t28-11.5l35-35ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h287q16 0 30.5 6t25.5 17l194 194q11 11 17 25.5t6 30.5v447q0 33-23.5 56.5T720-80H240Zm280-560v-160H240v640h480v-440H560q-17 0-28.5-11.5T520-640ZM240-800v200-200 640-640Z"/></svg>
                    <span>IMPORT CONTACTS</span>
                </button>
            </div>
        </div>

        <div class="relative pb-2">
            <div class="overflow-auto mb-3">
                <table class="w-full bg-white shadow">
                    <thead>
                        <tr>
                            <th class="text-white bg-black"></th>
                            <th class="px-4 py-2 font-semibold text-white bg-black">User</th>
                            <th class="px-4 py-2 font-semibold text-white bg-black">Company</th>
                            <th class="px-4 py-2 font-semibold text-white bg-black">Phone Number</th>
                            <th class="px-4 py-2 font-semibold text-white bg-black">Alert</th>
                            <th class="px-4 py-2 font-semibold text-white bg-black">Group(s)</th>
                            <th class="px-4 py-2 font-semibold text-white bg-black min-w-52"></th>
                        </tr>
                    </thead>
                    <tbody class="border border-t-0 border-solid border-gray-200">
                        <template x-for="contact in contacts" :key="contact.id">
                            <tr :class="[(currentDeleteContact?.id && currentDeleteContact.id == contact.id) ? 'z-50' : '']" class="group/tr bg-white hover:bg-gray-200 relative">
                                <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm"></td>
                                <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm">
                                    <span x-text="contact.name"></span>
                                    <span x-text="contact.lastname"></span>
                                </td>
                                <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm">
                                    <span x-text="contact.company"></span>
                                </td>
                                <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm">
                                    <span x-text="contact.phone"></span>
                                </td>
                                <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm">
                                </td>
                                <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm">
                                    <div x-show="contact.groups?.length" class="flex flex-wrap gap-2">
                                        <template x-for="grp in contact.groups" :key="grp.id">
                                            <div class="inline-flex max-w-56 truncate rounded px-3 py-2 text-sm leading-none text-gray-500 border border-solid border-gray-300 bg-gray-200 group-hover/tr:bg-white">
                                                <span class="self-center truncate" x-text="grp.name"></span>
                                            </div>
                                        </template>
                                    </div>
                                </td>
                                <td class="pr-6 py-2 border-0 border-b border-solid border-gray-100 text-sm">
                                    <div class="flex gap-2 items-center justify-end">
                                        <div x-show="(currentDeleteContact?.id && currentDeleteContact.id == contact.id)" class="flex gap-3 justify-end">
                                            <div class="font-semibold max-w-72">
                                                <span x-show="!isDeletingContact">Are you sure you want to delete this contact?</span>
                                                <span x-show="isDeletingContact">Please wait, deleting this contact...</span>
                                            </div>
                                            <div x-show="isDeletingContact" x-cloak class="min-w-16 text-center text-primary-500">
                                                <x-loader class="w-4 h-4" />
                                            </div>
                                            <div x-show="!isDeletingContact" x-cloak class="flex min-w-16">
                                                <button type="button" @click="handleCancelDeleteContact()" class="flex-none px-2 py-0 border-0 bg-transparent font-semibold underline underline-offset-2">No</button>
                                                <button type="button" @click="handleConfirmedDeleteContact(contact)" class="flex-none px-1 py-0 border-0 bg-transparent font-semibold underline underline-offset-2 text-primary-500">Yes</button>
                                            </div>
                                        </div>
                                        <div :class="[(currentDeleteContact?.id && currentDeleteContact.id == contact.id) ? 'hidden':'']" class="flex gap-2 items-center">
                                            <div class="relative group py-1">
                                                <button type="button" @click="handleEditContactBtn(contact)" class="w-6 h-6 px-0 py-0 inline-flex items-center justify-center rounded-full border-0 text-white bg-gray-400 group-hover:bg-primary-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="currentColor" viewBox="0 0 512 512"><path d="m362.7 19.3-48.4 48.4 130 130 48.4-48.4c25-25 25-65.5 0-90.5l-39.4-39.5c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2c-2.5 8.5-.2 17.6 6 23.8s15.3 8.5 23.7 6.1L151 475.7c14.1-4.2 27-11.8 37.4-22.2l233.3-233.2-130-130z"/></svg>
                                                </button>
                                                <div class="pointer-events-none select-none hidden z-1 group-hover:inline-flex left-7 top-0 bottom-0 px-2 pb-1 bg-gray-200 absolute font-medium text-sm leading-none text-primary-500 items-center justify-center">
                                                    <span>Edit</span>
                                                </div>
                                            </div>
                                            <div class="relative group py-1">
                                                <div class="pointer-events-none select-none hidden group-hover:inline-flex right-7 top-0 bottom-0 px-2 pb-1 bg-gray-200 absolute font-medium text-sm leading-none text-primary-500 items-center justify-center">
                                                    <span>Delete</span>
                                                </div>
                                                <button @click="handleDeleteContact(contact)" type="button" class="w-6 h-6 px-0 py-0 inline-flex items-center justify-center rounded-full border-0 text-white bg-gray-400 group-hover:bg-primary-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="px-2">
                <p x-cloak class="text-sm mb-1">Page <span x-text="page"></span> of <span x-text="totalPages"></span>, Total <span x-text="totalContacts"></span></p>
                <div x-cloak x-show="totalPages > 1">
                    <div class="flex flex-wrap gap-2">
                        <template x-if="page > 1">
                            <button @click="loadContacts(page - 1)" class="px-3 py-2 leading-none rounded border border-solid border-primary-500 bg-primary-500 text-white">Prev</button>
                        </template>
                        <template x-if="page > 1">
                            <template x-for="prevPage in prevPages">
                                <button @click="loadContacts(prevPage)" class="px-3 py-2 leading-none rounded border border-solid border-primary-500 bg-primary-500 text-white" x-text="prevPage"></button>
                            </template>
                        </template>
                        <button x-text="page" class="px-3 py-2 leading-none rounded border border-solid border-gray-500 bg-gray-100 disabled"></button>
                        <template x-for="nextPage in nextPages">
                            <button @click="loadContacts(nextPage)" class="px-3 py-2 leading-none rounded border border-solid border-primary-500 bg-primary-500 text-white" x-text="nextPage"></button>
                        </template>
                        <template x-if="page != totalPages">
                            <button @click="loadContacts(page + 1)" class="px-3 py-2 leading-none rounded border border-solid border-primary-500 bg-primary-500 text-white">Next</button>
                        </template>
                    </div>
                </div>
            </div>
            <div x-show="isLoadingContacts" class="absolute py-2 top-0 bottom-0 left-0 right-0 min-h-28 text-center backdrop-blur-xs">
                <x-loader class="w-8 h-8 text-white" />
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="newContactsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newContactsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <form @submit.prevent="handleNewContactForm($el)" action="{{ route('contacts.store') }}" class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-lg mt-3 font-title font-semibold" id="newContactsModalLabel">
                            <span x-show="isSavingContact">Saving Contact...</span>
                            <span x-show="!isSavingContact"><span x-show="modalInputId?.length">Update</span><span x-show="!modalInputId?.length">New</span> Contact</span>
                        </h4>
                        <button type="button" title="Close" @click="handleCloseModalBtn()" data-not-bs-dismiss="modal" aria-label="Close" class="absolute -top-2 -right-1 w-7 h-7 px-0 py-0 border text-gray-500 border-solid border-gray-400 inline-flex items-center justify-center rounded-full bg-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="w-5 h-5" fill="currentColor" viewBox="0 -960 960 960">
                                <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z" />
                            </svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="flex flex-wrap -mx-2 mb-2">
                            <div class="w-full sm:w-6/12 lg:w-4/12 px-2 mb-3">
                                <div>First Name*</div>
                                <input type="text" name="name" x-model="modalInputName" id="new-contact-modal-first-input" required class="w-full py-2 rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400" />
                            </div>
                            <div class="w-full sm:w-6/12 lg:w-4/12 px-2 mb-3">
                                <div>Last Name</div>
                                <input type="text" name="lastname" x-model="modalInputLastname" class="w-full py-2 rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400" />
                            </div>
                            <div class="w-full sm:w-6/12 lg:w-4/12 px-2 mb-3">
                                <div>Ph. Number*</div>
                                <input type="number" name="phone" x-model="modalInputPhone" required class="form-input-no-arrows w-full py-2 rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400" />
                            </div>
                            <div class="w-full sm:w-6/12 lg:w-4/12 px-2 mb-3">
                                <div>Country</div>
                                <select name="country" x-model="modalInputCountry" required class="w-full py-2 rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $cntry_key => $cntry_value)
                                        <option value="{{ $cntry_key }}">{{ $cntry_value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full lg:w-8/12 px-2 mb-3">
                                <div>Company</div>
                                <input type="text" name="company" x-model="modalInputCompany" class="w-full py-2 rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400" />
                            </div>
                            <div class="w-full px-2 mb-3">
                                <div>Groups</div>
                                <div @click.away="isContactGroupDropdownOpen = false">
                                    <div class="relative">
                                        <input type="text" x-model="contactGroupKeyword" @focus="handleContactGroupKeywordFocus()" @input.debounce.750ms="handleContactGroupKeywordChange()" spellcheck="false" placeholder="Search for a group" class="py-2 pl-8 font-title w-full text-sm rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400" />
                                        <span class="absolute left-0 top-0 bottom-0 pointer-events-none w-8 inline-flex items-center justify-center">
                                            <span x-show="isLoadingContactGroups" class="absolute top-0 left-0 bottom-0 right-0 inline-flex items-center justify-center text-primary-500" x-transition.opacity><x-loader class="w-4 h-4" /></span>
                                            <svg xmlns="http://www.w3.org/2000/svg" x-show="!isLoadingContactGroups" x-transition.opacity width="13" height="13" class="w-3 h-3" fill="none"><path fill="#515151" d="M10 5.067C10 2.273 7.757 0 5 0S0 2.273 0 5.067c0 2.793 2.243 5.066 5 5.066s5-2.273 5-5.066Zm-5 3.8c-2.068 0-3.75-1.705-3.75-3.8 0-2.096 1.682-3.8 3.75-3.8s3.75 1.704 3.75 3.8c0 2.095-1.682 3.8-3.75 3.8Zm7.317 3.614a.619.619 0 0 1-.884 0L8.507 9.516c.326-.265.623-.565.884-.896l2.926 2.966a.64.64 0 0 1 0 .895Z"/></svg>
                                        </span>
                                    </div>
                                    <div class="relative mb-2">
                                        <div x-show="isContactGroupDropdownOpen" x-transition.scale.top class="absolute min-h-12 w-full max-w-sm overflow-y-auto bg-white rounded border border-solid border-gray-200 shadow-lg">
                                            <div x-show="isLoadingContactGroups" class="absolute text-center py-2 top-0 bottom-0 left-0 right-0 backdrop-blur-sm">
                                                <x-loader />
                                            </div>
                                            <div x-show="!isLoadingContactGroups && !contactGroups?.length" class="px-3 py-2">
                                                Nothing found, create groups to add contacts
                                            </div>
                                            <div x-show="contactGroups?.length">
                                                <template x-for="contactGroup in contactGroups" :key="contactGroup.id">
                                                    <button type="button" @click="handleContactGroupDropdownClick(contactGroup)" class="w-full px-3 py-2 leading-tight inline-flex items-center justify-between truncate border-0 bg-transparent hover:bg-gray-100">
                                                        <span x-text="contactGroup.name" class="grow text-left truncate"></span>
                                                        <span x-show="contactGroup?.added" class="inline-flex flex-none items-center text-gray-400">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="w-4 h-4" fill="currentColor" viewBox="0 -960 960 960">
                                                                <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z" />
                                                            </svg>
                                                        </span>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="selectedContactGroup in selectedContactGroups" :key="selectedContactGroup.id">
                                        <div class="inline-flex max-w-56 truncate rounded pl-3 text-sm leading-tight text-gray-600 border border-solid border-gray-200 bg-gray-100">
                                            <input type="hidden" name="contact_group_id[]" :value="selectedContactGroup.id" />
                                            <span class="self-center truncate" x-text="selectedContactGroup.name"></span>
                                            <button type="button" @click="handleRemoveSelectedContactGroup(selectedContactGroup)" title="Clear" class="px-2 py-2 text-gray-600 border-0 bg-transparent">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="w-4 h-4" fill="currentColor" viewBox="0 -960 960 960">
                                                    <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="w-full px-2">
                                <div>Comments</div>
                                <textarea rows="4" name="comments" x-model="modalInputComments" class="w-full py-2 rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400"></textarea>
                                <input type="hidden" name="id" x-model="modalInputId" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="flex justify-end">
                            <button type="submit" :disabled="isSavingContact" class="inline-flex gap-2 font-title font-semibold rounded px-4 py-3 text-sm justify-center items-center leading-none no-underline border border-solid border-primary-500 text-white bg-primary-500 disabled:opacity-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none"><path fill="#fff" fill-rule="evenodd" d="M7.2 0H4.8v4.8H0v2.4h4.8V12h2.4V7.2H12V4.8H7.2V0Z" clip-rule="evenodd"/></svg>
                                <span x-show="isSavingContact">SAVING CONTACT...</span>
                                <span x-show="!isSavingContact"><span x-show="modalInputId?.length">UPDATE</span><span x-show="!modalInputId?.length">CREATE</span> CONTACT</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <span x-show="(currentDeleteContact?.id)" data-show="isOpenNewContactGroupForm || isOpenEditContactGroupForm || isOpenDeleteContactGroupForm" x-cloak x-transition.opacity class="fixed top-0 right-0 bottom-0 left-0 bg-black/50"></span>

    </div>
    <x-slot name="headScript">
        <script type="text/javascript">
            var CONTACT_GROUPS_ROUTE_INDEX = "{{ route('contact-groups.index') }}";
            var CONTACTS_ROUTE_INDEX = "{{ route('contacts.index') }}";
            var CONTACTS_ROUTE_DELETE = "{{ route('contacts.delete') }}";
        </script>
    </x-slot>
</x-admin-layout>

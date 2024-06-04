<x-admin-layout>
    <div class="max-w-screen-xl mx-auto px-4 mb-8">

        <div class="flex flex-wrap justify-between gap-3 mt-4 mb-3">
            <div class="inline-flex flex-wrap gap-3">
                <x-header.nav-btn :href="route('dashboard')">ACTIVITY</x-header.nav-btn>
                <x-header.nav-btn active="1" :href="route('contact-groups.index')">GROUPS</x-header.nav-btn>
                <x-header.nav-btn :href="route('contacts.index')">CONTACTS</x-header.nav-btn>
                <x-header.nav-btn :href="route('templates.index')">TEMPLATES</x-header.nav-btn>
                <x-header.add-btn>NEW GROUP</x-header.add-btn>
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

        <div class="flex flex-wrap bg-white border border-solid border-gray-100 shadow">
            <div class="w-full md:w-4/12 lg:w-3/12 flex flex-col border-solid border-0 border-r border-gray-100">
                <div class="px-4 py-3 flex-none border border-solid border-0 border-b border-gray-100">
                    <div class="text-lg font-semibold font-title mb-2">Groups</div>
                    <div class="block relative">
                        <input type="text" placeholder="Search groups" class="py-2 pl-8 w-full font-title text-sm rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400" />
                        <span class="absolute left-0 top-0 bottom-0 pointer-events-none px-3 inline-flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" class="" fill="none"><path fill="#515151" d="M10 5.067C10 2.273 7.757 0 5 0S0 2.273 0 5.067c0 2.793 2.243 5.066 5 5.066s5-2.273 5-5.066Zm-5 3.8c-2.068 0-3.75-1.705-3.75-3.8 0-2.096 1.682-3.8 3.75-3.8s3.75 1.704 3.75 3.8c0 2.095-1.682 3.8-3.75 3.8Zm7.317 3.614a.619.619 0 0 1-.884 0L8.507 9.516c.326-.265.623-.565.884-.896l2.926 2.966a.64.64 0 0 1 0 .895Z"/></svg>
                        </span>
                    </div>
                </div>
                <div class="relative min-h-40 h-full grow">
                    <div class="absolute w-full h-full overflow-y-auto">
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                        <button type="button" class="px-2 border-0 flex font-normal text-left w-full bg-transparent hover:bg-gray-50">
                            <span class="inline-block truncate px-3 py-2 grow border border-solid border-0 border-b border-gray-100">Group Name alkdf alksjdflaskjd falskdjf </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-8/12 lg:w-9/12 ">
                <div class="flex flex-wrap gap-3 relative">
                    <div class="px-4 py-4 grow">
                        <div class="text-lg font-title font-semibold mb-2">ANZBC Account</div>
                        <div class="inline-block relative">
                            <input type="text" placeholder="Search for contact in group" class="py-2 pl-8 w-60 max-w-full font-title text-sm rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400" />
                            <span class="absolute left-0 top-0 bottom-0 pointer-events-none px-3 inline-flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" class="" fill="none"><path fill="#515151" d="M10 5.067C10 2.273 7.757 0 5 0S0 2.273 0 5.067c0 2.793 2.243 5.066 5 5.066s5-2.273 5-5.066Zm-5 3.8c-2.068 0-3.75-1.705-3.75-3.8 0-2.096 1.682-3.8 3.75-3.8s3.75 1.704 3.75 3.8c0 2.095-1.682 3.8-3.75 3.8Zm7.317 3.614a.619.619 0 0 1-.884 0L8.507 9.516c.326-.265.623-.565.884-.896l2.926 2.966a.64.64 0 0 1 0 .895Z"/></svg>
                            </span>
                        </div>
                    </div>
                    <div class="px-4 py-4 self-end text-sm text-gray-400 font-normal flex-none text-right">
                        <div>Created by Grant Kull</div>
                        <div>Created on 22nd of May 2024</div>
                        <div>BGCG Fixed Income Solutions</div>
                    </div>
                    <button type="button" title="Close" class="absolute -top-2 -right-2 w-7 h-7 px-0 py-0 border text-gray-500 border-solid border-gray-400 inline-flex items-center justify-center rounded-full bg-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="w-5 h-5" fill="currentColor" viewBox="0 -960 960 960"><path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z"/></svg>
                    </button>
                </div>
                <div class="overflow-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="text-white bg-black"></th>
                                <th class="px-4 py-2 font-semibold text-white bg-black">Recipient</th>
                                <th class="px-4 py-2 font-semibold text-white bg-black">From</th>
                                <th class="px-4 py-2 font-semibold text-white bg-black">Profile</th>
                                <th class="px-4 py-2 font-semibold text-white bg-black">Status</th>
                                <th class="px-4 py-2 font-semibold text-white bg-black">Date/Time</th>
                                <th class="px-4 py-2 font-semibold text-white bg-black">Out/In</th>
                                <th class="px-4 py-2 font-semibold text-white bg-black">Message</th>
                                <th class="px-4 py-2 font-semibold text-white bg-black"></th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @for($i = 0; $i < 10; $i++)
                                <tr>
                                    <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm"></td>
                                    <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm">All Futures</td>
                                    <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm">Grant Krull</td>
                                    <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm">+61 473 128 738</td>
                                    <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm">
                                        Sent
                                    </td>
                                    <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm">
                                        14/02/249:53am
                                    </td>
                                    <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm">

                                    </td>
                                    <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut blandit orci arcu, at tempu
                                        <a href="#!" class="text-primary-500">View More</a>
                                    </td>
                                    <td class="px-4 py-2 border-0 border-b border-solid border-gray-100 text-sm"></td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>

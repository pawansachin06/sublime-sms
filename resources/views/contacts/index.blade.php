<x-admin-layout>
    <div class="max-w-screen-xl mx-auto px-4 mb-8">

        <div class="flex flex-wrap justify-between gap-3 mt-4 mb-3">
            <div class="inline-flex flex-wrap gap-3">
                <x-header.nav-btn :href="route('dashboard')">ACTIVITY</x-header.nav-btn>
                <x-header.nav-btn :href="route('contact-groups.index')">GROUPS</x-header.nav-btn>
                <x-header.nav-btn active="1" :href="route('contacts.index')">CONTACTS</x-header.nav-btn>
                <x-header.nav-btn :href="route('templates.index')">TEMPLATES</x-header.nav-btn>
                <x-header.add-btn>NEW CONTACT</x-header.add-btn>
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
                    <input type="text" placeholder="User" class="py-2 pl-8 font-title text-sm rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400" />
                    <span class="absolute left-0 top-0 bottom-0 pointer-events-none px-3 inline-flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" class="" fill="none"><path fill="#515151" d="M10 5.067C10 2.273 7.757 0 5 0S0 2.273 0 5.067c0 2.793 2.243 5.066 5 5.066s5-2.273 5-5.066Zm-5 3.8c-2.068 0-3.75-1.705-3.75-3.8 0-2.096 1.682-3.8 3.75-3.8s3.75 1.704 3.75 3.8c0 2.095-1.682 3.8-3.75 3.8Zm7.317 3.614a.619.619 0 0 1-.884 0L8.507 9.516c.326-.265.623-.565.884-.896l2.926 2.966a.64.64 0 0 1 0 .895Z"/></svg>
                    </span>
                </div>
                <select name="" class="py-2 font-title text-sm rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400">
                    <option value="">Alert</option>
                    <option value="">Failed</option>
                </select>
                <div class="inline-block relative">
                    <input type="text" placeholder="Phone Number" class="py-2 pl-8 font-title text-sm rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400" />
                    <span class="absolute left-0 top-0 bottom-0 pointer-events-none px-3 inline-flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" class="" fill="none"><path fill="#515151" d="M10 5.067C10 2.273 7.757 0 5 0S0 2.273 0 5.067c0 2.793 2.243 5.066 5 5.066s5-2.273 5-5.066Zm-5 3.8c-2.068 0-3.75-1.705-3.75-3.8 0-2.096 1.682-3.8 3.75-3.8s3.75 1.704 3.75 3.8c0 2.095-1.682 3.8-3.75 3.8Zm7.317 3.614a.619.619 0 0 1-.884 0L8.507 9.516c.326-.265.623-.565.884-.896l2.926 2.966a.64.64 0 0 1 0 .895Z"/></svg>
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

        <div class="overflow-auto">
            <table class="w-full bg-white shadow">
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
                <tbody class="border border-t-0 border-solid border-gray-200">
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
</x-admin-layout>

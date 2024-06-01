<x-admin-layout>
    <div class="max-w-screen-xl mx-auto px-4 mb-8">
        <div class="flex flex-wrap justify-between gap-3 mt-4 mb-3">
            <div class="inline-flex flex-wrap gap-3">
                <x-header.nav-btn active="1">ACTIVITY</x-header.nav-btn>
                <x-header.nav-btn>GROUPS</x-header.nav-btn>
                <x-header.nav-btn>CONTACTS</x-header.nav-btn>
                <x-header.nav-btn>TEMPLATES</x-header.nav-btn>
                <x-header.add-btn>NEW SMS</x-header.add-btn>
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

        <div class="flex flex-wrap items-center gap-3 my-3">
            <div class="inline-block relative">
                <input type="text" placeholder="User/group" class="py-2 pl-8 font-title text-sm rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400" />
                <span class="absolute left-0 top-0 bottom-0 pointer-events-none px-3 inline-flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" class="" fill="none"><path fill="#515151" d="M10 5.067C10 2.273 7.757 0 5 0S0 2.273 0 5.067c0 2.793 2.243 5.066 5 5.066s5-2.273 5-5.066Zm-5 3.8c-2.068 0-3.75-1.705-3.75-3.8 0-2.096 1.682-3.8 3.75-3.8s3.75 1.704 3.75 3.8c0 2.095-1.682 3.8-3.75 3.8Zm7.317 3.614a.619.619 0 0 1-.884 0L8.507 9.516c.326-.265.623-.565.884-.896l2.926 2.966a.64.64 0 0 1 0 .895Z"/></svg>
                </span>
            </div>
            <select name="" class="py-2 font-title text-sm rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400">
                <option value="">Delivered</option>
                <option value="">Delivered</option>
                <option value="">Delivered</option>
                <option value="">Delivered</option>
            </select>
            <div class="inline-block relative">
                <input type="text" placeholder="Date/Time From" class="py-2 font-title text-sm rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400" />
                <span class="absolute right-0 top-0 bottom-0 pointer-events-none px-3 inline-flex gap-1 items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="16" fill="none"><path fill="#515151" fill-rule="evenodd" d="M4.455 1.273V.955A.957.957 0 0 0 3.5 0h-.636a.957.957 0 0 0-.955.955v.318h2.546Zm7.636 0V.955A.957.957 0 0 0 11.136 0H10.5a.957.957 0 0 0-.955.955v.318h2.546ZM14 4.455V3.5c0-.877-.714-1.59-1.59-1.59H1.59C.715 1.91 0 2.622 0 3.5v.955h14ZM0 13.682V5.09h14v8.59c0 .878-.714 1.592-1.59 1.592H1.59c-.876 0-1.59-.714-1.59-1.591Zm3.66-.637a.796.796 0 0 0 .795-.795v-.955a.796.796 0 0 0-.796-.795h-.954a.796.796 0 0 0-.796.795v.955c0 .438.357.795.796.795h.954Zm0-3.818a.796.796 0 0 0 .795-.795v-.955a.796.796 0 0 0-.796-.795h-.954a.796.796 0 0 0-.796.795v.955c0 .438.357.795.796.795h.954Zm3.817 3.818a.796.796 0 0 0 .796-.795v-.955a.796.796 0 0 0-.796-.795h-.954a.796.796 0 0 0-.796.795v.955c0 .438.357.795.796.795h.954Zm0-3.818a.796.796 0 0 0 .796-.795v-.955a.796.796 0 0 0-.796-.795h-.954a.796.796 0 0 0-.796.795v.955c0 .438.357.795.796.795h.954Zm3.819 3.818a.796.796 0 0 0 .795-.795v-.955a.796.796 0 0 0-.795-.795h-.955a.796.796 0 0 0-.796.795v.955c0 .438.357.795.796.795h.955Zm0-3.818a.796.796 0 0 0 .795-.795v-.955a.796.796 0 0 0-.795-.795h-.955a.796.796 0 0 0-.796.795v.955c0 .438.357.795.796.795h.955Z" clip-rule="evenodd"/></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" fill="none"><path fill="#515151" d="M7.5 15c4.136 0 7.5-3.364 7.5-7.5S11.636 0 7.5 0 0 3.364 0 7.5 3.364 15 7.5 15ZM7.098 3.214a.401.401 0 1 1 .804 0v3.884h2.943c.22 0 .402.18.402.402a.403.403 0 0 1-.402.402H7.5a.401.401 0 0 1-.402-.402V3.214Z"/></svg>
                </span>
            </div>
            <div class="inline-block relative">
                <input type="text" placeholder="Date/Time To" class="py-2 font-title text-sm rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400" />
                <span class="absolute right-0 top-0 bottom-0 pointer-events-none px-3 inline-flex gap-1 items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="16" fill="none"><path fill="#515151" fill-rule="evenodd" d="M4.455 1.273V.955A.957.957 0 0 0 3.5 0h-.636a.957.957 0 0 0-.955.955v.318h2.546Zm7.636 0V.955A.957.957 0 0 0 11.136 0H10.5a.957.957 0 0 0-.955.955v.318h2.546ZM14 4.455V3.5c0-.877-.714-1.59-1.59-1.59H1.59C.715 1.91 0 2.622 0 3.5v.955h14ZM0 13.682V5.09h14v8.59c0 .878-.714 1.592-1.59 1.592H1.59c-.876 0-1.59-.714-1.59-1.591Zm3.66-.637a.796.796 0 0 0 .795-.795v-.955a.796.796 0 0 0-.796-.795h-.954a.796.796 0 0 0-.796.795v.955c0 .438.357.795.796.795h.954Zm0-3.818a.796.796 0 0 0 .795-.795v-.955a.796.796 0 0 0-.796-.795h-.954a.796.796 0 0 0-.796.795v.955c0 .438.357.795.796.795h.954Zm3.817 3.818a.796.796 0 0 0 .796-.795v-.955a.796.796 0 0 0-.796-.795h-.954a.796.796 0 0 0-.796.795v.955c0 .438.357.795.796.795h.954Zm0-3.818a.796.796 0 0 0 .796-.795v-.955a.796.796 0 0 0-.796-.795h-.954a.796.796 0 0 0-.796.795v.955c0 .438.357.795.796.795h.954Zm3.819 3.818a.796.796 0 0 0 .795-.795v-.955a.796.796 0 0 0-.795-.795h-.955a.796.796 0 0 0-.796.795v.955c0 .438.357.795.796.795h.955Zm0-3.818a.796.796 0 0 0 .795-.795v-.955a.796.796 0 0 0-.795-.795h-.955a.796.796 0 0 0-.796.795v.955c0 .438.357.795.796.795h.955Z" clip-rule="evenodd"/></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" fill="none"><path fill="#515151" d="M7.5 15c4.136 0 7.5-3.364 7.5-7.5S11.636 0 7.5 0 0 3.364 0 7.5 3.364 15 7.5 15ZM7.098 3.214a.401.401 0 1 1 .804 0v3.884h2.943c.22 0 .402.18.402.402a.403.403 0 0 1-.402.402H7.5a.401.401 0 0 1-.402-.402V3.214Z"/></svg>
                </span>
            </div>
            <select name="" class="py-2 font-title text-sm rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400">
                <option value="">Outbox</option>
                <option value="">Delivered</option>
                <option value="">Delivered</option>
                <option value="">Delivered</option>
            </select>
            <div class="inline-block relative">
                <input type="text" placeholder="Search" class="py-2 pl-8 font-title text-sm rounded border-gray-400 border-solid focus:border-primary-500 focus:ring-primary-400" />
                <span class="absolute left-0 top-0 bottom-0 pointer-events-none px-3 inline-flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" class="" fill="none"><path fill="#515151" d="M10 5.067C10 2.273 7.757 0 5 0S0 2.273 0 5.067c0 2.793 2.243 5.066 5 5.066s5-2.273 5-5.066Zm-5 3.8c-2.068 0-3.75-1.705-3.75-3.8 0-2.096 1.682-3.8 3.75-3.8s3.75 1.704 3.75 3.8c0 2.095-1.682 3.8-3.75 3.8Zm7.317 3.614a.619.619 0 0 1-.884 0L8.507 9.516c.326-.265.623-.565.884-.896l2.926 2.966a.64.64 0 0 1 0 .895Z"/></svg>
                </span>
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

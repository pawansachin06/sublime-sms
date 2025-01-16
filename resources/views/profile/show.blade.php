<x-admin-layout toastify="1">

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-section-border />
            @endif


            @if(auth()->user()->isSuperAdmin())
                <?php
                $data = auth()->user()->getActivityReportSettings();
                ?>
                <div class="mt-10 sm:mt-0">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1 flex justify-between">
                            <div class="px-4 sm:px-0">
                                <h3 class="text-lg font-medium text-gray-900">Activity Report</h3>
                                <p class="mt-1 mb-0 text-sm text-gray-600">Specify sms activity report time and email</p>
                            </div>
                        </div>
                        <div class="mt-5 md:mt-0 md:col-span-2">
                            <div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg">
                                <div class="max-w-xl text-sm text-gray-600">
                                    <form data-js="app-form" action="{{ route('activity-report-settings') }}">
                                        <div class="mb-4">
                                            <label for="activity-report-emails" class="block font-medium text-sm text-gray-700">
                                                Enter the emails (separate by comma) and select the time when to deliver report
                                            </label>
                                            <input type="text" id="activity-report-emails" name="activity_report_emails" value="{{ !empty($data['emails']) ? $data['emails'] : '' }}" class="border-gray-300 focus:border-primary-500 focus:ring-primary-400 rounded-md shadow-sm mt-1 block w-full" />
                                        </div>
                                        <div class="mb-4 select-none">
                                            <div class="flex gap-6">
                                                <label class="inline-flex gap-2 items-center cursor-pointer">
                                                    <input type="checkbox" name="activity_report_time[]" value="daily" <?= in_array('daily', $data['times']) ? 'checked' : '' ?> class="flex-none w-5 h-5 my-1 rounded border-solid text-primary-500 focus:ring-primary-400 border-gray-500"  />
                                                    <span class="leading-tight cursor-pointer">Daily</span>
                                                </label>
                                                <label class="inline-flex gap-2 items-center cursor-pointer">
                                                    <input type="checkbox" name="activity_report_time[]" value="weekly" <?= in_array('weekly', $data['times']) ? 'checked' : '' ?> class="flex-none w-5 h-5 my-1 rounded border-solid text-primary-500 focus:ring-primary-400 border-gray-500"  />
                                                    <span class="leading-tight cursor-pointer">Weekly</span>
                                                </label>
                                                <label class="inline-flex gap-2 items-center cursor-pointer">
                                                    <input type="checkbox" name="activity_report_time[]" value="monthly" <?= in_array('monthly', $data['times']) ? 'checked' : '' ?> class="flex-none w-5 h-5 my-1 rounded border-solid text-primary-500 focus:ring-primary-400 border-gray-500"  />
                                                    <span class="leading-tight cursor-pointer">Monthly</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="">
                                            <p data-js="app-form-status" class="mb-1 hidden"></p>
                                            <button type="submit" data-js="app-form-btn" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">Save Changes</button>
                                        </div>
                                    </form>
                                    <div class="mt-2">
                                        <form data-js="app-form" action="{{ route('sms.send-report') }}">
                                            <p data-js="app-form-status" class="mb-1 hidden"></p>
                                            <x-button type="submit" data-js="app-form-btn">Send Last 7 days email</x-button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <x-section-border />
            @endif


            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>

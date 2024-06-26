document.addEventListener('alpine:init', function () {
    var iphoneSmsTextarea = document.getElementById('iphone-sms-textarea');
    if (iphoneSmsTextarea && typeof autosize == 'function') {
        autosize(iphoneSmsTextarea);
    }

    var newSmsModal = new bsModal('#newSmsModal');
    var smsCreatedModal = new bsModal('#smsCreatedModal');

    var currentTemplateMessageInput = document.getElementById('current-template-message-input');
    var templatesAbortController = null;
    var contactGroupAbortController = null;

    var templatesRouteIndex = TEMPLATES_ROUTE_INDEX;
    var contactGroupsRouteIndex = CONTACT_GROUPS_ROUTE_INDEX;

    Alpine.data('activityData', function () {
        return {
            isLoadingContactGroups: false,

            isPersonalizeDropdownOpen: false,
            currentTemplateMsg: '',
            showSmsEditExitForm: false,

            templates: [],
            isLoadingTemplates: false,
            searchTemplateKeyword: '',
            showTemplateSearchKeywordClearBtn: false,

            send_at: '',
            scheduled: true,
            resMessage: '',
            resMessage2: '',

            contactGroupKeyword: '',
            contactGroups: [],
            selectedContactGroups: [],
            isContactGroupDropdownOpen: false,
            isLoadingContactGroups: false,

            separateNumbers: [],

            isFormEdited: false,
            isSavingSms: false,

            handleNewSmsForm(form) {
                var self = this;
                let formData = new FormData(form);
                let url = form.getAttribute('action');
                self.isSavingSms = true;
                self.resMessage = '';
                self.resMessage2 = '';
                axios.post(url, formData).then(function (res) {
                    dev && console.log(res.data);
                    self.handleExitSmsForm();
                    self.scheduled = res.data?.scheduled;
                    self.resMessage = (res.data?.message) ? res.data.message : 'No response from server';
                    self.resMessage2 = (res.data?.message2) ? res.data.message2 : '';
                    smsCreatedModal.show();
                }).catch(function (err) {
                    dev && console.log(err);
                    let msg = getAxiosError(err);
                    Toastify({
                        text: msg,
                        className: 'toast-error',
                        position: 'center',
                    }).showToast();
                }).finally(function () {
                    self.isSavingSms = false;
                });
            },
            handleCloseModalBtn() {
                var self = this;
                if (self.isFormEdited) {
                    self.showSmsEditExitForm = true;
                } else {
                    self.isFormEdited = false;
                    self.showSmsEditExitForm = false;
                    self.separateNumbers = [];
                    self.selectedContactGroups = [];
                    newSmsModal.hide();
                    self.$refs?.newSmsFormRef?.reset();
                }
            },
            loadTemplates() {
                var self = this;
                if (templatesAbortController) {
                    templatesAbortController.abort();
                }
                templatesAbortController = new AbortController();
                self.isLoadingTemplates = true;
                axios.get(templatesRouteIndex, {
                    params: { keyword: self.searchTemplateKeyword },
                    signal: templatesAbortController.signal,
                }).then(function (res) {
                    dev && console.log(res.data);
                    if (res.data?.success) {
                        self.templates = res.data.items;
                    } else {
                        let msg = (res.data?.message) ? res.data.message : 'No response from server';
                        Toastify({
                            text: msg,
                            className: (res.data?.success) ? 'toast-success' : 'toast-error',
                            position: 'center',
                        }).showToast();
                    }
                    self.isLoadingTemplates = false;
                }).catch(function (err) {
                    if (err.code === 'ERR_CANCELED') {
                    } else {
                        dev && console.error(err);
                        let msg = getAxiosError(err);
                        Toastify({
                            text: msg,
                            className: 'toast-error',
                            position: 'center',
                        }).showToast();
                        self.isLoadingTemplates = false;
                    }
                });
            },
            handleMsgInput() {
                autosize.update(iphoneSmsTextarea)
            },
            handlePersonalizeItemClick(word) {
                var self = this;
                word = '[' + word + ']';
                self.isPersonalizeDropdownOpen = false;
                var startPos = currentTemplateMessageInput.selectionStart;
                var endPos = currentTemplateMessageInput.selectionEnd;
                var text = self.currentTemplateMsg;
                self.currentTemplateMsg = text.substring(0, startPos) + word + text.substring(endPos, text.length);
                // Move the cursor to the end of the inserted word
                self.$nextTick(() => {
                    currentTemplateMessageInput.selectionStart = currentTemplateMessageInput.selectionEnd = startPos + word.length;
                    // Set focus back to the textarea
                    currentTemplateMessageInput.focus();
                });
            },
            handleContactGroupKeywordChange() {
                var self = this;
                if (contactGroupAbortController) {
                    contactGroupAbortController.abort();
                }
                self.isLoadingContactGroups = true;
                self.isContactGroupDropdownOpen = true;
                contactGroupAbortController = new AbortController();
                axios.get(contactGroupsRouteIndex + '?keyword=' + self.contactGroupKeyword, {
                    signal: contactGroupAbortController.signal,
                }).then(function (res) {
                    dev && console.log(res.data);
                    if (res.data?.success) {
                        self.contactGroups = res.data.items;
                        self.handleDuplicateContactGroupMarking();
                    } else {
                        let msg = (res.data?.message) ? res.data.message : 'No response from server';
                        Toastify({
                            text: msg,
                            className: (res.data?.success) ? 'toast-success' : 'toast-error',
                            position: 'center',
                        }).showToast();
                    }
                    self.isLoadingContactGroups = false;
                }).catch(function (err) {
                    if (err.code === 'ERR_CANCELED') {
                    } else {
                        dev && console.error(err);
                        self.isContactGroupDropdownOpen = false;
                        let msg = getAxiosError(err);
                        Toastify({
                            text: msg,
                            className: 'toast-error',
                            position: 'center',
                        }).showToast();
                        self.isLoadingContactGroups = false;
                    }
                });
            },
            handleContactGroupKeywordFocus() {
                if (this.contactGroups.length) {
                    this.isContactGroupDropdownOpen = true;
                }
            },
            handleDuplicateContactGroupMarking() {
                var self = this;
                self.$nextTick(() => {
                    for (var j = 0; j < self.contactGroups.length; j++) {
                        self.contactGroups[j].added = false;
                        if (self.selectedContactGroups?.length) {
                            for (var i = 0; i < self.selectedContactGroups.length; i++) {
                                if (self.contactGroups[j].id == self.selectedContactGroups[i].id) {
                                    self.contactGroups[j].added = true;
                                    break;
                                }
                            }
                        }
                    }
                });
            },
            handleRemoveSelectedContactGroup(group) {
                var self = this;
                // iterate backward to avoid index shifting
                for (let i = self.selectedContactGroups.length - 1; i >= 0; i--) {
                    if (self.selectedContactGroups[i]['id'] === group.id) {
                        self.selectedContactGroups.splice(i, 1);
                        break;
                    }
                }
                self.handleDuplicateContactGroupMarking();
            },
            handleContactGroupDropdownClick(group) {
                var self = this;
                self.isContactGroupDropdownOpen = false;
                let isDuplicate = false;
                for (var i = 0; i < self.selectedContactGroups.length; i++) {
                    if (self.selectedContactGroups[i].id == group.id) {
                        isDuplicate = true;
                        break;
                    }
                }
                if (!isDuplicate) {
                    self.selectedContactGroups.push({
                        id: group.id,
                        uid: group.uid,
                        name: group.name,
                    });
                } else {
                    self.handleRemoveSelectedContactGroup(group);
                }
                self.handleDuplicateContactGroupMarking();
            },
            handleFormEnter(){
            },
            handleContactGroupClickEnter(e = null) {
                // e.preventDefault();
                // e.stopPropagation();
                var self = this;
                self.isContactGroupDropdownOpen = false;
                self.isLoadingContactGroups = false;
                if (self.contactGroupKeyword.trim().length) {
                    if (self.separateNumbers.indexOf(self.contactGroupKeyword) == -1) {
                        self.separateNumbers.push(self.contactGroupKeyword.trim());
                        if (contactGroupAbortController) {
                            contactGroupAbortController.abort();
                        }
                    }
                }
                self.contactGroupKeyword = '';
            },
            handleRemoveSeparateNumber(separateNumber) {
                var self = this;
                // iterate backward to avoid index shifting
                for (let i = self.separateNumbers.length - 1; i >= 0; i--) {
                    if (self.separateNumbers[i] === separateNumber) {
                        self.separateNumbers.splice(i, 1);
                        break;
                    }
                }
            },
            sendSmsFormChanged() {
                this.isFormEdited = true;
            },
            handleExitSmsForm() {
                var self = this;
                self.isFormEdited = false;
                newSmsModal.hide();
                self.separateNumbers = [];
                self.selectedContactGroups = [];
                self.showSmsEditExitForm = false;
                self.$refs?.newSmsFormRef?.reset();
            },
            init() {
                var self = this;
                self.loadTemplates();
                var sendAtEl = document.getElementById('send_at');
                if (sendAtEl) {
                    flatpickr('#send_at', {
                        disableMobile: 'true',
                        allowInput: true,
                        enableTime: true,
                        altInput: true,
                        altFormat: 'd/m/Y h:i K',
                        dateFormat: 'Y-m-d H:i:s',
                        onChange: function (selectedDates, dateStr, instance) {
                            self.send_at = dateStr;
                        },
                        onClose: function(selectedDates, dateStr, instance) {
                            self.send_at = dateStr;
                        }
                    });
                }
            },
        }
    });
});

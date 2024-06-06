document.addEventListener('alpine:init', function () {
    const newContactsModal = new bsModal('#newContactsModal');
    var contactGroupsRouteIndex = CONTACT_GROUPS_ROUTE_INDEX;
    var contactsRouteIndex = CONTACTS_ROUTE_INDEX;

    var contactGroupAbortController = null;
    var contactAbortController = null;

    Alpine.data('contacts', function () {
        return {
            mounted: false,
            isSavingContact: false,

            page: 1,
            perPage: 10,
            contacts: [],
            totalPages: 0,
            totalContacts: 0,
            isLoadingContacts: false,

            contactGroupKeyword: '',
            contactGroups: [],
            selectedContactGroups: [],
            isContactGroupDropdownOpen: false,
            isLoadingContactGroups: false,

            handleNewContactForm(form) {
                var self = this;
                self.isSavingContact = true;
                var data = new FormData(form);
                var url = form.getAttribute('action');
                axios.post(url, data).then(function (res) {
                    dev && console.log(res.data);
                    if(res.data?.reset){
                        form.reset();
                    }
                    if(res.data?.close){
                        newContactsModal.hide();
                        self.selectedContactGroups = [];
                        self.handleDuplicateContactGroupMarking();
                    }
                    let msg = (res.data?.message) ? res.data.message : 'No response from server';
                    Toastify({
                        text: msg,
                        className: (res.data?.success) ? 'toast-success' : 'toast-error',
                        position: 'center',
                    }).showToast();
                }).catch(function (err) {
                    dev && console.error(err);
                    let msg = getAxiosError(err);
                    Toastify({
                        text: msg,
                        className: 'toast-error',
                        position: 'center',
                    }).showToast();
                    self.isLoadingContactGroups = false;
                }).finally(function () {
                    self.isSavingContact = false;
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
            handleRemoveSelectedContactGroup(group){
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
                        name: group.name,
                    });
                } else {
                    self.handleRemoveSelectedContactGroup(group);
                }
                self.handleDuplicateContactGroupMarking();
            },
            loadContacts(page){
                var self = this;
                if(contactAbortController){
                    contactAbortController.abort();
                }
                contactAbortController = new AbortController();
                self.isLoadingContacts = true;
                page = page ? page : self.contactPage;

                axios.get(contactsRouteIndex, {
                    params: {},
                    signal: contactAbortController.signal,
                }).then(function(res){
                    dev && console.log(res.data);
                    if(res.data?.success){
                        self.contacts = res.data.data.data;
                        self.page = res.data.data.current_page;
                        self.totalContacts = res.data.data.total;
                        self.totalPages = res.data.data.last_page;
                    } else {
                        let msg = (res.data?.message) ? res.data.message : 'No response from server';
                        Toastify({
                            text: msg,
                            className: (res.data?.success) ? 'toast-success' : 'toast-error',
                            position: 'center',
                        }).showToast();
                    }
                }).catch(function(err){
                    if (err.code === 'ERR_CANCELED') {
                    } else {
                        dev && console.error(err);
                        let msg = getAxiosError(err);
                        Toastify({
                            text: msg,
                            className: 'toast-error',
                            position: 'center',
                        }).showToast();
                        self.isLoadingContacts = false;
                    }
                }).finally(function(){

                });
            },
            init() {
                this.loadContacts();
                this.mounted = true;
            }
        }
    });
});
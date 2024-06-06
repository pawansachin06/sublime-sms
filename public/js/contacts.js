document.addEventListener('alpine:init', function () {
    var newContactsModalEl = document.getElementById('newContactsModal');
    const newContactsModal = new bsModal(newContactsModalEl);
    var contactGroupsRouteIndex = CONTACT_GROUPS_ROUTE_INDEX;
    var contactsRouteIndex = CONTACTS_ROUTE_INDEX;
    var contactsRouteDelete = CONTACTS_ROUTE_DELETE;

    var contactGroupAbortController = null;
    var contactAbortController = null;

    if(newContactsModalEl){
        newContactsModalEl.addEventListener('shown.bs.modal', function(){
            document.getElementById('new-contact-modal-first-input')?.focus();
        });
    }

    Alpine.data('contacts', function () {
        return {
            mounted: false,
            isSavingContact: false,

            searchKeywordName: '',
            showSearchKeywordNameClearBtn: false,
            searchKeywordPhone: '',
            showSearchKeywordPhoneClearBtn: false,

            modalInputId: '',
            modalInputName: '',
            modalInputLastname: '',
            modalInputPhone: '',
            modalInputCountry: '',
            modalInputCompany: '',
            modalInputComments: '',

            page: 1,
            perPage: 10,
            contacts: [],
            prevPages: [],
            nextPages: [],
            totalPages: 0,
            totalContacts: 0,
            isLoadingContacts: false,

            currentDeleteContact: null,
            isDeletingContact: false,

            contactGroupKeyword: '',
            contactGroups: [],
            selectedContactGroups: [],
            isContactGroupDropdownOpen: false,
            isLoadingContactGroups: false,

            handleCloseModalBtn(){
                newContactsModal.hide();
                var self = this;
                if(self.modalInputId?.length){
                    self.loadContacts();
                }
                self.modalInputId = '';
                self.modalInputName = '';
                self.modalInputLastname = '';
                self.modalInputPhone = '';
                self.modalInputCountry = '';
                self.modalInputCompany = '';
                self.modalInputComments = '';
                self.selectedContactGroups = [];
                self.handleDuplicateContactGroupMarking();
            },

            handleEditContactBtn(contact){
                newContactsModal.show();
                var self = this;
                self.modalInputId = contact.id;
                self.modalInputName = contact.name;
                self.modalInputLastname = contact.lastname;
                self.modalInputPhone = contact.phone;
                self.modalInputCountry = contact.country;
                self.modalInputCompany = contact.company;
                self.modalInputComments = contact.comments;
                if(contact?.groups?.length){
                    self.selectedContactGroups = contact.groups;
                } else {
                    self.selectedContactGroups = [];
                }
                self.handleDuplicateContactGroupMarking();
            },

            handleSearchKeywordName(){
                if(this.searchKeywordName.trim().length){
                    this.showSearchKeywordNameClearBtn = true;
                } else {
                    this.showSearchKeywordNameClearBtn = false;
                }
                this.loadContacts(1);
            },
            handleSearchKeywordPhone(){
                if(this.searchKeywordPhone.trim().length){
                    this.showSearchKeywordPhoneClearBtn = true;
                } else {
                    this.showSearchKeywordPhoneClearBtn = false;
                }
                this.loadContacts(1);
            },
            handleClearSearchKeywordName(){
                this.searchKeywordName = '';
                this.showSearchKeywordNameClearBtn = false;
                this.loadContacts(1);
            },
            handleClearSearchKeywordPhone(){
                this.searchKeywordPhone = '';
                this.showSearchKeywordPhoneClearBtn = false;
                this.loadContacts(1);
            },

            handleDeleteContact(contact){
                this.currentDeleteContact = contact;
            },
            handleConfirmedDeleteContact(contact){
                var self = this;
                self.isDeletingContact = true;
                axios.post(contactsRouteDelete, {
                    id: contact.id
                }).then(function(res){
                    if(res.data.success){
                        self.loadContacts();
                        self.currentDeleteContact = null;
                    }
                    let msg = (res.data?.message) ? res.data.message : 'No response from server';
                    Toastify({
                        text: msg,
                        className: (res.data?.success) ? 'toast-success' : 'toast-error',
                        position: 'center',
                    }).showToast();
                }).catch(function(err){
                    dev && console.error(err);
                    let msg = getAxiosError(err);
                    Toastify({
                        text: msg,
                        className: 'toast-error',
                        position: 'center',
                    }).showToast();
                }).finally(function(){
                    self.isDeletingContact = false;
                });
            },
            handleCancelDeleteContact(){
                this.currentDeleteContact = null;
            },

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
                    if(res.data?.reload){
                        self.loadContacts();
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
                    params: {
                        page: page,
                        phone: self.searchKeywordPhone,
                        keyword: self.searchKeywordName,
                    },
                    signal: contactAbortController.signal,
                }).then(function(res){
                    dev && console.log(res.data);
                    if(res.data?.success){
                        self.contacts = res.data.data.data;
                        self.page = res.data.data.current_page;
                        self.totalContacts = res.data.data.total;
                        self.totalPages = res.data.data.last_page;
                        self.prevPages = [];
                        self.nextPages = [];
                        for (let i = self.page - 2; i < self.page; i++) {
                            if (i > 0) self.prevPages.push(i);
                        }
                        for (let j = self.page + 1; j < self.totalPages; j++) {
                            self.nextPages.push(j);
                            if (j >= self.page + 2) break;
                        }
                    } else {
                        let msg = (res.data?.message) ? res.data.message : 'No response from server';
                        Toastify({
                            text: msg,
                            className: (res.data?.success) ? 'toast-success' : 'toast-error',
                            position: 'center',
                        }).showToast();
                    }
                    self.isLoadingContacts = false;
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
                });
            },
            init() {
                this.loadContacts();
                this.mounted = true;
            }
        }
    });
});
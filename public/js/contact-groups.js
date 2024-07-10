document.addEventListener('alpine:init', function () {
    const importContactsModal = new bsModal('#importContactGroupsModal');
    var contactGroupsRouteIndex = CONTACT_GROUPS_ROUTE_INDEX;
    var contactGroupsRouteDelete = CONTACT_GROUPS_ROUTE_DELETE;
    var contactRouteIndexRoute = CONTACT_ROUTE_INDEX;
    var currentContactGroupAbortController = null;
    var importContactsFileEl = document.getElementById('import_contacts_file');


    Alpine.data('contactGroups', function () {
        return {
            mounted: false,
            isLoadingContacts: false,
            isLoadingContactGroups: false,
            isCreatingContactGroup: false,
            isOpenNewContactGroupForm: false,
            isOpenEditContactGroupForm: false,
            isDeletingContactGroup: false,
            isOpenDeleteContactGroupForm: false,

            currentContactGroup: { id: 0, name: '', createdBy: '', createdOn: '', profile: '' },

            importContactsFilename: '',
            importContactsDisabled: true,
            isImportingContacts: false,
            importContactStep: 'upload',
            importContactsErrorMsg: '',
            countNewPhoneNumbers: 0,

            showContactGroupSearchClearBtn: false,
            contactGroupSearchKeyword: '',
            contactGroups: [],
            isFirstTimeContactGroupsLoaded: true,

            contactPage: 1,
            contactPerPage: 10,
            contactTotalRows: 0,
            contactTotalPages: 0,
            contacts: [],


            handleImportContactsFile(e) {
                var self = this;
                if (e.target.files?.length) {
                    let file = e.target.files[0];
                    self.importContactsFilename = file.name;
                    self.importContactsDisabled = false;
                } else {
                    self.importContactsDisabled = true;
                    self.importContactsFilename = '';
                }
            },

            handleImportContactsForm(form) {
                var self = this;
                self.isImportingContacts = true;
                let formData = new FormData(form);
                formData.append('group_id', self.currentContactGroup.id);
                formData.append('step', self.importContactStep);
                let url = form.getAttribute('action');
                axios.post(url, formData).then(function (res) {
                    if(res.data.hasNewPhoneNumbers){
                        self.importContactStep = 'hasNewPhoneNumbers';
                        self.countNewPhoneNumbers = res.data.newPhoneNumbers;
                    } else {
                        self.importContactsErrorMsg = res.data.message;
                        self.importContactStep = 'complete';
                    }
                }).catch(function (err) {
                    dev && console.error(err);
                    let msg = getAxiosError(err);
                    self.importContactsErrorMsg = msg;
                    Toastify({
                        text: msg,
                        className: 'toast-error',
                        position: 'center',
                    }).showToast();
                }).finally(function () {
                    self.isImportingContacts = false;
                });
            },

            closeImportContactsModal() {
                importContactsModal.hide();
                importContactsFileEl.value = '';
                this.importContactsFilename = '';
                this.importContactsErrorMsg = '';
                this.countNewPhoneNumbers = 0;
                this.importContactsDisabled = true;
                this.importContactStep = 'upload';
            },

            clearSelectedContactGroup() {
                this.currentContactGroup = { id: 0, name: '', createdBy: '', createdOn: '', profile: '' }
            },
            handleDeleteContactGroup() {
                var self = this;
                self.isOpenEditContactGroupForm = false;
                self.$refs.newGroupNameInputRef.value = '';
                if (this.isOpenDeleteContactGroupForm) {
                    self.isDeletingContactGroup = true;
                    axios.post(contactGroupsRouteDelete, {
                        id: self.currentContactGroup.id,
                    }).then(function (res) {
                        dev && console.log(res.data);
                        if (res.data.success) {
                            self.loadContactGroups();
                            self.clearSelectedContactGroup();
                            self.isOpenDeleteContactGroupForm = false;
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
                        self.isDeletingContactGroup = false;
                    });
                } else {
                    self.isOpenDeleteContactGroupForm = true;
                }
            },
            handleCloseDeleteContactGroupForm() {
                this.isOpenDeleteContactGroupForm = false;
            },
            handleContactGroupInput() {
                if (this.contactGroupSearchKeyword.trim().length) {
                    this.showContactGroupSearchClearBtn = true;
                } else {
                    this.showContactGroupSearchClearBtn = false;
                }
                this.loadContactGroups();
            },
            handleClearSearchContactGroup() {
                this.contactGroupSearchKeyword = '';
                this.showContactGroupSearchClearBtn = false;
                this.loadContactGroups();
            },
            handleSelectGroup(contactGroup) {
                var self = this;
                self.isOpenNewContactGroupForm = false;
                self.currentContactGroup = {
                    id: contactGroup.id,
                    name: contactGroup.name,
                    createdBy: contactGroup.createdBy,
                    createdOn: contactGroup.createdOn,
                    profile: contactGroup.profile,
                }
            },
            handleOpenNewContactGroupForm() {
                var self = this;
                this.isOpenNewContactGroupForm = true;
                this.clearSelectedContactGroup();
                this.$nextTick(function () {
                    self.$refs.newGroupNameInputRef.focus();
                });
            },
            handleOpenEditContactGroupForm() {
                var self = this;
                this.isOpenEditContactGroupForm = true;
                this.$nextTick(function () {
                    self.$refs.newGroupNameInputRef.value = self.currentContactGroup.name;
                    self.$refs.newGroupNameInputRef.focus();
                });
            },
            handleCancelGroup() {
                if (this.isOpenEditContactGroupForm) {
                    this.$refs.newGroupNameInputRef.value = '';
                    this.isOpenEditContactGroupForm = false
                } else {
                    this.clearSelectedContactGroup();
                }
                this.isOpenNewContactGroupForm = false;
            },
            loadContacts(page) {
                var self = this;
                page = page ? page : self.contactPage;
                // self.isLoadingContacts = true;
                // axios.post(contactRouteIndexRoute, {
                //     page: self.contactPage
                // });

            },
            loadContactGroups(page) {
                var self = this;
                // If there's an ongoing request, abort it
                if (currentContactGroupAbortController) {
                    currentContactGroupAbortController.abort();
                }
                self.isLoadingContactGroups = true;
                // Create a new AbortController
                currentContactGroupAbortController = new AbortController();

                axios.get(contactGroupsRouteIndex, {
                    params: {
                        keyword: self.contactGroupSearchKeyword,
                    },
                    signal: currentContactGroupAbortController.signal,
                }).then(function (res) {
                    dev && console.log(res.data);
                    if (res.data?.success) {
                        self.contactGroups = res.data.items;
                        if (self.isFirstTimeContactGroupsLoaded && res.data.items.length) {
                            self.currentContactGroup = {
                                id: res.data.items[0].id,
                                name: res.data.items[0].name,
                                createdBy: res.data.items[0].createdBy,
                                createdOn: res.data.items[0].createdOn,
                                profile: res.data.items[0].profile,
                            }
                            self.isFirstTimeContactGroupsLoaded = false;
                        }
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
            handleCreateContactGroup(form) {
                var self = this;
                self.isCreatingContactGroup = true;
                var url = form.getAttribute('action');
                var data = new FormData(form);
                if (self.isOpenEditContactGroupForm && self.currentContactGroup?.id) {
                    data.append('id', self.currentContactGroup.id);
                }
                axios.post(url, data).then(function (res) {
                    if (res.data?.success) {
                        self.isOpenNewContactGroupForm = false;
                        self.isOpenEditContactGroupForm = false;
                        // newGroupModal.hide();
                        self.currentContactGroup = {
                            id: res.data.item.id,
                            name: res.data.item.name,
                            createdBy: res.data.item.createdBy,
                            createdOn: res.data.item.createdOn,
                            profile: res.data.item.profile,
                        }
                        self.loadContacts(1);
                        self.loadContactGroups();
                    }
                    if (res.data?.reset) {
                        form.reset();
                    }
                    dev && console.log(res.data);
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
                }).finally(function () {
                    self.isCreatingContactGroup = false;
                });
            },
            init() {
                this.mounted = true;
                this.loadContactGroups();
            },
        }
    });
});
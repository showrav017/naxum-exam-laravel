@include('portal.inc.header')
<div class="content-wrapper" id="app">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">User Profile</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                     :src="userProfileImage"
                                     alt="User profile picture">
                            </div>
                            <h3 class="profile-username text-center" v-html="userName"></h3>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Total Contacts</b> <a class="float-right"><span v-html="totalUserContacts"></span></a>
                                </li>
                                <li class="list-group-item text-center">
                                    <input id='filePick' type='file' style="display:none" @change="selectFile" ref="file" accept="image/png, image/gif, image/jpeg">
                                    <button type="button" class="btn btn-primary btn-xs" v-on:click="doFilePick()">Update Profile Picture</button>
                                </li>
                            </ul>


                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            Settings
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <form class="form-horizontal">
                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-2 col-form-label">First Name</label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.first_name" type="text" class="form-control" id="inputName" placeholder="Put First Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail" class="col-sm-2 col-form-label">Last Name</label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.last_name" type="text" class="form-control" id="inputEmail" placeholder="Put Last Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputName2" class="col-sm-2 col-form-label">Mobile Number</label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.mobile_number" type="text" class="form-control" id="inputName2" placeholder="Put Mobile Number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputExperience" class="col-sm-2 col-form-label">E-Mail</label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.email" type="text" class="form-control" id="inputName2" placeholder="Put E-Mail">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputSkills" class="col-sm-2 col-form-label">Facebook URL</label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.facebook_url" type="text" class="form-control" id="inputSkills" placeholder="Put Facebook URL">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputSkills" class="col-sm-2 col-form-label">Linked-In URL</label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.linked_in_url" type="text" class="form-control" id="inputSkills" placeholder="Put Linked-In URL">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputSkills" class="col-sm-2 col-form-label">Website URL</label>
                                    <div class="col-sm-10">
                                        <input v-model="settings.web_site" type="text" class="form-control" id="inputSkills" placeholder="Put Website URL">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="button" class="btn btn-primary" v-on:click="submitData()">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script>
    const { createApp } = Vue
    var theInstance;
    createApp({
        data() {
            return {
                userProfileImage:"https://via.placeholder.com/300",
                userName:"",
                totalUserContacts:"",
                settings:{
                    "first_name":"",
                    "last_name":"",
                    "mobile_number":"",
                    "email":"",
                    "facebook_url":"",
                    "linked_in_url":"",
                    "web_site":""
                }
            }
        },
        mounted()
        {
            theInstance = this;
            this.loadProfile();
        },
        methods: {
            loadProfile()
            {
                axios.get('{{ env("APP_URL") }}api/users/my_profile', {
                    headers: {
                        Authorization: 'Bearer <?php echo session('AuthorizationToken'); ?>'
                    },
                })
                    .then(function (response) {
                        theInstance.userName = (response.data.data.first_name +" "+ response.data.data.last_name);
                        theInstance.totalUserContacts = response.data.data.totalUserContacts;

                        theInstance.settings.first_name = response.data.data.first_name;
                        theInstance.settings.last_name = response.data.data.last_name;
                        theInstance.settings.mobile_number = response.data.data.mobile_number;
                        theInstance.settings.email = response.data.data.email;
                        theInstance.settings.facebook_url = response.data.data.facebook_url;
                        theInstance.settings.linked_in_url = response.data.data.linked_in_url;
                        theInstance.settings.web_site = response.data.data.web_site;

                        if(response.data.data.profile_picture_location
                            && response.data.data.profile_picture_location.length > 1)
                            theInstance.userProfileImage="{{ env("APP_URL") }}profile_picture/"+response.data.data.profile_picture_location;
                    })
                    .catch(error => {
                        if (error.response) {
                            switch (error.response.status)
                            {
                                case 401:
                                    alert("You have to Login Again");
                                    window.location.href = "{{ env("APP_URL") }}logout";
                                    break;
                            }
                        }
                    });
            },
            selectFile() {
                theInstance.settings.profile_picture_location = this.$refs.file.files[0];
                if((this.$refs.file.files[0].size/1000) < 500)
                {
                    this.updateProfilePicture();
                } else alert("Image size cannot be more then 500 MB")
            },
            doFilePick()
            {
                $('#filePick').click();
            },
            updateProfilePicture() {
                const formData = new FormData();
                formData.append('profile_picture', theInstance.settings.profile_picture_location);
                const headers = { 'Content-Type': 'multipart/form-data', 'Authorization': 'Bearer <?php echo session('AuthorizationToken'); ?>' };
                axios.post('{{ env("APP_URL") }}api/users/update_profile_picture', formData, { headers }).then((res) => {
                    alert("Profile Updated");
                    theInstance.loadProfile();
                }).catch(error => {
                    if (error.response) {
                        switch (error.response.status)
                        {
                            case 401:
                                alert("You have to Login Again");
                                window.location.href = "{{ env("APP_URL") }}logout";
                                break;
                        }
                    }
                });
            },
            submitData()
            {
                axios.put('{{ env("APP_URL") }}api/users/update_my_profile', theInstance.settings, {
                    headers: {
                        Authorization: 'Bearer <?php echo session('AuthorizationToken'); ?>'
                    },
                })
                    .then(function (response) {
                        alert("Profile Updated");
                        theInstance.loadProfile();
                    })
                    .catch(error => {
                        if (error.response) {
                            switch (error.response.status)
                            {
                                case 401:
                                    alert("You have to Login Again");
                                    window.location.href = "{{ env("APP_URL") }}logout";
                                    break;
                                default:
                                    alert(JSON.stringify(error.response.data.message))
                            }
                        }
                    });
            }
        }
    }).mount('#app')
</script>

@include('portal.inc.footer')

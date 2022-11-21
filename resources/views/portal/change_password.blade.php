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
                            Change Your Current Password
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <form class="form-horizontal">
                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-2 col-form-label">Old Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" placeholder="Put your old password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail" class="col-sm-2 col-form-label">New Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" placeholder="Put your New password" v-model="settings.new_password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputName2" class="col-sm-2 col-form-label">New Password (Again)</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" placeholder="Put your New password Again"  v-model="settings.new_password_again">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="button" class="btn btn-danger" v-on:click="submitData()">Update Password</button>
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
                    new_password:"",
                    new_password_again:""
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
            submitData()
            {
                if(theInstance.settings.new_password === theInstance.settings.new_password_again)
                {
                    axios.post('{{ env("APP_URL") }}api/users/change_my_password', theInstance.settings, {
                        headers: {
                            Authorization: 'Bearer <?php echo session('AuthorizationToken'); ?>'
                        },
                    })
                    .then(function (response) {
                        alert("You have to Re-Login Again")
                        window.location.href = "{{ env("APP_URL") }}logout";
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
                } else alert("New Password not matched")
            }
        }
    }).mount('#app')
</script>

@include('portal.inc.footer')


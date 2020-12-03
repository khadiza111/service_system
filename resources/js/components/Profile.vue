<style>
.widget-user-header {
    background-position: center center;
    background-size: cover;
    height: 250px !important;
}
.widget-user .card-footer{
    padding: 0;
}
</style>

<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="card card-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header text-white" style="background-image:url('./img/user-cover.jpg')">
                    <h3 class="widget-user-username text-left">{{ this.form.name }}</h3>
                    <h5 class="widget-user-desc text-left">{{this.form.type}}</h5>
                </div>
                <div class="widget-user-image">
                    <img class="img-circle" :src="getProfilePhoto()" alt="User Avatar">
                </div>

            </div>
            </div>
            <!-- tab -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active show" href="#profile" data-toggle="tab">User Profile</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active show" id="profile">
                                <form class="form-horizontal">
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                                    <div class="col-sm-12">
                                    <input type="" v-model="form.name" class="form-control" id="inputName" placeholder="Name" :class="{ 'is-invalid': form.errors.has('name') }">
                                     <has-error :form="form" field="name"></has-error>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                                    <div class="col-sm-12">
                                    <input type="email" v-model="form.email" class="form-control" id="inputEmail" placeholder="Email"  :class="{ 'is-invalid': form.errors.has('email') }">
                                     <has-error :form="form" field="email"></has-error>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputMobile" class="col-sm-2 control-label">Mobile</label>

                                    <div class="col-sm-12">
                                    <input type="text" v-model="form.mobile" class="form-control" id="inputMobile" placeholder="Mobile"  :class="{ 'is-invalid': form.errors.has('mobile') }">
                                     <has-error :form="form" field="mobile"></has-error>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputAddress" class="col-sm-2 control-label">Address</label>

                                    <div class="col-sm-12">
                                    <textarea  v-model="form.address" class="form-control" id="inputAddress" placeholder="Address" :class="{ 'is-invalid': form.errors.has('address') }"></textarea>
                                     <has-error :form="form" field="address"></has-error>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="photo" class="col-sm-2 control-label">Profile Photo</label>
                                    <div class="col-sm-12">
                                        <input type="file" @change="updateProfile" name="photo" class="form-input">
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="password" class="col-sm-12 control-label">Password (if changing)</label>

                                    <div class="col-sm-12">
                                    <input type="password"
                                        v-model="form.password"
                                        class="form-control"
                                        id="password"
                                        placeholder="password"
                                        :class="{ 'is-invalid': form.errors.has('password') }"
                                    >
                                     <has-error :form="form" field="password"></has-error>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-12">
                                    <button type="submit" @click.prevent="updateInfo" class="btn btn-success">Update</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
          </div>
          <!-- end tabs -->
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
            form: new Form({
                  id: '',
                  name : '',
                  email : '',
                  mobile: '',
                  address: '',
                  password : '',
                  type : '',
                  bio : '',
                  photo : '',
                })
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            getProfilePhoto(){
                let photo = (this.form.photo.length > 200) ? this.form.photo : "img/profile/"+this.form.photo;
                // return "img/profile/"+ this.form.photo;
                return photo;
            },
            updateInfo(){
                this.$Progress.start();
                if(this.form.password == ''){
                    this.form.password = undefined;
                }
                this.form.put('api/profile/')
                .then(()=>{
                    Fire.$emit('AfterCreated');
                    this.$Progress.finish();
                })
                .catch(()=>{
                    this.$Progress.fail();
                });
            },
            updateProfile(e){
                // console.log('uploading..');
                let file = e.target.files[0];
                // console.log(file);
                let reader = new FileReader();
                let limit = 1024 * 1024 * 2;
                if(file['size'] > limit) {
                    Swal.fire(
                        'Error',
                        'Oops...',
                        'You are uploading a large file',
                    )
                    return false;
                }
                    reader.onloadend = (file) => {
                    // console.log("RESULT", reader.result);
                    this.form.photo = reader.result;
                    }
                    reader.readAsDataURL(file);
            }
        },
        created() {
            axios.get("api/profile")
            .then(({ data }) => (this.form.fill(data)));
        }
    }
</script>

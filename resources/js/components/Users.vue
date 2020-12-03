<template>
    <div class="container">
        <div class="row mt-4" v-if="$gate.isAdminOrAuthor()">
          <div class="col-md-12">
            <div class="card user-head">
              <div class="card-header">
                <h3 class="card-title">Users Table</h3>

                <div class="card-tools">
                    <button class="btn btn-info" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Mobile</th>
                      <th>Address</th>
                      <th>Type</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="user in users.data" :key="user.id">
                      <td>{{user.id}}</td>
                      <td>{{user.name}}</td>
                      <td>{{user.email}}</td>
                      <td>{{user.mobile}}</td>
                      <td>{{user.address}}</td>
                      <td>{{user.type | upperText}}</td>
                      <td>
                          <a href="#">
                              <i class="fa fa-angle-double-right orange"></i>
                          </a>
                          ||
                          <a href="#" @click="editModal(user)">
                              <i class="fa fa-user-edit"></i>
                          </a>
                          ||
                          <a href="#" @click="deleteUser(user.id)">
                              <i class="fa fa-trash red"></i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <pagination :data="users" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div v-if="!$gate.isAdminOrAuthor()">
          <not-found></not-found>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" v-show="!editMode" id="addNewLabel">Add User</h5>
                <h5 class="modal-title" v-show="editMode" id="addNewLabel">Update User Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form @submit.prevent="editMode ? updateUser() : createUser()">  
              <div class="modal-body">
                  <div class="form-group">
                    <input v-model="form.name" type="text" name="name" placeholder="Name"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                    <has-error :form="form" field="name"></has-error>
                  </div>
                  <div class="form-group">
                    <input v-model="form.email" type="email" name="email" placeholder="Email ID"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                    <has-error :form="form" field="email"></has-error>
                  </div>
                  <div class="form-group">
                    <textarea v-model="form.bio" name="bio" id="bio" placeholder="Short Bio (Optional)" class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }">
                    </textarea>
                    <has-error :form="form" field="bio"></has-error>
                  </div>
                  <div class="form-group">
                    <select name="type" v-model="form.type" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                      <option value="">Select User Role</option>
                      <option value="admin">Admin</option>
                      <option value="user">Standard User</option>
                      <option value="author">Author</option>
                    </select>
                    <has-error :form="form" field="type"></has-error>
                  </div>
                  <div class="form-group">
                    <input v-model="form.password" type="password" name="password" id="password" placeholder="Password"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                    <has-error :form="form" field="password"></has-error>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i></button>
                  <button v-show="editMode" type="submit" class="btn btn-success"><i class="fa fa-pen"></i></button>
                  <!-- <button v-show="editMode" type="submit" class="btn btn-success"><i class="fa fa-pen-square"></i></button> -->
                  <button v-show="!editMode" type="submit" class="btn btn-primary"><i class="fa fa-plus"></i></button>
              </div>
            </form>
            </div>
        </div>
        </div>
    </div>
</template>

<style>
  .user-head {
    background-color: #f0e191;
  }
</style>

<script>
    export default {
        data() {
          return {
            editMode: false,
            users : {},
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
        methods: {
          getResults(page = 1) {
            axios.get('api/user?page=' + page)
              .then(response => {
                this.users = response.data;
              });
          },
          updateUser(id){
            this.$Progress.start();
            // console.log('Editing Data');
            this.form.put('api/user/'+this.form.id)
            .then(()=>{
              //success
              $('#addNew').modal('hide');
              Swal.fire(
                'Updated!',
                'Your file has been updated.',
                'success'
              )
              this.$Progress.finish();
              Fire.$emit('AfterCreated');
            })
            .catch(()=>{
              this.$Progress.fail();
            });
          },
          editModal(user){
            this.editMode = true;
            this.form.reset();
            $('#addNew').modal('show');
            this.form.fill(user);
          },
          newModal(){
            this.editMode = false;
            this.form.reset();
            $('#addNew').modal('show');
          },
          deleteUser(id){
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                // send Ajax request to the Server
                if(result.value) {
                    this.form.delete('api/user/'+id).then(()=>{
                        Swal.fire(
                          'Deleted!',
                          'Your file has been deleted.',
                          'success'
                        )
                        Fire.$emit('AfterCreated');
                    }).catch(()=>{
                      Swal("Failed", "Something wrong!!", "warning");
                    });
                }
            })
          },
          loadUsers(){
            if(this.$gate.isAdminOrAuthor()){
              axios.get("api/user").then(({ data }) => (this.users = data));
            } 
          },
          createUser(){
            this.$Progress.start();
            this.form.post('api/user')
            .then(()=>{
              Fire.$emit('AfterCreated');
                $('#addNew').modal('hide');
                Toast.fire({
                  icon: 'success',
                  title: 'User created successfully'
                });
                this.$Progress.finish();
            })
            .catch(()=>{

            })
          }
        },
        created() {
            //For searching
            Fire.$on('searching', () => {
              let query = this.$parent.search;
              axios.get('api/findUser?q='+query)
              .then((data) => {
                this.users = data.data
              })
              .catch()
            })

            this.loadUsers();
            Fire.$on('AfterCreated',() => {
              this.loadUsers();
            });
            // setInterval(() => this.loadUsers(), 3000);
        }
    }
</script>

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
            <!-- tab -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active show" href="#visaProcess" data-toggle="tab">Visa Processing</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active show" id="visaProcess">
                                <form class="form-horizontal">
                                <div class="form-group">
                                    <label for="inputVdate" class="col-sm-2 control-label">Visa Date</label>

                                    <div class="col-sm-12">
                                    <input type="" v-model="form.vdate" class="form-control" id="inputVdate" placeholder="Visa Date" :class="{ 'is-invalid': form.errors.has('vdate') }">
                                     <has-error :form="form" field="vdate"></has-error>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputVRef" class="col-sm-2 control-label">Visa Ref No.</label>

                                    <div class="col-sm-12">
                                    <input type="text" v-model="form.vrefNo" class="form-control" id="inputVRef" placeholder="vrefNo"  :class="{ 'is-invalid': form.errors.has('vrefNo') }">
                                     <has-error :form="form" field="vrefNo"></has-error>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-12">
                                    <button type="submit" @click.prevent="updateVInfo" class="btn btn-success">Save</button>
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
                vdate: '',
                vrefNo: '',
                })
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            updateVInfo(){
                this.$Progress.start();
                if(this.form.vdate == ''){
                    this.form.vdate = undefined;
                }
                if(this.form.vrefNo == ''){
                    this.form.vrefNo = undefined;
                }
                this.form.put('api/visaProcess/')
                .then(()=>{
                    Fire.$emit('AfterCreated');
                    this.$Progress.finish();
                })
                .catch(()=>{
                    this.$Progress.fail();
                });
            },
        },
        created() {
            axios.get("api/visaProcess")
            .then(({ data }) => (this.form.fill(data)));
        }
    }
</script>

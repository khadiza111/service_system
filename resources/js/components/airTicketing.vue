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
                        <li class="nav-item"><a class="nav-link active show" href="#airticket" data-toggle="tab">Air Ticketing</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active show" id="airticket">
                                <form class="form-horizontal">
                                <div class="form-group">
                                    <label for="inputDate" class="col-sm-2 control-label">Booking Date</label>

                                    <div class="col-sm-12">
                                    <input type="" v-model="form.date" class="form-control" id="inputDate" placeholder="Date" :class="{ 'is-invalid': form.errors.has('date') }">
                                     <has-error :form="form" field="date"></has-error>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputRef" class="col-sm-2 control-label">Ref No.</label>

                                    <div class="col-sm-12">
                                    <input type="text" v-model="form.refNo" class="form-control" id="inputRef" placeholder="refNo"  :class="{ 'is-invalid': form.errors.has('refNo') }">
                                     <has-error :form="form" field="refNo"></has-error>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-12">
                                    <button type="submit" @click.prevent="upInfo" class="btn btn-success">Save</button>
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
                date: '',
                refNo: '',
                })
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            upInfo(){
                this.$Progress.start();
                if(this.form.date == ''){
                    this.form.date = undefined;
                }
                if(this.form.refNo == ''){
                    this.form.refNo = undefined;
                }
                this.form.put('api/airTicket/')
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
            axios.get("api/airTicket")
            .then(({ data }) => (this.form.fill(data)));
        }
    }
</script>

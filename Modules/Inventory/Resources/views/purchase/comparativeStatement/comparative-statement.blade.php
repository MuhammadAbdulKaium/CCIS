@extends('layouts.master')
@section('content')
<div id="app">
    <div class="content-wrapper">
        <div v-if="!pageLoader">
            <section class="content-header">
                <h1>
                    <i class="fa fa-th-list"></i> Manage  |<small>Comparative Statement</small>
                </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="#">Inventory</a></li>
                    <li><a href="#">Purchase</a></li>
                    <li class="active">Comparative Statement</li>
                </ul>
            </section>

            <section class="content">

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Comparative Statement List</h3>
                        <div class="box-tools">
                            <a v-if="dataList.pageAccessData['inventory/comparative-statement-data/create']" class="btn btn-success btn-sm" href="{{ url('inventory/comparative-statement-data/create') }}"><i class="fa fa-plus-square"></i> New</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <form action="" class="form-inline">
                            <div class="row" style="margin-bottom: 10px">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Per page</label>
                                        <select name="listPerPage" class="form-control" v-model="listPerPage" @change="getResults(1)">
                                            <option v-for="size in pageSize" :value="size">@{{size}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <multiselect :select-label="''" :deselect-label="''" v-if="dataList.voucher_list" name="voucher_id_model" v-model="filter.voucher_id_model" :options="dataList.voucher_list"  placeholder="Select voucher" label="voucher_no" track-by="id" @input="selectVoucher" :options-limit="10000"></multiselect>
                                </div>
                                <div class="col-sm-3">
                                    <multiselect :select-label="''" :deselect-label="''" v-if="dataList.item_list" name="item_id_model" v-model="filter.item_id_model" :options="dataList.item_list"  placeholder="Select item" label="product_name" track-by="item_id" @input="selectFilterItem" :options-limit="10000"></multiselect>
                                </div>
                                <div class="col-sm-3">
                                    <select name="status" class="form-control" v-model="filter.status" style="width:100%">
                                        <option  value="">Select Status</option>
                                        <option  value="p">Pending</option>
                                        <option  value="1">Approved</option>
                                        <option  value="2">Partial Approved</option>
                                        <option  value="3">Reject</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 30px">
                                <div class="col-sm-3">
                                    <v-date-picker v-model="filter.from_date" :config="dateOptions" style="width: 100%;" placeholder="From date"></v-date-picker>
                                </div>   
                                <div class="col-sm-3">
                                    <v-date-picker v-model="filter.to_date" :config="dateOptions" style="width: 100%;" placeholder="To date"></v-date-picker>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" name="search_key" placeholder="Search by keyword" class="form-control" v-model="filter.search_key" style="width: 100%;" autocomplete="off">
                                </div>    
                                <div class="col-sm-1">
                                    <button class="btn btn-primary" @click="getResults(1)"><i class="fa fa-search"></i> Search</button>
                                </div>
                                <div class="col-sm-1" style="padding-left: 0">
                                    <button type="button" class="btn btn-secondary"><i class="fa fa-print"></i> Print <i class="fa fa-caret-down"></i></button>
                                </div>
                                <div class="col-sm-2" style="padding-left: 0">
                                    <a class="btn btn-success btn-xs"
                                    href="{{url('accounts/signatory-config-data',"comparative-statement")}}"
                                    data-target="#globalModal" data-toggle="modal"
                                    data-modal-size="modal-lg">signatory-config</a>
                                </div>
                            </div>
                        </form>
                        
                        <div class="table-responsive" style="max-height: 500px">
                            <table class="table table-striped table-bordered m-b-0">
                                <thead>
                                    <tr>
                                        <th width="6%">Select</th>
                                        <th class="sortable" v-bind:class="getSortingClass('voucher_no')" @click="sortingChanged('voucher_no')">Voucher #</th>
                                        <th class="sortable" v-bind:class="getSortingClass('item_id')" @click="sortingChanged('item_id')">Item Name</th>
                                        <th class="sortable" v-bind:class="getSortingClass('date')" @click="sortingChanged('date')">Date</th>
                                        <th class="sortable" v-bind:class="getSortingClass('inventory_comparative_statement_details_info.status')" @click="sortingChanged('inventory_comparative_statement_details_info.status')">Status</th>
                                        <th>Remarks</th>
                                        <th>Approved By</th>
                                        <th width="22%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="Object.keys(paginate_data).length > 0">
                                        <tr v-for="(list, index) in paginate_data" v-bind:key="index">
                                            <td><input type="checkbox" true-value="1" false-value="0" v-model="list.del_id" @change="deleteCheck(list.del_id, list.id)"></td>
                                            <td>@{{list.voucher_no}}</td>
                                            <td>@{{list.product_name}}</td>
                                            <td>@{{list.stock_in_date}}</td>
                                            <td>
                                                <span v-if="list.status==0">Pending</span>
                                                <span v-if="list.status==1">Approved</span>
                                                <span v-if="list.status==2">Partial Approved</span>
                                                <span v-if="list.status==3">Reject</span>
                                            </td>
                                            <td>@{{list.remarks}}</td>
                                            <td v-html="list.approved_text"></td>
                                            <td>
                                                <template v-if="dataList.pageAccessData['inventory/comparative-statement.delete'] || dataList.pageAccessData['inventory/comparative-statement.show'] || dataList.pageAccessData['inventory/comparative-statement.approval']">
                                                    <a v-if="list.has_approval=='yes' && dataList.pageAccessData['inventory/comparative-statement.approval']" class="btn btn-primary btn-xs" @click="voucherApproval('comparative-statement-approval',list.id)"
                                                    ><i class="fa fa-check-square" aria-hidden="true"></i> Approved</a>
                                                    <a v-if="list.status==0 && dataList.pageAccessData['inventory/comparative-statement.delete']"  @click="deleteItem(list.id)"
                                                        class="btn btn-danger btn-xs" data-placement="top"
                                                        data-content="delete" title="delete"><i class="fa fa-trash-o"></i></a>
                                                    <a v-if="dataList.pageAccessData['inventory/comparative-statement.show']" class="btn btn-primary btn-xs" @click="openModal('detailsForm', 'comparative-statement-data/'+list.cs_id)" 
                                                       ><i class="fa fa-info-circle" aria-hidden="true"></i> Details</a>
                                                </template>
                                                <template v-else>
                                                    N/A
                                                </template>
                                                <a class="btn btn-primary btn-xs" :href="baseUrl+'/comparative-statement-history/'+list.cs_id"><i class="fa fa-info-circle" aria-hidden="true"></i> Cs History</a>
                                                <button class="btn btn-xs btn-success" @click="PrintPurchase(list.cs_id)"> <i class="fa fa-print"></i> Print</button>
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                  <tr>
                                    <td colspan="8" class="text-center">No Record found!</td>
                                  </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-2 p-t-15">
                                <button v-if="dataList.pageAccessData['inventory/comparative-statement.delete']" @click="deleteArrayItem()" class="btn btn-danger">Delete</button>
                            </div>
                            <div class="col-sm-10 pull-right">
                                <pagination v-model="currentPageNo" :total-page="totalPage" @change="getResults" :max-size="10"/>
                            </div>
                        </div>                        
                    </div>
                </div>
            </section>            
        </div>
        <div v-if="pageLoader" class="loading-screen">
          <div class="loading-circle"></div>
          <p class="loading-text">Loading</p>
        </div>
    </div>

    <div class="modal" id="detailsForm" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                            aria-hidden="true">×</span></button></h4>
                    <h4 class="modal-title">Comparative Statement Details
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Voucher No</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.voucher_no}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Vendor</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.vendor_name}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Campus</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.campus_name}}
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">CS Date</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.cs_date}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Due Date</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.due_date_formate}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Instruction of</label>
                                <div class="col-md-8 p-b-15">
                                    @{{formData.name}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow">
                        <table class="responsive table table-striped table-bordered" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Item Name</th>
                                    <th>Item SKU</th>
                                    <th>Qty</th>
                                    <th>UOM</th>
                                    <th>Rate</th>
                                    <th>Amt.</th>
                                    <th>Discount</th>
                                    <th>VAT</th>
                                    <th>VAT Type</th>
                                    <th>Net Amt.</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(list, index) in formData.voucherDetailsData" v-bind:key="index">
                                    <td valign="middle">@{{index+1}}</td>
                                    <td valign="middle">@{{list.product_name}}</td>     
                                    <td valign="middle">@{{list.sku}}</td> 
                                    <td class="text-right" valign="middle">@{{parseFloat(list.qty).toFixed(list.decimal_point_place)}}</td>
                                    <td valign="middle">@{{list.uom}}</td>                   
                                    <td class="text-right" valign="middle">@{{parseFloat(list.rate).toFixed(2)}}</td> 
                                    <td class="text-right" valign="middle">@{{parseFloat(list.amount).toFixed(2)}}</td> 
                                    <td class="text-right" valign="middle">@{{parseFloat(list.discount).toFixed(2)}}</td>
                                    <td class="text-right" valign="middle">@{{list.vat_per}}</td>
                                    <td class="text-right" valign="middle">@{{list.vat_type}}</td> 
                                    <td class="text-right" valign="middle">@{{parseFloat(list.net_amount).toFixed(2)}}</td>
                                    <td valign="middle">@{{list.remarks}}</td>   
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label required">Comments:</label>
                        <div class="col-md-9 p-b-15">
                            @{{formData.comments}}
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button class="btn btn-success" @click="PrintPurchase(formData.id)"> <i class="fa fa-print"></i> Print</button>
                    <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="loader">
                    <div class="es-spinner">
                        <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                    </div>
                </div>
            </div>
        </div>
   </div>
</div>
@endsection



@section('scripts')
<script type="text/javascript">
    window.dataUrl = 'comparative-statement-data';
    window.baseUrl = '{{url('/inventory')}}';
    window.token = '{{@csrf_token()}}';
</script>
<script src="{{URL::asset('vuejs/vue.min.js') }}"></script>
<script src="{{URL::asset('vuejs/uiv.min.js') }}"></script>
<script src="{{URL::asset('vuejs/vue-multiselect.min.js') }}"></script>
<script src="{{URL::asset('vuejs/axios.min.js') }}"></script>
<script src="{{URL::asset('vuejs/vee-validate.js') }}"></script>
<script src="{{URL::asset('vuejs/vue-toastr.umd.min.js') }}"></script>
<script src="{{URL::asset('vuejs/sweetalert2.all.min.js') }}"></script>
<script src="{{URL::asset('vuejs/moment.min.js') }}"></script>
<link href="{{ URL::asset('vuejs/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
<script src="{{URL::asset('vuejs/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{URL::asset('vuejs/vue-bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{URL::asset('vuejs/mixin.js') }}"></script>
<script>
     axios.defaults.headers.common['X-CSRF-TOKEN'] = token; 
    Vue.use(VeeValidate);
    Vue.mixin(mixin);
    Vue.component('multiselect', window.VueMultiselect.default)
    Vue.component('v-date-picker', VueBootstrapDatetimePicker)
    var app = new Vue({
      el: '#app',
      data: {
        filter:{
            item_id_model:'',
            item_id:0,
            voucher_id_model:'',
            voucher_id:0,
            from_date:null,
            to_date:null,
            status
        },
        formData:{
            date:null,
            due_date:null,
            voucherDetailsData:[],
            itemAdded:'no'
        }
      },
      created(){
        this.getResults(1);
      },
      methods:{       
        PrintPurchase(id){
            const url = this.baseUrl+'/purchase/comparative-statement/print/'+id;
            window.open(url, '_blank');
        }, 
        selectFilterItem(item){
            if(item) this.filter.item_id = item.item_id;
            else this.filter.item_id = 0; 
        },
        selectVoucher(voucher){
            if(voucher) this.filter.voucher_id = voucher.id;
            else this.filter.voucher_id = 0; 
        }
        
      }
    })

</script>   
@endsection

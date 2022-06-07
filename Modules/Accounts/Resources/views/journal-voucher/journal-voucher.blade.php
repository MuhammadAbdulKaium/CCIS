@extends('layouts.master')
@section('css')
    <style type="text/css">
        .modal {
            overflow-y:auto;
        }
    </style>
@endsection
@section('content')
    <div id="app">
        <div class="content-wrapper">
            <div v-if="!pageLoader">
                <section class="content-header">
                    <h1>
                        <i class="fa fa-th-list"></i> Manage  |<small>Journal Voucher</small>
                    </h1>
                    <ul class="breadcrumb">
                        <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                        <li><a href="#">Accounts</a></li>
                        <li class="active">Journal Voucher</li>
                    </ul>
                </section>

                <section class="content">

                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-search"></i> Journal Voucher List</h3>
                            <div class="box-tools">
                                <a v-if="dataList.pageAccessData['accounts/journal-voucher-data/create']" class="btn btn-success btn-sm" @click="openModal('addForm', 'journal-voucher-data/create',true)"><i class="fa fa-plus-square"></i> New</a>
                            </div>
                        </div>
                        <div class="box-body">
                            <form action="" class="form-inline">
                                <div class="row" style="margin-bottom: 10px">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Per page</label>
                                            <select name="listPerPage" class="form-control" v-model="listPerPage" @change="getResults(1)">
                                                <option v-for="size in pageSize" :value="size">@{{size}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <v-date-picker v-model="filter.from_date" :config="dateOptions" style="width: 100%;" placeholder="From date"></v-date-picker>
                                    </div>  
                                    <div class="col-sm-3">
                                        <v-date-picker v-model="filter.to_date" :config="dateOptions" style="width: 100%;" placeholder="To date"></v-date-picker>
                                    </div>


                                    <div class="col-sm-2">
                                        <select name="status" class="form-control" v-model="filter.status" style="width:100%">
                                            <option  value="">Select Status</option>
                                            <option  value="p">Pending</option>
                                            <option  value="1">Approved</option>
                                            <option  value="2">Reject</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-2">
                                        <input type="text" name="search_key" placeholder="Search by keyword" class="form-control" v-model="filter.search_key" style="width: 100%;" autocomplete="off">
                                    </div>
                                </div>

                                <div class="row" style="margin-bottom: 10px">
                                    <div class="col-sm-9"></div>
                                    <div class="col-sm-1">
                                        <a class="btn btn-success btn-xs"
                                        href="{{url('accounts/signatory-config-data',"journal")}}"
                                        data-target="#globalModal" data-toggle="modal"
                                        data-modal-size="modal-lg">signatory-config</a>
                                    </div>
                                    <div class="col-sm-1">
                                        <button class="btn btn-primary" @click="getResults(1)"><i class="fa fa-search"></i> Search</button>
                                    </div>
                                    <div class="col-sm-1" style="padding-left: 0">
                                        <button type="button" class="btn btn-secondary"><i class="fa fa-print"></i> Print <i class="fa fa-caret-down"></i></button>
                                    </div>
                                    
                                </div>

                            </form>

                            <div class="table-responsive" style="max-height: 500px">
                                <table class="table table-striped table-bordered m-b-0">
                                    <thead>
                                    <tr>
                                        <th width="6%">#</th>
                                        <th class="sortable" v-bind:class="getSortingClass('voucher_no')" @click="sortingChanged('voucher_no')">Voucher #</th>
                                        <th class="sortable" v-bind:class="getSortingClass('amount')" @click="sortingChanged('amount')">Amount</th>
                                        <th class="sortable" v-bind:class="getSortingClass('trans_date')" @click="sortingChanged('trans_date')">Date</th>
                                        <th class="sortable" v-bind:class="getSortingClass('accounts_transaction.status')" @click="sortingChanged('accounts_transaction.status')">Status</th>
                                        <th>Approved By</th>
                                        <th>Remarks</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <template v-if="Object.keys(paginate_data).length > 0">
                                        <tr v-for="(list, index) in paginate_data" v-bind:key="index">
                                            <td>@{{previousPageTotal+index+1}}</td>
                                            <td>@{{list.voucher_no}}</td>
                                            <td>@{{list.amount}}</td>
                                            <td>@{{list.trans_date_formate}}</td>
                                            <td>
                                                <span v-if="list.status==0">Pending</span>
                                                <span v-if="list.status==1">Approved</span>
                                                <span v-if="list.status==2">Reject</span>
                                            </td>

                                            <td v-html="list.approved_text"></td>

                                            <td>@{{list.remarks}}</td>
                                            <td>
                                                <a v-if="list.has_approval=='yes' && dataList.pageAccessData['accounts/journal-voucher.approval']" class="btn btn-primary btn-xs" @click="voucherApproval('journal-voucher-approval',list.id)"
                                                ><i class="fa fa-check-square" aria-hidden="true"></i> Approve</a>
                                                <a v-if="list.status==0 && !list.someOneApproved && dataList.pageAccessData['accounts/journal-voucher.edit']" class="btn btn-primary btn-xs" @click="openModal('addForm', 'journal-voucher-data/'+list.id+'/edit')" title="Edit"><i class="fa fa-edit"></i></a>
                                                <a v-if="list.status==0 && dataList.pageAccessData['accounts/journal-voucher.delete']"  @click="deleteItem(list.id)"
                                                   class="btn btn-danger btn-xs" data-placement="top"
                                                   data-content="delete" title="delete"><i class="fa fa-trash-o"></i></a>
                                                <a v-if="dataList.pageAccessData['accounts/journal-voucher.show']" class="btn btn-primary btn-xs" @click="openModal('detailsForm', 'journal-voucher-data/'+list.id)"
                                                ><i class="fa fa-info-circle" aria-hidden="true"></i> Details</a>
                                                <button type="submit" class="btn btn-success btn-xs" @click="print('journal-voucher/'+list.id)">
                                                    <span><i class="fa fa-print"></i> Print</span>
                                                </button>
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
                                <div class="col-sm-12 pull-right">
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

        <!-- Modal form -->

        <div class="modal" id="addForm" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="width:1100px">
                <form class="form-horizontal" @submit.prevent="submitForm(formData,{},initNewVoucherData)">
                    <div class="modal-content" v-if="!pageLoader">
                        <div class="modal-header">
                            <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                                        aria-hidden="true">×</span></button>
                            <h4 class="modal-title">
                                <span v-if="formData.id">Edit Journal Voucher</span>
                                <span v-else>New Journal Voucher</span>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger alert-auto-hide" v-if="errorsList.length>0">
                                <ul>
                                    <li v-for="(error, i) in errorsList" v-bind:key="i">@{{error}}</li>
                                </ul>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label required">Voucher No <span class="text-danger">*</span></label>
                                        <div class="col-md-8 p-b-15">
                                            <input type="text" name="voucher_no" v-model="formData.voucher_no"  class="form-control" data-vv-as="voucher name" v-validate="'required'" :readonly="formData.auto_voucher" placeholder="Enter voucher no">
                                            <span class="error" v-if="$validator.errors.has('voucher_no')">@{{$validator.errors.first('voucher_no')}}</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label required">Manual Ref. no</label>
                                        <div class="col-md-8 p-b-15">
                                            <input type="text" name="manual_ref_no" v-model="formData.manual_ref_no"  class="form-control" placeholder="Ref. no">
                                        </div>
                                    </div>

                                    
                                </div>
                                <div class="col-sm-6">

                                    <div class="form-group">
                                        <label class="col-md-4 control-label required">Journal Date <span class="text-danger">*</span></label>
                                        <div class="col-md-8 p-b-15">
                                            <v-date-picker name="trans_date" data-vv-as="Date" v-validate="'required'" v-model="formData.trans_date" :config="dateOptions" style="width: 100%;" placeholder="dd/mm/yyyy"></v-date-picker>
                                            <span class="error" v-if="$validator.errors.has('trans_date')">@{{$validator.errors.first('trans_date')}}</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label required">Narration <span class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <textarea name="remarks" v-model="formData.remarks" placeholder="Narration" class="form-control" data-vv-as="Note" v-validate="'required'" maxlength="255"></textarea>
                                            <span class="error" v-if="$validator.errors.has('remarks')">@{{$validator.errors.first('remarks')}}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <hr style="margin:0 0 15px 0">

                            <table class="responsive table table-striped table-bordered" cellspacing="0">
                                <thead>
                                <tr>
                                    <th width="25%">Debit Ledger <span class="text-danger">*</span></th>
                                    <th>Debit Amount <span class="text-danger">*</span></th>
                                    <th width="25%">Credit Ledger <span class="text-danger">*</span></th>
                                    <th>Credit Amount <span class="text-danger">*</span></th>
                                    <th>Rmarks</th>
                                    <th class="text-center" width="10%">
                                        Action
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td valign="middle" class="text-center" rowspan="2" style="vertical-align:middle;">
                                        <multiselect :select-label="''" :deselect-label="''" v-if="modalData.ledger" name="dr_sub_ledger_model" v-model="gridData.dr_sub_ledger_model" :options="modalData.ledger" placeholder="Select Ledger" label="accountCodeView" track-by="id" @input="selectDrLedger" :options-limit="10000"><span slot="noResult">Oops! No elements found. Consider changing the search query.</span></multiselect>
                                    </td>
                                    <td valign="middle" class="text-right">
                                        <input type="number" name="dr_amount" v-model="gridData.dr_amount"  class="form-control" placeholder="e.g.5000">
                                    </td>
                                    <td valign="middle">
                                        <multiselect :select-label="''" :deselect-label="''" v-if="modalData.ledger" name="cr_sub_ledger_model" v-model="gridData.cr_sub_ledger_model" :options="modalData.ledger" placeholder="Select Ledger" label="accountCodeView" track-by="id" @input="selectCrLedger" :options-limit="10000"><span slot="noResult">Oops! No elements found. Consider changing the search query.</span></multiselect>
                                    </td>
                                    <td valign="middle" class="text-right">
                                        <input type="number" name="cr_amount" v-model="gridData.cr_amount"  class="form-control" placeholder="e.g.5000">
                                    </td>
                                    <td class="text-right" valign="middle">
                                        <input type="text" name="narration" v-model="gridData.narration" class="form-control" placeholder="Remarks">
                                    </td>
                                    <td class="text-center" valign="middle" rowspan="2" style="vertical-align:middle;">
                                        <button type="button" class="btn btn-success" @click="voucherGridAdd($event)">Add</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <table class="responsive table table-striped table-bordered" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>Ledger</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Remarks</th>
                                    <th class="text-center" width="16%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-if="formData.itemAdded=='yes'">
                                    <template v-for="(data, index) in formData.voucherDebitData" v-bind:key="index">
                                    <tr>
                                        <td valign="middle">@{{data.dr_accountCode}}</td>          
                                        <td valign="middle" class="text-right">@{{data.dr_amount}}</td>              
                                        <td class="text-right" valign="middle">0</td> 
                                        <td class="text-right" valign="middle">@{{data.narration}}</td>
                                        <td class="text-center" valign="middle" style="vertical-align:middle;">
                                            <button class="btn-xs btn-danger" title="Delete" @click="voucherGridRemove($event, data, 'debit')"><i class="fa fa-trash"></i></button>
                                        </td>              
                                    </tr>
                                    </template>
                                    <template v-for="(data, index) in formData.voucherCreditData" v-bind:key="index">
                                        <tr>
                                            <td valign="middle"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                @{{data.cr_accountCode}}
                                            </td>          
                                            <td valign="middle" class="text-right">0</td>              
                                            <td class="text-right" valign="middle">@{{data.cr_amount}}</td>
                                            <td class="text-right" valign="middle">@{{data.narration}}</td> 
                                            <td class="text-center" valign="middle" style="vertical-align:middle;">
                                                <button class="btn-xs btn-danger" title="Delete" @click="voucherGridRemove($event, data, 'credit')"><i class="fa fa-trash"></i></button>
                                            </td>              
                                        </tr>
                                    </template>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="6" align="center">Nothing here</td>
                                    </tr>
                                </template>

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td class="text-right"><b>Total</b></td>
                                    <td class="text-right">@{{formData.totalDebit}}</td>
                                    <td class="text-right">@{{formData.totalCredit}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" :disabled="buttonDisabled">
                                <span v-if="formData.id">Update</span>
                                <span v-else>Save</span>
                            </button>
                            <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal" id="detailsForm" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="width:1100px">
                <div class="modal-content" v-if="!pageLoader">
                    <div class="modal-header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title">
                            <span>Journal Voucher</span>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger alert-auto-hide" v-if="errorsList.length>0">
                            <ul>
                                <li v-for="(error, i) in errorsList" v-bind:key="i">@{{error}}</li>
                            </ul>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div style="text-align: center">
                                    <h4><b>@{{ formData.institute }}</b></h4>
                                    <h4><b>@{{ formData.campus }}</b></h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <span><b>Voucher No: @{{formData.voucher_no}}</b></span>
                            </div>
                            <div class="col-sm-6">
                                <span style="float: right"><b>Date: @{{formData.trans_date}}</b></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="responsive table table-striped table-bordered" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>Ledger</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Remarks</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(data, index) in formData.voucherDebitData" v-bind:key="index">
                                            <tr>
                                                <td valign="middle">@{{data.dr_accountCode}}</td>          
                                                <td valign="middle" class="text-right">@{{data.dr_amount}}</td>              
                                                <td class="text-right" valign="middle">0</td> 
                                                <td valign="middle">@{{data.remarks}}</td> 
                                            </tr>
                                        </template>
                                        <template v-for="(data, index) in formData.voucherCreditData" v-bind:key="index">
                                            <tr>
                                                <td valign="middle"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    @{{data.cr_accountCode}}
                                                </td>          
                                                <td valign="middle" class="text-right">0</td>              
                                                <td class="text-right" valign="middle">@{{data.cr_amount}}</td> 
                                                <td valign="middle">@{{data.remarks}}</td>  
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td class="text-right"><b>Total</b></td>
                                        <td class="text-right">@{{formData.totalDebit}}</td>
                                        <td class="text-right">@{{formData.totalCredit}}</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <p v-if="formData.totalDebit"><b>In Word: @{{ inWords(formData.totalDebit) }} taka only.</b></p>
                                <p><b>Remarks: </b>@{{formData.remarks}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" @click="print('journal-voucher/'+formData.id)">
                            <span><i class="fa fa-print"></i> Print</span>
                        </button>
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
</div>

@endsection





@section('scripts')
    <script type="text/javascript">
        window.dataUrl = 'journal-voucher-data';
        window.baseUrl = '{{url('/accounts')}}';
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
    <script src="{{URL::asset('vuejs/v-tooltip.min.js') }}"></script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
        Vue.use(VeeValidate);
        Vue.mixin(mixin);
        Vue.component('v-date-picker', VueBootstrapDatetimePicker)
        Vue.component('multiselect', window.VueMultiselect.default)
        var app = new Vue({
            el: '#app',
            data: {
                filter:{
                    from_date:null,
                    to_date:null,
                    status:''
                },
                formData:{
                    date:null,
                    due_date:null,
                    voucherDebitData:[],
                    voucherCreditData:[],
                    itemAdded:'no',
                    remarks:null
                }
            },
            created(){
                this.getResults(1);
            },
            methods:{
                selectDrLedger(ledger){
                    if(ledger && ledger.id>0){
                        this.$set(this.gridData, 'dr_sub_ledger', ledger.id);
                        this.$set(this.gridData, 'dr_accountCode', ledger.accountCode);
                    }else{
                        this.$set(this.gridData, 'dr_sub_ledger', 0);
                        this.$set(this.gridData, 'dr_accountCode', '');
                    }
                },
                selectCrLedger(ledger){
                    if(ledger && ledger.id>0){
                        this.$set(this.gridData, 'cr_sub_ledger', ledger.id);
                        this.$set(this.gridData, 'cr_accountCode', ledger.accountCode);
                    }else{
                        this.$set(this.gridData, 'cr_sub_ledger', 0);
                        this.$set(this.gridData, 'cr_accountCode', '');
                    }
                },
                voucherGridAdd(event){
                    event.preventDefault();
                    if((this.gridData.dr_sub_ledger && parseFloat(this.gridData.dr_amount)>0) || (this.gridData.cr_sub_ledger && parseFloat(this.gridData.cr_amount)>0)){
                        if (this.gridData.dr_sub_ledger !== this.gridData.cr_sub_ledger){
                            var checkExists =  this.gridDataExist(this.gridData);
                            if(checkExists){ 
                                alert("You have already added these Ledgers"); return false; 
                            }
                            this.$set(this.gridData, 'id', 0);
                            if(this.gridData.dr_sub_ledger){
                                this.formData.voucherDebitData.push(this.gridData);
                            }
                            if(this.gridData.cr_sub_ledger){
                                this.formData.voucherCreditData.push(this.gridData);
                            }
                            this.gridData = {};
                            this.formData.itemAdded='yes';
                            this.totalAmountCalculate();
                        } else {
                            alert('Debit ledger & Credit ledger can not be same!');
                        }
                    }else{
                        if(!this.gridData.dr_sub_ledger && !this.gridData.cr_sub_ledger){
                            alert('Please select Ledger');
                        }else if(!this.gridData.dr_amount && !this.gridData.cr_amount){
                            alert('Please enter amount');
                        }else if(this.gridData.dr_amount<=0 && this.gridData.cr_amount<=0){
                            alert('Please enter amount greater than zero');
                        }else if(this.gridData.dr_sub_ledger && !parseFloat(this.gridData.dr_amount)>0){
                            alert('Please enter dr amount greater than zero');
                        }else if(this.gridData.cr_sub_ledger && !parseFloat(this.gridData.cr_amount)>0){
                            alert('Please enter cr amount greater than zero');
                        }
                    }
                },
                voucherGridRemove(event, data, type){
                    event.preventDefault();
                    if(type=='debit'){
                        let index = this.formData.voucherDebitData.indexOf(data);
                        this.formData.voucherDebitData.splice(index, 1);
                        // pair ledger data remove
                        if(data.dr_sub_ledger && data.cr_sub_ledger){
                            this.formData.voucherCreditData.forEach(function(v,key){
                                if(+data.dr_sub_ledger==+v.dr_sub_ledger && +data.cr_sub_ledger==+v.cr_sub_ledger){
                                    v.dr_sub_ledger=0;
                                    if(v.dr_amount){
                                        v.dr_amount=0;
                                    }
                                }
                            });
                        }
                    }else{
                        let index = this.formData.voucherCreditData.indexOf(data);
                        this.formData.voucherCreditData.splice(index, 1);
                        // pair ledger data remove
                        if(data.dr_sub_ledger && data.cr_sub_ledger){
                            this.formData.voucherDebitData.forEach(function(v,key){
                                if(+data.dr_sub_ledger==+v.dr_sub_ledger && +data.cr_sub_ledger==+v.cr_sub_ledger){
                                    v.cr_sub_ledger=0;
                                    if(v.cr_amount){
                                        v.cr_amount=0;
                                    }
                                }
                            });
                        }
                    }
                    this.totalAmountCalculate();
                    if(this.formData.voucherDebitData.length<1 && this.formData.voucherCreditData.length<1){
                        this.formData.itemAdded='no';
                    }
                },
                gridDataExist(input){
                    var dataExits = false;
                    var dr_sub_ledger = input.dr_sub_ledger;
                    var cr_sub_ledger = input.cr_sub_ledger;
                    if(dr_sub_ledger){
                        this.formData.voucherDebitData.forEach(function(v, i){
                            if(+v.dr_sub_ledger == +dr_sub_ledger){
                                dataExits=true;
                            }
                        });
                        if(!dataExits){
                            this.formData.voucherCreditData.forEach(function(v, i){
                                if(+v.cr_sub_ledger == +dr_sub_ledger){
                                    dataExits=true;
                                }
                            });
                        }
                    }
                    if(!dataExits){
                        if(cr_sub_ledger){
                            this.formData.voucherCreditData.forEach(function(v, i){
                                if(+v.cr_sub_ledger == +cr_sub_ledger){
                                    dataExits=true;
                                }
                            });
                            if(!dataExits){
                                this.formData.voucherDebitData.forEach(function(v, i){
                                    if(+v.dr_sub_ledger == +cr_sub_ledger){
                                        dataExits=true;
                                    }
                                });
                            }

                        }
                    }
                    return dataExits;            
                },
                totalAmountCalculate(){
                    var totalDebit = 0; var totalCredit = 0; 
                    this.formData.voucherDebitData.forEach(function(list, index){
                        totalDebit+= Number(list.dr_amount);
                    });
                    this.formData.voucherCreditData.forEach(function(list, index){
                        totalCredit+= Number(list.cr_amount);
                    });
                    this.$set(this.formData, 'totalDebit', totalDebit);
                    this.$set(this.formData, 'totalCredit', totalCredit);
                },
                initNewVoucherData(){
                    const _this = this;
                    let URL = _this.baseUrl + '/journal-voucher-data/create';
                    axios.get(URL).then(res => {
                        if (res.data.status == 'logout') {
                            window.location.href = res.data.url;
                        } else {
                            _this.pageLoader = false;
                            if(res.data.status == 0){
                                _this.showToster({
                                    status: 0,
                                    message: res.data.message
                                });
                            }else{
                                if (typeof res.data.formData !== 'undefined') {
                                    _this.formData = res.data.formData;
                                }
                                _this.modalData = res.data;
                                if (typeof callback === 'function') {
                                    callback();
                                }
                            }
                        }
                    })
                    .catch(error => {
                        _this.pageLoader = false;
                        _this.showToster({
                            status: 0,
                            message: 'opps! something went wrong'
                        });
                    });
                }

            }
        })

    </script>
@endsection

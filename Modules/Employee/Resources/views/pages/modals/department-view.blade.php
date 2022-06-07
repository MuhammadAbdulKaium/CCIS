
   <div class="modal-header">
      <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
      <h4 class="modal-title"><i class="fa fa-eye"></i> View Employee Department | <small>{{$departmentProfile->name}}</small></h4>
   </div>
   <div class="modal-body">
      <div class="box-body table-responsive">
         <table id="w0" class="table table-striped table-bordered detail-view">
            <tbody>
               <tr>
                  <th style="width:150px">Department</th>
                  <td>{{$departmentProfile->name}}</td>
               </tr>
               <tr>
                  <th style="width:150px">Alias</th>
                  <td>{{$departmentProfile->alias}}</td>
               </tr>
               <tr>
                  <th style="width:150px">Created At</th>
                  <td>{{$departmentProfile->created_at->format('d M, Y, H:i:s A')}}</td>
               </tr>
               <tr>
                  <th style="width:150px">Created By</th>
                  <td>created by {{ $departmentProfile->createdBy->name }} </td>
               </tr>
               <tr>
                  <th style="width:150px">Updated At</th>
                  <td>{{$departmentProfile->updated_at->format('d M, Y, H:i:s A')}}</td>
               </tr>
               <tr>
                  <th style="width:150px">Updated By</th>
                  <td>updatd by {{ $departmentProfile->updatedBy->name }} </td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
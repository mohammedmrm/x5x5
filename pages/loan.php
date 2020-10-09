<?php
if(file_exists("script/_access.php")){
  require_once("script/_access.php");
  access([1,2,5]);
}
?>
<style>
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <div class="dropdown dropdown-inline" data-toggle="kt-tooltip" title="Quick actions" data-placement="top">
                    <span>اضافة سلفه</span>
                    <a data-toggle="modal" data-target="#addloansModal" class="btn btn-icon btn btn-label btn-label-brand btn-bold" data-toggle="dropdown" data-offset="0px,0px" aria-haspopup="true" aria-expanded="false">
                        <i class="flaticon2-add-1"></i>

                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end:: Subheader -->
					<!-- begin:: Content -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				السلف
			</h3>
		</div>
	</div>
    <form id="loanFillterForm">
    <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
    	<label>العميل:</label>
    	<select onchange="getloans()" id="client" name="client" class="form-control kt-input" id="branch" name="branch" data-col-index="6">
    	</select>
    </div>
    <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
      <label>الفترة الزمنية :</label>
        <div class="input-daterange input-group" id="kt_datepicker">
        <input onchange="getloans()" type="text" class="form-control kt-input" name="start" id="start" placeholder="من" data-col-index="5">
        <div class="input-group-append">
        <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
        </div>
        <input onchange="getloans()" type="text" class="form-control kt-input" name="end"  id="end" placeholder="الى" data-col-index="5">
    </div>
    </div>
	<div class="kt-portlet__body">
		<!--begin: Datatable -->
		<table class="table table-striped table-bordered table-hover table-checkable responsive no-wrap" id="tb-loans">
			       <thead>
	  						<tr>
	 							<th>ID</th>
								<th>العميل</th>
								<th>المبلغ</th>
								<th>التاريخ</th>
								<th>سحب او سلفه</th>
								<th>تعديل</th>
                            </tr>
      	            </thead>
                            <tbody id="loansTable">
                            </tbody>
                            <tfoot>
	                <tr>
	 							<th>ID</th>
								<th>العميل</th>
								<th>المبلغ</th>
								<th>التاريخ</th>
								<th>سحب او سلفه</th>
								<th>تعديل</th>

					</tr>
	           </tfoot>
		</table>
		<!--end: Datatable -->
	</div>
</form>
</div>
</div>
<!-- end:: Content -->
</div>


            <!--begin::Page Vendors(used by this page) -->
<script src="assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="assets/js/demo1/pages/components/datatables/extensions/responsive.js" type="text/javascript"></script>
<script type="text/javascript">
function getloans(){
$.ajax({
  url:"script/_getLoans.php",
  type:"POST",
  data:$("#loanFillterForm").serialize(),
  success:function(res){
   console.log(res);
   $("#tb-loans").DataTable().destroy();
   $("#loansTable").html("");
   $.each(res.data,function(){
     if(this.type == 1){
       bg="text-success";
       type = "سلفه";
     }else{
       bg="text-danger";
       type="سحب"
     }
     $("#loansTable").append(
       '<tr class="">'+
            '<td class=" fa-2x '+bg+'">'+this.l_id+'</td>'+
            '<td class=" fa-2x '+bg+'">'+this.name+'</td>'+
            '<td class=" fa-2x '+bg+'">'+formatMoney(this.price)+'</td>'+
            '<td class=" fa-2x '+bg+'">'+this.date+'</td>'+
            '<td class=" fa-2x '+bg+'">'+type+'</td>'+
            '<td class=" fa-2x '+bg+'">'+
                '<button class="btn btn-clean btn-link '+bg+'" onclick="editLoan('+this.price+','+this.client_id+',\''+this.note+'\','+this.l_id+')" data-toggle="modal" data-target="#editloansModal"><span class="flaticon-edit"></sapn></button>'+
                '<button class="btn btn-clean btn-link '+bg+'" onclick="deleteLoan('+this.l_id+')" data-toggle="modal" data-target="#deleteloansModal"><span class="flaticon-delete"></sapn></button>'+
            '</td>'+
       '</tr>');
     });
     var myTable= $('#tb-loans').DataTable({
        className: 'select-checkbox',
        "ordering": false,
        targets: 0,
        "oLanguage": {
        "sLengthMenu": "عرض _MENU_ سجل",
        "sSearch": "بحث:",

        select: {
        style: 'os',
        selector: 'td:first-child'
    }
      }
});
    },
   error:function(e){
    console.log(e);
  }
});
}
getloans();

</script>
<div class="modal fade" id="addloansModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">اضافة قضاء او ناحية او حي</h4>
        </div>
        <div class="modal-body">
		<!--begin::Portlet-->
		<div class="kt-portlet">

			<!--begin::Form-->
			<form class="kt-form" id="addloansForm">
				<div class="kt-portlet__body">
					<div class="form-group">
						<label>العميل</label>
						<select data-show-subtext="true" data-live-search="true" class="selectpicker form-control" name="loan_client" id="loan_client"  value="">
                        </select>
                        <span class="form-text text-danger" id="loan_client_err"></span>
					</div>
                    <div class="form-group">
						<label>المبلغ</label>
						<input type="name" name="loan_price" class="form-control"  placeholder="الميلغ">
						<span class="form-text  text-danger" id="loan_price_err"></span>
					</div>
	            </div>
	            <div class="kt-portlet__foot kt-portlet__foot--solid">
					<div class="kt-form__actions kt-form__actions--right">
						<button type="button" onclick="addLoan()" class="btn btn-brand">اضافة</button>
						<button type="reset" data-dismiss="modal" class="btn btn-secondary">الغاء</button>
					</div>
				</div>
			</form>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->
        </div>
      </div>

    </div>
  </div>

<div class="modal fade" id="editloansModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">تعديل السلفه</h4>
        </div>
        <div class="modal-body">
		<!--begin::Portlet-->
		<div class="kt-portlet">

			<!--begin::Form-->
			<form class="kt-form" id="editloansForm">
				<div class="kt-portlet__body">
					<div class="form-group">
						<label>العميل</label>
						<select data-show-subtext="true" data-live-search="true" class="selectpicker form-control " name="e_loan_client" id="e_loan_client"  value="">
                        </select>
                        <span class="form-text text-danger" id="e_loan_client_err"></span>
					</div>
					<div class="form-group">
						<label>المبلغ</label>
						<input type="number" id="e_loan_price" name="e_loan_price" class="form-control"  placeholder="ادخل الاسم الكامل">
						<span class="form-text  text-danger" id="e_loan_price_err"></span>
					</div>
	            </div>
	            <div class="kt-portlet__foot kt-portlet__foot--solid">
					<div class="kt-form__actions kt-form__actions--right">
						<button type="button" onclick="updateLoan()" class="btn btn-brand">حفظ التغيرات</button>
						<button type="reset" data-dismiss="modal" class="btn btn-secondary">الغاء</button>
					</div>
				</div>
                <input type="hidden" name="e_loan_id" id="editloanid"/>
			</form>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->
        </div>
      </div>

    </div>
  </div>

<script type="text/javascript" src="js/getClients.js"></script>
<script>
getClients($("#loan_client"));
getClients($("#client"));
getClients($("#e_loan_client"));
function addLoan(){
  $.ajax({
    url:"script/_addLoan.php",
    type:"POST",
    data:$("#addloansForm").serialize(),
    beforeSend:function(){

    },
    success:function(res){
       console.log(res);
       if(res.success == 1){
         $("#kt_form input").val("");
         Toast.success('تم الاضافة');
         getloans($("#loansesTable"));
       }else{
           $("#loan_client_err").text(res.error["client_err"]);
           $("#loan_price_err").text(res.error["price_err"]);
           $("#loan_note_err").text(res.error["note_err"]);
           Toast.warning("هناك بعض المدخلات غير صالحة",'خطأ');
       }

    },
    error:function(e){
     console.log(e);
     Toast.error('خطأ');
    }
  });
}
function editLoan(price,client,note,id){
  $("#editloanid").val(id);
  $('#e_loan_client').val(client);
  $('#e_loan_client').selectpicker('val',client);
  $('#e_loan_price').val(price);

}
function updateLoan(){
    $.ajax({
       url:"script/_updateLoan.php",
       type:"POST",
       data:$("#editloansForm").serialize(),
       beforeSend:function(){

       },
       success:function(res){
          console.log(res);
       if(res.success == 1){
         $("#kt_form input").val("");
          Toast.success('تم التحديث');
          getloans($("#loansesTable"));
       }else{
           $("#e_town_name_err").text(res.error["town_name_err"]);
           $("#e_town_city_err").text(res.error["town_city_err"]);
           Toast.warning("هناك بعض المدخلات غير صالحة",'خطأ');
       }

       },
       error:function(e){
        Toast.error('خطأ');
        console.log(e);
       }
    })
}
function deleteloans(id){
  if(confirm("هل انت متاكد من الحذف")){
      $.ajax({
        url:"script/_deleteLoan.php",
        type:"POST",
        data:{id:id},
        success:function(res){
          console.log(res);
         if(res.success == 1){
           Toast.success('تم الحذف');
           getloans($("#loansesTable"));
         }else{
           Toast.warning(res.msg);
         }
        } ,
        error:function(e){
          console.log(e);
        }
      });
  }
}
</script>
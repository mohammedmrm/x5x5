<style type="text/css">
.success {
  background-color: #E3FDE1;
}
.danger {
  background-color: #FFD5C8;
}
</style>

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				الصرفيات
			</h3>
		</div>
	</div>

	<div class="kt-portlet__body">
     <form id="filtterMoneyMainForm">
		<!--begin: Datatable -->

          <div class="row kt-margin-b-20">
            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            	<button type="button" data-toggle="modal" data-target="#awardMoneyMainModal" class="form-control btn text-center btn-success" >صرف</button>
            </div>
            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
             <div class="text-danger" id="MoneyMain_pay_err">

             </div>
            </div>
<!--          <div class="align-self-end justify-content-end col-lg-3 kt-margin-b-10-tablet-and-mobile">
               <label>الرصيد المتوفر :</label>
               <label id="branch_balance">$0</label>
          </div>-->
          </div>
          <fieldset><legend>فلتر</legend>
          <div class="row kt-margin-b-20">
          <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>الفترة الزمنية :</label>
            <div class="input-daterange input-group" id="kt_datepicker">
  				<input  onchange="getMoneyMain()" value="<?php echo date("Y-m-d",strtotime(' - 30 day'));?>" type="text" class="form-control kt-input" name="start" id="start" placeholder="من" data-col-index="5">
  				<div class="input-group-append">
  					<span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
  				</div>
  				<input onchange="getMoneyMain()" value="<?php echo date("Y-m-d",strtotime(' + 1 day'));?>"  type="text" class="form-control kt-input" name="end" id="end" placeholder="الى" data-col-index="5">
          	</div>
            </div>
          </div>
          </fieldset>
		<table class="table  table-bordered  responsive no-wrap" id="tb-MoneyMain">
			       <thead>
	  						<tr>
								<th>السبب</th>
								<th>المبلغ</th>
								<th>التاريخ</th>
								<th>الملاحضات</th>
								<th>حذف</th>
							</tr>
      	            </thead>
                            <tbody id="MoneyMainTable">
                            </tbody>
		</table>
        <div class="kt-section__content kt-section__content--border">
		<nav aria-label="...">
			<ul class="pagination" id="pagination">

			</ul>
        <input type="hidden" id="p" name="p" value="<?php if(!empty($_GET['p'])){ echo $_GET['p'];}else{ echo 1;}?>"/>
		</nav>
     	</div>

        </form>
		<!--end: Datatable -->
	</div>
</div>	</div>
<!-- end:: Content -->
<div class="modal fade" id="awardMoneyMainModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">صرف</h4>
        </div>
        <div class="modal-body">
		<!--begin::Portlet-->
		<div class="kt-portlet">

			<!--begin::Form-->
			<form class="kt-form" id="payMoneyMainForm">
				<div class="kt-portlet__body">

                    <div class="text-danger" id="MoneyMain_err"></div>
                    <div class="form-group">
						<label>الغرض من الصرف:</label>
						<select class="form-control selectpicker" id="reason" name="reason">
                           <option value="">-- اختر --</option>
                           <option value="كَاز">كَاز</option>
                           <option value="كارتات">كارتات</option>
                           <option value="ضيافه">ضيافه</option>
                           <option value="سلف">سلف</option>
                           <option value="حولات">حولات</option>
                           <option value="انترنيت">انترنيت</option>
                           <option value="ماء و كهرباء">ماء و كهرباء</option>
                           <option value="اجور نقل البريد">اجور نقل البريد</option>
                           <option value="ايجار عقار">ايجار عقار</option>
                        </select>
                        <span class="form-text  text-danger" id="type_err"></span>
					</div>
					<div class="form-group">
						<label>المبلغ:</label>
						<input type="number" name="price" class="form-control" placeholder="">
						<span class="form-text text-danger" id="price_err"></span>
					</div>
					<div class="form-group">
						<label>ملاحظات:</label>
						<textarea  class="form-control"  name="note"></textarea>
						<span id="note_err" class="form-text  text-danger"></span>
					</div>
	            </div>
	            <div class="kt-portlet__foot kt-portlet__foot--solid">
					<div class="kt-form__actions kt-form__actions--right">
						<button type="button" onclick="MoneyMain()" class="btn btn-brand">اضافة</button>
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
<div class="modal fade" id="editMoneyMainModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">صرف</h4>
        </div>
        <div class="modal-body">
		<!--begin::Portlet-->
		<div class="kt-portlet">

			<!--begin::Form-->
			<form class="kt-form" id="editMoneyMainForm">
				<div class="kt-portlet__body">

                    <div class="text-danger" id="a_MoneyMain_pay_err"></div>
                    <div class="form-group">
						<label>الغرض من الصرف:</label>
						<select class="form-control" id="e_reason" name="e_reason">
                           <option>-- اختر --</option>
                           <option value="Stuff">شراء مواد</option>
                           <option value="Award">مكافأة</option>
                           <option value="Fees">اجور عمل خارجية</option>
                           <option value="Refund">ارجاع مبلغ للطالب</option>
                        </select>
						<span class="form-text  text-danger" id="e_reason_err"></span>
					</div>
					<div class="form-group">
						<label>المبلغ:</label>
						<input type="number" id="e_price" name="e_price" class="form-control" placeholder="">
						<span class="form-text text-danger" id="e_price_err"></span>
					</div>
					<div class="form-group">
						<label>ملاحظات:</label>
						<textarea  class="form-control"  id="e_note" name="e_note"></textarea>
						<span id="e_note_err" class="form-text  text-danger"></span>
					</div>
                    <input type="hidden" value="0" id="editMoneyMainid" name="editMoneyMainid"/>
	            </div>
	            <div class="kt-portlet__foot kt-portlet__foot--solid">
					<div class="kt-form__actions kt-form__actions--right">
						<button type="button" onclick="updateMoneyMain()" class="btn btn-brand">حفظ التعديلات</button>
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
<!--begin::Page Vendors(used by this page) -->
<script src="assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<!--end::Page Vendors -->



<!--begin::Page Scripts(used by this page) -->
<script src="assets/js/demo1/pages/components/datatables/extensions/responsive.js" type="text/javascript"></script>
<script src="js/getBraches.js" type="text/javascript"></script>
<script type="text/javascript">

function getPays(){
$.ajax({
  url:"script/_getPays.php",
  type:"POST",
  data:$("#filtterMoneyMainForm").serialize(),
  beforeSend:function(){
    $("#pagination").html("");
  },
  success:function(res){
   console.log(res);
   $("#tb-MoneyMain").DataTable().destroy();
   $("#MoneyMainTable").html("");
   $.each(res.data,function(){
     $("#MoneyMainTable").append(
       '<tr class="'+bg+'">'+
            '<td>'+this.reason+'</td>'+
            '<td>'+formatMoney(this.price)+'</td>'+
            '<td>'+this.date+'</td>'+
            '<td >'+this.note+'</td>'+
            '<td >'+
                '<button type="button" class="btn btn-clean btn-link" onclick="deleteMoneyMain('+this.p_id+')" data-toggle="modal" data-target="#deleteloansModal"><span class="flaticon-delete"></sapn></button>'+
            '</td>'+
        '</tr>');
     });
     $("#tb-MoneyMain").DataTable().destroy();
     var myTable= $('#tb-MoneyMain').DataTable();
    },
   error:function(e){
    console.log(e);
  }
});
}

function MoneyMain(){
$.ajax({
  url:"script/_setPays.php",
  type:"POST",
  data:$("#payMoneyMainForm").serialize(),
  beforeSend:function(){
   $("#a_MoneyMain_pay_err").html("");
  },
  success:function(res){
   console.log(res);
   if(res.success == 1){
      Toast.success('تم الاضافة');
      getPays();
   }else{
      $("#reason_err").text(res.error.reason);
      $("#price_err").text(res.error.money);
      $("#note_err").text(res.error.note);
   }
  },
   error:function(e){
    console.log(e);
    Toast.error('خطأ');
  }
});
}

function editMoneyMain(id){
  $("#editMoneyMainid").val(id);
  $.ajax({
    url:"script/_getMoneyMain1.php",
    data:{id: id},
    beforeSend:function(){

    },
    success:function(res){
      if(res.success == 1){
        $.each(res.data,function(){
          $('#e_reason').val(this.reason);
          $('#e_price').val(this.MoneyMain);
          $('#e_note').val(this.note);
         });
      }
      console.log(res);
    },
    error:function(e){
      console.log(e);
    }
  });
}

function updateMoneyMain(){
    $.ajax({
       url:"script/_updateMoneyMain.php",
       type:"POST",
       data:$("#editMoneyMainForm").serialize(),
       beforeSend:function(){
          $('#e_branch_err').val('');
          $('#e_price_err').val('');
          $('#e_note_err').val('');
       },
       success:function(res){
         console.log(res);
       if(res.success == 1){
         $("#kt_form input").val("");
          Toast.success('تم التحديث');
          getMoneyMain();
          getBalance();
       }else{
           $("#e_branch_err").text(res.error["branch"]);
           $("#e_price_err").text(res.error["price"]);
           $("#e_note_err").text(res.error["note"]);
           Toast.warning(res.error.msg);
       }

       },
       error:function(e){
        Toast.error('خطأ');
        console.log(e);
       }
    });
}
function deleteMoneyMain(id){
  if(confirm("هل انت متاكد من الحذف")){
      $.ajax({
        url:"script/_deleteMoneyMain.php",
        type:"POST",
        data:{id:id},
        success:function(res){
         if(res.success == 1){
           Toast.success('تم الحذف');
           getPays();
         }else{
           Toast.warning(res.msg);
         }
         console.log(res)
        } ,
        error:function(e){
          console.log(e);
        }
      });
  }
}
function getBalance(){

  $.ajax({
    url:"script/_getBranchBalance.php",
    type:"POST",
    beforeSend:function(){
      $("#filtterMoneyMainForm").addClass("loading");
    },
    success:function(res){
      console.log(res);
      $("#filtterMoneyMainForm").removeClass("loading");
      $.each(res.data,function(){
          $("#branch_balance").text("  $"+this.balance);
      });
    },
    error:function(e){
      $("#filtterMoneyMainForm").removeClass("loading");
      console.log(e);
    }
  });
}
getBraches($('#branch'));
getPays();
</script>
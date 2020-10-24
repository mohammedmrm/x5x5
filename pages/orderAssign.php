<?php
if(file_exists("script/_access.php")){
require_once("script/_access.php");
access([1,2,3,5]);
}
?>
<style>
fieldset {
		border: 1px solid #ddd !important;
		margin: 0;
		xmin-width: 0;
		padding: 10px;
		position: relative;
		border-radius:4px;
		background-color:#f5f5f5;
		padding-left:10px !important;
		width:100%;
}
legend
{
	font-size:14px;
	font-weight:bold;
	margin-bottom: 0px;
	width: 55%;
	border: 1px solid #ddd;
	border-radius: 4px;
	padding: 5px 5px 5px 10px;
	background-color: #ffffff;
}
</style>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">

    </div>
</div>
<!-- end:: Subheader -->
					<!-- begin:: Content -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
			  الطلبيات
			</h3>
		</div>
	</div>

	<div class="kt-portlet__body">
		<!--begin: Datatable -->
        <form id="ordertabledata" class="kt-form kt-form--fit kt-margin-b-20">
          <fieldset><legend>فلتر</legend>
          <div class="row kt-margin-b-20">
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>الفرع:</label>
            	<select onchange="getclient()" class="form-control kt-input" id="branch" name="branch" data-col-index="6">
            	</select>
            </div>
           <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>العميل:</label>
            	<select onchange="getorders();getStores($('#store'),$(this).val());" data-show-subtext="true" data-live-search="true"  class="selectpicker form-control kt-input" id="client" name="client" data-col-index="7">
            		<option value="">Select</option>
            	</select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>الصفحة (البيج):</label>
            	<select onchange="getorders()" data-show-subtext="true" data-live-search="true"  class="selectpicker form-control kt-input" id="store" name="store" data-col-index="7">
            		<option value="">Select</option>
            	</select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>الحالة:</label>
            	<select onchange="getorders()" class="form-control kt-input" id="orderStatus" name="orderStatus" data-col-index="7">
            		<option value="">Select</option>
            	</select>
            </div>
            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>الفترة الزمنية :</label>
            <div class="input-daterange input-group" id="kt_datepicker">
  				<input onchange="getorders()" type="text" class="form-control kt-input" name="start" id="start" placeholder="من" data-col-index="5">
  				<div class="input-group-append">
  					<span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
  				</div>
  				<input onchange="getorders()" type="text" class="form-control kt-input" name="end"  id="end" placeholder="الى" data-col-index="5">
          	</div>
            </div>
          </div>
          <div class="row kt-margin-b-20">
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>رقم الوصل:</label>
            	<input id="order_no" name="order_no" onkeyup="getorders()" type="text" class="form-control kt-input" placeholder="" data-col-index="0">
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>اسم او هاتف المستلم:</label>
            	<input name="customer" onkeyup="getorders()" type="text" class="form-control kt-input" placeholder="" data-col-index="1">
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>المحافظة المرسل لها:</label>
            	<select id="city" name="city" onchange="getorders();getTowns2($('#town'),$(this).val());" class="form-control kt-input" data-col-index="2">
            		<option value="">Select</option>
                </select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>المنطقه:</label>
                <select id="town" name="town" data-live-search="true" onchange="getorders()" class="form-control kt-input" data-col-index="2">
            	</select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>حاله الاحاله:</label>
            	<select id="assignStatus" name="assignStatus" onchange="getclient()" class="form-control kt-input" data-col-index="2">
            		<option value="1">الطلبات غير المحاله</option>
            		<option value="2">الطلبيات المحاله</option>
            		<option value="3">الكل</option>
                </select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>حالة تسليم المبلغ للعميل:</label>
                <select name="money_status" onchange="getorders()" class="form-control kt-input" data-col-index="2">
            		<option value="">... اختر...</option>
            		<option value="1">تم تسليم المبلغ</option>
            		<option value="0">لم يتم تسليم المبلغ</option>
                </select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>بغداد او محافظات:</label>
            	<select id="BOrO" name="BOrO" onchange="getorders()" class="form-control kt-input" data-col-index="2">
            		<option value="all">الكل</option>
            		<option value="1">يغداد</option>
            		<option value="2">محافظات</option>
                </select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                	<label>عدد السجلات</label>
                	<input onchange="getorders()" type="number" value="10" class="form-control kt-input" name="limit" data-col-index="7" />
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                	<label class=" fa-2x">عدد الطلبيات</label><br />
                	<label class="text-info fa-2x" id="orders_count">0</label>
            </div>
          </div>
          </fieldset>
        <table class="table table-striped  table-bordered responsive nowrap" id="tb-orders">
			       <thead>
	  						<tr>
										<th><input  id="allselector" type="checkbox"><span></span></th>
										<th>رقم الشحنه</th>
                                        <th>رقم الوصل</th>
										<th >اسم و هاتف العميل</th>
										<th >رقم هاتف المستلم</th>
										<th>عنوان المستلم</th>
										<th>شركه التوصل</th>
										<th>مبلغ الوصل</th>
                                        <th>مبلغ التوصيل</th>
                                        <th>الخصم</th>
                                        <th>حالة المبلغ</th>
                                        <th >التاريخ</th>
						   </tr>
      	            </thead>
                            <tbody id="ordersTable">
                            </tbody>
		</table>
        <div class="kt-section__content kt-section__content--border">
		<nav aria-label="...">
			<ul class="pagination" id="pagination">

			</ul>
        <input type="hidden" id="p" name="p" value="<?php if(!empty($_GET['p'])){ echo $_GET['p'];}else{ echo 1;}?>"/>
		</nav>
     	</div>
        <hr />
          <fieldset><legend>التحديثات</legend>
          <div class="row kt-margin-b-20">
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>شركة التوصيل:</label>
            	<select onchange="getApiStore($(this).val())" class="selectpicker form-control kt-input" data-live-search="true" name="company" id="company" data-col-index="2">
            		<option value="">... اختر شركه التوصيل ...</option>
            	</select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>احاله المحدد بأسم السوق:</label>
            	<select  id="apistore" name="apistore" class="selectpicker form-control kt-input" data-col-index="2">
            		<option value="">... اختر ...</option>
            	</select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>احالة:</label>
            	<input type="button" onclick="sendOrders()" class="form-control btn btn-success" value="نفذ" />
            </div>
          </div>
          </fieldset>
        </form>
		<!--end: Datatable -->
	</div>
</div>	</div>
<!-- end:: Content -->				</div>
<!--begin::Page Vendors(used by this page) -->
<script src="assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="assets/js/demo1/pages/components/datatables/extensions/responsive.js" type="text/javascript"></script>
<script src="js/getClients.js" type="text/javascript"></script>
<script src="js/getStores.js" type="text/javascript"></script>
<script src="js/getorderStatus.js" type="text/javascript"></script>
<script src="js/getCompanies.js" type="text/javascript"></script>
<script src="js/getCities.js" type="text/javascript"></script>
<script src="js/getBraches.js" type="text/javascript"></script>
<script type="text/javascript">
function download_Receipts(){
    var domain = "script/downloadReceipts.php?";
    var data = $("#ordertabledata").serialize()+"&islimited=1";
    window.open(domain + data, '_blank');
}
getStores($('#store'));
function getorders(){
$.ajax({
  url:"script/_getOrders.php",
  type:"POST",
  data:$("#ordertabledata").serialize(),
  beforeSend:function(){
    $("#tb-orders").addClass("loading");
  },
  success:function(res){
   console.log(res);
   //saveEventDataLocally(res)
   $("#tb-orders").removeClass("loading");
   $("#tb-orders").DataTable().destroy();
   $('#ordersTable').html("");
   $("#pagination").html("");
   if(res.pages >= 1){
     if(res.page > 1){
         $("#pagination").append(
          '<li class="page-item"><a href="#" onclick="getorderspage('+(Number(res.page)-1)+')" class="page-link">السابق</a></li>'
         );
     }else{
         $("#pagination").append(
          '<li class="page-item disabled"><a href="#" class="page-link">السابق</a></li>'
         );
     }
     if(Number(res.pages) <= 5){
       i = 1;
     }else{
       i =  Number(res.page) - 5;
     }
     if(i <=0 ){
       i=1;
     }
     for(i; i <= res.pages; i++){
       if(res.page != i){
         $("#pagination").append(
          '<li class="page-item"><a href="#" onclick="getorderspage('+(i)+')"  class="page-link">'+i+'</a></li>'
         );
       }else{
         $("#pagination").append(
          '<li class="page-item active"><span class="page-link">'+i+'</span></li>'
         );
       }
       if(i == Number(res.page) + 5 ){
         break;
       }
     }
     if(res.page < res.pages){
         $("#pagination").append(
          '<li class="page-item"><a href="#" onclick="getorderspage('+(Number(res.page)+1)+')" class="page-link">التالي</a></li>'
         );
     }else{
         $("#pagination").append(
          '<li class="page-item disabled"><a href="#" class="page-link">التالي</a></li>'
         );
     }
   }
   $("#orders_count").text(res.orders);
   i=0;
   $.each(res.data,function(){
      $('#ordersTable').append(
       '<tr>'+
            '<td class=""><input type="checkbox" value="'+this.id+'" name="ids[]" rowid="'+this.id+'"><span></span></td>'+
            '<td>'+this.id+'</td>'+
            '<td>'+this.order_no+'</td>'+
            '<td>'+this.store_name+'<br />'+phone_format(this.client_phone)+'</td>'+
            '<td>'+phone_format(this.customer_phone)+'</td>'+
            '<td>'+this.city+'/'+this.town+'<br />'+this.address+'</td>'+
            '<td>'+this.dev_comp_name+'</td>'+
            '<td>'+formatMoney(this.total_price)+'</td>'+
            '<td>'+formatMoney(this.dev_price)+'</td>'+
            '<td>'+formatMoney(this.discount)+'</td>'+
            '<td>'+this.money_status+'</td>'+
            '<td>'+this.date+'</td>'+
         '</tr>');
     });

     var myTable= $('#tb-orders').DataTable({
       "bPaginate": false,
       "bLengthChange": false,
       "bFilter": false,
       serverPaging: true,
       "scrollX":true,
      });

    },
   error:function(e){
     $("#tb-orders").removeClass("loading");
    console.log(e);
  }
});
}
function getTowns2(elem,city){
   $.ajax({
     url:"script/_getTowns.php",
     type:"POST",
     data:{city: city},
     beforeSent:function(){

     },
     success:function(res){
       elem.html("");
       elem.append("<option value=''>-- اختر --</option>");
       $.each(res.data,function(){
         elem.append("<option value='"+this.id+"'>"+this.name+"</option>");
       });
       elem.selectpicker('refresh');
       console.log(res);
     },
     error:function(e){
        elem.append("<option value='' class='bg-danger'>خطأ اتصل بمصمم النظام</option>");
        console.log(e);
     }
   });
}
getTowns2($("#town"),1);
function getorderspage(page){
    $("#p").val(page);
    getorders();
}
getClients($("#client"));
function getclient(){
 getClients($("#client"),$("#branch").val());
 getorders();
}

$(document).ready(function(){
  getBraches($("#branch"));
  getCompanies($('#company'));
  getorders();
$("#allselector").change(function() {
    var ischecked= $(this).is(':checked');
    if(!ischecked){
      $('input[name="ids\[\]"]').attr('checked', false);;
    }else{
      $('input[name="ids\[\]"]').attr('checked', true);;
    }
});
$('#start').datepicker({
    format: "yyyy-mm-dd",
    showMeridian: true,
    todayHighlight: true,
    autoclose: true,
    pickerPosition: 'bottom-left',
    defaultDate:'now'
});
$('#end').datepicker({
    format: "yyyy-mm-dd",
    showMeridian: true,
    todayHighlight: true,
    autoclose: true,
    pickerPosition: 'bottom-left',
    defaultDate:'now'
});
getorderStatus($("#orderStatus"));
getCities($("#city"));
});
function getApiStore(id){
      $.ajax({
        url:"script/_getApiStore.php",
        type:"POST",
        data:$("#ordertabledata").serialize(),
        success:function(res){
          $("#apistore").html("");
          console.log(res);
          if(res.response != null){
          if(res.response.success == 1){
            $.each(res.response.data,function(){
              $("#apistore").append(
                '<option value="'+this.id+'">'+this.name+'</option>'
              );
            });
          }else{
            toastr.warning("لايمكن طلب تحميل الاسواق",'فشل الاتصال');
          }
          }else{
            toastr.warning("تاكد من معلومات الشركه",'فشل الاتصال');
          }
           $("#apistore").selectpicker("refresh");
        },
        error:function(e){
          toastr.error("خطأ!");
          console.log(e);
        }
      });
}
function sendOrders(){
      $.ajax({
        url:"script/_sendOrders.php",
        type:"POST",
        data:$("#ordertabledata").serialize(),
        success:function(res){
          console.log(res);
          Toast.success("تم الاحاله "+res.response.count.added + " شحنه");
          if(res.response.count.not > 0){
            Toast.warning(res.response.count.not + " شحنه محاله مسبقاً");
          }
          getorders();
        },
        error:function(e){
           Toast.error("خطأ!");
          console.log(e);
        }
      });
}
</script>

  <div class="modal fade" id="editorderStatusModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">تحديث حالة الطلب</h4>
        </div>
        <div class="modal-body">
		<!--begin::Portlet-->
		<div class="kt-portlet">

			<!--begin::Form-->
			<form class="kt-form" id="addClientForm">
				<div class="kt-portlet__body">
					<div class="form-group">
						<label>الحالة</label>
						<select data-show-subtext="true" data-live-search="true" type="text" class="selectpicker form-control dropdown-primary" name="orderStatus" id="orderStatus"  value="">
                        </select>
                        <span class="form-text text-danger" id="orderStatus_err"></span>
					</div>
					<div class="form-group">
						<label>ملاحظات:</label>
						<input type="name" name="rderStatus_note" class="form-control"  placeholder="">
						<span class="form-text  text-danger" id="orderStatus_note_err"></span>
					</div>
                    <div class="input-group date">
						<input size="16" type="text"  readonly class="form-control form_datetime"  placeholder="الوقت والتاريخ" id="orderStatus_date">
						<div class="input-group-append">
							<span class="input-group-text">
							<i class="la la-calendar glyphicon-th"></i>
							</span>
						</div>
						<span class="form-text  text-danger" id="orderStatus_date_err"></span>
					</div>
	            </div>
	            <div class="kt-portlet__foot kt-portlet__foot--solid">
					<div class="kt-form__actions kt-form__actions--right">
						<button type="button" onclick="addClient()" class="btn btn-brand">اضافة</button>
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
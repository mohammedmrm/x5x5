<?php
if(file_exists("script/_access.php")){
  require_once("script/_access.php");
  access([1,2,3,5,6]);
}
?>
<?
include("config.php");
?>
<style>

@media print {
  body * {
    visibility: hidden;

  }
  #printReportForm, .header{
    display: none;
  }

  #section-to-print, #section-to-print * {
    visibility: visible;
    color: #000000;

  }
  #section-to-print {
    //position: absolute;
    margin:0px;
    padding: 0px;
    left: 0;

  }
  .dele, .edit{
   visibility: hidden;
   display: none;
  }
}
.text-white {
  color: #FFFFFF;
  padding: 15px;
}
 .form-group {
    margin-bottom: 0rem;
}
.sp {
  background-color:#FDF5E6;
}
#order-section input:focus, #order-section button:focus,  #order-section textarea:focus {
  border: 3px solid #8B0000 !important;
}
@page {
  size: landscape;
  margin: 5mm 5mm 5mm 5mm;
  }
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">

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
				اضافه طلبيات
			</h3>
		</div>
	</div>


	<div class="kt-portlet__body">
    <form id="orderstabledata" class="kt-form kt-form--fit kt-margin-b-20">
         <div id="order-section">
         <div class="row">
            <div class="form-group col-lg-2">
                <label> اضافه عميل و صفحه</label><br />
                <input data-toggle="modal" data-target="#addClientModal" type="button" class="btn btn-primary" name="add_client" id="add_client" value="اضافة عميل و صفحه"/>
             </div>
            <div class="form-group col-lg-2">
                <label> اضافه صفحه فقط</label><br />
                <input data-toggle="modal" data-target="#addStoreModal" type="button" class="btn btn-success" name="add_client"  value="اضافه صفحه فقط"/>
            </div>
         </div>
         <div class="row">
         <div class="col-lg-6">
			<div class="form-group"  >
				<label>الصفحه (البيج)</label>
				<select store='store' tabindex="1" autofocus  data-show-subtext="true" data-live-search="true" type="text" class="selectpicker  form-control dropdown-primary" name="store" id="store"  value="">

                </select>
                <span id="store_err" storeerr="storeerr" class="form-text text-danger"></span>
			</div>
            <div class="form-group">
				<label>رقم الهاتف المستلم</label>
				<input  type="tel" tabindex="3"  phone="phone" style="direction: ltr !important;"  data-inputmask="'mask': '9999-999-9999'" value="" class="form-control sp" noseq="1" id="customer_phone" name="customer_phone"/>
				<span id="customer_phone_err"  class="form-text text-danger"></span>
			</div>
            <div class="form-group">
				<label>المبلغ الكلي</label>
				<input   price="price" tabindex="7"  onkeyup="CurrencyFormatted($(this),$(this).val())" type="text" class="form-control sp" id="order" name="order_price" placeholder="المبلغ" value="">
				<span id="order_price_err" class="form-text text-danger"></span>
			</div>
            <div class="form-group">
				<label>اضافه على كل حال</label>
				<input  type="checkbox" checkbox="checkbox" onclick="checkanyway()"  class="form-control" id="checkbox" name="checkbox" />
				<input  type="hidden" class="form-control" id="check" name="check"/>
				<span id="check_err"  class="form-text text-danger"></span>
			</div>
            <input type='hidden' order='order' id="order_id" value='' name='order_id'>
           </div>
           <div class="col-lg-6">
                <div class="form-group">
                	<label>رقم الوصل:</label>
                	<input  no="no" tabindex="2"  id="order_no1" name="order_no" barcode="barcode"  type="text" class="form-control kt-input sp" placeholder="">
                   <span id="order_no_err" class="form-text text-danger"></span>
                </div>
    			<div class="row">
        			<div class="form-group col-md-4">
        				<label>المحافظة المرسل لها</label>
        				<select  city="city" tabindex="4"  onchange='getTowns($("#town"),$("#city").val())' city="city" data-show-subtext="true" data-live-search="true" type="text" class="selectpicker  form-control dropdown-primary" name="city" id="city"  value="">

                        </select>
                        <span id="city_err"class="form-text text-danger"></span>
        			</div>
                    <div class="form-group col-md-4">
        				<label>القضاء او المنطقه</label>
        				<select   town="town" tabindex="5"  data-show-subtext="true" data-live-search="true" type="text" class="selectpicker  form-control dropdown-primary" name="town" id="town"  value="">

                        </select>
                        <span id="town_err" class="form-text text-danger"></span>
        			</div>
                    <div class="form-group col-md-4">
        				<label>تفاصيل اكثر عن العنوان</label>
        				<textarea  address="address"  tabindex="6"  type="text" class="form-control" name="order_address" value="" style="margin-top: 0px; margin-bottom: 0px; height: 38px;" ></textarea>
        				<span id="order_address_err" class="form-text text-danger"></span>
        			</div>
    			</div>
                <div class="form-group">
    				<label>ملاحظات</label>
    				<textarea type="text" tabindex="8" note="note" class="form-control" id="order_note" name="order_note" value="" style="margin-top: 0px; margin-bottom: 0px; height: 38px;"></textarea>
    				<span id="order_note_err" class="form-text text-danger"></span>
    			</div>
           </div>
           </div>
           <input type="hidden" value="1" id="num" name="num[]"/>
          </div>
          <div class="row kt-margin-b-20 text-center">
              <button type="button" tabindex="9" onclick="addOrders()" class="btn btn-info btn-lg text-white">رفع و تاكيد الشحنه</button>
          </div>
          <input  type="hidden" value="1" id="counter"/>
		<!--begin: Datatable -->
        </form>
        <!--end: Datatable -->
	</div>

</div>

</div>
<!-- end:: Content -->
</div>
<div class="modal fade" id="addClientModal" role="dialog">
    <div class="modal-dialog modal-sm">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">اضافة عميل</h4>
        </div>
        <div class="modal-body">
		<!--begin::Portlet-->
		<div class="kt-portlet">

			<!--begin::Form-->
			<form class="kt-form" id="addClientForm">
                <div class="row">
  				  <div class="col-md-12">
  				    <div class="kt-portlet__body">
  					<div class="form-group">
  						<label>الفرع</label>
  						<select data-show-subtext="true" data-live-search="true" type="text" class="selectpicker form-control dropdown-primary" name="client_branch" id="client_branch"  value="">
                        </select>
                          <span class="form-text text-danger" id="client_branch_err"></span>
  					</div> <br /><br />
  					<div class="form-group">
  						<label>الاسم العميل:</label>
  						<input type="name" name="client_name" class="form-control"  placeholder="ادخل الاسم الكامل">
  						<span class="form-text  text-danger" id="client_name_err"></span>
  					</div>
  					<div class="form-group">
  						<label>اسم الصفحه (البيج):</label>
  						<input type="text" name="page" class="form-control" placeholder="">
  						<span class="form-text text-danger" id="page_err"></span>
  					</div>
  					<div class="form-group">
  						<label>رقم الهاتف:</label>
  						<input type="text" name="client_phone" class="form-control" placeholder="ادخل رقم الهاتف">
  						<span  id="client_phone_err"class="form-text  text-danger"></span>
  					</div>
  					<div class="form-group">
  						<label>كلمة السر:</label>
  						<input type="password" name="client_password" class="form-control" placeholder="ادخل كلمة السر">
  						<span class="form-text  text-danger" id="client_password_err"></span>
  					</div>
  	            </div>
  	            </div>
                <!--<div class="col-md-6">
                    <div class="kt-portlet__body">
    					<div class="form-group">
    						<label>سعر التوصيل بغداد:</label>
    						<input type="text" name="client_dev_price_b" class="form-control" placeholder="">
    						<span class="form-text  text-danger" id="client_dev_price_b_err"></span>
    					</div>
    					<div class="form-group">
    						<label>سعر التوصيل باقي المحافضات:</label>
    						<input type="text" name="client_dev_price_o" class="form-control" placeholder="">
    						<span class="form-text  text-danger" id="client_dev_price_o_err"></span>
    					</div>
    					<div class="form-group">
    						<label>استثنائات:</label>
    						<button type="button" onclick="addexpetionprice()" name="" class="btn btn-success" placeholder="">
                             <span class="flaticon-add"></span>&nbsp;&nbsp;اضافة سعر توصيل لمحافظة معينة
                            </button>
    					 </div>
                         <div id="exceptionCities"></div>
                    </div>
                  </div>-->
                </div>
	            <div class="kt-portlet__foot kt-portlet__foot--solid">
					<div class="kt-form__actions kt-form__actions--right">
						<button type="button" onclick="addClientAndPage()" class="btn btn-brand">اضافة</button>
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
<div class="modal fade " id="addStoreModal" role="dialog">
    <div class="modal-dialog modal-sm">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">اضافة صفحة</h4>
        </div>
        <div class="modal-body">
		<!--begin::Portlet-->
		<div class="kt-portlet">

			<!--begin::Form-->
			<form class="kt-form" id="addStoreForm">
                <div class="row">
  				  <div class="col-md-12">
  				    <div class="kt-portlet__body">
  					<div class="form-group">
  						<label>العميل</label>
  						<select data-show-subtext="true" data-live-search="true" type="text" class="selectpicker form-control dropdown-primary" name="client" id="client"  value="">
                          </select>
                          <span class="form-text text-danger" id="client_err"></span>
  					</div>
  					<div class="form-group">
  						<label>الاسم الصفحة:</label>
  						<input type="name" name="name" class="form-control"  placeholder="ادخل الاسم الكامل">
  						<span class="form-text  text-danger" id="Store_name_err"></span>
  					</div>
  					<div class="form-group">
  						<label>ملاحظات:</label>
  						<input type="name" name="note" class="form-control"  placeholder="ادخل الاسم الكامل">
  						<span class="form-text  text-danger" id="Store_note_err"></span>
  					</div>
                 </div>
  	            </div>
                </div>
	            <div class="kt-portlet__foot kt-portlet__foot--solid">
					<div class="kt-form__actions kt-form__actions--right">
						<button type="button" onclick="addStore()" class="btn btn-brand">اضافة</button>
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
<div class="modal fade" id="addtownsModal" role="dialog">
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
			<form class="kt-form" id="addtownsForm">
				<div class="kt-portlet__body">
					<div class="form-group">
						<label>المدينة</label>
						<select data-show-subtext="true" data-live-search="true" type="text" class="selectpicker form-control dropdown-primary" name="town_city" id="town_city"  value="">
                        </select>
                        <span class="form-text text-danger" id="town_city_err"></span>
					</div>
                    <div class="form-group">
						<label>اسم المنطقة:</label>
						<input type="name" name="town_name" class="form-control"  placeholder="اسم الحالة">
						<span class="form-text  text-danger" id="town_name_err"></span>
					</div>
					<div class="form-group">
                     <input type="checkbox" name="center" id="center" /> مركز
                    </div>
	            </div>
	            <div class="kt-portlet__foot kt-portlet__foot--solid">
					<div class="kt-form__actions kt-form__actions--right">
						<button type="button" onclick="addtowns()" class="btn btn-brand">اضافة</button>
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
<script src="js/getClients.js" type="text/javascript"></script>
<script src="js/getStores.js" type="text/javascript"></script>
<script src="js/getorderStatus.js" type="text/javascript"></script>
<script src="js/getCities.js" type="text/javascript"></script>
<script src="js/getTowns.js" type="text/javascript"></script>
<script src="js/scanner-jquery.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js" type="text/javascript"></script>
<script type="text/javascript">
getCities($("#city"));
getTowns($("#town"),$("#city").val());
getStores($("#store"));
getBraches($("#branch_to1"));
getBraches($("#client_branch"));
function checkanyway(){
 if($("#checkbox").is(':checked')){
    $("#check").val(1);
    console.log(1);
 }else{
    $("#check").val(0);
    console.log(0);
 }
}
function getAllClient(ele){
   $.ajax({
     url:"script/_getClientsAll.php",
     type:"POST",
     success:function(res){
       ele.html("");
       ele.append(
           '<option value="">... اختر ...</option>'
       );
       $.each(res.data,function(){
         ele.append("<option value='"+this.id+"'>"+this.name+"-"+this.phone+"</option>");
       });
       console.log(res);
       ele.selectpicker('refresh');
     },
     error:function(e){
        ele.append("<option value='' class='bg-danger'>خطأ اتصل بمصمم النظام</option>");
        console.log(e);
     }
   });
}
getAllClient($("#client"));
$(function () {
  $('[data-toggle="popover"]').popover()
})
function  customerHistory(){
  $('[data-toggle="popover"]').popover();
}
function getAllClient(ele){
   $.ajax({
     url:"script/_getClientsAll.php",
     type:"POST",
     success:function(res){
       ele.html("");
       ele.append(
           '<option value="">... اختر ...</option>'
       );
       $.each(res.data,function(){
         ele.append("<option value='"+this.id+"'>"+this.name+"-"+this.phone+"</option>");
       });
       console.log(res);
       ele.selectpicker('refresh');
     },
     error:function(e){
        ele.append("<option value='' class='bg-danger'>خطأ اتصل بمصمم النظام</option>");
        console.log(e);
     }
   });
}

$(document).scannerDetection({
	timeBeforeScanTest: 200, // wait for the next character for upto 200ms
	startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
	endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
	avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
	onComplete: function(barcode, qty){
	  $.ajax({
           url:"script/_getOrderBarcode.php",
           data:{id: Number(barcode) },
           type:"POST",
           success:function(res){
            console.log(res)
            if(res.success == 1){
              $.each(res.data,function(){
                $("[price='price']").last().val(this.price);
                $("[no='no']").last().val(this.order_no);
                $('[store="store"]').last().val(this.store_id);
                $("[city='city']").last().val(this.to_city);
                getTowns($("[town='town']").last(),$("[city='city']").last().val());
                $("[town='town']").last().val(this.to_town);
                $("[address='address']").last().val(this.address);
                $("[town='price']").last().val(this.price);
                $("[phone='phone']").last().val(this.customer_phone);
                $("[note='note']").last().val(this.note);
                $("[order='order']").last().val(Number(barcode));
                $("[case='case']").last().html('<h2>تاكيد فقط</h2>');
                $('.selectpicker').selectpicker('refresh');
                $("#confirmOrders").append("<input type='hidden' value='"+barcode+"' name='cOrder[]'>");
                $("#addmorenumber").val(1);
                addMore();
              });
            }
           },
           error:function(e){
             console.log(e);
           },
         })

     } // main callback function
});





function checkPhone(){
  var arr = [];
  var id = [];
  var out = [];
  counts={};
  $("input[name='customer_phone']").each(function() {
        arr.push($(this).val());
        id.push($(this).attr("noseq"));
  });

  for (var i=0;i<arr.length;i++) {
      var item = arr[i];
      var k  = id[i];
      counts[item] = counts[item] >= 1 ? counts[item] + 1 : 1;
      if (counts[item] >= 2) {
        if($("#checkbox"+k).is(':checked')){
         $("#customer_phone_err"+(k)).text('');
         $("#customer_phone"+(k)).css('background-color',"#FDF5E6");
        }else{
         $("#customer_phone_err"+(k)).text('رقم الهاتف  مكرر');
         $("#customer_phone"+(k)).css('background-color',"#FFA07A");
         out.push(item);
        }
      }else{
         $("#customer_phone_err"+(k)).text('');
         $("#customer_phone"+(k)).css('background-color',"#FDF5E6");
      }
   }
   console.log(out);
   if(out.length == 0){
      addOrders();
    }
}

function addOrders(){
 $.ajax({
    url:"script/_addOrders-tameen.php",
    type:"POST",
    data:$("#orderstabledata").serialize(),
    beforeSend:function(){
      $('.text-danger').text('');
    },
    success:function(res){
        console.log(res);
       if(res.success == 1){
         $("#orderstabledata input[name='order_no']").val("");
         $("#orderstabledata input[name='order_price']").val("");
         $("#orderstabledata input[name='customer_name']").val("");
         $("#orderstabledata input[name='customer_phone']").val("");
         $("#orderstabledata input[name='order_note']").val("");
         $("#orderstabledata input[name='order_address']").val("");
         $('[city="city"]').val("");
         $('#store').val("");
         $('#town').val("");
         $(".selectpicker").selectpicker('refresh');
         Toast.success('تم الاضافة');
         $("#store").next().focus();
       }else{
           $("#order_no_err").text(res.error["order_no"]);
           $("#order_type_err").text(res.error["order_type"]);
           $("#order_price_err").text(res.error["order_price"]);
           $("#weight_err").text(res.error["weight"]);
           $("#qty_err").text(res.error["qty"]);
           $("#store_err").text(res.error["store"]);
           $("#store_phone_err").text(res.error["store_phone"]);
           $("#customer_name_err").text(res.error["customer_name"]);
           $("#customer_phone_err").text(res.error["customer_phone"]);
           $("#city_err").text(res.error["city"]);
           $("#town_err").text(res.error["town"]);
           $("#branch_err").text(res.error["branch_from"]);
           $("#branch_to_err").text(res.error["branch_to"]);
           $("#town_err").text(res.error["town"]);
           $("#with_dev_err").text(res.error["with_dev"]);
           $("#order_note_err").text(res.error["order_note"]);
           $("#order_address_err").text(res.error["order_address"]);
           $("#mainbranch_err").text(res.error["branch_from"]);
           $("#maincity_err").text(res.error["city"]);
           $("#mainstore_err").text(res.error["store"]);
           $("#prefix_err").text(res.error["prefix"]);

           if(
           res.error["order_no"] != null ||
           res.error["order_type"] != null ||
           res.error["order_price"] != null ||
           res.error["dev_price"] != null ||
           res.error["weight"] != null   ||
           res.error["qty"] != null
           ){$("#s1").addClass("text-danger");}
           if(
           res.error["branch_from"] != null ||
           res.error["store_phone"] != null ||
           res.error["store"] != null
           ){$("#s2").addClass("text-danger");}
           if(
           res.error["customer_name"] != null ||
           res.error["customer_phone"] != null ||
           res.error["city"]!= null ||
           res.error["town"]!= null
           ){$("#s3").addClass("text-danger");}
           if(
           res.error["branch_to"] != null
           ){$("#s4").addClass("text-danger");}
           Toast.warning("هناك بعض المدخلات غير صالحة",'');
       }
    },
    error:function(e){
      console.log(e);
       Toast.error('خطأ');
    }
  });
}

function updatetown(){
  getTowns($('[town="town"]'),$("#maincity").val());
}
function deleteOrder(no){
  $("#f"+no).fadeOut(300, function() { $(this).remove(); });;
}

$(document).ready(function(){
  //$('[type="tel"]').mask('0000-000-0000');
  $(":input").inputmask();
});
jQuery.extend(jQuery.expr[':'], {
    focusable: function (el, index, selector) {
        return $(el).is('a, button, :input, [tabindex]');
    }
});
$(document).keydown(function(e) {
if (event.which === 13 || event.keyCode === 13 ) {
     //addOrders();
}


var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
if(key == 37) {
    $(":focus").closest('.form-group').next().find('input,button,textarea').first().focus();
}
if(key == 39) {
    $(":focus").closest('.form-group').prev().find('input,button,textarea').first().focus();
}
});

function customerHistory(ref){
  $.ajax({
        url:"charts/_getCustomerHistory.php",
        type:"POST",
        data:{phone: $("#customer_phone"+ref).val()},
        beforeSend:function(){
          $("#customerHistory"+ref).addClass("loading");
        },
        success:function(res){
          $("#customerHistory"+ref).removeClass("loading");
          console.log(res);
          if(res.success == 1 && res.data[0].recieved != null){
           $.each(res.data,function(){
             $("#customerHistory"+ref).html(
             '<span style="color:#006600">المستلمة:</span><span style="color:#006600">'+this.recieved+'</span> | '+
             '<span style="color:#CC0000">الراجعه: </span><span style="color:#CC0000">'+this.returnd+'</span> | '+
             '<span style="color:#333333">اخر ملاحظه: </span><span style="color:#333333">'+this.note+'</span>'
             );
           });
          }else{
            $("#customerHistory"+ref).html(
             '<span>لايوجد بينات</span><br />'
             );
          }
        },
        error:function(e){
        $("#customerHistory").removeClass("loading");
                    $("#customerHistory").html(
             '<span>لايوجد بينات</span><br />'
        );
        }
  });
}
function getCompanies(elem){
$.ajax({
  url:"script/_getcompanies.php",
  type:"POST",
  success:function(res){
   console.log(res);
   elem.html("");
     elem.append(
       '<option value="0">الشركه الرئسيه</option>'
     );
   $.each(res.data,function(){
     elem.append(
       '<option value="'+this.id+'">'+this.name +'</option>'
     );
     elem.selectpicker('refresh');
   });
  },
  error:function(e){
    console.log(e);
  }
});
}
function CurrencyFormatted(input, blur) {
  // appends $ to value, validates decimal side
  // and puts cursor back in right position.

  // get input value
  var input_val = input.val();

  // don't validate empty input
  if (input_val === "") { return; }

  // original length
  var original_len = input_val.length;

  // initial caret position
  var caret_pos = input.prop("selectionStart");

  // check for decimal
  if (input_val.indexOf(".") >= 0) {

    // get position of first decimal
    // this prevents multiple decimals from
    // being entered
    var decimal_pos = input_val.indexOf(".");

    // split number by decimal point
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);

    // add commas to left side of number
    left_side = formatNumber(left_side);

    // validate right side
    right_side = formatNumber(right_side);

    // On blur make sure 2 numbers after decimal
    if (blur === "blur") {
      right_side += "00";
    }

    // Limit decimal to only 2 digits
    right_side = right_side.substring(0, 2);

    // join number by .
    input_val =  left_side + "." + right_side;

  } else {
    // no decimal entered
    // add commas to number
    // remove all non-digits
    input_val = formatNumber(input_val);
    input_val =  input_val;

    // final formatting
    if (blur === "blur") {
      input_val += ".00";
    }
  }

  // send updated string to input
  input.val(input_val);

  // put caret back in the right position
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}
function formatNumber(n) {
  // format number 1000000 to 1,234,567
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}
getCompanies($("#company"));


function addClientAndPage(){
$.ajax({
     url:"script/_addClientAndPage.php",
     type:"POST",
     data:$("#addClientForm").serialize(),
     beforeSend:function(){
           $("#client_name_err").text('');
           $("#client_phone_err").text('');
           $("#client_email_err").text('');
           $("#client_branch_err").text('');
           $("#client_password_err").text('');
           $("#page_err").text('');
           $("#addClientForm").addClass('loading');
     },
     success:function(res){
       $("#addClientForm").removeClass('loading');
       if(res.success == 1){
         $("#addClientForm input").val("");
         Toast.success('تم الاضافة');
         getStores($("#mainstore"));
         getStores($('[store="store"]').last());
         $('#addClientModal').modal('hide');
       }else{
           $("#client_name_err").text(res.error["client_name_err"]);
           $("#client_phone_err").text(res.error["client_phone_err"]);
           $("#client_email_err").text(res.error["client_email_err"]);
           $("#client_branch_err").text(res.error["client_branch_err"]);
           $("#client_password_err").text(res.error["client_password_err"]);
           $("#page_err").text(res.error["page_err"]);

       }
       console.log(res);
     },
     error:function(e){
       console.log(e);
       $("#addClientForm").removeClass('loading');
       Toast.error.displayDuration=5000;
       Toast.error('تأكد من المدخلات','خطأ');
     }
  });
  }
  function addStore(){
  $.ajax({
     url:"script/_addStore.php",
     type:"POST",
     data:$("#addStoreForm").serialize(),
     beforeSend:function(){
     $("#addStoreForm").addClass('loading');
     $("#Store_name_err").text('');
     $("#client_err").text('');
     $("#Store_note_err").text('');
     },
     success:function(res){
       $("#addStoreForm").removeClass('loading');
       console.log(res);
       if(res.success == 1){
         $("#addStoreForm input").val("");
         Toast.success('تم الاضافة');
         getStores($("#mainstore"));
         getStores($('[store="store"]').last());
         $('#addStoreModal').modal('hide');
       }else{
           $("#Store_name_err").text(res.error["name"]);
           $("#client_err").text(res.error["client"]);
           $("#Store_note_err").text(res.error["note"]);
           }

     },
     error:function(e){
       $("#addStoreForm").removeClass('loading');
       console.log(e);
       Toast.error.displayDuration=5000;
       Toast.error('تأكد من المدخلات','خطأ');
     }
  });
}
function addtowns(){
  $.ajax({
    url:"script/_addtowns.php",
    type:"POST",
    data:$("#addtownsForm").serialize(),
    beforeSend:function(){

    },
    success:function(res){
       console.log(res);
       if(res.success == 1){
         $("#kt_form input").val("");
         Toast.success('تم الاضافة');
          if($("#by").val() == 'city'){
             getTowns($("[town='town']").last(),$("#maincity").last().val());
          }else{
              getTowns($("[town='town']").last(),$("[city='city']").last().val());
          }
       }else{
           $("#town_name_err").text(res.error["town_err"]);
           $("#town_city_err").text(res.error["city_err"]);
           $("#center_err").text(res.error["center_err"]);
           Toast.warning("هناك بعض المدخلات غير صالحة",'خطأ');
       }

    },
    error:function(e){
     console.log(e);
     Toast.error('خطأ');
    }
  });
}

</script>
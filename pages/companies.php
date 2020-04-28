<?php
require_once("script/_access.php");
access([1]);
?>
<style>
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <div class="dropdown dropdown-inline" data-toggle="kt-tooltip" title="اضافة عميل" data-placement="top">
                    <span>اضافة شركه جديد</span>
                    <a data-toggle="modal" data-target="#addCompanyModal" class="btn btn-icon btn btn-label btn-label-brand btn-bold" data-toggle="dropdown" data-offset="0px,0px" aria-haspopup="true" aria-expanded="false">
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
				العملاء
			</h3>
		</div>
	</div>

	<div class="kt-portlet__body" id="Company_table">
		<!--begin: Datatable -->
		<table class="table table-striped- table-bordered table-hover table-checkable responsive no-wrap" id="tb-getAllcompanies">
			       <thead>
	  						<tr>
								<th>ID</th>
								<th>شعار الشركه</th>
								<th>اسم الشركه</th>
								<th>رقم الهاتف</th>
								<th>البريد الالكتروني</th>
								<th>تعديل</th>
		  					</tr>
      	            </thead>
                            <tbody id="getAllcompaniesTable">
                            </tbody>
                            <tfoot>
	                <tr>
								<th>ID</th>
								<th>شعار الشركه</th>
								<th>اسم الشركه</th>
								<th>رقم الهاتف</th>
								<th>البريد الالكتروني</th>
								<th>تعديل</th>
					</tr>
	           </tfoot>
		</table>
		<!--end: Datatable -->
	</div>
</div>	</div>
<!-- end:: Content -->				</div>


            <!--begin::Page Vendors(used by this page) -->
                            <script src="assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
                        <!--end::Page Vendors -->



            <!--begin::Page Scripts(used by this page) -->
                            <script src="assets/js/demo1/pages/components/datatables/extensions/responsive.js" type="text/javascript"></script>
                       <script type="text/javascript">
function getAllcompanies(elem){
$.ajax({
  url:"script/_getAllcompanies.php",
  type:"POST",
  beforeSend:function(){
    $("#Company_table").addClass('loading');
  },
  success:function(res){
   $("#tb-getAllcompanies").DataTable().destroy();
   console.log(res);
   elem.html("");
   $("#Company_table").removeClass('loading');
   $.each(res.data,function(){
     elem.append(
       '<tr>'+
            '<td>'+this.id+'</td>'+
            '<td>'+this.name+'</td>'+
            '<td>'+this.phone+'</td>'+
            '<td>'+this.email+'</td>'+
            '<td>'+this.branch+'</td>'+
            '<td width="150px">'+
              '<button class="btn btn-clean btn-icon-lg" onclick="editCompany('+this.id+')" data-toggle="modal" data-target="#editCompany"><span class="flaticon-edit"></sapn>'+
              '<button class="btn btn-clean btn-icon-lg" onclick="deleteCompany('+this.id+')" data-toggle="modal" data-target="#deleteCompany"><span class="flaticon-delete"></sapn>'+
            '</button></td>'+

       '</tr>');
     });
     var myTable= $('#tb-getAllcompanies').DataTable({
        targets: 0,
        "oLanguage": {
        "sLengthMenu": "عرض_MENU_سجل",
        "sSearch": "بحث:" ,
        select: {
        style: 'os',
        selector: 'td:first-child'
    }
      }
});
    },
   error:function(e){
    $("#Company_table").removeClass('loading');
    console.log(e);
  }
});
}
getAllcompanies($("#getAllcompaniesTable"));

</script>
<div class="modal fade" id="editCompany" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">تحديث بيانات العميل</h4>
        </div>
        <div class="modal-body">
		<!--begin::Portlet-->
		<div class="kt-portlet">

			<!--begin::Form-->
			<form class="kt-form" id="editCompanyForm">
				<div class="kt-portlet__body">
					<div class="form-group">
						<label>الفرع</label>
						<select data-show-subtext="true" data-live-search="true" type="text" class="selectpicker form-control dropdown-primary" name="e_Company_branch" id="e_Company_branch"  value="">
                        </select>
                        <span class="form-text text-danger" id="e_Company_branch_err"></span>
					</div>
					<div class="form-group">
						<label>الاسم الكامل:</label>
						<input type="name" id="e_Company_name" name="e_Company_name" class="form-control"  placeholder="ادخل الاسم الكامل">
						<span class="form-text  text-danger" id="e_Company_name_err"></span>
					</div>
					<div class="form-group">
						<label>الايميل:</label>
						<input type="email" id="e_Company_email" name="e_Company_email" class="form-control" placeholder="ادخل البريد الالكتروني">
						<span class="form-text text-danger" id="e_Company_email_err"></span>
					</div>
					<div class="form-group">
						<label>رقم الهاتف:</label>
						<input type="text" id="e_Company_phone" name="e_Company_phone" class="form-control" placeholder="ادخل رقم الهاتف">
						<span  id="e_Company_phone_err"class="form-text  text-danger"></span>
					</div>
					<div class="form-group">
						<label>كلمة السر:</label>
						<input type="password" id="e_Company_password" name="e_Company_password" class="form-control" placeholder="ادخل كلمة السر">
						<span class="form-text  text-danger" id="e_Company_password_err"></span>
					</div>
	            </div>
	            <div class="kt-portlet__foot kt-portlet__foot--solid">
					<div class="kt-form__actions kt-form__actions--right">
						<button type="button" onclick="updateCompany()" class="btn btn-brand">تحديث</button>
						<button type="reset" data-dismiss="modal" class="btn btn-secondary">الغاء</button>
					</div>
				</div>
                <input type="hidden" id="editCompanyid" name="editCompanyid" />
			</form>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->
        </div>
      </div>

    </div>
  </div>

<script type="text/javascript" src="js/getCities.js"></script>
<script type="text/javascript" src="js/getManagers.js"></script>
<script type="text/javascript" src="js/getBraches.js"></script>
<script>
function editCompany(id){
  $(".text-danger").text("");
  $("#editCompanyid").val(id);
  getCities($("#e_Company_city"));
  getManagers($("#e_Company_manager"));
  $.ajax({
    url:"script/_getCompanyByID.php",
    data:{id: id},
    beforeSend:function(){
      $("#editCompanyForm").addClass('loading');
    },
    success:function(res){
       $("#editCompanyForm").removeClass('loading');
      if(res.success == 1){
        $.each(res.data,function(){
          $('#e_Company_name').val(this.name);
          $('#e_Company_email').val(this.email);
          $('#e_Company_phone').val(this.phone);
          $('#e_Company_branch').selectpicker('val', this.branch_id);
        });
      }
      console.log(res);
    },
    error:function(e){
      $("#editCompanyForm").removeClass('loading');
      console.log(e);
    }
  });
}
function updateCompany(){
    $(".text-danger").text("");
    $.ajax({
       url:"script/_updateCompany.php",
       type:"POST",
       data:$("#editCompanyForm").serialize(),
       beforeSend:function(){
        $("#editCompanyForm").addClass('loading');
       },
       success:function(res){
         $("#editCompanyForm").removeClass('loading');
         console.log(res);
       if(res.success == 1){
          $("#kt_form input").val("");
          Toast.success('تم التحديث');
          getAllcompanies($("#getAllcompaniesTable"));
       }else{
           $("#e_Company_branch_err").text(res.error["Company_branch_err"]);
           $("#e_Company_name_err").text(res.error["Company_name_err"]);
           $("#e_Company_email_err").text(res.error["Company_email_err"]);
           $("#e_Company_phone_err").text(res.error["Company_phone_err"]);
           $("#e_Company_password_err").text(res.error["Company_password_err"]);
           Toast.warning("هناك بعض المدخلات غير صالحة",'خطأ');
       }
       },
       error:function(e){
        $("#editCompanyForm").removeClass('loading');
        Toast.error('خطأ');
        console.log(e);
       }
    })
}
function deleteCompany(id){
  if(confirm("هل انت متاكد من الحذف")){
      $.ajax({
        url:"script/_deleteCompany.php",
        type:"POST",
        data:{id:id},
        success:function(res){
         if(res.success == 1){
           Toast.success('تم الحذف');
           getAllcompanies($("#getAllcompaniesTable"));
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
</script>
  <!-- Modal -->
  <div class="modal fade " id="addCompanyModal" role="dialog">
    <div class="modal-dialog modal-lg">

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
			<form class="kt-form" id="addCompanyForm">
                <div class="row">
  				  <div class="col-md-6">
  				    <div class="kt-portlet__body">
  					<div class="form-group">
  						<label>الفرع</label>
  						<select data-show-subtext="true" data-live-search="true" type="text" class="selectpicker form-control dropdown-primary" name="Company_branch" id="Company_branch"  value="">
                          </select>
                          <span class="form-text text-danger" id="Company_branch_err"></span>
  					</div>
  					<div class="form-group">
  						<label>الاسم الكامل:</label>
  						<input type="name" name="Company_name" class="form-control"  placeholder="ادخل الاسم الكامل">
  						<span class="form-text  text-danger" id="Company_name_err"></span>
  					</div>
  					<div class="form-group">
  						<label>الايميل:</label>
  						<input type="email" name="Company_email" class="form-control" placeholder="ادخل البريد الالكتروني">
  						<span class="form-text text-danger" id="Company_email_err"></span>
  					</div>
  					<div class="form-group">
  						<label>رقم الهاتف:</label>
  						<input type="text" name="Company_phone" class="form-control" placeholder="ادخل رقم الهاتف">
  						<span  id="Company_phone_err"class="form-text  text-danger"></span>
  					</div>
  					<div class="form-group">
  						<label>كلمة السر:</label>
  						<input type="password" name="Company_password" class="form-control" placeholder="ادخل كلمة السر">
  						<span class="form-text  text-danger" id="Company_password_err"></span>
  					</div>
  	            </div>
  	            </div>
                  <div class="col-md-6">
                    <div class="kt-portlet__body">
    					<div class="form-group">
    						<label>سعر التوصيل بغداد:</label>
    						<input type="text" name="Company_dev_price_b" class="form-control" placeholder="">
    						<span class="form-text  text-danger" id="Company_dev_price_b_err"></span>
    					</div>
    					<div class="form-group">
    						<label>سعر التوصيل باقي المحافضات:</label>
    						<input type="text" name="Company_dev_price_o" class="form-control" placeholder="">
    						<span class="form-text  text-danger" id="Company_dev_price_o_err"></span>
    					</div>
    					<div class="form-group">
    						<label>استثنائات:</label>
    						<button type="button" onclick="addexpetionprice()" name="" class="btn btn-success" placeholder="">
                             <span class="flaticon-add"></span>&nbsp;&nbsp;اضافة سعر توصيل لمحافظة معينة
                            </button>
    					 </div>
                         <div id="exceptionCities"></div>
                    </div>
                  </div>
                </div>
	            <div class="kt-portlet__foot kt-portlet__foot--solid">
					<div class="kt-form__actions kt-form__actions--right">
						<button type="button" onclick="addCompany()" class="btn btn-brand">اضافة</button>
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

  <!-- Modal -->
  <div class="modal fade " id="devPriceCompany" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">تعديل اسعار التوصيل للعميل</h4>
        </div>
        <div class="modal-body">
		<!--begin::Portlet-->
		<div class="kt-portlet">

			<!--begin::Form-->
			<form class="kt-form" id="devPriceCompanyForm">
                <div class="row">
                <div class="col-md-12">
  				   <div id="devPriceItems" class=""></div>
                   <label class="text-danger" id="devPrice_err"></label>
                </div>
                 </div>
	            <div class="kt-portlet__foot kt-portlet__foot--solid">
					<div class="kt-form__actions kt-form__actions--right">
						<button type="button" onclick="updateDevPriceCompany()" class="btn btn-brand">تحديث اسعار التوصيل</button>
						<button type="reset" data-dismiss="modal" class="btn btn-secondary">الغاء</button>
					</div>
				</div>
                <input type="hidden" name="Company_id" id="Company_id"/>
			</form>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->
        </div>
      </div>

    </div>
  </div>

  <script src="assets/js/demo1/pages/custom/profile/overview-3.js" type="text/javascript"></script>
  <script type="text/javascript" src="js/getCities.js"></script>
  <script type="text/javascript">
  getBraches($("#Company_branch"));
  getBraches($("#e_Company_branch"));
  function addCompany(){
  $.ajax({
     url:"script/_addCompany.php",
     type:"POST",
     data:$("#addCompanyForm").serialize(),
     success:function(res){
       console.log(res);
       if(res.success == 1){
         getAllcompanies($("#getAllcompaniesTable"));
         $("#addCompanyForm input").val("");
         Toast.success('تم الاضافة');
       }else{
           $("#Company_name_err").text(res.error["Company_name_err"]);
           $("#Company_phone_err").text(res.error["Company_phone_err"]);
           $("#Company_email_err").text(res.error["Company_email_err"]);
           $("#Company_branch_err").text(res.error["Company_branch_err"]);
           $("#Company_password_err").text(res.error["Company_password_err"]);
       }

     },
     error:function(e){
       console.log(e);
       Toast.error.displayDuration=5000;
       Toast.error('تأكد من المدخلات','خطأ');
     }
  });
}
indecater = 1;
function addexpetionprice(){
 city =     			'<div class="form-group">'+
    						'<label>سعر توصيل مستثنى</label><br />'+
  					        '<select indecater="'+indecater+'" data-show-subtext="true" data-live-search="true" type="text" class="form-control dropdown-primary" name="Company_dev_city_e[]" id="Company_dev_city_e[]"  value="">'+
                            '<option>اختر</option>'+
                            '</select>'+
                            '<input type="text" name="Company_dev_price_e[]" class="form-control" placeholder="سعر التوصيل">'+
    						'<span class="form-text  text-danger" id="Company_dev_price_o_err"></span>'+
    					'</div>'
if(indecater > 18){
$("#exceptionCities").append(city);
 getCities($('[indecater="'+indecater+'"]'));
 indecater = indecater +1;
 }else{
   Toast.error('لا يمكن اضافة المزيد');
 }
}
function devPriceCompany(id){
  $("#Company_id").val(id);
  $.ajax({
     url:"script/_getDevPriceCompany.php",
     type:"POST",
     data:{id: id},
     success:function(res){
        $("#devPriceItems").html("");
        i=1;
        $.each(res.data,function(){
             console.log(res);
             $("#devPriceItems").append(
      					'<div class="col-md-12">'+
      					'<div class="form-group">'+
      						'<label>'+i+"-"+this.city+'</label>'+
      						'<input type="number" value="'+this.price+'" step="50" name="devPrice[]" class="form-control" placeholder="">'+
      						'<input type="hidden" value="'+this.city_id+'"step="50" name="devCity[]" class="form-control" placeholder="">'+
      					'</div>'+
      					'</div>'
             );
         i++;
       });

     },
     error:function(e){
       console.log(e);
     }
     });
}
function updateDevPriceCompany(){
  $.ajax({
     url:"script/_updateDevPriceCompany.php",
     type:"POST",
     data:$("#devPriceCompanyForm").serialize(),
     success:function(res){
       console.log(res);
        if(res.success != 1){
          $("#devPrice_err").text(res.error);
          Toast.warning("هناك بعض المدخلات غير صالحة",'خطأ');
        }else{
          $("#devPrice_err").text("");
          Toast.success('تم تحديث اسعار التوصيل');
        }

     },
     error:function(e){
       console.log(e);
       Toast.error('خطأ');
     }
    });
}
  </script>
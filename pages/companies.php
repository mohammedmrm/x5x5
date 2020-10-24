<?php
if(file_exists("script/_access.php")){
require_once("script/_access.php");
access([1]);
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
			<h1 class="kt-portlet__head-title">
				شركات التوصيل
			</h1>
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
								<th>نص تسجيل الشركه</th>
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
								<th>نص تسجيل الشركه</th>
								<th>تعديل</th>
					</tr>
	           </tfoot>
		</table>
		<!--end: Datatable -->
	</div>
</div>	</div>
<!-- end:: Content -->
</div>
<div class="modal fade" id="getToken" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"></button>
          <h4 class="modal-title">عرض التوكن الخاص بشركه التوصيل السانده</h4>
        </div>
        <div class="modal-body">
		<!--begin::Portlet-->
		<div class="kt-portlet">

			<!--begin::Form-->
			<form class="kt-form" id="">
				<div class="kt-portlet__body">
					<div class="form-group">
                        <div class="input-group">
    						<input type="text" class="form-control" id="token" placeholder="Type some value to copy">
    						<div class="input-group-append">
    							<a class="btn btn-secondary" data-clipboard="true" data-clipboard-target="#token"><i class="la la-copy"></i></a>
    						</div>
					    </div>
					</div>
					<div class="form-group">
                        <div class="input-group">
    						<input type="text" class="form-control" id="dns" value="<?php echo $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'/'?>" placeholder="Type some value to copy">
    						<div class="input-group-append">
    							<a class="btn btn-secondary" data-clipboard="true" data-clipboard-target="#dns"><i class="la la-copy"></i></a>
    						</div>
					    </div>
					</div>
				</div>
                <div class="kt-portlet__foot kt-portlet__foot--solid">
					<div class="kt-form__actions kt-form__actions--right">
						<button type="button" onclick="showEarnings()" class="btn btn-brand">تحديث</button>
						<button type="reset" data-dismiss="modal" class="btn btn-secondary">الغاء</button>
					</div>
				</div>
                <input type="hidden" id="sett_client_id" name="sett_client_id" />
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
<script type="text/javascript">
function getAllcompanies(elem){
$.ajax({
  url:"script/_getAllcompanies.php",
  type:"POST",
  beforeSend:function(){
    $("#Company_table").addClass('loading');
  },
  success:function(res){
   console.log(res);
   $("#Company_table").removeClass('loading');
   $("#tb-getAllcompanies").DataTable().destroy();
   elem.html("");
   $.each(res.data,function(){
     elem.append(
       '<tr>'+
            '<td>'+this.id+'</td>'+
            '<td><img src="img/logos/companies/'+this.logo+'" width="100px"></td>'+
            '<td>'+this.name+'</td>'+
            '<td>'+this.phone+'</td>'+
            '<td>'+this.text1+'</td>'+
            '<td width="150px">'+
              '<button class="btn btn-clean btn-icon-lg" onclick="editCompany('+this.id+')" data-toggle="modal" data-target="#editCompany"><span class="flaticon-edit"></sapn></button>'+
              '<button class="btn btn-clean btn-icon-lg" onclick="deleteCompany('+this.id+')" data-toggle="modal" data-target="#deleteCompany"><span class="flaticon-delete"></sapn></button>'+
              '<button class="btn btn-clean btn-icon-lg" onclick="getCompanyToken('+this.id+')" data-toggle="modal" data-target="#getToken"><span class="flaticon-feed"></sapn></button>'+
            '</td>'+

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
          <h4 class="modal-title">تحديث بيانات الشركه</h4>
        </div>
        <div class="modal-body">
		<!--begin::Portlet-->
		<div class="kt-portlet">

			<!--begin::Form-->
			<form class="kt-form" id="editCompanyForm">
				<div class="kt-portlet__body">
					<div class="form-group">
						<label>اسم الشركه:</label>
						<input type="name" id="e_Company_name" name="e_company_name" class="form-control"  placeholder="ادخل الاسم الكامل">
						<span class="form-text  text-danger" id="e_Company_name_err"></span>
					</div>
					<div class="form-group">
						<label>شعار الشركه:</label>
						<input type="file" id="e_company_logo" name="e_company_logo" class="form-control" placeholder="ادخل رقم الهاتف">
						<span  id="e_Company_logo_err"class="form-text  text-danger"></span>
					</div>
  					<div class="form-group">
  						<label>توكن:</label>
  						<input type="text" id="e_Company_token" name="e_company_token" class="form-control" placeholder="ex: 1r76yuiort34984.....">
  						<span  id="e_Company_token_err"class="form-text  text-danger"></span>
  					</div>
                      					<div class="form-group">
  						<label>الموقع الالكتروني:</label>
  						<input type="text" id="e_Company_dns" name="e_company_dns" class="form-control" placeholder="ex: www.example.com">
  						<span  id="e_Company_dns_err"class="form-text  text-danger"></span>
  					</div>
					<div class="form-group">
						<label>رقم الهاتف:</label>
						<input type="text" id="e_Company_phone" name="e_company_phone" class="form-control" placeholder="ادخل رقم الهاتف">
						<span  id="e_Company_phone_err"class="form-text  text-danger"></span>
					</div>
					<div class="form-group">
						<label>النص الاول:</label>
						<textarea id="e_Company_text1" name="e_company_text1" class="form-control"></textarea>
						<span class="form-text  text-danger" id="e_Company_text1_err"></span>
					</div>
					<div class="form-group">
						<label>النص الثاني:</label>
						<textarea id="e_Company_text2" name="e_company_text2" class="form-control"></textarea>
						<span class="form-text  text-danger" id="e_Company_text2_err"></span>
					</div>
	            </div>
	            <div class="kt-portlet__foot kt-portlet__foot--solid">
					<div class="kt-form__actions kt-form__actions--right">
						<button type="button" onclick="updateCompany()" class="btn btn-brand">تحديث</button>
						<button type="reset" data-dismiss="modal" class="btn btn-secondary">الغاء</button>
					</div>
				</div>
                <input type="hidden" id="editCompanyid" name="editcompanyid" />
			</form>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->
        </div>
      </div>

    </div>
  </div>
<script>
function editCompany(id){
  $(".text-danger").text("");
  $("#editCompanyid").val(id);
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
          $('#e_Company_token').val(this.token);
          $('#e_Company_dns').val(this.dns);
          $('#e_Company_phone').val(this.phone);
          $('#e_Company_text1').val(this.text1);
          $('#e_Company_text2').val(this.text2);
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
    var myform = document.getElementById('editCompanyForm');
    var fd = new FormData(myform);
    $.ajax({
       url:"script/_updateCompany.php",
       type:"POST",
       data:fd,
       processData: false,  // tell jQuery not to process the data
       contentType: false,
       cache: false,
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
           $("#e_Company_name_err").text(res.error["name"]);
           $("#e_Company_dns_err").text(res.error["dns"]);
           $("#e_Company_phone_err").text(res.error["phone"]);
           $("#e_Company_token_err").text(res.error["token"]);
           $("#e_Company_text1_err").text(res.error["text1"]);
           $("#e_Company_text2_err").text(res.error["text2"]);
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
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">اضافة شركه</h4>
        </div>
        <div class="modal-body">
		<!--begin::Portlet-->
		<div class="kt-portlet">

			<!--begin::Form-->
			<form class="kt-form" id="addCompanyForm">
                <div class="row">
  				  <div class="kt-portlet__body">
  					<div class="form-group">
  						<label>الاسم الشركه:</label>
  						<input type="name" name="Company_name" class="form-control"  placeholder="ادخل الاسم ">
  						<span class="form-text  text-danger" id="Company_name_err"></span>
  					</div>
  					<div class="form-group">
  						<label>شعار الشركه:</label>
  						<input type="file" name="Company_logo" class="form-control">
  						<span  id="Company_logo_err"class="form-text  text-danger"></span>
  					</div>
  					<div class="form-group">
  						<label>توكن:</label>
  						<input type="text" name="Company_token" class="form-control" placeholder="ex: 1r76yuiort34984.....">
  						<span  id="Company_token_err"class="form-text  text-danger"></span>
  					</div>
                      					<div class="form-group">
  						<label>الموقع الالكتروني:</label>
  						<input type="text" name="Company_dns" class="form-control" placeholder="ex: www.example.com">
  						<span  id="Company_dns_err"class="form-text  text-danger"></span>
  					</div>
  					<div class="form-group">
  						<label>رقم الهاتف:</label>
  						<input type="text" name="Company_phone" class="form-control" placeholder="ادخل رقم الهاتف">
  						<span  id="Company_phone_err"class="form-text  text-danger"></span>
  					</div>
  					<div class="form-group">
  						<label>نص تسجيل الشركه:</label>
  						<textarea  name="Company_text1" class="form-control" ></textarea>
  						<span class="form-text  text-danger" id="Company_text1_err"></span>
  					</div>
  					<div class="form-group">
  						<label>نص افرع الشركه:</label>
  						<textarea  name="Company_text2" class="form-control" ></textarea>
  						<span class="form-text  text-danger" id="Company_text2_err"></span>
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

  <script src="assets/js/demo1/pages/custom/profile/overview-3.js" type="text/javascript"></script>
  <script type="text/javascript" src="js/getCities.js"></script>
  <script src="assets/js/demo1/pages/components/forms/widgets/clipboard.js" type="text/javascript"></script>
  <script type="text/javascript">
  function addCompany(){
    var myform = document.getElementById('addCompanyForm');
    var fd = new FormData(myform);
  $.ajax({
     url:"script/_addCompany.php",
     type:"POST",
     data:fd,
     processData: false,  // tell jQuery not to process the data
     contentType: false,
   	 cache: false,
     beforeSend:function(){
          $("#Company_name_err").text('');
           $("#Company_phone_err").text('');
           $("#Company_text1_err").text('');
           $("#Company_text2_err").text('');
           $("#Company_logo_err").text('');
     },
     success:function(res){
       console.log(res);
       if(res.success == 1){
         getAllcompanies($("#getAllcompaniesTable"));
         $("#addCompanyForm input").val("");
         Toast.success('تم الاضافة');
       }else{
           $("#Company_name_err").text(res.error["Company_name_err"]);
           $("#Company_phone_err").text(res.error["Company_phone_err"]);
           $("#Company_text1_err").text(res.error["Company_text1_err"]);
           $("#Company_text2_err").text(res.error["Company_text2_err"]);
           $("#Company_logo_err").text(res.error["Company_logo_err"]);
           $("#Company_token_err").text(res.error["Company_token_err"]);
           $("#Company_dns_err").text(res.error["Company_dns_err"]);
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
function getCompanyToken(id){
    $.ajax({
       url:"script/_getCompanyToken.php",
       type:"POST",
       data:{id: id},
       beforeSend:function(){
        $("#getToken").addClass('loading');
       },
       success:function(res){
         $("#getToken").removeClass('loading');
         console.log(res);
       if(res.success == 1){
          $("#token").val(res.token);
       }else{
           Toast.warning('خطأ');
       }
       },
       error:function(e){
        $("#getToken").removeClass('loading');
        Toast.error('خطأ');
        console.log(e);
       }
    });
}
  </script>
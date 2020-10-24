
<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				فروع الشركة
			</h3>
		</div>
	</div>

	<div class="kt-portlet__body">
     <form id="filtterSalariesForm">
		<!--begin: Datatable -->
          <fieldset><legend>فلتر</legend>
          <div class="row kt-margin-b-20">
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>الفرع:</label>
            	<select onchange="getSalaries()" class="form-control kt-input" id="branch" name="branch" data-col-index="6">
            	</select>
            </div>
            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
            	<label>الشهر:</label>
            	<select onchange="getSalaries()" class="selectpicker form-control kt-input" id="month" name="month" data-col-index="6">
                 <option value="0">--اختر الشهر--</option>
                 <option value="1">شهر 1</option>
                 <option value="2">شهر 2</option>
                 <option value="3">شهر 3</option>
                 <option value="4">شهر 4</option>
                 <option value="5">شهر 5</option>
                 <option value="6">شهر 6</option>
                 <option value="7">شهر 7</option>
                 <option value="8">شهر 8</option>
                 <option value="9">شهر 9</option>
                 <option value="10">شهر 10</option>
                 <option value="11">شهر 11</option>
                 <option value="12">شهر 12</option>
                </select>
            </div>
            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                  <label class="h3">المبلغ الكلي:</label><br />
                  <label class="h4" id="salaries"> $0</label>
            </div>
            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                  <label class="h4" id="status"></label>
            </div>
          </div>
          </fieldset>
		<table class="table table-striped- table-bordered table-hover table-checkable responsive no-wrap" id="tb-salary">
			       <thead>
	  						<tr>
								<th>الفرع</th>
								<th>عدد الموضفين</th>
								<th>مجموع الرواتب</th>
								<th>الحالة</th>
								<th>شيت الرواتب</th>
								<th>صرف</th>
							</tr>
      	            </thead>
                            <tbody id="salariesTable">
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


<!--begin::Page Vendors(used by this page) -->
<script src="assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<!--end::Page Vendors -->



<!--begin::Page Scripts(used by this page) -->
<script src="assets/js/demo1/pages/components/datatables/extensions/responsive.js" type="text/javascript"></script>
<script type="text/javascript">
function getSalaries(){
$.ajax({
  url:"script/_getSalaries.php",
  type:"POST",
  data:$("#filtterSalariesForm").serialize(),
  beforeSend:function(){
    $("#pagination").html("");
  },
  success:function(res){
   console.log(res);
   $("#tb-salary").DataTable().destroy();
   $("#salariesTable").html("");
   $("#salariesTable").html("");
   $.each(res.data,function(){
     $("#salaries").text(formatMoney(res.total));
     if(res.role == 1){
       btn = '<button class="btn btn-primary" type="button" onclick="paySalaries('+this.b_id+')">صرف</button>';
     }else if(res.role == 2){
       btn = '<button class="btn btn-success" type="button" onclick="confirmSalaries('+this.pay_id+')">تاكيد</button>';
     }
     $("#salariesTable").append(
       '<tr>'+
            '<td>'+this.branch_name+'</td>'+
            '<td>'+this.staffnumber+'</td>'+
            '<td>'+formatMoney(this.salaries)+'</td>'+
            '<td>'+this.status+'</td>'+
            '<td><a href="script/salarySheet.php?id='+this.b_id+'&month='+res.month+'&year='+res.year+'" target="_blank">ملف الرواتب</a></td>'+
            '<td>'+
                btn+
            '</td>'+
       '</tr>');
     });
     $("#tb-salary").DataTable().destroy();
     var myTable= $('#tb-salary').DataTable({
     columns:[
    //"dummy" configuration
        { visible: true }, //col 1
        { visible: true }, //col 2
        { visible: true }, //col 3
        { visible: true }, //col 4
        { visible: true }, //col 5
        { visible: true }, //col 6
        ],
});
    },
   error:function(e){
    console.log(e);
  }
});
}
getSalaries();

</script>
<script type="text/javascript" src="js/getBraches.js"></script>
<script>
getBraches($("#branch"));
function paySalaries(id){
$.ajax({
  url:"script/_paySalaries.php",
  type:"POST",
  data:$("#filtterSalariesForm").serialize()+ '&id=' + id,
  beforeSend:function(){
   $("#salary_pays_err").html("");
  },
  success:function(res){
   console.log(res);
   if(res.success == 1){
     Toast.success(res.msg);
     getSalaries();
   }else{
     Toast.warning(res.msg);
   }
  },
   error:function(e){
    console.log(e);
  }
});
}
function confirmSalaries(id){
  if(confirm("هل انت متاكد من تاكيد سحب  المبلغ")){
      $.ajax({
        url:"script/_confirmSalaries.php",
        type:"POST",
        data:{id:id},
        success:function(res){
         if(res.success == 1){
           Toast.success(res.msg);
           getSalaries();
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
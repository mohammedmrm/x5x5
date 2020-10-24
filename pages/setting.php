<?php
include_once('config.php');
?>
<br /><div class="row">
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<div class="row">
	<div class="col-lg-12">
		<!--begin::Portlet-->
		<div class="kt-portlet kt-portlet--sticky" id="kt_page_portlet">
			<div class="kt-portlet__head kt-portlet__head--lg" >
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">اعدادات النظام العامه</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<a href="#" class="btn btn-secondary kt-margin-r-10">
						<i class="la la-arrow-left"></i>
						<span class="kt-hidden-mobile">Back</span>
					</a>
					<div class="btn-group">
						<button type="button"  onclick="save()" class="btn btn-primary">
							<i class="la la-check"></i>
							<span class="kt-hidden-mobile">حفظ</span>
						</button>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">
				<form class="kt-form" id="settingForm">
					<div class="row">
						<div class="col-xl-8">
							<div class="kt-section kt-section--first">
								<div class="kt-section__body">
                                   <h4>الاعدادات العامه</h4>
									<div class="form-group row">
										<label class="col-3 col-form-label" >اسم الشركه</label>
										<div class="col-9">
											<input class="form-control" value="<?php echo $config['Company_name']?>" id="name" name="name">
                                            <span class="form-text text-danger" id="name_err"></span>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-3 col-form-label" value="<?php echo $config['Company_address']?>">عنوان الشركه</label>
										<div class="col-9">
											<input class="form-control" type="text" id="address" name="address" value="<?php echo $config['Company_address']?>">
                                            <span class="form-text text-danger" id="address_err"></span>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-3 col-form-label" >رقم هاتف الشركه</label>
										<div class="col-9">
											<input class="form-control" id="phone" name="phone" value="<?php echo $config['Company_phone']?>" type="text">
											<span class="form-text text-danger" id="phone_err"></span>
										</div>
									</div>
                                    <div class="form-group row">
										<label class="col-3 col-form-label" >توصيل بغداد</label>
										<div class="col-9">
											<div class="input-group">
												<input type="number" min='0'step="250" name="dev_b" id="dev_b" value="<?php echo $config['dev_b']?>" class="form-control"  value="loop">
												<span class="form-text text-danger" id="dev_b_err"></span>
											</div>
										</div>
									</div>
                                    <div class="form-group row">
										<label class="col-3 col-form-label" >توصيل المحافظات</label>
										<div class="col-9">
											<div class="input-group">
												<input type="number" min='0'step="250" name="dev_o" id="dev_o" value="<?php echo $config['dev_o']?>" class="form-control"  value="loop">
												<span class="form-text text-danger" d="dev_o_err"></span>
											</div>
										</div>
									</div>
                                    <div class="form-group row">
										<label class="col-3 col-form-label"  >اجره المندوب</label>
										<div class="col-9">
											<div class="input-group">
												<input type="number" min='0'step="250" name="driver_price" id="driver_price" value="<?php echo $config['driver_price']?>" class="form-control"  value="loop">
												<span class="form-text text-danger" d="driver_price_err"></span>
											</div>
										</div>
									</div>
                                    <div class="form-group row">
										<label class="col-3 col-form-label">الاجره الاضافيه لكل 500000 دينار عراقي</label>
										<div class="col-9">
											<div class="input-group">
												<input type="number" min='0'step="250" name="addOnOver500" id="addOnOver500" value="<?php echo $config['addOnOver500']?>" class="form-control"  value="loop">
												<span class="form-text text-danger" d="addOnOver500_err"></span>
											</div>
										</div>
									</div>
                                    <div class="form-group row">
										<label class="col-3 col-form-label">الاجره الاضافيه لكل 1 كيلوغرام اضافي</label>
										<div class="col-9">
											<div class="input-group">
												<input type="number" min='0'step="250" name="weightPrice" id="weightPriceOver500" value="<?php echo $config['weightPrice']?>" class="form-control"  value="loop">
												<span class="form-text text-danger" d="weightPrice_err"></span>
											</div>
										</div>
									</div>
                                    <div class="form-group row">
										<label class="col-3 col-form-label">شعار الشركه</label>
										<div class="col-4">
											<div class="input-group">
												<input type="file"  name="logo" id="logo" class="form-control"  >
												<span class="form-text text-danger" d="logo_err"></span>
											</div>
										</div>
                                        <div class="col-3">
                                        <img  width="130px" src="img/logos/logo.png"/>
                                        </div>
									</div>
                                    <hr />

                                    <h4>تطبيق العميل</h4>
									<div class="form-group row">
										<label class="col-3  h2" >اعلان 1</label>
										<div class="col-9">
											<textarea class="form-control summernote" type="text" id="c_ad1" name="c_ad1"><?php echo $config['c_ad1']?></textarea>
                                            <span class="form-text text-danger" id="c_ad1_err"></span>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-3 col-form-label h2" >اعلان 2</label>
										<div class="col-9">
											<textarea  class="form-control summernote" type="text" id="c_ad2" name="c_ad2"><?php echo $config['c_ad2']?></textarea>
                                            <span class="form-text text-danger" id="c_ad2_err"></span>
										</div>
									</div>
                                    <hr />

                                    <h4>تطبيق المندوب</h4>
									<div class="form-group row">
										<label class="col-3 h2" >اعلان 1</label>
										<div class="col-9">
											<textarea class="form-control summernote" type="text" id="c_ad1" name="d_ad1" ><?php echo $config['d_ad1']?></textarea>
                                            <span class="form-text text-danger" id="d_ad1_err"></span>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-3 h2" >اعلان 2</label>
										<div class="col-9">
											<textarea  class="form-control summernote" type="text" id="c_ad2" name="d_ad2"><?php echo $config['d_ad2']?></textarea>
                                            <span class="form-text text-danger" id="d_ad2_err"></span>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
				</form>
			</div>
		</div>
		<!--end::Portlet-->
	</div>
</div>
</div>
</div>
<script>
function save(){
    var myform = document.getElementById('settingForm');
    var fd = new FormData(myform);
  $.ajax({
    url:"script/_setSetting.php",
    type:"POST",
     data:fd,
     processData: false,  // tell jQuery not to process the data
     contentType: false,
     cache: false,
    beforeSend:function(){
      $("#settingForm").addClass("loading");
    },
    success:function(res){
     $("#settingForm").removeClass("loading");
     console.log(res);
     Toast.success('تم الحفظ');
    },
    error:function(e){
     $("#settingForm").removeClass("loading");
     console.log(e);
    }
  });
}
$('.summernote').summernote();
</script>
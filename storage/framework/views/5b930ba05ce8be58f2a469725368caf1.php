<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">
<head>

    <meta charset="utf-8" />
    <title><?php echo e(get_app_setting('title')); ?> | <?php echo e(Str::title(str_replace('-', ' ', request()->segment(2)))); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo e(asset(get_app_setting('favicon')??'admin-assets/images/favicon.ico')); ?>">
    <?php echo $__env->yieldPushContent('links'); ?>
    <!-- Layout config Js -->
    <script src="<?php echo e(asset('admin-assets/js/layout.js')); ?>"></script>
    <!-- Bootstrap Css -->
    <link href="<?php echo e(asset('admin-assets/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?php echo e(asset('admin-assets/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
    <!-- App Css-->
   
    <link href="<?php echo e(asset('admin-assets/libs/sweetalert/sweetalert.css')); ?>" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    
     <link href="<?php echo e(asset('admin-assets/css/app.min.css')); ?>" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="<?php echo e(asset('admin-assets/css/custom.min.css')); ?>" rel="stylesheet" type="text/css" />
</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

       <?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- ========== App Menu ========== -->
        <?php echo $__env->make('admin.layouts.aside', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <?php $__env->startSection('main'); ?>
                              <?php echo $__env->yieldSection(); ?> 
                    <!-- End Page-content -->
                </div>
            </div>

            <?php echo $__env->make('admin.layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->



    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <div class="customizer-setting d-none d-md-block">
        <div class="btn-info btn-rounded shadow-lg btn btn-icon btn-lg p-2" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas">
            <i class='mdi mdi-spin mdi-cog-outline fs-22'></i>
        </div>
    </div>

    <!-- Theme Settings -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- JAVASCRIPT -->
    <script src="<?php echo e(asset('admin-assets/libs/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/libs/simplebar/simplebar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/libs/node-waves/waves.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/libs/feather-icons/feather.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/js/pages/plugins/lord-icon-2.1.0.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/libs/sweetalert/sweetalert.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/js/custom.js')); ?>"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <?php if(Session::has('message')): ?>
        <script type="text/javascript">
            Toastify({
                text: "<?php echo e(Session::get('message')); ?>",
                duration: 3000,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "right", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                className: "<?php echo e(Session::get('class')); ?>",

            }).showToast();
        </script>
    <?php endif; ?>
    <!-- App js -->
    
     <?php echo $__env->yieldPushContent('scripts'); ?>

     
     <script src="<?php echo e(asset('admin-assets/js/app.js')); ?>"></script>
     <script type="text/javascript">
    function deleteAjax(url,callback=null){  

    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this data!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        confirmButtonText: "Yes, Delete it !",
        cancelButtonText: "No, Cancel it !",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
        $.ajax({
            url:url,
            method: 'post',
            data:{'_method':'DELETE','_token':'<?php echo e(csrf_token()); ?>'},
            dataType:'json',
            success:function(response){
                if(response.class){
                    swal({
                        title: "Deleted!",
                        text: "Your imaginary file has been deleted.",
                        type: "success",
                        timer: 2000,
                        showConfirmButton: false
                    });
                   // Command: toastr[response.class](response.message);
                    $('.datatable').DataTable().draw('page');
                    $('.dataTableAjax').DataTable().draw('page');

                }
                if(document.getElementsByClassName('dataTableAjax')){
                    $('.dataTableAjax').DataTable().draw();
                }
                if(document.getElementsByClassName('datatable')){
                    // setTimeout(function(){
                    //     window.location.reload();
                    // }, 300)
                    $('.datatable').DataTable().draw('page');
                    
                }
                if(callback)
                    callback(callback);
            }
        });

            
        }
    });
    return false;
}

    // var drEvent = $('.dropify-edit').dropify();

    // drEvent.on('dropify.afterClear', function(event, element){
    //     alert('File deleted');
    // });
     </script>
</body>
</html><?php /**PATH /home/sanix/Documents/ecommerce/resources/views/admin/layouts/master.blade.php ENDPATH**/ ?>
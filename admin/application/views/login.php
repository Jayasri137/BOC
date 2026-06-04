<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- <link rel="icon" href="/bs5/images/favicon.ico"> -->

    <title>BluestoneOCS - Log in </title>
  
	<!-- Vendors Style-->
	<link rel="stylesheet" href="<?=base_url();?>assets/css/vendors_css.css">
	  
	<!-- Style-->    
	<link rel="stylesheet" href="<?=base_url();?>assets/css/horizontal-menu.css"> 
	<link rel="stylesheet" href="<?=base_url();?>assets/css/style.css">
	<link rel="stylesheet" href="<?=base_url();?>assets/css/skin_color.css">	

</head>
	
<body class="hold-transition theme-fruit bg-img" style="background-image: url(<?=base_url();?>assets/images/auth-bg/bg-8.png)">
	
	<div class="container h-p100">
    <div class="row align-items-center justify-content-md-center h-p100">	
        
        <div class="col-12">
            <div class="row justify-content-center g-0">
                <div class="col-lg-5 col-md-5 col-12">
                    <div class="logo-lg text-center" style="margin-bottom: 4rem;">
                        <img src="<?= base_url('assets/images/bluestone.png'); ?>" 
     alt="Bluestone OCS Logo" 
     class="img-fluid bg-white p-5 rounded" 
     style="height: 60px; width: auto;" />
                    </div>
                    <div class="bg-white rounded10 shadow-lg" style="margin-top: 3rem;">
                        
                        <div class="content-top-agile p-20 pb-0">
                        
                            <h3 class="underline">Login</h3>
                        </div>
                        <!-- form -->
                        <div class="p-40">
    <form action="<?php echo base_url();?>" method="post" > 
        <div class="form-group">
            <div class="input-group mb-3">
                <span class="input-group-text bg-transparent"><i class="ti-user"></i></span>
                <input type="text" class="form-control ps-15 bg-transparent" placeholder="Username" id="email" name="username" required>
            </div>
        </div>

        <div class="form-group">
            <div class="input-group mb-3">
                <span class="input-group-text bg-transparent"><i class="ti-lock"></i></span>
                <input type="password" class="form-control ps-15 bg-transparent" placeholder="Password" id="password" name="password" required>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="checkbox">
                    <input type="checkbox" id="basic_checkbox_1">
                    <label for="basic_checkbox_1">Remember Me</label>
                </div>
            </div>

            <div class="col-12 text-center">
                <button type="submit" class="btn btn-danger mt-10">SIGN IN</button>
            </div>
        </div>
    </form>    
</div>

                                                <!--/////////////form///////////  -->
                    </div>
                    <!-- <div class="text-center">
                      <p class="mt-20 text-white">- Sign With -</p>
                      <p class="gap-items-2 mb-20">
                          <a class="btn btn-social-icon btn-round btn-facebook" href="#"><i class="fa fa-facebook"></i></a>
                          <a class="btn btn-social-icon btn-round btn-twitter" href="#"><i class="fa fa-twitter"></i></a>
                          <a class="btn btn-social-icon btn-round btn-instagram" href="#"><i class="fa fa-instagram"></i></a>
                        </p>	
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>


	<!-- Vendor JS -->
	<script src="src/js/vendors.min.js"></script>
	<script src="src/js/pages/chat-popup.js"></script>
    <script src="assets/icons/feather-icons/feather.min.js"></script>	

</body>

</html>
<script>
/*
  Soft-disable browser back navigation on this page.
  Note: cannot 100% prevent all browser behaviors, but this technique
  blocks normal back navigation in most browsers.
*/

(function () {
  // push an extra history state so "back" will stay
  function pushState() {
    try {
      history.pushState(null, document.title, location.href);
    } catch (e) { /* ignore */ }
  }

  // initial push
  pushState();

  // when user hits back (popstate) — push state again to negate it
  window.addEventListener('popstate', function (e) {
    pushState();
  });

  // also watch for hashchange (some browsers use hashes)
  window.addEventListener('hashchange', function () {
    pushState();
  });

  // prevent Backspace from navigating back when not focused on an input
  window.addEventListener('keydown', function (e) {
    var doPrevent = false;
    var target = e.target || e.srcElement;
    var isInput = /INPUT|TEXTAREA|SELECT/.test(target.nodeName) || target.isContentEditable;

    if (e.key === 'Backspace' && !isInput) {
      doPrevent = true;
    }

    // Ctrl/Cmd + [ / ArrowLeft combinations sometimes trigger navigation — block them
    if ((e.ctrlKey || e.metaKey) && (e.key === '[' || e.key === 'ArrowLeft')) {
      doPrevent = true;
    }

    if (doPrevent) {
      e.preventDefault();
      e.stopPropagation();
      // re-push history to be safe
      pushState();
      return false;
    }
  }, true);

  // Optional: prevent form resubmission/popups when trying to leave
  window.addEventListener('beforeunload', function (e) {
    // If you want to show a prompt comment the next two lines out.
    // e.preventDefault();
    // e.returnValue = '';
  });
})();
</script>

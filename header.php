<!DOCTYPE html>
<html lang="en">


<head>

<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', 'YOUR_PIXEL_ID'); // Replace with your Pixel ID
  fbq('track', 'PageView');
</script>
<noscript>
  <img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=YOUR_PIXEL_ID&ev=PageView&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->

	<!-- Meta Configuration -->
 	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  	<meta name="author" content="Bluestone Overseas Consultants - Your Companion in Worldwide Education">
 	<meta name="language" content="English">
  	<meta name="distribution" content="global">
 	<meta name="revisit-after" content="7 days">
  	<meta name="robots" content="index, follow">

	<!-- SEO -->
<title><?php echo $pageTitle ?? "Study Abroad Consultants in Coimbatore | Bluestone Overseas"; ?></title>
<meta name="description" content="<?php echo $metaDescription ?? "Looking for the best study abroad consultants in Coimbatore? Get expert guidance for admissions, student visas, scholarships, IELTS, PTE, and study in Canada, UK, USA & Australia."; ?>">

        <!-- open graph meta tag -->
        <meta property="og:title" content="Bluestone Overseas - Trusted Overseas Education Consultants" />
        <meta property="og:description" content="Bluestone Overseas provides expert guidance for overseas education, immigration, and student visa consultancy services." />
        <meta property="og:url" content="https://bluestoneoverseas.com/" />
        <meta property="og:type" content="website" />
        <meta property="og:image" content="https://bluestoneoverseas.com/images/logo-img.png" />
        <meta property="og:site_name" content="Bluestone Overseas" />
        <meta property="og:locale" content="en_US" />
        <!-- <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Your Page Title" />
        <meta name="twitter:description" content="Brief description of the page" />
        <meta name="twitter:image" content="https://example.com/image.jpg" />
        <meta name="twitter:site" content="@YourTwitterHandle" /> -->


	<!-- Canonical & Verification -->
<?php
$canonical = 'https://www.bluestoneoverseas.com' . rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if ($canonical === 'https://www.bluestoneoverseas.com') {
    $canonical .= '/';
}
?>
<link rel="canonical" href="<?php echo htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8'); ?>" />
  	<meta name="google-site-verification" content="6XJeYJRBhkAMZfAqMRM7baFwTbZA54aaIcRd0WjBvYU">
  	<meta name="google-site-verification" content="uufHadHWr1VYTfMfUrK7gzYjZ31PS6C9M1ZcJHA5Au4">
    <meta name="google-site-verification" content="XnLIPxSb1zS2cMqVAY2vq9EDrDmyJ7dPKCELSHbh0c8" />

	<!-- Favicon -->
  	<link rel="shortcut icon" href="images/favicon.png">

  	<!-- Stylesheets -->
  	<link rel="stylesheet" href="css/bootstrap.min.css">
  	<link rel="stylesheet" href="css/animate.css">
  	<link rel="stylesheet" href="css/font-awesome.css">
  	<link rel="stylesheet" href="css/themify-icons.css">
  	<link rel="stylesheet" href="css/flaticon.css">
  	<link rel="stylesheet" href="css/slick.css">
  	<link rel="stylesheet" href="revolution/css/rs6.css" id="rs-plugin-settings-css">
  	<link rel="stylesheet" href="css/prettyPhoto.css">
  	<link rel="stylesheet" href="css/shortcodes.css">
  	<link rel="stylesheet" href="css/main.css">
  	<link rel="stylesheet" href="css/megamenu.css">
  	<link rel="stylesheet" href="css/responsive.css">
  	<link rel="stylesheet" href="css/style-blog.css">
  	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">
   
<!-- Font Awesome 5 Free CDN -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->


	<!-- WhatsApp Button Styling -->
  	<style>
    	.whatsapp-btn {
     	 position: fixed;
      	bottom: 100px;
      	right: 20px;
      	background-color: #25D366;
      	color: white;
      	border: none;
      	border-radius: 50%;
      	width: 60px;
      	height: 60px;
      	display: flex;
      	align-items: center;
      	justify-content: center;
      	box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
      	cursor: pointer;
      	text-decoration: none;
      	transition: transform 0.3s ease;
    	}
    	.whatsapp-btn:hover {
      	transform: scale(1.1);
    	}
    	.whatsapp-btn img {
      	width: 30px;
      	height: 30px;
    	}
    	 .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            padding: 20px;
        }

        .gallery-item img {
            width: 100%;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .gallery-item img:hover {
            transform: scale(1.05);
        }

        #lightbox {
            display: none;
            position: fixed;
            z-index: 999;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
            justify-content: center;
            align-items: center;
        }

        #lightbox img {
            max-width: 90%;
            max-height: 80vh;
            border-radius: 10px;
        }

        #lightbox .close {
            position: absolute;
            top: 20px;
            right: 40px;
            font-size: 40px;
            color: white;
            cursor: pointer;
        }
 	 </style>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": ["EducationalOrganization", "LocalBusiness"],
  "name": "Bluestone Overseas",
  "url": "https://bluestoneoverseas.com",
  "logo": "https://bluestoneoverseas.com/images/logo-img.png",
  "sameAs": [
    "https://www.facebook.com/bluestoneocs/",
    "https://www.instagram.com/bluestoneoverseas/",
    "https://www.linkedin.com/company/bluestone-com/?originalSubdomain=in"
  ],
  "description": "Study abroad consultants in Coimbatore offering visa services, IELTS coaching, student counseling & overseas education guidance.",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Renaissance Terrace, NO.126L, 2nd Floor, Opp. Bishop Appasamy College",
    "addressLocality": "Coimbatore",
    "addressRegion": "Tamil Nadu",
    "postalCode": "641001",
    "addressCountry": "IN"
  },
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+91-9876543210",
    "contactType": "Customer Support"
  },
  "openingHours": "Mo-Sa 10:00-18:00",
  "priceRange": "₹₹"
}
</script>


  	<!-- Google Tag Manager - Analytics -->
  	<script async src="https://www.googletagmanager.com/gtag/js?id=G-H09NK46FFD"></script>
  	<script>
   	 window.dataLayer = window.dataLayer || [];
    	function gtag() { dataLayer.push(arguments); }
    	gtag('js', new Date());
    	gtag('config', 'G-H09NK46FFD');
  	</script>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-17065954362">
</script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-17065954362');
</script>

  	<!-- Google Ads Conversion Tracking -->
  	<script async src="https://www.googletagmanager.com/gtag/js?id=AW-16603743701"></script>
  	<script>
    	gtag('config', 'AW-16603743701');
  	</script>
     <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-QYY86XZE2P"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-QYY86XZE2P');
</script>

<link rel="preload" href="assets/css/main.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="assets/css/main.css"></noscript>

<head>

<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', 'YOUR_PIXEL_ID'); // Replace with your Pixel ID
  fbq('track', 'PageView');
</script>
<noscript>
  <img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=YOUR_PIXEL_ID&ev=PageView&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->



	<!-- Meta Configuration -->
 	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  	<meta name="author" content="Bluestone Overseas Consultants - Your Companion in Worldwide Education">
 	<meta name="language" content="English">
  	<meta name="distribution" content="global">
 	<meta name="revisit-after" content="7 days">
  	<meta name="robots" content="index, follow">

	<!-- SEO -->
<title><?php echo $pageTitle ?? "Bluestone Overseas | Study Abroad Consultants"; ?></title>
<meta name="description" content="<?php echo $metaDescription ?? "Bluestone Overseas Consultancy offers trusted study abroad guidance, admissions support and visa services."; ?>">

        <!-- open graph meta tag -->
        <meta property="og:title" content="Bluestone Overseas - Trusted Overseas Education Consultants" />
        <meta property="og:description" content="Bluestone Overseas provides expert guidance for overseas education, immigration, and student visa consultancy services." />
        <meta property="og:url" content="https://bluestoneoverseas.com/" />
        <meta property="og:type" content="website" />
        <meta property="og:image" content="https://bluestoneoverseas.com/images/logo-img.png" />
        <meta property="og:site_name" content="Bluestone Overseas" />
        <meta property="og:locale" content="en_US" />
        <!-- <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Your Page Title" />
        <meta name="twitter:description" content="Brief description of the page" />
        <meta name="twitter:image" content="https://example.com/image.jpg" />
        <meta name="twitter:site" content="@YourTwitterHandle" /> -->


	<!-- Canonical & Verification -->
<?php
$canonical = 'https://www.bluestoneoverseas.com' . rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if ($canonical === 'https://www.bluestoneoverseas.com') {
    $canonical .= '/';
}
?>
<link rel="canonical" href="<?php echo htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8'); ?>" />
  	<meta name="google-site-verification" content="6XJeYJRBhkAMZfAqMRM7baFwTbZA54aaIcRd0WjBvYU">
  	<meta name="google-site-verification" content="uufHadHWr1VYTfMfUrK7gzYjZ31PS6C9M1ZcJHA5Au4">
    <meta name="google-site-verification" content="XnLIPxSb1zS2cMqVAY2vq9EDrDmyJ7dPKCELSHbh0c8" />

	<!-- Favicon -->
  	<link rel="shortcut icon" href="images/favicon.png">

  	<!-- Stylesheets -->
  	<link rel="stylesheet" href="css/bootstrap.min.css">
  	<link rel="stylesheet" href="css/animate.css">
  	<link rel="stylesheet" href="css/font-awesome.css">
  	<link rel="stylesheet" href="css/themify-icons.css">
  	<link rel="stylesheet" href="css/flaticon.css">
  	<link rel="stylesheet" href="css/slick.css">
  	<link rel="stylesheet" href="revolution/css/rs6.css" id="rs-plugin-settings-css">
  	<link rel="stylesheet" href="css/prettyPhoto.css">
  	<link rel="stylesheet" href="css/shortcodes.css">
  	<link rel="stylesheet" href="css/main.css">
  	<link rel="stylesheet" href="css/megamenu.css">
  	<link rel="stylesheet" href="css/responsive.css">
  	<link rel="stylesheet" href="css/style-blog.css">
  	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">
   
<!-- Font Awesome 5 Free CDN -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->


	<!-- WhatsApp Button Styling -->
  	<style>
    	.whatsapp-btn {
     	 position: fixed;
      	bottom: 100px;
      	right: 20px;
      	background-color: #25D366;
      	color: white;
      	border: none;
      	border-radius: 50%;
      	width: 60px;
      	height: 60px;
      	display: flex;
      	align-items: center;
      	justify-content: center;
      	box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
      	cursor: pointer;
      	text-decoration: none;
      	transition: transform 0.3s ease;
    	}
    	.whatsapp-btn:hover {
      	transform: scale(1.1);
    	}
    	.whatsapp-btn img {
      	width: 30px;
      	height: 30px;
    	}
    	 .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            padding: 20px;
        }

        .gallery-item img {
            width: 100%;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .gallery-item img:hover {
            transform: scale(1.05);
        }

        #lightbox {
            display: none;
            position: fixed;
            z-index: 999;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
            justify-content: center;
            align-items: center;
        }

        #lightbox img {
            max-width: 90%;
            max-height: 80vh;
            border-radius: 10px;
        }

        #lightbox .close {
            position: absolute;
            top: 20px;
            right: 40px;
            font-size: 40px;
            color: white;
            cursor: pointer;
        }
 	 </style>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": ["EducationalOrganization", "LocalBusiness"],
  "name": "Bluestone Overseas",
  "url": "https://bluestoneoverseas.com",
  "logo": "https://bluestoneoverseas.com/images/logo-img.png",
  "sameAs": [
    "https://www.facebook.com/bluestoneocs/",
    "https://www.instagram.com/bluestoneoverseas/",
    "https://www.linkedin.com/company/bluestone-com/?originalSubdomain=in"
  ],
  "description": "Study abroad consultants in Coimbatore offering visa services, IELTS coaching, student counseling & overseas education guidance.",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Renaissance Terrace, NO.126L, 2nd Floor, Opp. Bishop Appasamy College",
    "addressLocality": "Coimbatore",
    "addressRegion": "Tamil Nadu",
    "postalCode": "641001",
    "addressCountry": "IN"
  },
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+91-9876543210",
    "contactType": "Customer Support"
  },
  "openingHours": "Mo-Sa 10:00-18:00",
  "priceRange": "₹₹"
}
</script>


  	<!-- Google Tag Manager - Analytics -->
  	<script async src="https://www.googletagmanager.com/gtag/js?id=G-H09NK46FFD"></script>
  	<script>
   	 window.dataLayer = window.dataLayer || [];
    	function gtag() { dataLayer.push(arguments); }
    	gtag('js', new Date());
    	gtag('config', 'G-H09NK46FFD');
  	</script>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-17065954362">
</script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-17065954362');
</script>

  	<!-- Google Ads Conversion Tracking -->
  	<script async src="https://www.googletagmanager.com/gtag/js?id=AW-16603743701"></script>
  	<script>
    	gtag('config', 'AW-16603743701');
  	</script>
     <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-QYY86XZE2P"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-QYY86XZE2P');
</script>

<link rel="preload" href="assets/css/main.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="assets/css/main.css"></noscript>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Which countries do you provide study abroad consultancy for?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "We provide study abroad consultancy services for the UK, USA, Canada, Europe, Australia, Singapore, Dubai, and Malta, helping students choose suitable courses and universities."
      }
    },
    {
      "@type": "Question",
      "name": "Do you help with student visa processing?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, we offer complete student visa assistance, including document preparation, application filing, interview guidance, and visa status support."
      }
    },
    {
      "@type": "Question",
      "name": "Is personalized counseling available for students?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, our counselors provide one-to-one personalized guidance based on the student's academic background, career goals, and financial considerations."
      }
    },
    {
      "@type": "Question",
      "name": "What services does Bluestone Overseas Consultants offer?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Bluestone Overseas Consultants provides study abroad counseling, university admissions support, IELTS coaching, visa assistance, and pre-departure guidance for students planning to study overseas."
      }
    }
  ]
}
</script>



</head>

</head>
 

<body>
 
    <!--page start-->
    <div class="page">


<!-- header start -->
<header id="masthead" class="header cmt-header-style-01">
    <!-- top_bar -->
    <div class="top_bar cmt-bgcolor-darkgrey cmt-textcolor-white clearfix">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex flex-row align-items-center justify-content-center">
                        <div class="text-start top_bar_contact_item">
                            <div class="top_bar_icon"><i class="fa fa-envelope-o"></i></div>
                            <a href="mailto:info@bluestoneocs.com">info@bluestoneocs.com</a>
                            </div>
                        <div class="top_bar_contact_item ml-auto">
                            
                        </div>
                        <div class="top_bar_social">
                            <ul class="social-icons">
                                <li class="facebook-icon">
                                    <a href="https://www.facebook.com/bluestoneocs">
                                        <i class="ti ti-facebook"></i>
                                    </a>
                                </li>
                                <li class="instagram-icon">
                                    <a href="https://www.instagram.com/bluestoneoverseas">
                                        <i class="fa fa-instagram"></i>
                                    </a>
                                </li>
                                <li class="linkedin-icon">
                                    <a href="https://www.linkedin.com/company/bluestoneocs">
                                        <i class="ti ti-linkedin"></i>
                                    </a>
                                </li>
                                <li class="google-icon">
                                    <a href="https://bluestoneocs.business.site/">
                                        <i class="ti ti-google"></i>
                                    </a>
                                </li>
                                <li class="youtube-icon">
                                    <a href="https://www.youtube.com/@bluestoneeducation">
                                        <i class="ti ti-youtube"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="cmt-bg cmt-col-bgcolor-yes cmt-right-span cmt-bgcolor-skincolor pl-20">
                            <div class="cmt-col-wrapper-bg-layer cmt-bg-layer"></div>
                            <div class="layer-content">
                                <div class="top_bar_contact_item">
                                    <div class="top_bar_icon"><i class="fa fa-phone"></i></div>
                                    <a>+91 93428 99904</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- top_bar end-->

    <!-- site-header-menu -->
    <div id="site-header-menu" class="site-header-menu cmt-bgcolor-white">
        <div class="site-header-menu-inner cmt-stickable-header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- site-navigation -->
                        <div class="site-navigation d-flex flex-row align-items-center justify-content-between">
                            <!-- site-branding -->
                            <div class="site-branding">
                                <a class="home-link" href="index.php" title="Bluestone" rel="home">
                                    <img id="logo-img" class="img-center" src="images/logo-img.png" alt="Bluestone Overseas Consultants - Best Overseas Consultants">
                                </a>
                            </div><!-- site-branding end -->
                            <!-- widget-info -->
                            <div class="d-flex flex-row">
                                <div class="btn-show-menu-mobile menubar menubar--squeeze">
                                    <span class="menubar-box">
                                        <span class="menubar-inner"></span>
                                    </span>
                                </div>
                                <!-- menu -->
                                <nav class="main-menu menu-mobile" id="menu">
                                    <ul class="menu">
                                        <li class="mega-menu-item active">
                                            <a href="index.php" class="mega-menu-link">Home</a>
                                        </li>
                                        <li class="mega-menu-item">
                                            <a href="#" class="mega-menu-link">Study Abroad <i class="ti-angle-down" style="color: black;"></i></a>
                                            <ul class="mega-submenu ndmenu">
                                                <li><a href="usa.php"><span><img src="images/country/us.svg" class="wee" alt="US"></span>&nbsp;&nbsp;USA</a></li>
                                                <li><a href="uk.php"><span><img src="images/country/uk.svg" class="wee" alt="UK"></span>&nbsp;&nbsp;UK</a></li>
                                                <li><a href="canada.php"><span><img src="images/country/ca.svg" class="wee" alt="Canada"></span>&nbsp;&nbsp;Canada</a></li>
                                                <li><a href="Australia.php"><span><img src="images/country/au.svg" class="wee" alt="Australia"></span>&nbsp;&nbsp;Australia</a></li>
                                                <li><a href="Newzeland.php"><span><img src="images/country/nz.svg" class="wee" alt="Newzeland"></span>&nbsp;&nbsp;New Zealand</a></li>
                                                <li><a href="ireland.php"><span><img src="images/country/ie.svg" class="wee" alt="Ireland"></span>&nbsp;&nbsp;Ireland</a></li>
                                                <li><a href="Germany.php"><span><img src="images/country/de.svg" class="wee" alt="Germany"></span>&nbsp;&nbsp;Germany</a></li>
                                                <li><a href="swedan.php"><span><img src="images/country/se.svg" class="wee" alt="Sweden"></span>&nbsp;&nbsp;Sweden</a></li>
                                                <li><a href="France.php"><span><img src="images/france/flag.png" class="wee" alt="France"></span>&nbsp;&nbsp;France</a></li>
                                                <li><a href="Italy.php"><span><img src="images/country/it.svg" class="wee" alt="Italy"></span>&nbsp;&nbsp;Italy</a></li>
                                                <li><a href="Singapore.php"><span><img src="images/country/sg.svg" class="wee" alt="Singapore"></span>&nbsp;&nbsp;Singapore</a></li>
                                                <li><a href="Malaysia.php"><span><img src="images/country/my.svg" class="wee" alt="Malaysia"></span>&nbsp;&nbsp;Malaysia</a></li>
                                                <li><a href="Denmark.php"><span><img src="images/Denmark/flag.png" class="wee" alt="Denmark"></span>&nbsp;&nbsp;Denmark</a></li>
                                                <li><a href="Bulgaria.php"><span><img src="images/Bulgaria/flag.jpg" class="wee" alt="Bulgaria"></span>&nbsp;&nbsp;Bulgaria</a></li>
                                                <li><a href="Russia.php"><span><img src="images/Russia/flag.png" class="wee" alt="Russia"></span>&nbsp;&nbsp;Russia</a></li>
                                                <li><a href="Switzerland.php"><span><img src="images/Switzerland/flag.png" class="wee" alt="Switzerland"></span>&nbsp;&nbsp;Switzerland</a></li>
                                                <li><a href="South Korea.php"><span><img src="images/South Korea/flag.png" class="wee" alt="South Korea"></span>&nbsp;&nbsp;South Korea</a></li>
                                                <li><a href="Netherlands.php"><span><img src="images/Netherlands/flag.png" class="wee" alt="Netherlands"></span>&nbsp;&nbsp;Netherlands</a></li>
                                                <li><a href="UAE.php"><span><img src="images/UAE/flag.png" class="wee" alt="UAE"></span>&nbsp;&nbsp;UAE</a></li>
                                                <li><a href="Philipines.php"><span><img src="images/Philipines/flag.jpeg" class="wee" alt="Philippines"></span>&nbsp;&nbsp;Philippines</a></li>
                                            </ul>
                                        </li>

                                        <li class="mega-menu-item">
                                            <a href="#" class="mega-menu-link">About Us <i class="ti-angle-down" style="color: black;"></i></a>
                                            <ul class="mega-submenu">
                                                <li><a href="About_us.php">Our Profile</a></li>
                                                <li><a href="Award_Achievements.php">Award &amp; Achievements</a></li>
                                                <li><a href="event_mod.php">Events</a></li>
                                                <li><a href="Blog.php">Blog</a></li>
                                                <li><a href="gallery.php">Gallery</a></li>
                                                <!--<li><a href="team.php">Team Details</a></li>
                                                <li><a href="Career.php">Career</a></li>-->
                                            </ul>
                                        </li>
                                        <li class="mega-menu-item">
                                            <a href="#" class="mega-menu-link">Branch <i class="ti-angle-down" style="color: black;"></i></a>
                                            <ul class="mega-submenu">
                                                <li><a href="coimbatore.php">Coimbatore</a></li>
                                                <li><a href="chennai.php">Chennai</a></li>
                                                <li><a href="salem.php">Salem</a></li>
                                                <li><a href="erode.php">Erode</a></li>
                                                <li><a href="namakkal.php">Namakkal</a></li>
                                                <li><a href="nepal.php">Nepal</a></li>
                                                <li><a href="canada_branch.php">Canada</a></li>
                                                <li><a href="thirunelveli.php">Tirunelveli</a></li>
                                            </ul>
                                        </li>
                                        <li class="mega-menu-item">
                                            <a href="#" class="mega-menu-link">Test Preparations <i class="ti-angle-down" style="color: black;"></i></a>
                                            <ul class="mega-submenu">
                                                <li><a href="ielts_test.php">IELTS</a></li>
                                                <li><a href="TOEFL.php">TOEFL</a></li>
                                                <li><a href="PTE.php">PTE</a></li>
                                            </ul>
                                        </li>

                                        <li class="mega-menu-item">
                                            <a href="services_1.php" class="mega-menu-link">Our Services <i class="ti-angle-down" style="color: black;"></i></a>
                                            <ul class="mega-submenu">
                                                <li><a href="Student_Counselling.php">Student Counselling</a></li>
                                                <li><a href="University_Course_Selection.php">Course, Country &amp; University Selection</a></li>
                                                <li><a href="Application_Admission_Processing.php">Application &amp; Admission Processing</a></li>
                                                <li><a href="Financial_Assistance.php">Financial Assistance</a></li>
                                                <li><a href="Visa_Processing.php">Visa Processing</a></li>
                                                <li><a href="Accommodation_Travel_Assistance.php">Accommodation &amp; Travel Assistance</a></li>
                                                <li><a href="Part_Time_Job_Assistance.php">Part Time Job Assistance</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div><!-- site-navigation end-->
                    </div>
                </div>
            </div>
        </div>
    </div><!-- site-header-menu end-->
</header>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Dining Requests</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="style/main.css" rel="stylesheet">
    <link href="style/otherpage.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <?php include_once('top.view.php'); ?>
    <script type="text/javascript">
      var curr_nav = document.getElementsByClassName("nav-faq")[0];
      curr_nav.className += " active";
    </script>

    <div class="container wrap-main">

      <div class="row main">

        <div class="col-sm-12">

          <h1>Frequently Asked Questions</h1>
          <div class="wrap-faq-search">
            <div class="col-sm-12">
              <div class="input-group dropdown">
                <input type="text" class="form-control" id="faq_search_input" onkeyup="faqSearchOnKeyUp();" placeholder="Search by keyword" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="search-result dropdown-menu col-sm-12"></div>
              </div><!-- /input-group -->
            </div><!-- /.col-sm-12 -->
          </div>

          <div class="wrap-faq panel-group col-sm-12" id="accordion" role="tablist" aria-multiselectable="true">
            <?php include_once('faqs-list.php'); ?>
          </div>

          <div class="faq-scroll-up" onclick="faq_scrollTop();">
            <span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
          </div>

        </div>

      </div><!-- end of row -->

    </div> <!-- /container -->

    <!-- Site footer -->
    <?php include_once('footer.view.php'); ?>

  </body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
  <script src="js/faq.js"></script>
</html>

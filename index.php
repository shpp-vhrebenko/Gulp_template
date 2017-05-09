<?php
header("Content-type: text/html; charset=utf-8");
require_once 'lib/functions.php';       // подключение общей библиотеки функций

startup();

?>

<!doctype html>
<html class="no-js" lang="">
  <head>  
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DvaCom</title>
    <link rel="apple-touch-icon" href="apple-touch-icon.png">   
    <link rel="stylesheet" href="styles/main.css?<?php echo filemtime('styles/css/main.css');?>">    
   <!--  build:js scripts/head_vendor.js -->
    <script src="bower_components/jquery/dist/jquery.js"></script>   
   <!-- endbuild -->            
          
  </head>
  <body>    
    <!--[if lt IE 10]>
      <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]--> 
    <div class="js_loading_wraper">
      <div class="loading"></div>
    </div>
    <div id="wrapper">
        <div class="overlay"></div>
    
        <!-- Sidebar -->
        <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">            
            <ul class="nav sidebar-nav cl-effect-1" id="brand-nav">
                <li class="sidebar-nav__addBrand">
                    <a type="button" id="add_brand" class="nav__addBrand"><span data-hover="ADD BRAND">ADD BRAND</span></a>
                    <div class="add-form" id="add_brandNameForm">
                      <form >
                        <div class="input-group">                     
                          <input type="text" class="form-control brend-name"  placeholder="BRAND NAME" required>
                          <span class="input-group-addon danger js-add-name add-name"><span class="glyphicon glyphicon-remove"></span></span>
                        </div>
                      </form>
                    </div>
                </li>                                                     
            </ul>
        </nav>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <button type="button" class="hamburger is-closed" data-toggle="offcanvas">
              <span class="hamb-top"></span>
              <span class="hamb-middle"></span>
              <span class="hamb-bottom"></span>
            </button>
            <div class="row">
              <h2 class="header-category col-md-11" id="current_brand_header" data-brand-id=""> <<< ADD BRAND</h2>
              <div class="dropdown col-md-1 sort-products" id="sort-products">
                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">SORT
                <span class="caret"></span></button>
                <ul class="dropdown-menu">
                  <li><a type="button" class="typeSort js-price-sort" data-type-sort="ASC">Price<span class="glyphicon glyphicon-chevron-down iconPriceSort"></a></li> 
                  <li><a type="button" class="typeSort js-name-sort" data-type-sort="ASC">Name Product<span class="glyphicon glyphicon-chevron-down iconNameSort"></a></li>                 
                </ul>
              </div>              
            </div>           
                       
            <div class="container brand-content">
                <div class="row" id="brand-content">                                                       
                </div>
            </div>
            <button class="btn btn-lg btn-add-to-cart" data-toggle="modal" data-target="#myModal" id="productModal"><span class="glyphicon glyphicon-shopping-cart" ></span>ADD</button> 
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->   

    <!-- Modal ADD -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">ADD PRODUCT</h4>
          </div>
          <div class="modal-body">
            <form id="productForm" role="form" name="productForm" action="" method="post" enctype="multipart/form-data">
              <div class="form-group">
               <label for="productName">Product Name</label>
               <input type="text" class="form-control" id="productName" name="productName" required>
              </div>
              <div class="form-group">
                <label for="productDescription">Description</label>
                <textarea class="form-control" id="productDescription" name="productDescription"  required></textarea>
              <!--  <input type="text" class="form-control" id="productDescription" name="productDescription"  required> -->
              </div>
              <div class="form-group">
                <label for="productPrice">Product Price</label>               
                <input type="number" min="0" class="form-control" id="productPrice" name="productPrice" required="">
              </div>
              <div class="form-group">
                <label for="uploadImage">Upload image</label>
                <input type="hidden" name="MAX_FILE_SIZE" value="4194304"/>
                <input type="file" id="uploadImage" name="uploadImage">
                <p class="help-block">Select image(.jpg, .png) for upload.Image size < 4Mb</p>
              </div>                       
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-info" id="saveProduct" >Save</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
    <!-- End Modal ADD -->

    <!-- Modal Edit -->
    <div id="modalEditProduct" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">EDIT PRODUCT</h4>
          </div>
          <div class="modal-body">
            <form id="productEditForm" role="form" name="productEditForm" action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
               <label for="productEditName">Product Name</label>
               <input type="text" class="form-control" id="productEditName" name="productEditName" required>
             </div>
             <div class="form-group">
               <label for="productEditDescription">Description</label>
               <textarea class="form-control" id="productEditDescription" name="productEditDescription"  required></textarea>            
             </div>
             <div class="form-group">
               <label for="productEditPrice">Product Price</label> 
               <input type="hidden" name="idProductEdit" id="idProductEdit" value="" />              
               <input type="number" min="0" class="form-control" id="productEditPrice" name="productEditPrice" required="">
             </div> 
             <div class="form-group">
                <label for="uploadImageForEdit">Upload image</label>
                <input type="hidden" name="MAX_FILE_SIZE" value="4194304"/>
                <input type="file" id="uploadImageForEdit" name="uploadImageForEdit" data-current-link="">
                <p class="help-block">Select image(.jpg, .png) for edit.Image size < 4Mb</p>
              </div>                                  
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-info" id="saveEditProduct" >Save Edit</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
    <!-- End Modal Edit -->

    <!-- build:js scripts/footer_vendor.js -->   
      <script src="bower_components/bootstrap/dist/js/bootstrap.js"></script>
      <script src="bower_components/jquery-validation/dist/jquery.validate.js"></script>                 
    <!-- endbuild -->       
    
    <script src="scripts/main.js"></script>     
  
  </body>
</html>

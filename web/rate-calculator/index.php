<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title class="">Tính lãi suất theo gốc giảm dần</title>

  <!-- Bootstrap -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
    </head>
  <!--
  <body>


<div class="input-group">
  <span class="input-group-addon" id="basic-addon1">@</span>
  <input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon1">
</div>

<div class="input-group">
  <input type="text" class="form-control" placeholder="Recipient's username" aria-describedby="basic-addon2">
  <span class="input-group-addon" id="basic-addon2">@example.com</span>
</div>

<div class="input-group">
  <span class="input-group-addon">$</span>
  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
  <span class="input-group-addon">.00</span>
</div>

<label for="basic-url">Your vanity URL</label>
<div class="input-group">
  <span class="input-group-addon" id="basic-addon3">https://example.com/users/</span>
  <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3">
</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
-->
<body>

  <div class="container">
    <!-- <div class="header clearfix">
      <nav>
        <ul class="nav nav-pills pull-right">
          <li class="active" role="presentation"><a href="#">Home</a></li>
          <li role="presentation"><a href="#">About</a></li>
          <li role="presentation"><a href="#">Contact</a></li>
      </ul>
  </nav>
  <h3 class="text-muted">Tính lãi suất theo gốc giảm dần</h3>
</div> -->

<div class="jumbotron">
  <h3 class="text-muted">Tính lãi suất theo gốc giảm dần</h3>
    <form method="post" action="">
      <div class="form-group">
        <label for="borrowMoney">Số tiền cần vay</label>
        <div class="input-group">
          <input type="number" class="form-control" id="borrowMoney" name="borrowMoney" placeholder="Số tiền cần vay" value="<?php echo isset($_POST['borrowMoney'])?$_POST['borrowMoney']:1000000000; ?>" required="required" aria-describedby="borrowMoney-unit">
          <span class="input-group-addon" id="borrowMoney-unit">VND</span>
          </div>
      </div>
      <div class="form-group">
        <label for="borrowMonth">Số tháng vay</label>
        <div class="input-group">
          <input type="number" class="form-control" id="borrowMonth" name="borrowMonth" placeholder="Số tháng vay" value="<?php echo isset($_POST['borrowMonth'])?$_POST['borrowMonth']:120; ?>" required="required" aria-describedby="borrowMonth-unit">
          <span class="input-group-addon" id="borrowMonth-unit">Tháng</span>
        </div>
        
      </div>
      <div class="form-group">
        <label for="borrowMoney">Lãi suất đầu</label>
        <div class="input-group">
        <input type="number" class="form-control" id="firstRate" name="firstRate" placeholder="Lãi suất đầu" value="<?php echo isset($_POST['firstRate'])?$_POST['firstRate']:7.5; ?>" required="required" step="any" aria-describedby="firstRate-unit">
        <span class="input-group-addon" id="firstRate-unit">%</span>
        </div>
      </div>
      <div class="form-group">
        <label for="borrowMoney">Thời hạn lãi suất đầu</label>
        <div class="input-group">
        <input type="number" class="form-control" id="firstRateMonth" name="firstRateMonth" placeholder="Thời hạn lãi suất đầu" value="<?php echo isset($_POST['firstRateMonth'])?$_POST['firstRateMonth']:18; ?>" aria-describedby="firstRateMonth-unit">
                  <span class="input-group-addon" id="firstRateMonth-unit">Tháng</span>
                    </div>
      </div>
      <div class="form-group">
        <label for="borrowMonth">Lãi suất sau</label>
        <div class="input-group">
        <input type="number" class="form-control" id="lateRate" name="lateRate" placeholder="Lãi suất sau" value="<?php echo isset($_POST['lateRate'])?$_POST['lateRate']:10.5; ?>"  step="any">
        <span class="input-group-addon" id="lateRate-unit">%</span>
        </div>
      </div>

      <button type="submit" class="btn btn-default" name="calculate" value="calculate">Tính toán</button>
    </form>
  </div>
  <div data-example-id="simple-table" class="bs-example"> 
    <table class="table table-striped"> 
      <caption>KẾT QUẢ</caption> 
      <thead> 
        <tr> 
          <th>Tháng thứ</th> 
          <th>Tiền gốc phải trả</th> 
          <th>Tiền lãi phải trả</th> 
          <th>Tổng cộng</th> </tr> 
        </thead> 
        <tbody>
          <?php 
          if (!empty($_POST['calculate'])) {
            $year = 20;
            $total = $_POST['borrowMoney'];
            $time = $_POST['borrowMonth'];
            $first = $_POST['firstRate']/100;
            $last = $_POST['lateRate']/100;
            $firstRateMonth = $_POST['firstRateMonth'];
            $origin_pay = $total/$time;
            $tong = 0;
            for ($i=0; $i<$time; $i++) {

              if ($i<$firstRateMonth) {
                $lai = $total*$first/12;

              } else $lai = $total*$last/12;
              ?>
              <tr class="<?php if ($i<$firstRateMonth) echo "success" ?>"> 
                <th scope="row"><?php echo $i+1; ?></th> 
                <td><?php echo number_format(floor($origin_pay)); ?></td> 
                <td><?php echo number_format($lai); ?></td>
                <th><?php echo number_format($origin_pay+$lai); ?></td> 
              </tr>
              <?php

              $tong+=($origin_pay+$lai);
              $total = $total-$origin_pay;

            }
          }


          ?> 
          <?php if (isset($tong)) 
          echo "<tr><td colspan='4'>Tổng cộng số tiền phải trả trong ".$_POST['borrowMonth']." tháng: <strong>".number_format($tong)."</strong></td></tr>";
           ?>

        </tbody> 
      </table> 
      <div class="row"></div>
    </div>



    <footer class="footer">
      <p>&copy; 2016 Vinh Huu.</p>
    </footer>

  </div> <!-- /container -->


  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>


</body>
</html>
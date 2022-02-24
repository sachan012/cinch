<!DOCTYPE html>
<html lang="en">
<head>
  <title>Oil Changers</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="<?php echo base_url('assets/mailer/');?>style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=0" />
</head>
<body>
  <div class="mainDiv50">
    <div class="topDivImage">
      <!-- <span>Rewards</span> -->
      <img src="<?php echo base_url('assets/mailer/');?>OC-SM@2x.png" alt="" class="rewardsImg">
    </div>
    <header>
      <div class="text-center backgroundClass">
        <img src="<?php echo base_url('assets/mailer/');?>email.png" alt="">
        <p>Verify your Oil Changers account</p>
      </div>
    </header>

    <section>
      <div class="whiteDiv">
        <div class="row marginTopRow">
          <div class="col-sm-12 clinchDivP">
            <p>PRASHANT , Oil Changers uses security features like your email and mobile
              phone to provide you with a convenient and secure experience.</p>
          </div>
          <div class="col-sm-12 cnfmEmail">
            <span>CONFIRM YOUR EMAIL</span>
            <p>You can also confirm by clicking the link below:</p>
            <a href="https://portal-staging.cinch.io/verify?confirm=<?php echo $api_key?>">https://portal-staging.cinch.io/verify?confirm=<?php echo $api_key?>"></a>
          </div>
        </div>
      </div>
    </section>
    <div class="row footer">
      <div class="col-sm-12">
        <p>Looking for help? support@Oil Changers.io</p>
        <p>Copyright 2017 Oil Changers, LLC</p>
        <p>All rights reserved</p>
        <p>Patents Pending</p>
      </div>
    </div>
  </div>
</body>
</html>
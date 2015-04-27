<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="PHP Class which generates analog clock out of the digital time.">
    <meta name="author" content="Tomas Pavlatka [http://www.pavlatka.cz]">

    <title>PTX Clock - Generate Analog Clock from digital time</title>

    <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./css/ptx.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">PTX Clock - Generate Analog Clock from digital time</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse pull-right">
            <ul class="nav navbar-nav">
                <li><a href="http://www.pavlatka.cz" title="Tomas Pavlatka - a passionate PHP Developer">Tomas Pavlatka</a></li>
                <li><a href="http://www.pavlatka.cz/contact/" title="Tomas Pavlatka - contact me">Contact</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container">

    <div class="starter-template">
        <h1>PTX Clock - Generate Analog Clock from digital time</h1>
        <p class="lead">PHP Class to create analog clock from the time given. I have a son and they are teaching him at school the clock. I though it would be great to create a simple game to help him out. This class is just a beginning of the whole idea.</p>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form class="form" action="" id="GenerateClock">
                <div class="form-group">
                    <label for="ClockTime">Time</label>
                    <input class="form-control" type="text" id="ClockTime" placeholder="01:02"/>
                    <span class="help-block">Time must be in HH:ii format, e.g. 01:02</span>
                </div>

                <button type="submit" class="btn btn-primary">GENERATE</button>
            </form>

            <p><strong>PTX Clock on Github</strong></p>

            <ul>
                <li><a href="https://github.com/tomaspavlatka/PTX-Clock" title="PTX Clock - Generate Analog Clock from digital time">PTX Clock on Github</a> - Check the code or download it</li>
            </ul>

        </div>
        <div class="col-md-6">
            <div id="ClockImage" class="text-center"></div>
        </div>
    </div>



</div><!-- /.container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="./js/ptx.js"></script>
</body>
</html>

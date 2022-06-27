<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TooDay</title>
    <link href="https://fonts.googleapis.com/css?family=Heebo:400,700|Oxygen:700" rel="stylesheet">
    <link rel="stylesheet" href="css/app.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://unpkg.com/scrollreveal@4.0.5/dist/scrollreveal.min.js"></script>
</head>
<body class="is-boxed has-animations">
    <div class="body-wrap ">
    {{$slot}}
    <footer class="site-footer">
        <div class="footer-particles-container">
            <canvas id="footer-particles"></canvas>
        </div>
        <div class="site-footer-top">
            <section class="cta section text-light">
                <div class="container-sm">
                    <div class="cta-inner section-inner">
                        <div class="cta-header text-center">
                            <h2 class="section-title mt-0">Stay in the know</h2>
                            <p class="section-paragraph">Lorem ipsum is common placeholder text used to demonstrate the graphic elements of a document or visual presentation.</p>
                            <div class="cta-cta">
                                <a class="button button-primary button-wide-mobile" href="#">Be a Tooder</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="site-footer-bottom">
            <div class="container">
                <div class="site-footer-inner">
                    <div class="brand footer-brand">
                        <a href="#">
                            <img src="images/logo.svg" alt="Venus logo">
                        </a>
                    </div>
                    <ul class="footer-links list-reset">
                        <li>
                            <a href="{{ route('privacy-policy') }}">Privacy Policy</a>
                        </li>
                        <li>
                            <a href="{{ route('tnc') }}">Terms & Conditions</a>
                        </li>
                    </ul>
                    <ul class="footer-social-links list-reset">
                        <li>
                            <a href="#">
                                <span class="screen-reader-text">Instagram</span>
                                <i class="fa fa-instagram" style="font-size:18px;color:white"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="screen-reader-text">Facebook</span>
                                <i class="fa fa-facebook-f" style="font-size:16px;color:white"></i>

                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="screen-reader-text">Linkdin</span>
                                <i class="fa fa-linkedin-square" style="font-size:16px;color:white"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="footer-copyright">&copy; 2022 HashVizard, all rights reserved </div>
                </div>
            </div>
        </div>
    </footer>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="js/app.js"></script>
</body>
</html>

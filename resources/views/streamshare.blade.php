
@include('layout.header');

<script src="https://cdn.socket.io/socket.io-1.0.0.js"></script>

<script  src="https://unpkg.com/peerjs@1.3.1/dist/peerjs.min.js"></script>

<script src="{{ asset('streamscript.js')}}"></script>



<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">StreamShare </h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>

                </div>
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Three charts -->
        <!-- ============================================================== -->
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-12">
                <div class="white-box analytics-info">
                    <h3 class="box-title">Number of Streams Created</h3>
                    <ul class="list-inline two-part d-flex align-items-center mb-0">
                        <li>
                            <div id="sparklinedash"><canvas width="67" height="30"
                                                            style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                            </div>
                        </li>
                        <li class="ms-auto"><span class="counter text-success">659</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="white-box analytics-info">
                    <h3 class="box-title">Number of viewers connected</h3>
                    <ul class="list-inline two-part d-flex align-items-center mb-0">
                        <li>
                            <div id="sparklinedash2"><canvas width="67" height="30"
                                                             style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                            </div>
                        </li>
                        <li class="ms-auto"><span class="counter text-purple">869</span></li>
                    </ul>
                </div>
            </div>

        </div>
        <!-- ============================================================== -->
        <!-- PRODUCTS YEARLY SALES -->
        <!-- ============================================================== -->


        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                <div class="white-box">
                    <h3 class="box-title">Stream</h3>
                    <div class="d-md-flex">
                        <ul class="list-inline d-flex ms-auto">
                            <li class="ps-3">
                                <h5><i class="fa fa-circle me-1 text-info"></i>Mac</h5>
                            </li>
                            <li class="ps-3">
                                <h5><i class="fa fa-circle me-1 text-inverse"></i>Windows</h5>
                            </li>
                        </ul>
                    </div>
                    <div id="ct-visits" style="height: 405px;">
                        <div class="chartist-tooltip" style="top: -17px; left: -12px;"><span
                                class="chartist-tooltip-value">6</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- footer -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Page wrapper  -->

@include('layout.footer');

